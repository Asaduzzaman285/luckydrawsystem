<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\Admin\DrawController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Agent\AgentController;
use App\Http\Controllers\Auth\OtpVerifyController;
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
    $withdrawableBalance = app(\App\Services\Wallet\WalletService::class)->getWithdrawableBalance($user);
    
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

    return view('dashboard', compact('wallet', 'tickets', 'liveProducts', 'withdrawableBalance'));
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
    Route::get('/reports/agents', [\App\Http\Controllers\Admin\AgentReportController::class, 'index'])->name('admin.reports.agents');
    Route::post('/users/{user}/credit', [\App\Http\Controllers\Admin\UserController::class, 'credit'])->name('users.credit');
    Route::post('/draws/{draw}/select-winner', [DrawController::class, 'selectWinner'])->name('draws.select-winner');
    Route::get('/draws/{draw}/random-ticket', [DrawController::class, 'getRandomTicket'])->name('draws.random-ticket');
    Route::post('/draws/{draw}/pick-tier', [DrawController::class, 'pickTier'])->name('draws.pick-tier');
    Route::post('/draws/{draw}/trigger-lucky', [DrawController::class, 'triggerLucky'])->name('draws.trigger-lucky');
    Route::post('/draws/{draw}/trigger-fortune', [DrawController::class, 'triggerFortune'])->name('draws.trigger-fortune');
    Route::get('/draws/{draw}/preview/{tierId}', [DrawController::class, 'preview'])->name('draws.preview');
    Route::post('/draws/{draw}/confirm/{tierId}', [DrawController::class, 'confirm'])->name('draws.confirm');
    Route::post('/draws/{draw}/finalize', [DrawController::class, 'finalizeDraw'])->name('draws.finalize');
    Route::post('/tickets/{ticket}/finalize-prize', [DrawController::class, 'finalizePrize'])->name('tickets.finalize-prize');
    
    Route::get('/withdrawals', [WithdrawalController::class, 'index'])->name('admin.withdrawals.index');
    Route::post('/withdrawals/{request}/approve', [WithdrawalController::class, 'approve'])->name('admin.withdrawals.approve');
    Route::post('/withdrawals/{withdrawalRequest}/reject', [WithdrawalController::class, 'reject'])->name('admin.withdrawals.reject');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/products/{product}/buy', [\App\Http\Controllers\TicketController::class, 'purchase'])->name('products.buy');
    Route::post('/withdraw/request', [\App\Http\Controllers\Agent\AgentController::class, 'requestWithdrawalFromAgent'])->name('withdraw.request');
    
    // Deposit Requests
    Route::get('/deposit/request', [\App\Http\Controllers\DepositRequestController::class, 'create'])->name('deposit.request.create');
    Route::post('/deposit/request', [\App\Http\Controllers\DepositRequestController::class, 'store'])->name('deposit.request.store');

    // User Transaction History
    Route::get('/transactions', [\App\Http\Controllers\UserTransactionController::class, 'index'])->name('user.transactions');
});
// Point 6: OTP Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/otp-verify', [OtpVerifyController::class, 'show'])->name('otp.verify');
    Route::post('/otp-verify', [OtpVerifyController::class, 'verify'])->name('otp.verify.submit');
    Route::post('/otp-resend', [OtpVerifyController::class, 'resend'])->name('otp.resend');
    Route::post('/otp-password', function(Request $request) {
        app(\App\Services\Auth\OtpService::class)->generateAndSend(auth()->user(), 'password_reset');
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Verification code sent!']);
        }
        
        return back()->with('success', 'A 6-digit security code has been sent to your terminal/logs.');
    })->name('otp.password');
});

Route::middleware(['auth', 'role:agent|admin|super-admin', 'otp.verified'])->group(function () {
    Route::get('/agent', [AgentController::class, 'index'])->name('agent.dashboard');
    Route::post('/agent/users', [AgentController::class, 'createUser'])->name('agent.users.store');
    Route::post('/agent/deposit', [AgentController::class, 'deposit'])->name('agent.deposit.store');
    Route::post('/agent/withdraw', [AgentController::class, 'requestWithdrawal'])->name('agent.withdraw.store');
    Route::post('/agent/withdrawals/{request}/approve', [AgentController::class, 'approveUserWithdrawal'])->name('agent.withdrawals.approve');
    Route::post('/agent/withdrawals/{request}/reject', [AgentController::class, 'rejectUserWithdrawal'])->name('agent.withdrawals.reject');
    
    Route::get('/agent/prizes', [AgentController::class, 'prizes'])->name('agent.prizes.index');
    Route::post('/agent/prizes/{ticket}/distribute', [AgentController::class, 'distributePrize'])->name('agent.prizes.distribute');

    // Agent Deposit Requests (from customers)
    Route::get('/agent/deposit-requests', [\App\Http\Controllers\DepositRequestController::class, 'index'])->name('agent.deposit-requests.index');
    Route::post('/agent/deposit-requests/{depositRequest}/approve', [\App\Http\Controllers\DepositRequestController::class, 'approve'])->name('agent.deposit-requests.approve');
    Route::post('/agent/deposit-requests/{depositRequest}/reject', [\App\Http\Controllers\DepositRequestController::class, 'reject'])->name('agent.deposit-requests.reject');

    // Agent Reports
    Route::get('/agent/reports', [AgentController::class, 'reports'])->name('agent.reports');
});

// Admin Reports & Admin Deposit Requests (from agents)
Route::middleware(['auth', 'role:admin|super-admin'])->group(function () {
    // Admin sees ALL agents' transaction reports
    Route::get('/admin/reports', [\App\Http\Controllers\Admin\AdminController::class, 'reports'])->name('admin.reports');
    
    // Agent-to-Admin deposit requests
    Route::get('/admin/deposit-requests', [\App\Http\Controllers\DepositRequestController::class, 'adminIndex'])->name('admin.deposit-requests.index');
    Route::post('/admin/deposit-requests/{depositRequest}/approve', [\App\Http\Controllers\DepositRequestController::class, 'adminApprove'])->name('admin.deposit-requests.approve');
    Route::post('/admin/deposit-requests/{depositRequest}/reject', [\App\Http\Controllers\DepositRequestController::class, 'adminReject'])->name('admin.deposit-requests.reject');
});

// Agent deposit request to admin (agent submits request)
Route::middleware(['auth', 'role:agent'])->group(function () {
    Route::get('/agent/admin-deposit/request', [\App\Http\Controllers\DepositRequestController::class, 'agentCreate'])->name('agent.admin-deposit.create');
    Route::post('/agent/admin-deposit/request', [\App\Http\Controllers\DepositRequestController::class, 'agentStore'])->name('agent.admin-deposit.store');
});

Route::get('/results', [ResultController::class, 'index'])->name('results.index');
Route::get('/results/{draw}', [ResultController::class, 'show'])->name('results.show');

Route::get('/districts/{district}/upazillas', function (\App\Models\District $district) {
    return $district->upazillas()->orderBy('name')->get();
})->name('districts.upazillas');

require __DIR__ . '/auth.php';
