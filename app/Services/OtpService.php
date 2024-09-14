<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\KodeOtp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public function generateOtp($userId)
    {
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = \App\Models\User::find($userId);

        if (!$user) {
            Log::error("User dengan ID {$userId} tidak ditemukan.");
            return null;
        }

        Log::info("OTP untuk user ID {$userId} adalah: {$otp}");

        KodeOtp::create([
            'id_user' => $userId,
            'kode_otp' => $otp,
            'expired_at' => Carbon::now()->addMinutes(5),
        ]);

        try {
            Mail::to($user->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email OTP ke {$user->email}. Error: " . $e->getMessage());
        }

        return $otp;
    }

    public function validateOtp($userId, $otp)
    {
        $kodeOtp = KodeOtp::where('id_user', $userId)
            ->where('kode_otp', $otp)
            ->where('expired_at', '>', Carbon::now())
            ->first();

        $now = Carbon::now();
        Log::info("Sekarang: $now, OTP yang dimasukkan: $otp, Kode OTP ditemukan: " . ($kodeOtp ? json_encode($kodeOtp) : 'Tidak ditemukan'));

        if ($kodeOtp) {
            $kodeOtp->delete();
            return true;
        }

        Log::info("OTP tidak valid atau sudah kadaluarsa.");
        return false;
    }
}