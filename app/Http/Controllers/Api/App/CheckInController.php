<?php

namespace App\Http\Controllers;

use App\Models\CheckinActivity;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckinController extends Controller
{
    public function checkIn(Request $request)
    {
        // Ambil user yang sedang login
        $user = Auth::user();
        $currentDate = now()->toDateString(); // Dapatkan tanggal sekarang

        // Cek apakah user sudah check-in hari ini
        $existingCheckin = CheckinActivity::where('id_user', $user->id)
            ->whereDate('time', $currentDate)
            ->first();

        if ($existingCheckin && $existingCheckin->type == 0) {
            // Jika sudah ada check-in, update end time (check-out)
            $existingCheckin->update([
                'time' => now(),
                'type' => 1 // Type 1 untuk check-out
            ]);
            return response()->json(['message' => 'Check-out berhasil'], 200);
        }

        // Ambil lokasi company user
        $company = Company::where('id', $user->id_company)->first();

        // Hitung jarak dari lokasi user ke lokasi company
        $distance = $this->calculateDistance(
            $company->latitude, $company->longtitude,
            $request->latitude, $request->longtitude
        );

        // Tentukan limit jarak (misalnya 1 km)
        $distanceLimit = 20; // dalam km

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