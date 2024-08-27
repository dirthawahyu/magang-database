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
            $tripDetails = BusinessTrip::with(['company.companyCities.city', 'users'])
                ->get();

            $formattedTripDetails = $tripDetails->map(function ($trip) {
                return [
                    'company_name' => $trip->company->name ?? 'N/A',
                    'city_name' => $trip->company->companyCities->first()->city->name ?? 'N/A',
                    'start_date' => $trip->start_date,
                    'end_date' => $trip->end_date,
                    'status' => $trip->status,
                    'company_address' => $trip->company->companyCities->first()->address ?? 'N/A',
                    'pic' => $trip->company->companyCities->first()->pic ?? 'N/A',
                    'pic_role' => $trip->pic_role,
                    'extend_day' => $trip->extend_day,
                    'users' => $trip->users->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'full_name' => $user->full_name,
                        ];
                    })
                ];
            });

            return response()->json($formattedTripDetails);
        } catch (\Exception $e) {
            // Tangani error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
