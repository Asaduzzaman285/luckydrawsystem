<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Draw;

class DrawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $draws = Draw::latest()->paginate(20);
        
        $stats = [
            'total_draws' => Draw::count(),
            'active_this_month' => Draw::whereMonth('created_at', now()->month)->count(),
            'live_now' => Draw::where('status', 'live')->count(),
            'tickets_sold' => Draw::sum('sold_tickets'),
            'total_capacity' => Draw::sum('max_tickets') ?: 1000, // Fallback if no capacity defined
            'total_revenue' => Draw::sum('total_sales'),
        ];

        return view('admin.draws.index', compact('draws', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.draws.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'max_tickets' => 'nullable|integer|min:1',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'draw_time' => 'required|date|after:end_time',
            'winning_digit' => 'nullable|integer|min:0|max:9',
        ]);

        $draw = Draw::create(array_merge($validated, [
            'status' => 'live',
            'ticket_price' => 0, // No longer used, managed by Products
            'created_by' => auth()->id(),
        ]));

        return redirect()->route('draws.index')->with('success', 'Draw created successfully');
    }

    public function show(Draw $draw)
    {
        // Auto-sync financial data if it's missing or to ensure accuracy
        $actualSales = $draw->tickets()->sum('purchase_price');
        if ($draw->total_sales != $actualSales) {
            $draw->update([
                'total_sales' => $actualSales,
                'prize_pool_total' => $actualSales * 0.55,
                'sold_tickets' => $draw->tickets()->count()
            ]);
        }

        $winners = $draw->tickets()
            ->where('is_winner', true)
            ->with(['user', 'product'])
            ->orderBy('prize_tier_id', 'asc')
            ->get()
            ->groupBy('prize_tier_id');

        $tickets = $draw->tickets()->with('user')->latest()->take(100)->get();

        return view('admin.draws.show', compact('draw', 'winners', 'tickets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Draw $draw)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'ticket_price' => 'sometimes|numeric|min:0.01',
            'max_tickets' => 'nullable|integer|min:1',
            'status' => 'sometimes|in:draft,scheduled,live,closed,drawing,completed,cancelled',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
            'draw_time' => 'sometimes|date|after:end_time',
            'winning_digit' => 'nullable|integer|min:0|max:9',
        ]);

        $draw->update($validated);

        return redirect()->route('draws.index')->with('success', 'Draw updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Draw $draw)
    {
        $draw->delete();
        return redirect()->route('draws.index')->with('success', 'Draw deleted successfully');
    }

    /**
     * Select a winner for the draw.
     */
    public function selectWinner(Draw $draw, \App\Services\Draw\WinnerSelector $selector)
    {
        if ($draw->status === 'completed') {
            return back()->with('error', 'A winner has already been selected for this draw.');
        }

        if ($draw->tickets()->count() === 0) {
            return back()->with('error', 'No tickets have been sold for this draw. Cannot select winners.');
        }

        try {
            // Transition to drawing state as required by the service
            $draw->update(['status' => 'drawing']);

            $selector->conductDraw($draw);
            
            // Check if any winners were actually selected
            $winnerCount = \App\Models\Ticket::where('draw_id', $draw->id)
                ->where('is_winner', true)
                ->count();

            if ($winnerCount === 0) {
                $draw->update(['status' => 'live']);
                return back()->with('error', 'Draw completed but no winners were selected (check prize pool and ticket criteria).');
            }

            // Fetch the Tier 1 winner to display
            $winner = \App\Models\Ticket::where('draw_id', $draw->id)
                ->where('is_winner', true)
                ->where('prize_tier_id', 1)
                ->with('user')
                ->first();

            $msg = "Full draw sequence completed. {$winnerCount} winners identified.";
            if ($winner) {
                $msg .= " Tier 1 winner: {$winner->ticket_number} (User: {$winner->user->name})";
            }

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            // Restore live status if something went wrong
            $draw->update(['status' => 'live']);
            return back()->with('error', 'Error selecting winner: ' . $e->getMessage());
        }
    }

    /**
     * Pick a specific tier winner (Manual 1-3).
     */
    public function pickTier(Request $request, Draw $draw, \App\Services\Draw\WinnerSelector $selector)
    {
        $validated = $request->validate([
            'ticket_number' => 'required|string',
            'tier_id' => 'required|integer|min:1|max:3'
        ]);

        try {
            $selector->selectWinnerByTicketNumber($draw, $validated['ticket_number'], $validated['tier_id']);
            return back()->with('success', "Ticket #{$validated['ticket_number']} assigned to Tier {$validated['tier_id']}");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Trigger Tier 4 (Lucky Draw).
     */
    public function triggerLucky(Draw $draw, \App\Services\Draw\WinnerSelector $selector)
    {
        try {
            $selector->triggerLuckyDraw($draw);
            return back()->with('success', "Lucky Draw (Tier 4) processed successfully.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Trigger Tier 5 (Fortune Wheel).
     */
    public function triggerFortune(Draw $draw, \App\Services\Draw\WinnerSelector $selector)
    {
        try {
            $selector->triggerFortuneWheel($draw);
            return back()->with('success', "Fortune Wheel (Tier 5) processed successfully.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Preview winners for algorithmic tiers (AJAX).
     */
    public function preview(Draw $draw, int $tierId, \App\Services\Draw\WinnerSelector $selector)
    {
        try {
            if ($tierId === 4) {
                $data = $selector->previewLuckyDraw($draw);
            } elseif ($tierId === 5) {
                $data = $selector->previewFortuneWheel($draw);
            } else {
                return response()->json(['error' => 'Invalid tier for preview'], 400);
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Confirm and credit winners for algorithmic tiers.
     */
    public function confirm(Request $request, Draw $draw, int $tierId, \App\Services\Draw\WinnerSelector $selector)
    {
        $validated = $request->validate([
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'integer',
            'prize_per_winner' => 'required|numeric',
            'winning_digit' => 'nullable|integer'
        ]);

        try {
            $selector->finalizeTierWinners(
                $draw, 
                $tierId, 
                $validated['ticket_ids'], 
                $validated['prize_per_winner'], 
                $validated['winning_digit'] ?? null
            );

            return back()->with('success', "Tier {$tierId} finalized and winners credited.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mark a manual prize (Tier 1-3) as given/delivered.
     */
    public function finalizePrize(\App\Models\Ticket $ticket, \App\Services\Draw\WinnerSelector $selector)
    {
        try {
            $selector->finalizePrizeFulfillment($ticket);
            return back()->with('success', "Tier {$ticket->prize_tier_id} prize marked as GIVEN.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Finalize the draw manually (as a fallback).
     */
    public function finalizeDraw(Draw $draw)
    {
        if ($draw->status === 'completed') {
            return back()->with('error', 'Draw is already completed.');
        }

        // Validate that winners exist
        $winnerCount = $draw->tickets()->where('is_winner', true)->count();
        if ($winnerCount === 0) {
            return back()->with('error', 'Cannot finalize draw: No winners have been selected yet. Use the Winner Console to award prizes first.');
        }

        $draw->update([
            'status' => 'completed',
            'winner_selected_at' => now(),
            'winner_selection_method' => 'manual'
        ]);

        return back()->with('success', 'Draw finalized successfully.');
    }
}
