<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\CheckinActivity;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckInActivityController extends Controller
{
    public function checkIn(Request $request)
    {
        // Validasi input latitude dan longtitude
        $request->validate([
            'latitude' => 'required|numeric',
            'longtitude' => 'required|numeric',
        ]);

        // Ambil user yang sedang login
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        // Ambil tanggal sekarang
        $currentDate = now()->toDateString();

        // Cek apakah user sudah check-in hari ini
        $existingCheckin = CheckinActivity::where('id_user', $user->id)
            ->whereDate('time', $currentDate)
            ->orderBy('time', 'desc')
            ->first();

        if ($existingCheckin) {
            if ($existingCheckin->type == 0) {
                // Jika sudah check-in, tambahkan data baru untuk check-out
                CheckinActivity::create([
                    'id_user' => $user->id,
                    'time' => now(),
                    'type' => 1,
                    // Type 1 untuk check-out
                    'latitude' => $request->latitude,
                    'longtitude' => $request->longtitude,
                ]);

                return response()->json(['message' => 'Check-out berhasil'], 200);
            } elseif ($existingCheckin->type == 1) {
                // Jika sudah check-out, tolak check-in atau check-out
                return response()->json(['message' => 'Anda sudah melakukan check-out hari ini'], 400);
            }
        }

        // Ambil data employee terkait user
        $employee = Employee::where('id_user', $user->id)->first();

        if (!$employee) {
            return response()->json(['message' => 'Data employee tidak ditemukan'], 404);
        }

        // Ambil lokasi company dari employee
        $company = $employee->company; // Assuming there's a relation in Employee model

        if (!$company) {
            return response()->json(['message' => 'Data perusahaan tidak ditemukan'], 404);
        }

        // Hitung jarak dari lokasi user ke lokasi company
        $distance = $this->calculateDistance(
            $company->latitude, $company->longtitude,
            $request->latitude, $request->longtitude
        );

        // Tentukan limit jarak (misalnya 1 km)
        $distanceLimit = 1; // dalam km

        if ($distance <= $distanceLimit) {
            // Simpan check-in jika jaraknya kurang dari limit
            CheckinActivity::create([
                'id_user' => $user->id,
                'time' => now(),
                'type' => 0,
                // Type 0 untuk check-in
                'latitude' => $request->latitude,
                'longtitude' => $request->longtitude,
            ]);

            return response()->json(['message' => 'Check-in berhasil'], 200);
        }

        // Jika jaraknya lebih dari limit
        return response()->json(['message' => 'Check-in gagal, jarak terlalu jauh'], 400);
    }

    // Fungsi untuk menghitung jarak menggunakan rumus Haversine
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius bumi dalam km

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