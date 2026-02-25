<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Http\Requests\StoreWalletRequest;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    /**
     * Display a listing of all wallets.
     */
    public function index(): JsonResponse
    {
        $wallets = Wallet::with('user')->get();

        return response()->json([
            'success' => true,
            'data' => $wallets
        ]);
    }

    /**
     * Store a newly created wallet in storage.
     */
    public function store(StoreWalletRequest $request): JsonResponse
    {
        $wallet = Wallet::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'balance' => 0 // New wallets start with zero balance
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Wallet created successfully',
            'data' => $wallet
        ], 201);
    }

    /**
     * Display the specified wallet with its transactions and balance.
     */
    public function show(Wallet $wallet): JsonResponse
    {
        // Load transactions for this wallet
        $wallet->load('transactions');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $wallet->id,
                'name' => $wallet->name,
                'description' => $wallet->description,
                'balance' => $wallet->balance,
                'user_id' => $wallet->user_id,
                'transactions' => $wallet->transactions->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'description' => $transaction->description,
                        'amount' => $transaction->amount,
                        'type' => $transaction->type,
                        'created_at' => $transaction->created_at->toDateTimeString(),
                    ];
                }),
            ]
        ]);
    }

    /**
     * Remove the specified wallet from storage.
     */
    public function destroy(Wallet $wallet): JsonResponse
    {
        $wallet->delete();

        return response()->json([
            'success' => true,
            'message' => 'Wallet deleted successfully'
        ]);
    }
}