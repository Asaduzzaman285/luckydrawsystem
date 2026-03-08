<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Draw;
use App\Models\User;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\District;
use App\Models\WithdrawalRequest;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index()
    {
        $stats = [
            'agent_funds' => Wallet::whereHas('user', function($q) {
                $q->role('agent');
            })->sum('balance'),
            'active_draws' => Draw::where('status', 'live')->count(),
            'pending_withdrawals' => Transaction::where('type', 'withdrawal')->where('status', 'pending')->count(),
            'total_agents' => User::role('agent')->count(),
            'total_products' => Product::count(),
            'total_draws' => Draw::count(),
            'total_winners' => Ticket::where('is_winner', true)->distinct('user_id')->count(),
        ];

        // Data for Agents by District Chart
        $agentsByDistrict = District::withCount(['users as agent_count' => function($q) {
            $q->role('agent');
        }])->get(['name', 'agent_count']);

        // Data for Operations Summary Chart
        $operationsSummary = [
            'draws' => Draw::count(),
            'products' => Product::count(),
            'withdrawals' => WithdrawalRequest::count(),
        ];

        $pendingWithdrawals = Transaction::where('type', 'withdrawal')
            ->where('status', 'pending')
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('admin.dashboard', compact('stats', 'pendingWithdrawals', 'agentsByDistrict', 'operationsSummary'));
    }

    /**
     * Approve a withdrawal request.
     */
    public function approveWithdrawal(Request $request, string $id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('type', 'withdrawal')
            ->where('status', 'pending')
            ->firstOrFail();

        $transaction->update([
            'status' => 'completed',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'processed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Withdrawal approved successfully');
    }
}
