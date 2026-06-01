<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\OtpVerification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
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

        // 4. Send the OTP via MimSMS API
        $phone = ltrim($user->phone, '+');
        if (str_starts_with($phone, '01')) {
            $phone = '88' . $phone;
        }

        try {
            $response = Http::post('https://api.mimsms.com/api/SmsSending/SMS', [
                'UserName' => config('services.mimsms.username'),
                'Apikey' => config('services.mimsms.api_key'),
                'MobileNumber' => $phone,
                'CampaignId' => 'null',
                'SenderName' => config('services.mimsms.sender_name'),
                'TransactionType' => 'T',
                'Message' => "Your LuckoMart OTP code is: {$code}. Please do not share this code."
            ]);

            if ($response->successful()) {
                Log::info("MimSMS Sent Successfully to {$phone}", ['response' => $response->json()]);
            } else {
                Log::error("MimSMS Failed to Send to {$phone}", ['status' => $response->status(), 'response' => $response->body()]);
            }
        } catch (\Exception $e) {
            Log::error("MimSMS Exception for user {$user->id}: " . $e->getMessage());
        }

        Log::info("OTP GENERATED for User {$user->id} ({$user->phone}): {$code}. Type: {$type}");



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
