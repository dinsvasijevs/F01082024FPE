<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\CryptocurrencyController;
use App\Http\Controllers\CurrencySwitchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/transfer', [TransactionsController::class, 'transfer'])->name('transfer');
    Route::get('/cryptocurrencies', [CryptocurrencyController::class, 'index'])->name('cryptocurrencies');
    Route::post('/switch-currency', [CurrencySwitchController::class, 'switch'])->name('switch-currency');
    Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
    Route::get('/transactions', [TransactionsController::class, 'index'])->name('transactions.index');

    // Investment routes
    Route::get('/investments', [InvestmentController::class, 'index'])->name('investments.index');
    Route::post('/investments/trade', [InvestmentController::class, 'trade'])->name('investments.trade');

    // Cryptocurrency routes
    Route::post('/cryptocurrencies/sell', [CryptocurrencyController::class, 'sell'])->name('cryptocurrencies.sell');
    Route::post('/cryptocurrencies/buy', [CryptocurrencyController::class, 'buy'])->name('cryptocurrencies.buy');
    Route::post('/cryptocurrencies/sell', [CryptocurrencyController::class, 'sell'])->name('cryptocurrencies.sell');
});

require __DIR__.'/auth.php';
