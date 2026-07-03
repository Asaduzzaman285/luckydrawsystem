<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepositRequest;
use App\Models\User;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DepositRequestController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Show the form for creating a new deposit request (Customer side).
     */
    public function create()
    {
        $user = Auth::user();
        $agent = $user->agent ?? User::find($user->created_by); // Fallback to created_by if agent_id isn't strictly set

        if (!$agent) {
            return redirect()->route('dashboard')->with('error', 'You do not have an assigned agent. Please contact support.');
        }

        return view('deposit-requests.create', compact('agent'));
    }

    /**
     * Store a newly created deposit request (Customer side).
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'payment_method' => 'required|in:bKash,Nagad',
            'transaction_id' => 'required|string|unique:deposit_requests,transaction_id',
        ]);

        $user = Auth::user();
        $agent = $user->agent ?? User::find($user->created_by);

        if (!$agent) {
            return back()->with('error', 'You do not have an assigned agent.');
        }

        DepositRequest::create([
            'user_id' => $user->id,
            'agent_id' => $agent->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Deposit request submitted successfully. Waiting for agent approval.');
    }

    /**
     * Display a listing of deposit requests (Agent side).
     */
    public function index()
    {
        $agent = Auth::user();
        
        $requests = DepositRequest::where('agent_id', $agent->id)
            ->with('user')
            ->latest()
            ->get();

        return view('agent.deposit-requests.index', compact('requests'));
    }

    /**
     * Approve a deposit request (Agent side).
     */
    public function approve(DepositRequest $depositRequest)
    {
        if ($depositRequest->agent_id !== Auth::id()) {
            abort(403);
        }

        if ($depositRequest->status !== 'pending') {
            return back()->with('error', 'This request is already processed.');
        }

        try {
            DB::transaction(function () use ($depositRequest) {
                // Update request status
                $depositRequest->update(['status' => 'approved']);

                // Add balance using WalletService
                $this->walletService->deposit(
                    $depositRequest->user,
                    $depositRequest->amount,
                    "DEPOSIT-REQ-{$depositRequest->id}-{$depositRequest->transaction_id}",
                    Auth::id() // Processed by this agent
                );
            });

            return back()->with('success', 'Deposit request approved and wallet credited.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error approving deposit: ' . $e->getMessage());
        }
    }

    /**
     * Reject a deposit request (Agent side).
     */
    public function reject(Request $request, DepositRequest $depositRequest)
    {
        if ($depositRequest->agent_id !== Auth::id()) {
            abort(403);
        }

        if ($depositRequest->status !== 'pending') {
            return back()->with('error', 'This request is already processed.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $depositRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Deposit request rejected.');
    }
}
