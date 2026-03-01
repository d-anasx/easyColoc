<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Colocation;
use App\Models\Expense;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users'       => User::count(),
            'colocations' => Colocation::count(),
            'expenses'    => Expense::count(),
            'banned'      => User::where('is_banned', true)->count(),
            'active_colocations' => Colocation::where('status', 'active')->count(),
            'total_amount' => Expense::sum('amount'),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users()
    {
        $users = User::with('role')->latest()->get();
        return view('admin.users', compact('users'));
    }

    public function ban($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_banned' => true]);
        return redirect()->back()->with('success', $user->name . ' a été banni.');
    }

    public function unban($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_banned' => false]);
        return redirect()->back()->with('success', $user->name . ' a été débanni.');
    }

    public function stats()
    {
        $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];

        $expensesPerMonth    = Expense::all()->groupBy(fn($e) => $e->created_at->month)
            ->map(fn($g) => $g->sum('amount'));

        $colocationsPerMonth = Colocation::all()->groupBy(fn($c) => $c->created_at->month)
            ->map(fn($g) => $g->count());

        $usersPerMonth       = User::all()->groupBy(fn($u) => $u->created_at->month)
            ->map(fn($g) => $g->count());

        $expensesData    = collect(range(1, 12))->map(fn($m) => $expensesPerMonth[$m] ?? 0)->values();
        $usersData       = collect(range(1, 12))->map(fn($m) => $usersPerMonth[$m] ?? 0)->values();
        $colocationsData = collect(range(1, 12))->map(fn($m) => $colocationsPerMonth[$m] ?? 0)->values();

        return view('admin.stats', compact('months', 'expensesData', 'usersData', 'colocationsData'));
    }
}
