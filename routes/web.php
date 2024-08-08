<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\CryptocurrencyController;
use App\Http\Controllers\CurrencySwitchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/transfer', [TransactionController::class, 'transfer'])->name('transfer');
    Route::get('/investments', [InvestmentController::class, 'index'])->name('investments');
    Route::get('/cryptocurrencies', [CryptocurrencyController::class, 'index'])->name('cryptocurrencies');
    Route::post('/switch-currency', [CurrencySwitchController::class, 'switch'])->name('switch-currency');
});

require __DIR__.'/auth.php';
