<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function getUsersFullName(): JsonResponse
    {
        try {
            $users = User::all();
            $formattedUsers = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                ];
            });
            return response()->json($formattedUsers, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
