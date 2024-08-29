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

class BusinessTripController extends Controller
{
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
                    'company_address' => optional($trip->companyCity)->address ?? 'N/A',
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



}