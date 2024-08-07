<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/exchange-rate', [PaymentController::class, 'getExchangeRate']);

// If you want to protect the exchange rate route, you can use the auth:sanctum middleware
// Route::middleware('auth:sanctum')->get('/exchange-rate', [PaymentController::class, 'getExchangeRate']);

// You might want to group payment-related routes
Route::prefix('payments')->group(function () {
    Route::post('/', [PaymentController::class, 'store']);
    // Add more payment-related routes here as needed
});
