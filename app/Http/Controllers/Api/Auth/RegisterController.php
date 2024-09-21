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
        // Validasi field yang digunakan saat register
        $request->validate([
            'first_name' => ['required', 'min:3'],
            'last_name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'username' => ['required', 'unique:users,username'], // Username unik
            'password' => ['required', 'min:8'],
        ]);

        // Membuat user baru dengan field yang sesuai
        $register = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'username' => $request->username, // Menyimpan username
            'password' => Hash::make($request->password),
        ]);

        // Membuat token untuk user yang baru dibuat
        $token = $register->createToken('hrd-app')->plainTextToken;

        // Mengembalikan response token
        return response()->json([
            'token' => $token,
        ]);
    }
}
