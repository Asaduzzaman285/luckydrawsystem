<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DrawController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Agent\AgentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    // Safety redirect for staff roles
    if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->hasRole('agent')) {
        return redirect()->route('agent.dashboard');
    }

    $wallet = $user->wallet()->firstOrCreate([], ['balance' => 0]);
    $tickets = $user->tickets()->with('draw')->latest()->take(5)->get();
    $liveDraws = \App\Models\Draw::where('status', 'live')->withCount('tickets')->get();

    return view('dashboard', compact('wallet', 'tickets', 'liveDraws'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['role:super-admin'])->get('/super', function () {
    return 'Super Admin Only';
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('draws', DrawController::class);
    Route::post('/draws/{draw}/select-winner', [DrawController::class, 'selectWinner'])->name('draws.select-winner');
    Route::post('/withdrawals/{id}/approve', [AdminController::class, 'approveWithdrawal'])->name('withdrawals.approve');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/draws/{draw}/buy', [\App\Http\Controllers\TicketController::class, 'purchase'])->name('draws.buy');
});

Route::middleware(['auth', 'role:agent'])->group(function () {
    Route::get('/agent', [AgentController::class, 'index'])->name('agent.dashboard');
    Route::post('/agent/users', [AgentController::class, 'createUser'])->name('agent.users.store');
    Route::post('/agent/deposit', [AgentController::class, 'deposit'])->name('agent.deposit.store');
});

require __DIR__ . '/auth.php';
