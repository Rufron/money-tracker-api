<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Http\Requests\StoreTransactionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of all transactions.
     */
    public function index(): JsonResponse
    {
        $transactions = Transaction::with('wallet.user')->get();

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    /**
     * Store a newly created transaction in storage.
     * Updates the wallet balance accordingly.
     */
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        // Use database transaction to ensure data consistency
        return DB::transaction(function () use ($request) {
            // Create the transaction
            $transaction = Transaction::create($request->validated());

            // Get the associated wallet
            $wallet = Wallet::findOrFail($request->wallet_id);

            // Update wallet balance based on transaction type
            if ($request->type === 'income') {
                $wallet->balance += $request->amount;
            } else { // expense
                $wallet->balance -= $request->amount;
            }

            $wallet->save();

            // Load relationships for response
            $transaction->load('wallet');

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully',
                'data' => [
                    'transaction' => $transaction,
                    'updated_wallet_balance' => $wallet->balance
                ]
            ], 201);
        });
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction): JsonResponse
    {
        $transaction->load('wallet.user');

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }

    /**
     * Remove the specified transaction from storage.
     * Reverts the effect on wallet balance.
     */
    public function destroy(Transaction $transaction): JsonResponse
    {
        return DB::transaction(function () use ($transaction) {
            $wallet = $transaction->wallet;
            
            // Revert the effect on wallet balance
            if ($transaction->type === 'income') {
                $wallet->balance -= $transaction->amount;
            } else { // expense
                $wallet->balance += $transaction->amount;
            }
            
            $wallet->save();
            
            // Delete the transaction
            $transaction->delete();

            return response()->json([
                'success' => true,
                'message' => 'Transaction deleted successfully and wallet balance updated'
            ]);
        });
    }
}