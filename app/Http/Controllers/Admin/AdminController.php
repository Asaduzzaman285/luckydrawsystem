<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Transaction;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index()
    {
        $stats = [
            'total_balance' => \App\Models\Wallet::sum('balance'),
            'active_draws' => \App\Models\Draw::where('status', 'live')->count(),
            'pending_withdrawals' => Transaction::where('type', 'withdrawal')->where('status', 'pending')->count(),
            'total_users' => \App\Models\User::role('user')->count(),
        ];

        $pendingWithdrawals = Transaction::where('type', 'withdrawal')
            ->where('status', 'pending')
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('admin.dashboard', compact('stats', 'pendingWithdrawals'));
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
