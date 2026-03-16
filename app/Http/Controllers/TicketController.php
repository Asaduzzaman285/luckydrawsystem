<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Draw;
use App\Models\Ticket;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Handle the purchase of a product.
     */
    public function purchase(Request $request, Product $product)
    {
        $draw = $product->draw;

        if (!$draw || $draw->status !== 'live') {
            return back()->with('error', 'The draw for this product is not currently active.');
        }

        if ($draw->isLocked()) {
            return back()->with('error', 'This draw is currently locked for processing. No further purchases allowed.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = (int) $request->quantity;
        $totalPrice = (float) ($product->price * $quantity);
        $totalEntries = $quantity;

        if ($draw->max_tickets && ($draw->sold_tickets + $totalEntries) > $draw->max_tickets) {
            return back()->with('error', 'This draw does not have enough remaining tickets for your request.');
        }

        try {
            DB::transaction(function () use ($product, $draw, $quantity, $totalPrice, $totalEntries) {
                // 1. Deduct balance via WalletService
                $reference = "PROD-PURCHASE-U" . Auth::id() . "-P" . $product->id . "-" . uniqid();
                $transaction = $this->walletService->purchaseTicket(Auth::user(), $totalPrice, $reference);

                // 2. Create the ticket records
                for ($i = 0; $i < $totalEntries; $i++) {
                    Ticket::create([
                        'user_id' => Auth::id(),
                        'draw_id' => $draw->id,
                        'product_id' => $product->id,
                        'transaction_id' => $transaction->id,
                        'ticket_number' => strtoupper(bin2hex(random_bytes(4))),
                        'purchase_price' => $product->price, // Simple product price
                        'status' => 'active',
                    ]);
                }

                // 3. Update sold_tickets and total_sales on the Draw
                $draw->increment('sold_tickets', $totalEntries);
                $draw->increment('total_sales', $totalPrice);
                
                // Update prize_pool_total (55% of total sales)
                $draw->update(['prize_pool_total' => $draw->total_sales * 0.55]);
            });

            return back()->with('success', 'Product purchased successfully! You received ' . $totalEntries . ' entries. Good luck!');
        } catch (\Exception $e) {
            return back()->with('error', 'Purchase failed: ' . $e->getMessage());
        }
    }
}
