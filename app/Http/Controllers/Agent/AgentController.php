<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Transaction;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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
        $agentId = auth()->id();

        $stats = [
            'total_deposits' => Transaction::where('processed_by', $agentId)
                ->where('type', 'deposit')
                ->sum('amount'),
            'users_created' => User::where('created_by', $agentId)->count(),
        ];

        return view('agent.dashboard', compact('stats'));
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
     * Deposit funds for a user.
     */
    public function deposit(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'reference_id' => 'nullable|string|unique:transactions,reference_id',
        ]);

        $user = User::findOrFail($validated['user_id']);

        $transaction = $this->walletService->deposit(
            $user,
            $validated['amount'],
            $validated['reference_id'] ?? null,
            auth()->id()
        );

        return redirect()->back()->with('success', 'Deposit successful');
    }
}
