<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\District;
use App\Models\Upazilla;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentReportController extends Controller
{
    public function index(Request $request)
    {
        $query = User::role('agent')->with(['district', 'upazilla']);

        // Filters
        if ($request->district_id) {
            $query->where('district_id', $request->district_id);
        }
        if ($request->upazilla_id) {
            $query->where('upazilla_id', $request->upazilla_id);
        }
        if ($request->phone) {
            $query->where('phone', 'like', "%{$request->phone}%");
        }

        $agents = $query->get()->map(function($agent) {
            // Calculate Total Deposits Made by Agent to Users
            $agent->total_deposits = Transaction::where('user_id', $agent->id)
                ->where('type', 'transfer_out')
                ->sum('amount');

            // Calculate Commissions Earned
            $agent->total_commissions = Transaction::where('user_id', $agent->id)
                ->where('description', 'like', 'AGENT-COMMISSION%')
                ->sum('amount');

            // Find all users created by this agent
            $assignedUserIds = User::where('created_by', $agent->id)->pluck('id');
            
            // Total Tickets Sold to these users
            $agent->tickets_sold = \App\Models\Ticket::whereIn('user_id', $assignedUserIds)->count();

            return $agent;
        });

        $districts = District::orderBy('name')->get();
        $upazillas = $request->district_id ? Upazilla::where('district_id', $request->district_id)->orderBy('name')->get() : collect();

        return view('admin.reports.agents', compact('agents', 'districts', 'upazillas'));
    }
}
