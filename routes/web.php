<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\ExpenseController;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', [ColocationController::class, 'index'])->name('dashboard');
    Route::get('/colocations/{id}', [ColocationController::class, 'show'])->name('colocations.show');
    Route::get('expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::get('expenses/{id}', [ExpenseController::class, 'show'])->name('expenses.show');
    Route::post('expenses/{id}/mark-paid', [ExpenseController::class, 'markAsPaid'])->name('expenses.markAsPaid');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
