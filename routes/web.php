<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvitationController;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', [ColocationController::class, 'index'])->name('dashboard');
    Route::get('/colocations/{id}', [ColocationController::class, 'show'])->name('colocations.show');
    Route::get('expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('expenses/{id}', [ExpenseController::class, 'show'])->name('expenses.show');
    Route::post('expenses/{id}/mark-paid', [ExpenseController::class, 'markAsPaid'])->name('expenses.markAsPaid');
    Route::get('/colocations/{id}/invite', [ColocationController::class, 'invite'])->name('colocations.invite');
    Route::get('colocations/join/{token}', [ColocationController::class, 'showInvitation'])->name('colocations.invitation');
    Route::post('colocations/join/{token}', [ColocationController::class, 'join'])->name('colocations.join');
    Route::delete('/colocations/{id}/leave', [ColocationController::class, 'leave'])->name('colocations.leave');
    Route::delete('colocations/{colocationId}/remove/{memberId}', [ColocationController::class, 'removeMember'])->name('colocations.removeMember');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
