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
        // Temukan user berdasarkan user ID
        $user = \App\Models\User::find($userId);

        // Cek apakah user ditemukan
        if (!$user) {
            Log::error("User dengan ID {$userId} tidak ditemukan.");
            return null;
        }

        // Expire OTP sebelumnya yang belum kadaluarsa
        KodeOtp::where('id_user', $userId)
            ->where('expired_at', '>', Carbon::now())
            ->update(['expired_at' => Carbon::now()]);

        // Generate OTP baru
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Ambil fullname dari user, sesuaikan dengan nama kolom di database
        $fullname = $user->full_name; // atau $user->fullname, sesuaikan dengan nama kolom

        Log::info("OTP untuk user ID {$userId} adalah: {$otp}");

        // Simpan OTP baru ke database
        KodeOtp::create([
            'id_user' => $userId,
            'fullname' => $fullname, // Simpan fullname juga jika diperlukan
            'kode_otp' => $otp,
            'expired_at' => Carbon::now()->addMinutes(5),
        ]);

        // Kirimkan OTP ke email user
        $this->sendOtpEmail($fullname, $otp);

        return $otp;
    }



    public function sendOtpEmail($fullname, $otp)
    {
        $yourEmail = 'cillmystic@gmail.com';

        // Gunakan view untuk mengirim email
        Mail::send('emails.otp', ['fullname' => $fullname, 'otp' => $otp], function ($message) use ($yourEmail) {
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
