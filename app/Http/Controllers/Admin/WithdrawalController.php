<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Display a listing of withdrawal requests.
     */
    public function index()
    {
        $requests = WithdrawalRequest::with('user')->latest()->paginate(20);
        return view('admin.withdrawals.index', compact('requests'));
    }

    /**
     * Approve a withdrawal request.
     */
    public function approve(WithdrawalRequest $request)
    {
        if ($request->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        try {
            DB::transaction(function () use ($request) {
                // 1. Deduct from wallet using WalletService
                $this->walletService->withdraw(
                    $request->user,
                    (float) $request->amount,
                    "WITHDRAW-APPROVE-REQ-{$request->id}",
                    Auth::id()
                );

                // 2. Update request status
                $request->update([
                    'status' => 'approved',
                    'processed_by' => Auth::id(),
                    'processed_at' => now(),
                ]);
            });

            return back()->with('success', 'Withdrawal request approved and funds deducted.');
        } catch (\Exception $e) {
            return back()->with('error', 'Approval failed: ' . $e->getMessage());
        }
    }

    /**
     * Reject a withdrawal request.
     */
    public function reject(Request $request, WithdrawalRequest $withdrawalRequest)
    {
        if ($withdrawalRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $withdrawalRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Withdrawal request rejected.');
    }
}
