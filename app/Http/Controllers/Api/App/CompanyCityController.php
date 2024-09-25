<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyCity;
use Illuminate\Http\JsonResponse;

class CompanyCityController extends Controller
{
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

    public function getCompanyCity(): JsonResponse
    {
        try {
            $companyCities = CompanyCity::with(['company', 'city'])->get();
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
