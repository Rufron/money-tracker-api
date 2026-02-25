<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// User routes
Route::apiResource('users', UserController::class)->except(['update']);

// Wallet routes
Route::apiResource('wallets', WalletController::class)->except(['update']);

// Transaction routes
Route::apiResource('transactions', TransactionController::class)->except(['update']);

// Additional custom routes
Route::prefix('users/{user}')->group(function () {
    Route::get('wallets', [UserController::class, 'wallets']); // Already handled in show method
});

Route::prefix('wallets/{wallet}')->group(function () {
    Route::get('transactions', [WalletController::class, 'transactions']); // Already handled in show method
});