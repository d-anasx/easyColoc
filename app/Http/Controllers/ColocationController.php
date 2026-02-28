<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ColocationController extends Controller
{
    public function index()
    {
        $activeColocation = auth()->user()->colocations()->wherePivot('left_at', null)->first();

        if (!$activeColocation) {
            return view('dashboard', ['activeColocation' => null, 'expenses' => collect(), 'userBalance' => 0]);
        }

        $expenses = $activeColocation->expenses()->with('payers')->get();
        $role = $activeColocation->members->find(auth()->id())->pivot->role ?? null;
        $userBalance = $expenses->sum(function ($exp) {
            $userId = Auth::id();
            $count  = $exp->payers->count();
            $share  = round($exp->amount / $count, 2);
            // if creator
            if ($exp->paid_by === $userId) {
                $unpaidCount = $exp->payers->filter(fn($p) => !$p->pivot->is_paid && $p->id !== $userId)->count();
                return round($unpaidCount * $share, 2);
            }

            // if payer
            $payer = $exp->payers->firstWhere('id', $userId);
            if (!$payer || $payer->pivot->is_paid) {
                return 0;
            }

            return -$share;
        });



        return view('dashboard', compact('activeColocation', 'expenses', 'userBalance', 'role'));
    }

    public function show($id)
    {
        $colocation = Colocation::with(['members', 'expenses.payers', 'expenses.createdBy', 'categories'])->findOrFail($id);

        // settlements: per expense, members with is_paid = false owe createdBy
        $settlements = [];

        foreach ($colocation->expenses as $expense) {
            $share = round($expense->amount / max($expense->payers->count(), 1), 2);

            foreach ($expense->payers->where('pivot.is_paid', false) as $member) {
                if ($member->id === $expense->created_by) continue;

                $found = false;
                foreach ($settlements as &$s) {
                    if ($s['from'] === $member->name && $s['to'] === $expense->createdBy->name) {
                        $s['amount'] = round($s['amount'] + $share, 2);
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $settlements[] = [
                        'from'   => $member->name,
                        'to'     => $expense->createdBy->name,
                        'amount' => $share,
                    ];
                }
            }
        }
        return view('colocations.show', compact('colocation', 'settlements'));
    }

    public function invite($id)
    {
        $token = encrypt($id);
        $link = route('colocations.join', ['token' => $token]);
        return redirect()->back()->with('success', "Lien d'invitation : " . $link);
    }

    public function showInvitation($token)
    {
        try {
            $id = decrypt($token);
        } catch (\Exception $e) {
            abort(403, 'Lien invalide.');
        }

        $colocation = Colocation::with('members')->findOrFail($id);

        return view('colocations.invitation', compact('colocation', 'token'));
    }

    public function join($token)
    {
        try {
            $id = decrypt($token);
        } catch (\Exception $e) {
            abort(403, 'Lien invalide.');
        }

        $colocation = Colocation::findOrFail($id);

        if (auth()->user()->hasActiveColocation()) {
            return redirect()->route('dashboard')->with('error', 'Vous avez déjà une colocation active.');
        }

        if ($colocation->members->contains(auth()->id())) {
            return redirect()->route('dashboard')->with('error', 'Vous êtes déjà membre.');
        }

        $colocation->members()->attach(auth()->id(), [
            'role'      => 'member',
            'joined_at' => now(),
        ]);

        return redirect()->route('colocations.show', $colocation->id)->with('success', 'Vous avez rejoint la colocation !');
    }


    function getUserDebt(Colocation $colocation, int $userId): float
    {
        $debt = 0;
        foreach ($colocation->expenses as $expense) {
            $share = round($expense->amount / max($expense->payers->count(), 1), 2);
            $payer = $expense->payers->firstWhere('id', $userId);
            if ($payer && !$payer->pivot->is_paid && $expense->paid_by !== $userId) {
                $debt += $share;
            }
        }
        return $debt;
    }

    function clearDebt(Colocation $colocation, int $userId): void
    {
        foreach ($colocation->expenses as $expense) {
            $payer = $expense->payers->firstWhere('id', $userId);
            if ($payer && !$payer->pivot->is_paid) {
                $expense->payers()->detach($payer->id);
            }
        }
    }


    function updateReputation(User $user, float $debt): void
    {
        $debt > 0 ? $user->decrement('reputation') : $user->increment('reputation');
    }




    public function leave($id)
    {
        $colocation = Colocation::with(['expenses.payers'])->findOrFail($id);
        $user       = auth()->user();
        $debt       = $this->getUserDebt($colocation, $user->id);

        $this->updateReputation($user, $debt);
        $this->clearDebt($colocation, $user->id);
        $colocation->members()->updateExistingPivot($user->id, ['left_at' => now()]);

        return redirect()->route('dashboard')->with('success', 'Vous avez quitté la colocation.');
    }


    public function removeMember($colocationId, $memberId)
    {
        $colocation = Colocation::with(['expenses.payers'])->findOrFail($colocationId);
        $member     = User::findOrFail($memberId);
        $debt       = $this->getUserDebt($colocation, $memberId);

        $this->updateReputation($member, $debt);

        if ($debt > 0) {
            foreach ($colocation->expenses as $expense) {
                $payer = $expense->payers->firstWhere('id', $memberId);
                if ($payer && !$payer->pivot->is_paid && $expense->paid_by !== $memberId) {

                    // detach leaving member
                    $expense->payers()->detach($memberId);

                    // always attach owner as new unpaid entry
                    $expense->payers()->attach(auth()->id(), ['is_paid' => false]);
                }
            }
        } else {
            $this->clearDebt($colocation, $memberId);
        }

        $colocation->members()->updateExistingPivot($memberId, ['left_at' => now()]);

        return redirect()->back()->with('success', 'Membre retiré.');
    }
}
