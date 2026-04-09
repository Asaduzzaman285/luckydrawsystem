<?php

namespace App\Services\Wallet;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
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
        Log::debug("Starting wallet deposit", [
            'user_id' => $user->id,
            'amount' => $amount,
            'provided_ref' => $referenceId,
            'processed_by' => $processedBy
        ]);

        return DB::transaction(function () use ($user, $amount, $referenceId, $processedBy) {
            // Get or create wallet and lock it in one go if possible
            $wallet = $user->wallet()->firstOrCreate([], ['balance' => 0]);
            $wallet = Wallet::where('id', $wallet->id)->lockForUpdate()->first();

            $wallet->increment('balance', $amount);
            $wallet->increment('lifetime_deposit', $amount);
            $wallet->update(['last_transaction_at' => now()]);
            // No increment to lifetime_winnings here as this is a standard deposit.

            // Create transaction first to ensure it's recorded
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'deposit',
                'amount' => $amount,
                'reference_id' => $this->makeUniqueReference($referenceId),
                'processed_by' => $processedBy,
                'status' => 'completed',
            ]);

            // Log outside of heavy write block if possible, but still within transaction for safety
            $this->auditService->log('wallet_deposit', $wallet, null, ['balance' => $wallet->balance], "Deposited {$amount}");

            Log::info("Wallet deposit completed", ['transaction_id' => $transaction->id, 'ref' => $transaction->reference_id]);

            return $transaction;
        });
    }

    /**
     * Withdraw funds from user wallet.
     */
    public function withdraw(User $user, float $amount, string $referenceId = null, int $processedBy = null): Transaction
    {
        Log::debug("Starting wallet withdrawal", [
            'user_id' => $user->id,
            'amount' => $amount,
            'provided_ref' => $referenceId
        ]);

        return DB::transaction(function () use ($user, $amount, $referenceId, $processedBy) {
            $wallet = $user->wallet()->lockForUpdate()->first();

            if (!$wallet || $wallet->balance < $amount) {
                throw new \Exception('Insufficient balance');
            }

            $wallet->decrement('balance', $amount);
            $wallet->increment('lifetime_withdrawals', $amount);
            $wallet->update(['last_transaction_at' => now()]);

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'withdrawal',
                'amount' => $amount,
                'reference_id' => $this->makeUniqueReference($referenceId),
                'processed_by' => $processedBy,
                'status' => 'completed',
            ]);

            return $transaction;
        });
    }

    /**
     * Refund a previously deducted withdrawal.
     */
    public function refundWithdrawal(User $user, float $amount, string $referenceId = null, int $processedBy = null): Transaction
    {
        return DB::transaction(function () use ($user, $amount, $referenceId, $processedBy) {
            $wallet = $user->wallet()->firstOrCreate([], ['balance' => 0]);
            $wallet = Wallet::where('id', $wallet->id)->lockForUpdate()->first();

            $wallet->increment('balance', $amount);
            $wallet->decrement('lifetime_withdrawals', $amount);
            $wallet->update(['last_transaction_at' => now()]);

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'deposit',
                'amount' => $amount,
                'reference_id' => $this->makeUniqueReference($referenceId),
                'processed_by' => $processedBy,
                'status' => 'completed',
                'description' => "Withdrawal Refund: {$referenceId}",
            ]);

            return $transaction;
        });
    }

    /**
     * Purchase a ticket using wallet balance.
     */
    public function purchaseTicket(User $user, float $amount, string $referenceId = null, int $processedBy = null): Transaction
    {
        Log::debug("Starting ticket purchase payment", [
            'user_id' => $user->id,
            'amount' => $amount,
            'provided_ref' => $referenceId
        ]);

        return DB::transaction(function () use ($user, $amount, $referenceId, $processedBy) {
            $wallet = $user->wallet()->lockForUpdate()->first();

            if (!$wallet || $wallet->balance < $amount) {
                throw new \Exception('Insufficient balance for ticket purchase');
            }

            $wallet->decrement('balance', $amount);
            $wallet->update(['last_transaction_at' => now()]);

            $this->auditService->log('ticket_purchase_payment', $wallet, null, ['balance' => $wallet->balance], "Paid {$amount} for ticket");

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'ticket_purchase',
                'amount' => $amount,
                'reference_id' => $this->makeUniqueReference($referenceId),
                'processed_by' => $processedBy,
                'status' => 'completed',
            ]);

            Log::info("Ticket purchase payment completed", ['transaction_id' => $transaction->id, 'ref' => $transaction->reference_id]);

            return $transaction;
        });
    }

    /**
     * Credit prize money.
     */
    public function creditPrize(User $user, float $amount, string $referenceId = null, int $processedBy = null): Transaction
    {
        Log::debug("Starting prize credit", [
            'user_id' => $user->id,
            'amount' => $amount,
            'provided_ref' => $referenceId
        ]);

        return DB::transaction(function () use ($user, $amount, $referenceId, $processedBy) {
            $wallet = $user->wallet()->firstOrCreate([], ['balance' => 0]);
            $wallet = Wallet::where('id', $wallet->id)->lockForUpdate()->first();

            $wallet->increment('balance', $amount);
            $wallet->increment('lifetime_winnings', $amount); // Track as price money
            $wallet->update(['last_transaction_at' => now()]);

            $this->auditService->log('prize_credit', $wallet, null, ['balance' => $wallet->balance], "Credited prize {$amount}");

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'prize_credit',
                'amount' => $amount,
                'reference_id' => $this->makeUniqueReference($referenceId),
                'processed_by' => $processedBy,
                'status' => 'completed',
            ]);

            Log::info("Prize credit completed", ['transaction_id' => $transaction->id, 'ref' => $transaction->reference_id]);

            return $transaction;
        });
    }

    /**
     * Atomically transfer funds between two users.
     */
    public function transfer(User $sender, User $receiver, float $amount, string $referenceId = null): Transaction
    {
        Log::debug("Starting wallet transfer", [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'amount' => $amount,
            'provided_ref' => $referenceId
        ]);

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

            $ref = $this->makeUniqueReference($referenceId);

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
            $transaction = Transaction::create([
                'user_id' => $receiver->id,
                'wallet_id' => $receiverWallet->id,
                'type' => 'transfer_in',
                'amount' => $amount,
                'reference_id' => $ref,
                'processed_by' => auth()->id(),
                'status' => 'completed',
                'description' => "Transfer from {$sender->name} (Ref: {$ref})",
            ]);

            Log::info("Wallet transfer completed", ['transaction_id' => $transaction->id, 'ref' => $transaction->reference_id]);

            return $transaction;
        });
    }

    /**
     * Get the withdrawable balance (Price Money Only).
     * Formula: min(balance, lifetime_winnings - lifetime_withdrawals)
     */
    public function getWithdrawableBalance(User $user): float
    {
        $wallet = $user->wallet()->firstOrCreate([], ['balance' => 0]);
        
        $winningsLimit = (float) ($wallet->lifetime_winnings - $wallet->lifetime_withdrawals);
        
        // Ensure limit is at least 0
        $winningsLimit = max(0, $winningsLimit);

        return (float) min($wallet->balance, $winningsLimit);
    }

    /**
     * Generate a unique reference ID.
     */
    protected function makeUniqueReference(?string $provided = null): string
    {
        // Enforce maximum length of 36 characters (UUID length)
        if ($provided && strlen($provided) <= 36) {
            // Check if it already exists
            if (!Transaction::where('reference_id', $provided)->exists()) {
                return $provided;
            }
            Log::warning("Duplicate reference ID provided, generating a new one", ['provided_ref' => $provided]);
        }

        return (string) Str::uuid();
    }
}
