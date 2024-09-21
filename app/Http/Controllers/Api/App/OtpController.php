<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\Request;

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

        $otp = $this->otpService->generateOtp($user->id);
        if (!$otp) {
            return response()->json(['error' => 'Failed to generate OTP'], 500);
        }

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