<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
        ]);

        $register = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'profile_photo' => "http://127.0.0.1:8000/storage/kucing1.jpeg",
            'password' => Hash::make($request->password),
        ]);

         $token = $register->createToken('hrd-app')->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }
}
