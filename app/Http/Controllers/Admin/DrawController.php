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
        return view('admin.draws.index', compact('draws'));
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
            'ticket_price' => 'required|numeric|min:0.01',
            'max_tickets' => 'nullable|integer|min:1',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'draw_time' => 'required|date|after:end_time',
        ]);

        $draw = Draw::create(array_merge($validated, ['status' => 'live']));

        return redirect()->route('draws.index')->with('success', 'Draw created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Draw $draw)
    {
        return response()->json($draw);
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

        try {
            // Transition to drawing state as required by the service
            $draw->update(['status' => 'drawing']);

            $ticket = $selector->selectWinner($draw);
            return back()->with('success', 'Winner selected: ' . $ticket->ticket_number . ' (User: ' . $ticket->user->name . ')');
        } catch (\Exception $e) {
            // Restore live status if something went wrong
            $draw->update(['status' => 'live']);
            return back()->with('error', 'Error selecting winner: ' . $e->getMessage());
        }
    }
}
