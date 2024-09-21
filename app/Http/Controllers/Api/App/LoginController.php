<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    protected $otpController;

    public function __construct(OtpController $otpController)
    {
        $this->otpController = $otpController;
    }

    public function loginApp(Request $request)
    {
        try {
            // Validasi permintaan
            $request->validate([
                'username' => ['required'],
                'password' => ['required'],
            ]);

            // Mencari pengguna berdasarkan username
            $user = User::where('username', $request->username)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Username atau password salah'], 401);
            }

            // Menghasilkan token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            // Mengatur request untuk mengirim OTP
            $otpRequest = new Request();
            $otpRequest->setUserResolver(function () use ($user) {
                return $user;
            });

            // Memanggil metode sendOtp dari OtpController
            $otpResponse = $this->otpController->sendOtp($otpRequest);

            // Mengembalikan respons
            return response()->json([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
                'otp_message' => $otpResponse->original['message'] // Ambil pesan OTP
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Login gagal', 'error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        // Revoke Token
        $request->user()->currentAccessToken()->delete();

        // Return response
        return response()->json(['message' => 'Logout sukses'], 200);
    }

    public function fetch(Request $request)
    {
        // Get user
        $user = $request->user();

        // Return response
        return response()->json($user, 200);
    }
}
