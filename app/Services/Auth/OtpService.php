<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\OtpVerification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OtpService
{
    /**
     * Generate and "send" an OTP for the user.
     */
    public function generateAndSend(User $user, string $type = 'login'): OtpVerification
    {
        // 1. Deactivate old OTPs of same type
        OtpVerification::where('user_id', $user->id)
            ->where('type', $type)
            ->update(['is_used' => true]);

        // 2. Generate 6 digit code
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // 3. Create record
        $otp = OtpVerification::create([
            'user_id' => $user->id,
            'code' => $code,
            'type' => $type,
            'expired_at' => Carbon::now()->addMinutes(10),
            'is_used' => false,
        ]);

        // 4. "Send" the OTP (For now, logging only)
        Log::info("OTP GENERATED for User {$user->id} ({$user->phone}): {$code}. Type: {$type}");

        // Optionally flash to session for testing purposes if in local env
        if (config('app.env') === 'local') {
            session()->flash('debug_otp', "DEBUG OTP (Check Logs in Production): {$code}");
        }

        return $otp;
    }

    /**
     * Verify an OTP.
     */
    public function verify(User $user, string $code, string $type = 'login'): bool
    {
        $otp = OtpVerification::where('user_id', $user->id)
            ->where('code', $code)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expired_at', '>', Carbon::now())
            ->first();

        if ($otp) {
            $otp->update(['is_used' => true]);
            return true;
        }

        return false;
    }
}
