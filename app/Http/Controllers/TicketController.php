<?php

namespace App\Http\Controllers;

use App\Models\Draw;
use App\Models\Ticket;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Handle the purchase of a ticket.
     */
    public function purchase(Request $request, Draw $draw)
    {
        if ($draw->status !== 'live') {
            return back()->with('error', 'This draw is not currently active.');
        }

        if ($draw->max_tickets && $draw->tickets()->count() >= $draw->max_tickets) {
            return back()->with('error', 'This draw has reached its maximum ticket limit.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($draw) {
                // 1. Deduct balance via WalletService
                $reference = "DRAW-PURCHASE-U" . Auth::id() . "-D" . $draw->id . "-" . uniqid();
                $transaction = $this->walletService->purchaseTicket(Auth::user(), (float) $draw->ticket_price, $reference);

                // 2. Create the ticket record
                Ticket::create([
                    'user_id' => Auth::id(),
                    'draw_id' => $draw->id,
                    'transaction_id' => $transaction->id,
                    'ticket_number' => 'TKT-' . strtoupper(bin2hex(random_bytes(4))),
                    'purchase_price' => $draw->ticket_price,
                    'status' => 'active',
                ]);

                // 3. Increment sold_tickets count on the Draw
                $draw->increment('sold_tickets');
            });

            return back()->with('success', 'Ticket purchased successfully! Good luck!');
        } catch (\Exception $e) {
            return back()->with('error', 'Purchase failed: ' . $e->getMessage());
        }
    }
}
