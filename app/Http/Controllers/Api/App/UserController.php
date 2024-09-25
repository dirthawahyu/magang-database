<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

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

    public function saveFcmToken(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'fcm_token' => 'required|string',
        ]);

        $employee = Employee::where('id_user', $request->user_id)->first();
        if ($employee) {
            $employee->fcm_token = $request->fcm_token;
            $employee->save();
            return response()->json(['message' => 'FCM Token saved successfully.']);
        }

        return response()->json(['message' => 'Employee not found.'], 404);
    }

    public function deleteFcmToken(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
        ]);

        // Menghapus FCM token dari database
        DB::table('employee')->where('id_user', $request->user_id)->update(['fcm_token' => null]);

        return response()->json(['message' => 'FCM token deleted successfully.']);
    }
}
