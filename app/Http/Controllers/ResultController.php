<?php

namespace App\Http\Controllers;

use App\Models\Draw;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Display a listing of completed draws.
     */
    public function index()
    {
        $draws = Draw::where('status', 'completed')
            ->orderBy('draw_time', 'desc')
            ->paginate(12);

        return view('results.index', compact('draws'));
    }

    /**
     * Display the results of a specific draw.
     */
    public function show(Draw $draw)
    {
        if ($draw->status !== 'completed') {
            abort(404, 'Draw results not yet published.');
        }

        $winners = $draw->tickets()
            ->where('is_winner', true)
            ->with(['user', 'product'])
            ->orderBy('prize_tier_id', 'asc')
            ->get();

        // Group winners by tier for better display
        $tieredWinners = $winners->groupBy('prize_tier_id');

        return view('results.show', compact('draw', 'tieredWinners'));
    }
}
