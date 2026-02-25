<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(): JsonResponse
    {
        $users = User::with('wallets')->get();
        
        // Add total balance to each user
        $users->each(function ($user) {
            $user->total_balance = $user->total_balance;
        });
        
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    /**
     * Display the specified user with their wallets and balances.
     */
    public function show(User $user): JsonResponse
    {
        // Load wallets with their current balances
        $user->load('wallets');
        
        // Add total balance to response
        $user->total_balance = $user->total_balance;

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'total_balance' => $user->total_balance,
                'wallets' => $user->wallets->map(function ($wallet) {
                    return [
                        'id' => $wallet->id,
                        'name' => $wallet->name,
                        'description' => $wallet->description,
                        'balance' => $wallet->balance,
                    ];
                }),
            ]
        ]);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}