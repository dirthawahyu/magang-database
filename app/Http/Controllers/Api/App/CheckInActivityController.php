<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\CheckinActivity;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckInActivityController extends Controller
{
    public function getTodayActivities($user)
    {
        $today = Carbon::today();
        $activities = CheckinActivity::where('id_user', $user)
            ->whereDate('time', $today)
            ->get();
        return response()->json($activities);
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longtitude' => 'required|numeric',
        ]);
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }
        $currentDate = now()->toDateString();
        $existingCheckin = CheckinActivity::where('id_user', $user->id)
            ->whereDate('time', $currentDate)
            ->orderBy('time', 'desc')
            ->first();
        if ($existingCheckin) {
            if ($existingCheckin->type == 0) {
                $checkOutTime = now();
                CheckinActivity::create([
                    'id_user' => $user->id,
                    'time' => $checkOutTime,
                    'type' => 1,
                    'latitude' => $request->latitude,
                    'longtitude' => $request->longtitude,
                ]);
                return response()->json([
                    'message' => 'Check-out berhasil',
                    'check_out_time' => $checkOutTime->format('H:i') // Mengembalikan waktu check-out
                ], 200);
            } elseif ($existingCheckin->type == 1) {
                return response()->json(['message' => 'Anda sudah melakukan check-out hari ini'], 400);
            }
        }
        $employee = Employee::where('id_user', $user->id)->first();
        if (!$employee) {
            return response()->json(['message' => 'Data employee tidak ditemukan'], 404);
        }
        $company = $employee->company; 
        if (!$company) {
            return response()->json(['message' => 'Data perusahaan tidak ditemukan'], 404);
        }
        $distance = $this->calculateDistance(
            $company->latitude,
            $company->longtitude,
            $request->latitude,
            $request->longtitude
        );
        $distanceLimit = 1;
        if ($distance <= $distanceLimit) {
            $checkInTime = now();
            CheckinActivity::create([
                'id_user' => $user->id,
                'time' => $checkInTime,
                'type' => 0,
                'latitude' => $request->latitude,
                'longtitude' => $request->longtitude,
            ]);
            return response()->json([
                'message' => 'Check-in berhasil',
                'check_in_time' => $checkInTime->format('H:i') // Mengembalikan waktu check-in
            ], 200);
        }
        return response()->json(['message' => 'Check-in gagal, jarak terlalu jauh'], 400);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; 
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
        $latDiff = $lat2 - $lat1;
        $lonDiff = $lon2 - $lon1;
        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos($lat1) * cos($lat2) *
            sin($lonDiff / 2) * sin($lonDiff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}
