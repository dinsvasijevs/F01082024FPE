<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\CryptocurrencyController;
use App\Http\Controllers\CurrencySwitchController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/investments', [InvestmentController::class, 'index'])->middleware(['auth'])->name('investments');
Route::get('/cryptocurrencies', [CryptocurrencyController::class, 'index'])->middleware(['auth'])->name('cryptocurrencies');
Route::post('/switch-currency', [CurrencySwitchController::class, 'switch'])->middleware(['auth'])->name('switch-currency');

require __DIR__.'/auth.php';
