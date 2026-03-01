<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use App\Models\User;
use App\Http\Requests\StoreColocationRequest;
use Illuminate\Support\Facades\Auth;

class ColocationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $activeColocation = auth()->user()->colocations()->wherePivot('left_at', null)->first();
         
        if (!$activeColocation) {
            return view('dashboard', ['activeColocation' => null, 'expenses' => collect(), 'userBalance' => 0]);
        }

        $expenses = $activeColocation->expenses()->with('payers')->get();
        $role = $activeColocation->members->find(Auth::id())->pivot->role ?? null;
        $userTotalSpent = $expenses
                        ->filter(fn($exp) => $exp->paid_by === $userId)
                        ->sum('amount');
        $userBalance = $expenses->sum(function ($exp) use ($userId) {

            $payer = $exp->payers->firstWhere('id', $userId);

            if ($exp->paid_by === $userId) {
                return $exp->payers->filter(fn($p) => !$p->pivot->is_paid && $p->id !== $userId)
                    ->sum(fn($p) => $p->pivot->amount);
            }

            if ($payer && !$payer->pivot->is_paid) {
                return -$payer->pivot->amount;
            }

            return 0;
        });



        return view('dashboard', compact('activeColocation', 'expenses', 'userBalance', 'role', 'userTotalSpent'));
    }

    public function create()
    {
        if (auth()->user()->hasActiveColocation()) {
            return redirect()->route('dashboard')->with('error', 'Vous avez déjà une colocation active.');
        }

        return view('colocations.create');
    }


    public function store(StoreColocationRequest $request)
    {
        $colocation = Colocation::create([
            'name'        => $request->name,
            'description' => $request->description,
            'status'      => 'active',
        ]);

        $colocation->members()->attach(auth()->id(), [
            'role'      => 'owner',
            'joined_at' => now(),
        ]);

        return redirect()->route('colocations.show', $colocation->id)->with('success', 'Colocation créée avec succès.');
    }

    public function show($id)
    {
        $colocation = Colocation::with(['members', 'expenses.payers', 'expenses.createdBy', 'categories'])->findOrFail($id);
        $this->authorize('view', $colocation);
        // members with is_paid = false owe payer
        $settlements = [];

        foreach ($colocation->expenses as $expense) {
            $share = round($expense->amount / max($expense->payers->count(), 1), 2);

            foreach ($expense->payers->where('pivot.is_paid', false) as $member) {
                if ($member->id === $expense->paid_by) continue;

                $found = false;
                foreach ($settlements as &$s) {
                    if ($s['from'] === $member->name && $s['to'] === $expense->createdBy->name) {
                        $s['amount'] = round($s['amount'] + $member->pivot->amount, 2);
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $settlements[] = [
                        'from'   => $member->name,
                        'to'     => $expense->createdBy->name,
                        'amount' => $member->pivot->amount, 
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

        if ($colocation->members->contains(Auth::id())) {
            return redirect()->route('dashboard')->with('error', 'Vous êtes déjà membre.');
        }

        $colocation->members()->attach(Auth::id(), [
            'role'      => 'member',
            'joined_at' => now(),
        ]);

        return redirect()->route('colocations.show', $colocation->id)->with('success', 'Vous avez rejoint la colocation !');
    }


    function getUserDebt(Colocation $colocation, int $userId): float
    {
        $debt = 0;
        foreach ($colocation->expenses as $expense) {
            $payer = $expense->payers->firstWhere('id', $userId);
            if ($payer && !$payer->pivot->is_paid && $expense->paid_by !== $userId) {
                $debt += $payer->pivot->amount;
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
        $member     = $colocation->members->find($user->id);

        if ($member->pivot->role === 'owner') {
            $colocation->members()->detach();
            $colocation->update(['status' => 'inactive']);
            return redirect()->route('dashboard')->with('success', 'Colocation annulée.');
        }

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

                    // detach member
                    $expense->payers()->detach($memberId);

                    // add member's share to owner's amount
                    $ownerPayer = $expense->payers->firstWhere('id', Auth::id());
                    if ($ownerPayer) {
                        $expense->payers()->updateExistingPivot(Auth::id(), [
                            'amount'  => $ownerPayer->pivot->amount + $payer->pivot->amount,
                            'is_paid' => false,
                        ]);
                    } else {
                        $expense->payers()->attach(Auth::id(), [
                            'is_paid' => false,
                            'amount'  => $payer->pivot->amount,
                        ]);
                    }
                }
            }
        }

        $colocation->members()->updateExistingPivot($memberId, ['left_at' => now()]);

        return redirect()->back()->with('success', 'Membre retiré.');
    }
}
