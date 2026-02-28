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

        $stats = [
            'total_deposits' => Transaction::where('processed_by', $agentId)
                ->where('type', 'deposit')
                ->sum('amount'),
            'users_created' => User::where('created_by', $agentId)->count(),
            'wallet_balance' => $agent->wallet->balance ?? 0,
        ];

        $withdrawalRequests = $agent->withdrawalRequests()->latest()->take(10)->get();

        return view('agent.dashboard', compact('stats', 'withdrawalRequests'));
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'created_by' => auth()->id(),
        ]);

        $user->assignRole('user');

        return redirect()->back()->with('success', 'User created successfully');
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
