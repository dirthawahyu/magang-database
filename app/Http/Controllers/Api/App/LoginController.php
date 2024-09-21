<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Helper\ResponseFormatter;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    //
    public function loginApp(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'username' => ['required'], // Username sebagai input
                'password' => ['required'],
            ]);

            // Find user by username
            $user = User::where('username', $request->username)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Username atau password salah'
                ], 401); 
            }

            // Generate token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            // Return response
            return response()->json([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 200); // Status sukses sebagai integer (200)
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Login gagal',
                'error' => $e->getMessage()
            ], 500); // Status server error sebagai integer (500)
        }
    }

    public function logout(Request $request)
    {
        // Revoke Token
        $token = $request->user()->currentAccessToken()->delete();

        // Return response
        return response()->json([
            'message' => 'Logout sukses'
        ], 200); // Status sukses sebagai integer (200)
    }

    public function fetch(Request $request)
    {
        // Get user
        $user = $request->user();

        // Return response
        return response()->json($user, 200); // Status sukses sebagai integer (200)
    }
}
