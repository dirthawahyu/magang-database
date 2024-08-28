<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\BusinessTrip;
use Illuminate\Http\JsonResponse;

class BusinessTripController extends Controller
{
    public function getAllTripDetails(): JsonResponse
    {
        try {
            $tripDetails = BusinessTrip::with(['companyCity.company', 'companyCity.city', 'users'])->get();

            $formattedTripDetails = $tripDetails->map(function ($trip) {
                return [
                    'company_name' => $trip->companyCity->company->name ?? 'N/A',
                    'city_name' => optional($trip->companyCity->city)->name ?? 'N/A',
                    'start_date' => $trip->start_date,
                    'end_date' => $trip->end_date,
                    'status' => $trip->status,
                    'company_address' => optional($trip->companyCity)->address ?? 'N/A',
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
}
