<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\BusinessTrip;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyCity;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class BusinessTripController extends Controller
{

    public function uploadFile(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Mendapatkan instance BusinessTrip berdasarkan ID
        $trip = BusinessTrip::find($id);

        // Menghapus file lama jika ada
        if ($trip->photo_document) {
            Storage::delete('public/photo_document/' . $trip->photo_document);
        }

        // Mengunggah file baru
        $file = $request->file('file');
        $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/photo_document', $filename);

        // Memperbarui path file di database
        $trip->photo_document = $filename;
        $trip->save();

        return response()->json([
            'message' => 'File uploaded successfully',
            'file_name' => $filename // Kembalikan nama file yang diunggah
        ]);
    }



    public function getAllTripDetails(): JsonResponse
    {
        try {
            $tripDetails = BusinessTrip::with(['companyCity.company', 'companyCity.city', 'users'])->get();

            $formattedTripDetails = $tripDetails->map(function ($trip) {
                return [
                    'id_business_trip' => $trip->id,
                    'company_name' => $trip->companyCity->company->name ?? 'N/A',
                    'city_name' => optional($trip->companyCity->city)->name ?? 'N/A',
                    'start_date' => $trip->start_date,
                    'end_date' => $trip->end_date,
                    'status' => $trip->status,
                    'note' => $trip->note,
                    'company_address' => optional($trip->companyCity)->address ?? 'N/A',
                    'photo_document' => $trip->photo_document,
                    'departure_from' => $trip->departure_from,
                    'pic' => optional($trip->companyCity)->pic ?? 'N/A',
                    'pic_role' => optional($trip->companyCity)->pic_role ?? 'N/A',
                    'pic_phone' => optional($trip->companyCity)->pic_phone ?? 'N/A',
                    'extend_day' => $trip->extend_day,
                    'users' => $trip->users->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'full_name' => $user->full_name,
                        ];
                    }),
                ];
            });

            return response()->json($formattedTripDetails);
        } catch (\Exception $e) {
            // Tangani error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'id_company_city' => 'required|exists:company_city,id',
            'note' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'departure_from' => 'required|string',
            'extend_day' => 'nullable|integer',
        ]);

        try {


            // Buat BusinessTrip baru
            $businessTrip = BusinessTrip::create([
                'id_company_city' => $validatedData['id_company_city'] ?? '',
                'note' => $validatedData['note'] ?? '',
                'photo_document' => $validatedData['photo_document'] ?? null,
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'departure_from' => $validatedData['departure_from'],
                'extend_day' => $validatedData['extend_day'] ?? 0,
            ]);

            return response()->json([
                'message' => 'Business trip created successfully',
                'data' => $businessTrip
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storeTripDetail(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'id_business_trip' => 'required|exists:business_trip,id',
            'id_user' => 'required|array',
            'id_user.*' => 'exists:users,id',
        ]);

        try {
            // Simpan detail perjalanan ke dalam tabel trip_detail
            foreach ($validatedData['id_user'] as $userId) {
                DB::table('trip_detail')->updateOrInsert(
                    ['id_user' => $userId, 'id_business_trip' => $validatedData['id_business_trip']],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }

            return response()->json([
                'message' => 'Trip details added successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function company()
    {
        $company = Company::all();

        return response()->json($company);
    }

    public function city()
    {
        $city = City::all();

        return response()->json($city);
    }

    public function updateExtendDay(Request $request, $id): JsonResponse
    {
        $validatedData = $request->validate([
            'extend_day' => 'required|integer',
        ]);

        try {
            // Cari BusinessTrip berdasarkan ID
            $businessTrip = BusinessTrip::findOrFail($id);

            // Update extend day
            $businessTrip->extend_day = $validatedData['extend_day'];
            $businessTrip->save();

            return response()->json(['message' => 'Extend day updated successfully', 'data' => $businessTrip], 200);
        } catch (\Exception $e) {
            // Tangani error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUsersFullName(): JsonResponse
    {
        try {
            $users = User::all(); // Ambil semua data user

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

    public function getCompanyCity(): JsonResponse
    {
        try {
            // Mengambil data dari tabel company_city beserta informasi terkait company dan city
            $companyCities = CompanyCity::with(['company', 'city'])->get();

            // Memformat data untuk output JSON
            $formattedCompanyCities = $companyCities->map(function ($companyCity) {
                return [
                    'id' => $companyCity->id,
                    'id_company' => $companyCity->company->id ?? 'N/A',
                    'company_name' => $companyCity->company->name ?? 'N/A',
                    'id_city' => $companyCity->city->id ?? 'N/A',
                    'city_name' => $companyCity->city->name ?? 'N/A',
                    'address' => $companyCity->address,
                    'pic' => $companyCity->pic,
                    'pic_role' => $companyCity->pic_role,
                    'pic_phone' => $companyCity->pic_phone,
                ];
            });

            return response()->json($formattedCompanyCities, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCategories(): JsonResponse
    {
        try {
            $categories = DB::table('category_expenditure')
                ->select('id', 'name') // Sesuaikan dengan kolom yang ada di tabel Anda
                ->get();

            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getNominalPlanning(Request $request, $id): JsonResponse
    {
        try {
            // Konversi ID dari permintaan menjadi integer
            $idBusinessTrip = (int) $id;

            // Mengambil data perencanaan berdasarkan ID perjalanan bisnis
            $planningData = DB::table('planning_realization_header')
                ->join('category_expenditure', 'planning_realization_header.id_category_expenditure', '=', 'category_expenditure.id')
                ->select('id_category_expenditure', 'category_expenditure.name as category_expenditure_name', 'planning_realization_header.keterangan', 'planning_realization_header.nominal')
                ->where('id_business_trip', $idBusinessTrip)
                ->where('type', 1) // Hanya mengambil data yang memiliki type 1 (Estimasi)
                ->get();

            return response()->json($planningData, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getNominalRealization(Request $request, $id): JsonResponse
    {
        try {
            // Konversi ID dari permintaan menjadi integer
            $idBusinessTrip = (int) $id;

            // Mengambil data realisasi berdasarkan ID perjalanan bisnis
            $realizationData = DB::table('planning_realization_header')
                ->join('category_expenditure', 'planning_realization_header.id_category_expenditure', '=', 'category_expenditure.id')
                ->select('id_category_expenditure', 'category_expenditure.name as category_expenditure_name', 'planning_realization_header.keterangan', 'planning_realization_header.nominal')
                ->where('id_business_trip', $idBusinessTrip)
                ->where('type', 0) // Hanya mengambil data yang memiliki type 0 (Realisasi)
                ->get();

            return response()->json($realizationData, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addRealization(Request $request): JsonResponse
    {
        try {
            // Validasi data yang diterima
            $validatedData = $request->validate([
                'id_business_trip' => 'required|integer',
                'id_category_expenditure' => 'required|integer',
                'nominal' => 'required|numeric',
                'photo_proof' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
                'keterangan' => 'nullable|string',
            ]);

            // Menyimpan file jika ada
            if ($request->hasFile('photo_proof')) {
                $file = $request->file('photo_proof');
                $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/photo_proofs', $filename); // Simpan di storage
                $validatedData['photo_proof'] = $filename; // Simpan nama file, bukan path
            } else {
                $validatedData['photo_proof'] = null; // Jika tidak ada file, set ke null
            }

            // Menambahkan data ke tabel dengan type = 0 (Realisasi)
            $validatedData['type'] = 0;

            $id = DB::table('planning_realization_header')->insertGetId($validatedData);

            return response()->json([
                'message' => 'Data added successfully',
                'id' => $id
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function updateRealization(Request $request, $id): JsonResponse
{
    try {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'keterangan' => 'nullable|string',
            'nominal' => 'nullable|numeric',
            'photo_proof' => 'nullable|file|mimes:jpeg,png,jpg|max:2048', // Validasi file
        ]);

        // Mendapatkan data yang ada untuk memastikan data file
        $existingRecord = DB::table('planning_realization_header')
            ->where('id', $id)
            ->where('type', 0) // Pastikan hanya memperbarui data realisasi
            ->first();

        if (!$existingRecord) {
            return response()->json([
                'message' => 'No data found with the given ID or type does not match',
            ], 404);
        }

        // Menyimpan file jika ada
        if ($request->hasFile('photo_proof')) {
            // Hapus file lama jika ada
            if ($existingRecord->photo_proof) {
                Storage::delete('public/photo_proofs/' . $existingRecord->photo_proof);
            }

            // Simpan file baru
            $file = $request->file('photo_proof');
            $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/photo_proofs', $filename);
            $validatedData['photo_proof'] = $filename; // Simpan nama file, bukan path
        } else {
            $validatedData['photo_proof'] = $existingRecord->photo_proof; // Pertahankan nama file yang ada jika tidak ada file baru
        }

        // Mengupdate data di tabel
        DB::table('planning_realization_header')
            ->where('id', $id)
            ->where('type', 0)
            ->update($validatedData);

        return response()->json([
            'message' => 'Data updated successfully',
        ], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}





    public function calculate(Request $request, $id): JsonResponse
    {
        try {
            // Konversi ID dari permintaan menjadi integer
            $idBusinessTrip = (int) $id;

            // Mengambil total nominal planning (estimasi)
            $totalPlanning = DB::table('planning_realization_header')
                ->where('id_business_trip', $idBusinessTrip)
                ->where('type', 1) // 1 berarti estimasi
                ->sum(DB::raw('CAST(nominal AS DECIMAL(15,2))'));

            // Mengambil total nominal realization (realisasi)
            $totalRealization = DB::table('planning_realization_header')
                ->where('id_business_trip', $idBusinessTrip)
                ->where('type', 0) // 0 berarti realisasi
                ->sum(DB::raw('CAST(nominal AS DECIMAL(15,2))'));

            // Menghitung selisih
            $difference = $totalPlanning - $totalRealization;

            return response()->json([
                'id_business_trip' => $idBusinessTrip,
                'total_nominal_planning' => number_format($totalPlanning, 2, ',', '.'),
                'total_nominal_realization' => number_format($totalRealization, 2, ',', '.'),
                'difference' => number_format($difference, 2, ',', '.'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getPercentage($id): JsonResponse
    {
        try {
            // Mengambil semua kategori berdasarkan ID perjalanan bisnis
            $categories = DB::table('planning_realization_header')
                ->select('id_category_expenditure')
                ->where('id_business_trip', $id)
                ->groupBy('id_category_expenditure')
                ->get();

            $results = [];

            foreach ($categories as $category) {
                // Mengambil total nominal untuk estimasi (type 1)
                $totalPlanning = DB::table('planning_realization_header')
                    ->where('id_business_trip', $id)
                    ->where('id_category_expenditure', $category->id_category_expenditure)
                    ->where('type', 1) // 1 berarti estimasi
                    ->sum(DB::raw('CAST(nominal AS DECIMAL(15,2))'));

                // Mengambil total nominal untuk realisasi (type 0)
                $totalRealization = DB::table('planning_realization_header')
                    ->where('id_business_trip', $id)
                    ->where('id_category_expenditure', $category->id_category_expenditure)
                    ->where('type', 0) // 0 berarti realisasi
                    ->sum(DB::raw('CAST(nominal AS DECIMAL(15,2))'));

                // Menghitung persentase
                $percentage = $totalPlanning > 0
                    ? (($totalPlanning - $totalRealization) / $totalPlanning) * 100
                    : 0;

                // Mendapatkan nama kategori
                $categoryName = DB::table('category_expenditure')
                    ->where('id', $category->id_category_expenditure)
                    ->value('name');

                $results[] = [
                    'id_category_expenditure' => $category->id_category_expenditure,
                    'category_name' => $categoryName,
                    'total_nominal_planning' => number_format($totalPlanning, 2, ',', '.'),
                    'total_nominal_realization' => number_format($totalRealization, 2, ',', '.'),
                    'percentage' => number_format($percentage, 2, ',', '.'),
                ];
            }

            return response()->json($results, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}