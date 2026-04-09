<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureOtpVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // If user is logged in
        if ($user) {
            // Check if user is an agent (or super-admin if you want)
            if ($user->hasRole('agent')) {
                // Check session flag
                if (!$request->session()->get('auth.otp_verified', false)) {
                    // Force OTP verification
                    return redirect()->route('otp.verify');
                }
            }
        }

        return $next($request);
    }
}
