<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use App\Services\Agent\AgentReassignmentService;

class UserController extends Controller
{
    /**
     * Display a listing of all users (Staff & Members).
     */
    public function index(Request $request)
    {
        $roleFilter = $request->get('role', 'all');
        
        $query = User::with(['district', 'agent', 'roles']);

        if ($roleFilter === 'staff') {
            $query->role(['admin', 'agent', 'super-admin']);
        } elseif ($roleFilter === 'members') {
            $query->role('user');
        } elseif ($roleFilter !== 'all') {
            $query->role($roleFilter);
        }

        // Security: Non-super-admins cannot see other admins or super-admins
        if (!auth()->user()->hasRole('super-admin')) {
            $query->whereHas('roles', function($q) {
                $q->whereIn('name', ['agent', 'user']);
            });
        }

        $users = $query->latest()->paginate(50);
        $districts = \App\Models\District::orderBy('name')->get();

        $stats = [
            'total_users' => User::count(),
            'admins' => User::role(['admin', 'super-admin'])->count(),
            'agents' => User::role('agent')->count(),
            'members' => User::role('user')->count(),
            'total_wallets_balance' => \App\Models\Wallet::sum('balance'),
        ];

        return view('admin.users.index', compact('users', 'stats', 'districts', 'roleFilter'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        $districts = District::orderBy('name')->get();
        return view('admin.users.create', compact('districts'));
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(Request $request, AgentReassignmentService $reassignmentService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,agent',
            'district_id' => 'nullable|exists:districts,id',
            'upazilla_id' => 'nullable|exists:upazillas,id',
        ]);

        // Security Check: Only Super Admin can create Admins
        if ($validated['role'] === 'admin' && !auth()->user()->hasRole('super-admin')) {
            return back()->with('error', 'Only Super Administrators can appoint new Admins.');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'district_id' => $validated['role'] === 'agent' ? $validated['district_id'] : null,
            'upazilla_id' => $validated['role'] === 'agent' ? $validated['upazilla_id'] : null,
            'created_by' => auth()->id(),
        ]);

        $user->assignRole($validated['role']);
        
        // Ensure wallet is created for stats/commissions
        $user->wallet()->create(['balance' => 0]);

        if ($validated['role'] === 'agent') {
            $reassignmentService->reassignFromSystemAgent($user);
        }

        return redirect()->route('users.index')->with('success', ucfirst($validated['role']) . ' account created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $districts = District::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'districts'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user, AgentReassignmentService $reassignmentService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'district_id' => 'nullable|exists:districts,id',
            'upazilla_id' => 'nullable|exists:upazillas,id',
            'is_active' => 'required|boolean',
        ]);

        $user->update($validated);

        if ($user->hasRole('agent')) {
            $reassignmentService->reassignFromSystemAgent($user);
        }

        return redirect()->route('users.index')->with('success', 'User profile updated.');
    }

    /**
     * Credit funds to a staff member (Agent/Admin).
     */
    public function credit(Request $request, User $user, \App\Services\Wallet\WalletService $walletService)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'reference' => 'nullable|string|max:255',
        ]);

        $walletService->deposit($user, $validated['amount'], $validated['reference'], auth()->id());

        return back()->with('success', "Credited $" . number_format($validated['amount'], 2) . " to {$user->name}'s wallet.");
    }
}
