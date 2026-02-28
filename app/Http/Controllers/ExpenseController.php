<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreExpenseRequest;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function show($id)
    {
        $expense = Expense::with(['payers', 'createdBy', 'category'])->findOrFail($id);

        $currentPayer = $expense->payers->firstWhere('id', auth()->id());
        $share = $currentPayer?->pivot->amount ?? 0;

        return view('expenses.show', compact('expense', 'share'));
    }

    public function create()
    {
        $colocation = auth()->user()->colocations()->first();
        $members    = $colocation->members;
        $categories = $colocation->categories;
        return view('expenses.create', compact('members', 'categories'));
    }

    public function store(StoreExpenseRequest $request)
    {
        $colocation = auth()->user()->colocations()->first();

        $expense = Expense::create([
            'colocation_id' => $colocation->id,
            'paid_by'    => Auth::id(),
            'category_id'   => $request->category_id,
            'title'         => $request->title,
            'amount'        => $request->amount,
            'created_at'    => now(),
        ]);

        $share = round($expense->amount / $colocation->members->count(), 2);
        $members = $colocation->members->pluck('id');
        $expense->payers()->attach($members, ['is_paid' => false, 'amount' => $share]);
        $expense->payers()->updateExistingPivot(Auth::id(), ['is_paid' => true]);

        return redirect()->route('colocations.show', $colocation->id)->with('success', 'Dépense ajoutée.');
    }

    public function markAsPaid($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->payers()->updateExistingPivot(Auth::id(), ['is_paid' => true]);
        return redirect()->back()->with('success', 'Paiement marqué comme effectué.');
    }
}
