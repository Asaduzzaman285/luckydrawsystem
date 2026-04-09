<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpVerifyController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function show()
    {
        if (!session()->has('auth.otp_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.otp-verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();
        
        if (!$user && session()->has('auth.otp_user_id')) {
            $user = \App\Models\User::find(session('auth.otp_user_id'));
        }

        if ($this->otpService->verify($user, $request->code, 'login')) {
            session()->put('auth.otp_verified', true);
            
            if ($user->hasRole('agent')) {
                return redirect()->route('agent.dashboard')
                    ->with('success', 'OTP Verified. Terminal Access Authorized.');
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['code' => 'Invalid or expired OTP code.']);
    }

    public function resend()
    {
        $user = Auth::user() ?: \App\Models\User::find(session('auth.otp_user_id'));
        
        if (!$user) {
            return redirect()->route('login');
        }

        $this->otpService->generateAndSend($user, 'login');

        return back()->with('success', 'A new 6-digit code has been generated and sent to your terminal/logs.');
    }
}
