<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\CheckinActivity;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


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
            'longitude' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi foto jika ada
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
            $company->longitude,
            $request->latitude,
            $request->longitude
        );

        $distanceLimit = 1; // 1 km sebagai batas jarak check-in/check-out
        $status = $distance <= $distanceLimit ? 0 : 1; // status 0 untuk sukses, 1 untuk gagal

        // Jika ada foto, status dianggap berhasil meskipun jarak tidak sesuai
        if ($request->hasFile('photo')) {
            $status = 0; // Anggap berhasil jika ada foto
        }

        // Jika ada check-in sebelumnya
        if ($existingCheckin) {
            if ($existingCheckin->type == 0 && $existingCheckin->status == 0) {
                // Jika sudah melakukan check-in (status = 0), maka lakukan check-out
                $checkOutTime = now();
                $checkOutActivity = CheckinActivity::create([
                    'id_user' => $user->id,
                    'time' => $checkOutTime,
                    'type' => 1, // Tipe check-out
                    'status' => $status, // Status check-out (0 = sukses, 1 = gagal)
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);

                $message = $status == 0 ? 'Check-out berhasil' : 'Check-out gagal, jarak terlalu jauh';

                // Jika ada foto yang diunggah, simpan foto untuk check-out
                if ($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                    $filePath = $file->storeAs('public/checkin_photos', $filename);

                    // Update aktivitas check-out dengan path foto
                    $checkOutActivity->photo = $filename;
                    $checkOutActivity->save();

                    // Kirim URL foto dalam respons
                    return response()->json([
                        'message' => $message,
                        'check_out_time' => $checkOutTime->format('H:i'),
                        'photo_url' => Storage::url('checkin_photos/' . $filename),
                    ], 200);
                }

                return response()->json([
                    'message' => $message,
                    'check_out_time' => $checkOutTime->format('H:i'),
                ], 200);
            } else if ($existingCheckin->type == 1 && $existingCheckin->status == 1) {
                // Jika sebelumnya check-out gagal (status == 1), izinkan check-out lagi
                $checkOutTime = now();
                $checkOutActivity = CheckinActivity::create([
                    'id_user' => $user->id,
                    'time' => $checkOutTime,
                    'type' => 1, // Tipe check-out
                    'status' => $status, // Status check-out (0 = sukses, 1 = gagal)
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);

                $message = $status == 0 ? 'Check-out berhasil' : 'Check-out gagal, jarak terlalu jauh';

                // Jika ada foto yang diunggah, simpan foto untuk check-out
                if ($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                    $filePath = $file->storeAs('public/checkin_photos', $filename);

                    // Update aktivitas check-out dengan path foto
                    $checkOutActivity->photo = $filename;
                    $checkOutActivity->save();

                    // Kirim URL foto dalam respons
                    return response()->json([
                        'message' => $message,
                        'check_out_time' => $checkOutTime->format('H:i'),
                        'photo_url' => Storage::url('checkin_photos/' . $filename),
                    ], 200);
                }

                return response()->json([
                    'message' => $message,
                    'check_out_time' => $checkOutTime->format('H:i'),
                ], 200);
            } else if ($existingCheckin->type == 1 && $existingCheckin->status == 0) {
                // Jika sudah melakukan check-out dengan status sukses, beri pesan sudah check-out
                return response()->json(['message' => 'Anda sudah melakukan check-out hari ini'], 400);
            }
        }

        // Jika belum ada check-in sebelumnya, lanjutkan untuk check-in
        $checkInTime = now();
        $checkInActivity = CheckinActivity::create([
            'id_user' => $user->id,
            'time' => $checkInTime,
            'type' => 0,
            'status' => $status,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        $message = $status == 0 ? 'Check-in berhasil' : 'Check-in gagal, jarak terlalu jauh';

        // Jika ada foto yang diunggah, simpan foto
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('public/checkin_photos', $filename);

            // Update aktivitas check-in dengan path foto
            $checkInActivity->photo = $filename;
            $checkInActivity->save();

            return response()->json([
                'message' => $message,
                'check_in_time' => $checkInTime->format('H:i'),
                'photo_url' => Storage::url('checkin_photos/' . $filename),
            ], 200);
        }

        return response()->json([
            'message' => $message,
            'check_in_time' => $checkInTime->format('H:i'),
        ], 200);
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
