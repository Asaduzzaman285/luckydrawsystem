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
     * Check if all required prizes (Tiers 1-5) are selected and auto-complete draw.
     */
    protected function checkAndAutoCompleteDraw(Draw $draw): void
    {
        $requiredTiers = [1, 2, 3, 4, 5];
        $selectedTiers = Ticket::where('draw_id', $draw->id)
            ->where('is_winner', true)
            ->distinct()
            ->pluck('prize_tier_id')
            ->toArray();

        $allTiersPresent = true;
        foreach ($requiredTiers as $tier) {
            if (!in_array($tier, $selectedTiers)) {
                $allTiersPresent = false;
                break;
            }
        }

        if ($allTiersPresent) {
            $draw->update([
                'status' => 'completed',
                'winner_selected_at' => now()
            ]);
            $this->auditService->log('draw_auto_completed', $draw, null, null, "Draw #{$draw->id} auto-completed: all 5 prize tiers selected.");
        }
    }

    /**
     * Get fresh total sales for calculation accuracy.
     */
    protected function getFreshTotalSales(Draw $draw): float
    {
        return (float) Ticket::where('draw_id', $draw->id)->sum('purchase_price');
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

            $totalSales = $this->getFreshTotalSales($draw);
            if ($totalSales <= 0) {
                throw new Exception("Draw #{$draw->id} has zero sales. Cannot conduct draw.");
            }

            // Sync total sales back to draw record
            $draw->update([
                'status' => 'drawing',
                'total_sales' => $totalSales,
                'prize_pool_total' => $totalSales * 0.55
            ]);

            $prizePool = $totalSales * 0.55;

            // Manual Tiers
            $this->selectTierWinner($draw, 1, $prizePool * 0.30);
            $this->selectTierWinner($draw, 2, $prizePool * 0.10);
            $this->selectTierWinner($draw, 3, $prizePool * 0.07);

            // Algorithmic Tiers
            $this->conductLuckyDrawTier($draw, $prizePool * 0.05);
            $this->conductFortuneWheelTier($draw, $prizePool * 0.03);

            $this->checkAndAutoCompleteDraw($draw);
            $this->auditService->log('draw_completed_sequence', $draw, null, null, "Full draw sequence triggered for #{$draw->id}");
        });
    }

    /**
     * Select a single winner for a specific tier (1, 2, or 3).
     */
    public function selectTierWinner(Draw $draw, int $tierId, float $prizeAmount): ?Ticket
    {
        $winner = Ticket::where('draw_id', $draw->id)
            ->where('is_winner', true)
            ->where('prize_tier_id', $tierId)
            ->first();

        if ($winner) {
            $winner->update([
                'prize_amount' => $prizeAmount,
                'status' => 'pending_verification'
            ]);
            return $winner;
        }

        $winner = Ticket::where('draw_id', $draw->id)
            ->where('is_winner', false)
            ->inRandomOrder()
            ->first();

        if ($winner) {
            $winner->update([
                'is_winner' => true,
                'prize_tier_id' => $tierId,
                'prize_amount' => $prizeAmount,
                'status' => 'pending_verification'
            ]);
            $this->checkAndAutoCompleteDraw($draw);
        }

        return $winner;
    }

    /**
     * Tier 4: Lucky Draw (matching last digit).
     */
    public function conductLuckyDrawTier(Draw $draw, float $totalTierPrize): void
    {
        $digit = $draw->winning_digit ?? str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
        $draw->update(['winning_digit' => $digit]);

        $winners = Ticket::where('draw_id', $draw->id)
            ->whereRaw("RIGHT(ticket_number, 2) = ?", [$digit])
            ->where('is_winner', false)
            ->get();

        if ($winners->isEmpty()) {
            // Updated behavior: Don't throw exception, just log that no one won this tier.
            $this->auditService->log('draw_no_winners_t4', $draw, null, null, "No winners for Tier 4 (Lucky Draw) with digit '{$digit}'.");
            $this->checkAndAutoCompleteDraw($draw);
            return;
        }

        $prizePerWinner = $totalTierPrize / $winners->count();

            foreach ($winners as $winner) {
                $winner->update([
                    'is_winner' => true,
                    'prize_tier_id' => 4,
                    'prize_amount' => $prizePerWinner,
                    'status' => 'completed'
                ]);
                $this->walletService->creditPrize($winner->user, (float) $prizePerWinner, "LUCKY-DRAW-D{$draw->id}");
            }
            $this->checkAndAutoCompleteDraw($draw);
    }

    /**
     * Tier 5: Fortune Wheel.
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
                    $this->walletService->creditPrize($winner->user, (float) $prizePerWinner, "FORTUNE-WHEEL-D{$draw->id}");
                }
                $this->checkAndAutoCompleteDraw($draw);
            }
        }
    }

    /**
     * Finalize winners for algorithmic tiers.
     */
    public function finalizeTierWinners(Draw $draw, int $tierId, array $ticketIds, float $prizePerWinner, ?int $winningDigit = null): void
    {
        DB::transaction(function () use ($draw, $tierId, $ticketIds, $prizePerWinner, $winningDigit) {
            if ($winningDigit !== null) {
                $draw->update(['winning_digit' => $winningDigit]);
            }

            $tickets = Ticket::whereIn('id', $ticketIds)
                ->where('draw_id', $draw->id)
                ->where('is_winner', false)
                ->get();

            // Crucial: Recalculate prize based on how many tickets were actually assigned
            // If user passed a pre-calculated prize but the ticket count changed, we re-verify
            $totalSales = $this->getFreshTotalSales($draw);
            $tierPercent = $tierId === 4 ? 0.05 : 0.03;
            $totalTierPrize = ($totalSales * 0.55) * $tierPercent;
            $actualPrizePerWinner = $tickets->count() > 0 ? $totalTierPrize / $tickets->count() : 0;

            foreach ($tickets as $ticket) {
                $ticket->update([
                    'is_winner' => true,
                    'prize_tier_id' => $tierId,
                    'prize_amount' => $actualPrizePerWinner,
                    'status' => 'completed'
                ]);

                $prefix = $tierId === 4 ? 'LUCKY' : 'FORTUNE';
                $this->walletService->creditPrize($ticket->user, $actualPrizePerWinner, "{$prefix}-DRAW-D{$draw->id}");
            }
            $this->checkAndAutoCompleteDraw($draw);
        });
    }

    /**
     * Manual selection for Tiers 1-3.
     */
    public function manualSelectWinner(Draw $draw, Ticket $ticket, int $tierId): void
    {
        if ($tierId > 3) throw new Exception("Manual selection for 1-3 only.");
        if ($ticket->draw_id !== $draw->id) throw new Exception("Ticket mismatch.");
        if ($ticket->is_winner && $ticket->prize_tier_id !== $tierId) throw new Exception("Exclusion: Already a winner.");

        DB::transaction(function () use ($draw, $ticket, $tierId) {
            Ticket::where('draw_id', $draw->id)->where('prize_tier_id', $tierId)->update(['is_winner' => false, 'prize_tier_id' => null, 'prize_amount' => 0, 'status' => 'active']);

            $totalSales = $this->getFreshTotalSales($draw);
            $prizePool = $totalSales * 0.55;
            $share = [1 => 0.30, 2 => 0.10, 3 => 0.07][$tierId];
            $prizeAmount = $prizePool * $share;

            $ticket->update(['is_winner' => true, 'prize_tier_id' => $tierId, 'prize_amount' => $prizeAmount, 'status' => 'pending_verification']);
            $this->checkAndAutoCompleteDraw($draw);
        });
    }

    public function finalizePrizeFulfillment(Ticket $ticket): void
    {
        if (!in_array($ticket->prize_tier_id, [1, 2, 3])) throw new Exception("Manual tiers only.");
        if ($ticket->status !== 'pending_verification') throw new Exception("Invalid status.");
        $ticket->update(['status' => 'completed', 'processed_at' => now(), 'processed_by' => auth()->id()]);
    }

    public function selectWinnerByTicketNumber(Draw $draw, string $ticketNumber, int $tierId): void
    {
        $ticket = Ticket::where('draw_id', $draw->id)->where('ticket_number', $ticketNumber)->firstOrFail();
        $this->manualSelectWinner($draw, $ticket, $tierId);
    }

    protected function getFortuneWheelEligibleTickets(Draw $draw)
    {
        $eligibleUserIds = Ticket::where('draw_id', $draw->id)
            ->select('user_id', DB::raw('count(*) as ticket_count'))
            ->groupBy('user_id')
            ->having('ticket_count', '>=', 1)
            ->pluck('user_id');

        return Ticket::where('draw_id', $draw->id)
            ->whereIn('user_id', $eligibleUserIds)
            ->where('is_winner', false)
            ->with('user')
            ->get();
    }

    public function previewLuckyDraw(Draw $draw): array
    {
        $digit = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
        $tickets = Ticket::where('draw_id', $draw->id)->whereRaw("RIGHT(ticket_number, 2) = ?", [$digit])->where('is_winner', false)->with('user')->get();
        $totalSales = $this->getFreshTotalSales($draw);
        $totalTierPrize = ($totalSales * 0.55) * 0.05;
        $prizePerWinner = $tickets->isNotEmpty() ? $totalTierPrize / $tickets->count() : 0;
        return ['digit' => $digit, 'tickets' => $tickets, 'prize_per_winner' => $prizePerWinner, 'total_prize' => $totalTierPrize];
    }

    public function previewFortuneWheel(Draw $draw): array
    {
        $eligibleTickets = $this->getFortuneWheelEligibleTickets($draw);
        $winners = $eligibleTickets->isNotEmpty() ? $eligibleTickets->random(min(10, $eligibleTickets->count())) : collect();
        $totalSales = $this->getFreshTotalSales($draw);
        $totalTierPrize = ($totalSales * 0.55) * 0.03;
        $prizePerWinner = $winners->isNotEmpty() ? $totalTierPrize / $winners->count() : 0;
        return ['tickets' => $winners, 'prize_per_winner' => $prizePerWinner, 'total_prize' => $totalTierPrize];
    }
}
