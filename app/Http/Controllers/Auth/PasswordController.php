<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $rules = [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ];

        if ($request->user()->hasRole('agent')) {
            $rules['otp_code'] = ['required', 'string', 'size:6'];
        }

        $validated = $request->validateWithBag('updatePassword', $rules);

        if ($request->user()->hasRole('agent')) {
            $otpService = app(\App\Services\Auth\OtpService::class);
            if (!$otpService->verify($request->user(), $validated['otp_code'], 'password_reset')) {
                return back()->withErrors(['otp_code' => 'Invalid or expired verification code.'], 'updatePassword');
            }
        }

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
