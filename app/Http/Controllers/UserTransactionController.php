<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class UserTransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);
            
        $depositRequests = \App\Models\DepositRequest::where('user_id', Auth::id())
            ->latest()
            ->paginate(10, ['*'], 'deposits_page');

        return view('user.transactions.index', compact('transactions', 'depositRequests'));
    }
}
