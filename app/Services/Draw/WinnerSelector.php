<?php

namespace App\Services\Draw;

use App\Models\Draw;
use App\Models\Ticket;
use App\Models\User;
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
     * Conduct the full draw with all prize tiers.
     */
    public function conductDraw(Draw $draw): void
    {
        DB::transaction(function () use ($draw) {
            $draw = Draw::where('id', $draw->id)->lockForUpdate()->first();

            if ($draw->status === 'completed') {
                throw new Exception("Draw #{$draw->id} is already completed.");
            }

            $draw->update(['status' => 'drawing']);

            $totalSales = $draw->total_sales ?? 0;
            $prizePool = $totalSales * 0.55; // 55% of total sales

            // Tier 1: 30% of prize pool (1 winner)
            $this->selectTierWinner($draw, 1, $prizePool * 0.30);

            // Tier 2: 10% of prize pool (1 winner)
            $this->selectTierWinner($draw, 2, $prizePool * 0.10);

            // Tier 3: 7% of prize pool (1 winner)
            $this->selectTierWinner($draw, 3, $prizePool * 0.07);

            // Tier 4: Lucky Draw (5% of prize pool, multiple winners)
            $this->conductLuckyDrawTier($draw, $prizePool * 0.05);

            // Tier 5: Fortune Wheel (3% of prize pool, multiple winners)
            $this->conductFortuneWheelTier($draw, $prizePool * 0.03);

            $draw->update([
                'status' => 'completed',
                'winner_selected_at' => now(),
                'winner_selection_method' => 'system'
            ]);

            $this->auditService->log('draw_completed', $draw, null, null, "Full draw completed for #{$draw->id}");
        });
    }

    /**
     * Trigger only Tier 4 (Lucky Draw).
     */
    public function triggerLuckyDraw(Draw $draw): void
    {
        DB::transaction(function () use ($draw) {
            $totalSales = $draw->total_sales ?? 0;
            $prizePool = $totalSales * 0.55;
            $this->conductLuckyDrawTier($draw, $prizePool * 0.05);
        });
    }

    /**
     * Trigger only Tier 5 (Fortune Wheel).
     */
    public function triggerFortuneWheel(Draw $draw): void
    {
        DB::transaction(function () use ($draw) {
            $totalSales = $draw->total_sales ?? 0;
            $prizePool = $totalSales * 0.55;
            $this->conductFortuneWheelTier($draw, $prizePool * 0.03);
        });
    }

    /**
     * Select a single winner for a specific tier (1, 2, or 3).
     */
    public function selectTierWinner(Draw $draw, int $tierId, float $prizeAmount): ?Ticket
    {
        // Check if manual winner already exists for this tier
        $winner = Ticket::where('draw_id', $draw->id)
            ->where('is_winner', true)
            ->where('prize_tier_id', $tierId)
            ->first();

        if ($winner) {
            $winner->update(['prize_amount' => $prizeAmount]);
            return $winner;
        }

        // Random selection
        $winner = Ticket::where('draw_id', $draw->id)
            ->where('is_winner', false)
            ->inRandomOrder()
            ->first();

        if ($winner) {
            $winner->update([
                'is_winner' => true,
                'prize_tier_id' => $tierId,
                'prize_amount' => $prizeAmount,
                'status' => 'pending_verification' // Tier 1-3 handled by agents
            ]);

            $this->auditService->log('winner_selected', $draw, null, null, "Tier {$tierId} winner: Ticket #{$winner->ticket_number}");
        }

        return $winner;
    }

    /**
     * Tier 4: Lucky Draw (matching last digit).
     */
    public function conductLuckyDrawTier(Draw $draw, float $totalTierPrize): void
    {
        $digit = $draw->winning_digit ?? rand(0, 9);
        $draw->update(['winning_digit' => $digit]);

        $winners = Ticket::where('draw_id', $draw->id)
            ->whereRaw("RIGHT(ticket_number, 1) = ?", [$digit])
            ->where('is_winner', false)
            ->get();

        if ($winners->isNotEmpty()) {
            $prizePerWinner = $totalTierPrize / $winners->count();

            foreach ($winners as $winner) {
                $winner->update([
                    'is_winner' => true,
                    'prize_tier_id' => 4,
                    'prize_amount' => $prizePerWinner,
                    'status' => 'completed'
                ]);

                // Auto-credit Tier 4 prizes
                $this->walletService->creditPrize($winner->user, (float) $prizePerWinner, "LUCKY-DRAW-D{$draw->id}");
            }
        }
    }

    /**
     * Tier 5: Fortune Wheel (purchased 50+ tickets).
     */
    public function conductFortuneWheelTier(Draw $draw, float $totalTierPrize): void
    {
        $eligibleTickets = $this->getFortuneWheelEligibleTickets($draw);

        if ($eligibleTickets->isNotEmpty()) {
            $winners = $eligibleTickets->random(min(10, $eligibleTickets->count()));

            if ($winners->isNotEmpty()) {
                $prizePerWinner = $totalTierPrize / $winners->count();

                foreach ($winners as $winner) {
                    $winner->update([
                        'is_winner' => true,
                        'prize_tier_id' => 5,
                        'prize_amount' => $prizePerWinner,
                        'status' => 'completed'
                    ]);

                    // Auto-credit Tier 5 prizes
                    $this->walletService->creditPrize($winner->user, (float) $prizePerWinner, "FORTUNE-WHEEL-D{$draw->id}");
                }
            }
        }
    }

    /**
     * Preview Tier 4 (Lucky Draw) without saving.
     */
    public function previewLuckyDraw(Draw $draw): array
    {
        $digit = rand(0, 9);
        $tickets = Ticket::where('draw_id', $draw->id)
            ->whereRaw("RIGHT(ticket_number, 1) = ?", [$digit])
            ->where('is_winner', false)
            ->with('user')
            ->get();

        $totalSales = $draw->total_sales ?? 0;
        $totalTierPrize = ($totalSales * 0.55) * 0.05;
        $prizePerWinner = $tickets->isNotEmpty() ? $totalTierPrize / $tickets->count() : 0;

        return [
            'digit' => $digit,
            'tickets' => $tickets,
            'prize_per_winner' => $prizePerWinner,
            'total_prize' => $totalTierPrize
        ];
    }

    /**
     * Preview Tier 5 (Fortune Wheel) without saving.
     */
    public function previewFortuneWheel(Draw $draw): array
    {
        $eligibleTickets = $this->getFortuneWheelEligibleTickets($draw);
        
        $winners = $eligibleTickets->isNotEmpty() 
            ? $eligibleTickets->random(min(10, $eligibleTickets->count())) 
            : collect();

        $totalSales = $draw->total_sales ?? 0;
        $totalTierPrize = ($totalSales * 0.55) * 0.03;
        $prizePerWinner = $winners->isNotEmpty() ? $totalTierPrize / $winners->count() : 0;

        return [
            'tickets' => $winners,
            'prize_per_winner' => $prizePerWinner,
            'total_prize' => $totalTierPrize
        ];
    }

    /**
     * Get eligible tickets for Fortune Wheel (50+ tickets per user).
     */
    protected function getFortuneWheelEligibleTickets(Draw $draw)
    {
        $eligibleUserIds = Ticket::where('draw_id', $draw->id)
            ->select('user_id', DB::raw('count(*) as ticket_count'))
            ->groupBy('user_id')
            ->having('ticket_count', '>=', 50)
            ->pluck('user_id');

        return Ticket::where('draw_id', $draw->id)
            ->whereIn('user_id', $eligibleUserIds)
            ->where('is_winner', false)
            ->with('user')
            ->get();
    }

    /**
     * Finalize and credit winners for algorithmic tiers.
     */
    public function finalizeTierWinners(Draw $draw, int $tierId, array $ticketIds, float $prizePerWinner, ?int $winningDigit = null): void
    {
        DB::transaction(function () use ($draw, $tierId, $ticketIds, $prizePerWinner, $winningDigit) {
            if ($winningDigit !== null) {
                $draw->update(['winning_digit' => $winningDigit]);
            }

            $tickets = Ticket::whereIn('id', $ticketIds)
                ->where('draw_id', $draw->id)
                ->get();

            foreach ($tickets as $ticket) {
                $ticket->update([
                    'is_winner' => true,
                    'prize_tier_id' => $tierId,
                    'prize_amount' => $prizePerWinner,
                    'status' => 'completed'
                ]);

                $prefix = $tierId === 4 ? 'LUCKY' : 'FORTUNE';
                $this->walletService->creditPrize($ticket->user, $prizePerWinner, "{$prefix}-DRAW-D{$draw->id}");
            }

            $this->auditService->log('tier_winners_finalized', $draw, null, null, "Tier {$tierId} finalized with " . count($ticketIds) . " winners.");
        });
    }

    /**
     * Admin manually selects a winner for a specific tier.
     */
    public function manualSelectWinner(Draw $draw, Ticket $ticket, int $tierId): void
    {
        if ($tierId > 3) {
            throw new Exception("Only Tier 1, 2, and 3 can be manually selected.");
        }

        if ($ticket->draw_id !== $draw->id) {
            throw new Exception("Ticket does not belong to this draw.");
        }

        DB::transaction(function () use ($draw, $ticket, $tierId) {
            // Remove existing winner for this tier if any
            Ticket::where('draw_id', $draw->id)
                ->where('prize_tier_id', $tierId)
                ->update([
                    'is_winner' => false,
                    'prize_tier_id' => null,
                    'prize_amount' => 0,
                    'status' => 'active'
                ]);

            $ticket->update([
                'is_winner' => true,
                'prize_tier_id' => $tierId,
                'status' => 'pending_verification'
            ]);

            $this->auditService->log('manual_winner_assignment', $draw, null, null, "Admin manually assigned Ticket #{$ticket->ticket_number} to Tier {$tierId}");
        });
    }

    /**
     * Select winner by ticket number (for the console).
     */
    public function selectWinnerByTicketNumber(Draw $draw, string $ticketNumber, int $tierId): void
    {
        $ticket = Ticket::where('draw_id', $draw->id)
            ->where('ticket_number', $ticketNumber)
            ->first();

        if (!$ticket) {
            throw new Exception("Ticket #{$ticketNumber} not found in this draw.");
        }

        $this->manualSelectWinner($draw, $ticket, $tierId);
    }
}
