<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function show($id)
    {

        $expense = Expense::findOrFail($id);
        $share = round($expense->amount / max($expense->payers->count(), 1), 2);

        return view('expenses.show', compact('expense', 'share'));
    }

    public function markAsPaid($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->payers()->updateExistingPivot(auth()->id(), ['is_paid' => true]);
        return redirect()->back()->with('success', 'Paiement marqué comme effectué.');
    }
}
