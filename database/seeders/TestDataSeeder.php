<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            // 'password' => Hash::make('password123'),
        ]);

        // Create wallets for the user
        $personalWallet = Wallet::create([
            'name' => 'Personal',
            'description' => 'Personal expenses',
            'user_id' => $user->id,
            'balance' => 0
        ]);

        $businessWallet = Wallet::create([
            'name' => 'Business',
            'description' => 'Business account',
            'user_id' => $user->id,
            'balance' => 0
        ]);

        // Add some transactions
        Transaction::create([
            'description' => 'Salary',
            'amount' => 5000,
            'type' => 'income',
            'wallet_id' => $personalWallet->id
        ]);

        Transaction::create([
            'description' => 'Groceries',
            'amount' => 150,
            'type' => 'expense',
            'wallet_id' => $personalWallet->id
        ]);

        Transaction::create([
            'description' => 'Client payment',
            'amount' => 10000,
            'type' => 'income',
            'wallet_id' => $businessWallet->id
        ]);

        // Update wallet balances
        $personalWallet->balance = 5000 - 150;
        $personalWallet->save();
        
        $businessWallet->balance = 10000;
        $businessWallet->save();
    }
}