<?php

namespace App\Services\Draw;

use App\Models\Draw;
use App\Models\Ticket;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;
use App\Services\Audit\AuditService;
use Exception;

class WinnerSelector
{
    protected $walletService;
    protected $auditService;

    public function __construct(WalletService $walletService, AuditService $auditService)
    {
        $this->walletService = $walletService;
        $this->auditService = $auditService;
    }

    /**
     * Select a winner for a specific draw.
     */
    public function selectWinner(Draw $draw): Ticket
    {
        return DB::transaction(function () use ($draw) {
            // 1. Validate draw state (Locking for update)
            $draw = Draw::where('id', $draw->id)->lockForUpdate()->first();

            if ($draw->status !== 'drawing') {
                throw new Exception("Draw must be in 'drawing' state. Current: {$draw->status}");
            }

            // 2. Generate verifiable seed as per spec
            $seed = hash(
                'sha256',
                $draw->id .
                now()->timestamp .
                $draw->sold_tickets .
                config('app.key')
            );

            // 3. Store seed for future verification
            $draw->update(['seed_hash' => $seed]);

            // 4. Seeded random selection
            $winner = Ticket::where('draw_id', $draw->id)
                ->where('is_winner', false)
                ->inRandomOrder($seed)
                ->first();

            if (!$winner) {
                throw new Exception('No tickets found for this draw.');
            }

            // 5. Mark as winner with metadata
            $winner->update([
                'is_winner' => true,
                'prize_amount' => $draw->total_pool > 0 ? $draw->total_pool : ($draw->sold_tickets * $draw->ticket_price * 0.9),
                'metadata' => array_merge($winner->metadata ?? [], [
                    'seed_hash' => $seed,
                    'selection_time' => now()->toIso8601String()
                ])
            ]);

            // 6. Audit trail using AuditService
            $this->auditService->log('winner_selected', $draw, null, null, "Ticket #{$winner->ticket_number} selected as winner with seed {$seed}");

            // 7. Credit prize money
            $this->walletService->creditPrize($winner->user, (float) $winner->prize_amount, "PRIZE-DRAW-{$draw->id}");

            // 8. Update draw status
            $draw->update([
                'status' => 'completed',
                'winner_selected_at' => now(),
                'winner_selection_method' => 'system'
            ]);

            $this->auditService->log('draw_completed', $draw, ['status' => 'drawing'], ['status' => 'completed'], "Draw #{$draw->id} completed");

            return $winner;
        });
    }
}
