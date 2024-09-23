<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // Impor Cache yang benar

class OtpController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function sendOtp(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        if (!$user->id) {
            return response()->json(['error' => 'User ID not found'], 400);
        }

        // Cek apakah ada cache untuk OTP user
        $cacheKey = 'otp_sent_' . $user->id;
        if (Cache::has($cacheKey)) { // Tanpa backslash
            $remainingTime = Cache::get($cacheKey) - time();
            return response()->json([
                'error' => 'OTP sudah dikirim. Coba lagi dalam ' . $remainingTime . ' detik.',
            ], 429);
        }

        // Generate OTP baru
        $otp = $this->otpService->generateOtp($user->id);
        if (!$otp) {
            return response()->json(['error' => 'Failed to generate OTP'], 500);
        }

        // Set cache dengan timeout 1 menit
        Cache::put($cacheKey, time() + 60, 60); // Tanpa backslash

        return response()->json([
            'message' => 'Kode OTP telah dikirim ke email.',
            'email' => $user->email,
            'otp' => $otp, // Hanya untuk debugging, hapus atau amankan di produksi
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $user = $request->user();
        $otp = $request->input('otp');

        if (empty($otp)) {
            return response()->json(['error' => 'OTP tidak boleh kosong'], 400);
        }

        $isValid = $this->otpService->validateOtp($user->id, $otp);

        if ($isValid) {
            return response()->json(['message' => 'OTP valid.']);
        } else {
            return response()->json(['message' => 'OTP tidak valid atau sudah kadaluarsa.'], 400);
        }
    }
}