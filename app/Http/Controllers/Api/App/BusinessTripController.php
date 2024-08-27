<?php

namespace App\Http\Controllers\Api\App;

use Illuminate\Http\Request;
use App\Models\BusinessTrip;
use App\Http\Controllers\Controller;


class BusinessTripController extends Controller
{
    public function getAllTripDetails()
{
    $tripDetails = BusinessTrip::with('users') // Eager load relasi users
        ->select(
            'business_trip.id as business_trip_id', // Alias untuk kolom id dari business_trip
            'company.name as company_name',
            'city.name as city_name',
            'business_trip.start_date',
            'business_trip.end_date',
            'business_trip.status',
            'company_city.address as company_address',
            'company_city.pic',
            'company_city.pic_role',
            'business_trip.extend_day'
        )
        ->join('company', 'business_trip.id_company', '=', 'company.id')
        ->join('company_city', 'company.id', '=', 'company_city.id_company')
        ->join('city', 'company_city.id_city', '=', 'city.id')
        ->get()
        ->map(function ($trip) {
            return [
                'company_name' => $trip->company_name,
                'city_name' => $trip->city_name,
                'start_date' => $trip->start_date,
                'end_date' => $trip->end_date,
                'status' => $trip->status,
                'company_address' => $trip->company_address,
                'pic' => $trip->pic,
                'pic_role' => $trip->pic_role,
                'extend_day' => $trip->extend_day,
                'users' => $trip->users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'full_name' => $user->first_name,
                    ];
                })
            ];
        });

    return response()->json($tripDetails);
}

}