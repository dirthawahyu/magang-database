<?php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function loginApp(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Email is not valid',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
        ]);

        if (Auth::attempt($credentials, true)) {
            return response()->json([
                'token' => Auth::user()->createToken('hrd-app')->plainTextToken,
            ], 200);
        }
        
        return response()->json([
            'error' => 'Your credentials do not match our records.'
        ], 401);
    }
}