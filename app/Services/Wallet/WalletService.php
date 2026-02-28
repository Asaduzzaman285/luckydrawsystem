<?php

namespace App\Services\Wallet;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Exceptions\InsufficientBalanceException;
use App\Services\Audit\AuditService;

class WalletService
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }
    /**
     * Deposit funds into user wallet.
     */
    public function deposit(User $user, float $amount, string $referenceId = null, int $processedBy = null): Transaction
    {
        return DB::transaction(function () use ($user, $amount, $referenceId, $processedBy) {
            // Get or create wallet and lock it in one go if possible
            $wallet = $user->wallet()->firstOrCreate([], ['balance' => 0]);
            $wallet = Wallet::where('id', $wallet->id)->lockForUpdate()->first();

            $wallet->increment('balance', $amount);
            $wallet->increment('lifetime_deposit', $amount);
            $wallet->update(['last_transaction_at' => now()]);

            // Create transaction first to ensure it's recorded
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'deposit',
                'amount' => $amount,
                'reference_id' => $referenceId ?? (string) Str::uuid(),
                'processed_by' => $processedBy,
                'status' => 'completed',
            ]);

            // Log outside of heavy write block if possible, but still within transaction for safety
            $this->auditService->log('wallet_deposit', $wallet, null, ['balance' => $wallet->balance], "Deposited {$amount}");

            return $transaction;
        });
    }

    /**
     * Withdraw funds from user wallet.
     */
    public function withdraw(User $user, float $amount, string $referenceId = null, int $processedBy = null): Transaction
    {
        return DB::transaction(function () use ($user, $amount, $referenceId, $processedBy) {
            $wallet = $user->wallet()->lockForUpdate()->first();

            if (!$wallet || $wallet->balance < $amount) {
                throw new \Exception('Insufficient balance');
            }

            $wallet->decrement('balance', $amount);
            $wallet->increment('lifetime_withdrawal', $amount);
            $wallet->update(['last_transaction_at' => now()]);

            $this->auditService->log('wallet_withdrawal', $wallet, null, ['balance' => $wallet->balance], "Withdrew {$amount}");

            return Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'withdrawal',
                'amount' => $amount,
                'reference_id' => $referenceId ?? (string) Str::uuid(),
                'processed_by' => $processedBy,
                'status' => 'completed',
            ]);
        });
    }

    /**
     * Purchase a ticket using wallet balance.
     */
    public function purchaseTicket(User $user, float $amount, string $referenceId = null, int $processedBy = null): Transaction
    {
        return DB::transaction(function () use ($user, $amount, $referenceId, $processedBy) {
            $wallet = $user->wallet()->lockForUpdate()->first();

            if (!$wallet || $wallet->balance < $amount) {
                throw new \Exception('Insufficient balance for ticket purchase');
            }

            $wallet->decrement('balance', $amount);
            $wallet->increment('lifetime_withdrawal', $amount);
            $wallet->update(['last_transaction_at' => now()]);

            $this->auditService->log('ticket_purchase_payment', $wallet, null, ['balance' => $wallet->balance], "Paid {$amount} for ticket");

            return Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'ticket_purchase',
                'amount' => $amount,
                'reference_id' => $referenceId ?? (string) Str::uuid(),
                'processed_by' => $processedBy,
                'status' => 'completed',
            ]);
        });
    }

    /**
     * Credit prize money.
     */
    public function creditPrize(User $user, float $amount, string $referenceId = null, int $processedBy = null): Transaction
    {
        return DB::transaction(function () use ($user, $amount, $referenceId, $processedBy) {
            $wallet = $user->wallet()->firstOrCreate([], ['balance' => 0]);
            $wallet = Wallet::where('id', $wallet->id)->lockForUpdate()->first();

            $wallet->increment('balance', $amount);
            $wallet->increment('lifetime_deposit', $amount);
            $wallet->update(['last_transaction_at' => now()]);

            $this->auditService->log('prize_credit', $wallet, null, ['balance' => $wallet->balance], "Credited prize {$amount}");

            return Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'prize_credit',
                'amount' => $amount,
                'reference_id' => $referenceId ?? (string) Str::uuid(),
                'processed_by' => $processedBy,
                'status' => 'completed',
            ]);
        });
    }

    /**
     * Atomically transfer funds between two users.
     */
    public function transfer(User $sender, User $receiver, float $amount, string $referenceId = null): Transaction
    {
        return DB::transaction(function () use ($sender, $receiver, $amount, $referenceId) {
            $senderWallet = $sender->wallet()->lockForUpdate()->first();
            $receiverWallet = $receiver->wallet()->firstOrCreate([], ['balance' => 0]);
            $receiverWallet = Wallet::where('id', $receiverWallet->id)->lockForUpdate()->first();

            if (!$senderWallet || $senderWallet->balance < $amount) {
                throw new \Exception('Insufficient balance for transfer');
            }

            // Deduct from sender
            $senderWallet->decrement('balance', $amount);
            $senderWallet->update(['last_transaction_at' => now()]);

            // Add to receiver
            $receiverWallet->increment('balance', $amount);
            $receiverWallet->increment('lifetime_deposit', $amount);
            $receiverWallet->update(['last_transaction_at' => now()]);

            $ref = $referenceId ?? (string) Str::uuid();

            // Log transaction for sender (Debit)
            Transaction::create([
                'user_id' => $sender->id,
                'wallet_id' => $senderWallet->id,
                'type' => 'transfer_out',
                'amount' => $amount,
                'reference_id' => (string) Str::uuid(),
                'processed_by' => auth()->id(),
                'status' => 'completed',
                'description' => "Transfer to {$receiver->name} (Ref: {$ref})",
            ]);

            // Log transaction for receiver (Credit)
            return Transaction::create([
                'user_id' => $receiver->id,
                'wallet_id' => $receiverWallet->id,
                'type' => 'transfer_in',
                'amount' => $amount,
                'reference_id' => (string) Str::uuid(),
                'processed_by' => auth()->id(),
                'status' => 'completed',
                'description' => "Transfer from {$sender->name} (Ref: {$ref})",
            ]);
        });
    }
}
