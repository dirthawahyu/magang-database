<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EditController extends Controller
{
    public function editProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone_number = $request->phone_number;
        $user->city = $request->city;
        $user->address = $request->address;
        $user->save();
        return response()->json(['message' => 'Profile updated successfully'], 200);
    }
}
