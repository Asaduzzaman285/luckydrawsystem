<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\Admin\DrawController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\WithdrawalController;
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
    
    // Group tickets by transaction to show "Purchases" instead of single entries
    $tickets = $user->tickets()
        ->with(['draw', 'product'])
        ->latest()
        ->get()
        ->groupBy('transaction_id')
        ->take(5); // Show last 5 purchases
    
    // Fetch products where the associated draw is live
    $liveProducts = \App\Models\Product::whereHas('draw', function($query) {
        $query->where('status', 'live');
    })->with('draw')->get();

    return view('dashboard', compact('wallet', 'tickets', 'liveProducts'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['role:super-admin'])->get('/super', function () {
    return 'Super Admin Only';
});

Route::middleware(['auth', 'role:admin|super-admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('draws', DrawController::class);
    Route::resource('products', ProductController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('/users/{user}/credit', [\App\Http\Controllers\Admin\UserController::class, 'credit'])->name('users.credit');
    Route::post('/draws/{draw}/select-winner', [DrawController::class, 'selectWinner'])->name('draws.select-winner');
    Route::post('/draws/{draw}/pick-tier', [DrawController::class, 'pickTier'])->name('draws.pick-tier');
    Route::post('/draws/{draw}/trigger-lucky', [DrawController::class, 'triggerLucky'])->name('draws.trigger-lucky');
    Route::post('/draws/{draw}/trigger-fortune', [DrawController::class, 'triggerFortune'])->name('draws.trigger-fortune');
    Route::get('/draws/{draw}/preview/{tierId}', [DrawController::class, 'preview'])->name('draws.preview');
    Route::post('/draws/{draw}/confirm/{tierId}', [DrawController::class, 'confirm'])->name('draws.confirm');
    Route::post('/draws/{draw}/finalize', [DrawController::class, 'finalizeDraw'])->name('draws.finalize');
    
    Route::get('/withdrawals', [WithdrawalController::class, 'index'])->name('admin.withdrawals.index');
    Route::post('/withdrawals/{request}/approve', [WithdrawalController::class, 'approve'])->name('admin.withdrawals.approve');
    Route::post('/withdrawals/{withdrawalRequest}/reject', [WithdrawalController::class, 'reject'])->name('admin.withdrawals.reject');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/products/{product}/buy', [\App\Http\Controllers\TicketController::class, 'purchase'])->name('products.buy');
});

Route::middleware(['auth', 'role:agent|super-admin'])->group(function () {
    Route::get('/agent', [AgentController::class, 'index'])->name('agent.dashboard');
    Route::post('/agent/users', [AgentController::class, 'createUser'])->name('agent.users.store');
    Route::post('/agent/deposit', [AgentController::class, 'deposit'])->name('agent.deposit.store');
    Route::post('/agent/withdraw', [AgentController::class, 'requestWithdrawal'])->name('agent.withdraw.store');
    
    Route::get('/agent/prizes', [AgentController::class, 'prizes'])->name('agent.prizes.index');
    Route::post('/agent/prizes/{ticket}/distribute', [AgentController::class, 'distributePrize'])->name('agent.prizes.distribute');
});

Route::get('/results', [ResultController::class, 'index'])->name('results.index');
Route::get('/results/{draw}', [ResultController::class, 'show'])->name('results.show');

require __DIR__ . '/auth.php';
