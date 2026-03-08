<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of staff (Admins/Agents).
     */
    public function index()
    {
        $query = User::role(['admin', 'agent', 'super-admin'])->with('district');
        
        // If regular admin, only show agents
        if (!auth()->user()->hasRole('super-admin')) {
            $query->role('agent');
        }

        $users = $query->latest()->paginate(20);
        $districts = \App\Models\District::orderBy('name')->get();

        $stats = [
            'total_staff' => User::role(['admin', 'agent', 'super-admin'])->count(),
            'admins' => User::role(['admin', 'super-admin'])->count(),
            'agents' => User::role('agent')->count(),
            'total_wallets_balance' => \App\Models\Wallet::whereHas('user', function($q) {
                $q->role(['admin', 'agent', 'super-admin']);
            })->sum('balance'),
        ];

        return view('admin.users.index', compact('users', 'stats', 'districts'));
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
    public function store(Request $request)
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
    public function update(Request $request, User $user)
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
