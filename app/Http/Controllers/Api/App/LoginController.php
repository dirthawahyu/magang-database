<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
            $request->validate([
                'username' => ['required'],
                'password' => ['required'],
            ]);
            $user = User::where('username', $request->username)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Username atau password salah'], 401);
            }
            $employee = DB::table('employee')
                ->where('id_user', $user->id)
                ->where('status', 'Active')
                ->first();
            if (!$employee) {
                return response()->json(['message' => 'Karyawan tidak aktif'], 403);
            }
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            $otpRequest = new Request();
            $otpRequest->setUserResolver(function () use ($user) {
                return $user;
            });
            $otpResponse = $this->otpController->sendOtp($otpRequest);
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
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout sukses'], 200);
    }

    public function fetch(Request $request)
    {
        $user = $request->user();
        return response()->json($user, 200);
    }
}
