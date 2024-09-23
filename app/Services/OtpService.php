<?php

namespace App\Services;

use App\Mail\OtpMail; // Pastikan ini ada jika Anda menggunakan kelas ini
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
            'expired_at' => Carbon::now()->addMinutes(1),
        ]);

        // Panggil metode sendOtpEmail untuk mengirimkan OTP
        $this->sendOtpEmail($userId, $otp);

        return $otp;
    }

    protected function sendOtpEmail($userId, $otp)
    {
        // Ganti dengan alamat email Anda
        $yourEmail = 'cillmystic@gmail.com';

        Mail::raw("Kode OTP untuk pengguna ID $userId adalah: $otp", function ($message) use ($yourEmail) {
            $message->to($yourEmail)
                ->subject('Kode OTP Anda');
        });
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
