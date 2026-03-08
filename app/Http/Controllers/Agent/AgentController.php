<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Transaction;
use App\Models\WithdrawalRequest;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Display the Agent Dashboard.
     */
    public function index()
    {
        $agent = Auth::user();
        $agentId = $agent->id;

        // Fetch users created by OR assigned to this agent
        $managedUsers = User::where(function($query) use ($agentId) {
                $query->where('created_by', $agentId)
                      ->orWhere('agent_id', $agentId);
            })
            ->with('wallet')
            ->latest()
            ->get();

        $managedUserIds = $managedUsers->pluck('id');

        $stats = [
            'total_deposits' => Transaction::where('processed_by', $agentId)
                ->where('type', 'deposit')
                ->sum('amount'),
            'users_created' => $managedUsers->count(),
            'wallet_balance' => $agent->wallet->balance ?? 0,
        ];

        // Fetch recent ticket purchases from users managed by this agent
        $recentTickets = \App\Models\Ticket::whereIn('user_id', $managedUserIds)
            ->with(['user', 'product', 'draw'])
            ->latest()
            ->take(10)
            ->get();

        $withdrawalRequests = $agent->withdrawalRequests()->latest()->take(10)->get();
        
        $pendingUserWithdrawals = WithdrawalRequest::where('target_agent_id', $agentId)
            ->where('status', 'pending')
            ->with('user')
            ->latest()
            ->get();

        return view('agent.dashboard', compact('stats', 'withdrawalRequests', 'managedUsers', 'recentTickets', 'pendingUserWithdrawals'));
    }

    /**
     * User requests a withdrawal from their agent.
     */
    public function requestWithdrawalFromAgent(Request $request)
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'target_agent_id' => 'required|exists:users,id',
            'payment_method' => 'required|string',
            'account_details' => 'required|string',
        ]);

        if (!$wallet || $wallet->balance < $validated['amount']) {
            return back()->with('error', 'Insufficient balance for this withdrawal.');
        }

        WithdrawalRequest::create([
            'user_id' => $user->id,
            'target_agent_id' => $validated['target_agent_id'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'account_details' => $validated['account_details'],
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Withdrawal request submitted to your agent.');
    }

    /**
     * Agent approves a user's withdrawal request.
     * This moves digital balance from User to Agent.
     */
    public function approveUserWithdrawal(WithdrawalRequest $request)
    {
        $agent = Auth::user();

        if ($request->target_agent_id !== $agent->id) {
            abort(403, 'Unauthorized.');
        }

        if ($request->status !== 'pending') {
            return back()->with('error', 'Request is already processed.');
        }

        try {
            DB::transaction(function () use ($request, $agent) {
                // Move balance: User -> Agent
                $this->walletService->transfer(
                    $request->user,
                    $agent,
                    $request->amount,
                    "WITHDRAWAL-APPROVAL-REQ{$request->id}"
                );

                $request->update([
                    'status' => 'approved',
                    'processed_by' => $agent->id,
                    'processed_at' => now(),
                    'admin_notes' => 'Approved by agent'
                ]);
            });

            return back()->with('success', 'Withdrawal approved. Balance transferred from user to your wallet.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Agent rejects a user's withdrawal request.
     */
    public function rejectUserWithdrawal(Request $req, WithdrawalRequest $request)
    {
        $agent = Auth::user();

        if ($request->target_agent_id !== $agent->id) {
            abort(403, 'Unauthorized.');
        }

        $request->update([
            'status' => 'rejected',
            'processed_by' => $agent->id,
            'processed_at' => now(),
            'admin_notes' => $req->notes ?? 'Rejected by agent'
        ]);

        return back()->with('success', 'Withdrawal request rejected.');
    }

    /**
     * Request a withdrawal for the agent.
     */
    public function requestWithdrawal(Request $request)
    {
        $agent = Auth::user();
        $wallet = $agent->wallet;

        $validated = $request->validate([
            'amount' => 'required|numeric|min:10',
            'payment_method' => 'required|string|max:255',
            'account_details' => 'required|string',
        ]);

        if (!$wallet || $wallet->balance < $validated['amount']) {
            return back()->with('error', 'Insufficient wallet balance for this withdrawal.');
        }

        WithdrawalRequest::create([
            'user_id' => $agent->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'account_details' => $validated['account_details'],
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Withdrawal request submitted for admin approval.');
    }

    /**
     * Display winning tickets for users managed by this agent.
     */
    public function prizes()
    {
        $agent = Auth::user();
        
        $prizes = \App\Models\Ticket::whereHas('user', function($query) use ($agent) {
                $query->where('agent_id', $agent->id);
            })
            ->where('is_winner', true)
            ->whereIn('prize_tier_id', [1, 2, 3])
            ->with(['user', 'draw', 'product'])
            ->latest()
            ->paginate(20);

        return view('agent.prizes', compact('prizes'));
    }

    /**
     * Mark a prize as distributed/completed.
     */
    public function distributePrize(Request $request, \App\Models\Ticket $ticket)
    {
        $agent = Auth::user();

        // Verify the ticket belongs to a user managed by this agent
        if ($ticket->user->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($ticket->status === 'completed') {
            return back()->with('error', 'This prize has already been distributed.');
        }

        $ticket->update([
            'status' => 'completed',
            'metadata' => array_merge($ticket->metadata ?? [], [
                'distributed_at' => now()->toIso8601String(),
                'distributed_by' => $agent->id,
                'distribution_notes' => $request->notes
            ])
        ]);

        return back()->with('success', 'Prize distribution marked as completed.');
    }

    /**
     * Create a new user.
     */
    public function createUser(Request $request)
    {
        $agent = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'district_id' => $agent->district_id,
            'upazilla_id' => $agent->upazilla_id,
            'created_by' => $agent->id,
            'agent_id' => $agent->id, // Also set as agent_id
        ]);

        $user->assignRole('user');

        return redirect()->back()->with('success', 'User created successfully in your assigned area.');
    }

    /**
     * Deposit funds for a user (from Agent's balance).
     */
    public function deposit(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'reference_id' => 'nullable|string|unique:transactions,reference_id',
        ]);

        $agent = Auth::user();
        $user = User::findOrFail($validated['user_id']);

        try {
            $this->walletService->transfer(
                $agent,
                $user,
                $validated['amount'],
                $validated['reference_id'] ?? null
            );
            return redirect()->back()->with('success', 'Credit processed successfully from your wallet');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to process credit: ' . $e->getMessage());
        }
    }
}
