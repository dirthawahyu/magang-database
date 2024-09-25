<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\BusinessTrip;
use App\Models\PlanningRealizationHeader;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class BusinessTripController extends Controller
{
    public function uploadFile(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        $trip = BusinessTrip::find($id);
        if ($trip->photo_document) {
            Storage::delete('public/photo_document/' . $trip->photo_document);
        }
        $file = $request->file('file');
        $filename = 'docs' . date('ymdHi') . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/photo_document', $filename);
        $trip->photo_document = $filename;
        $trip->save();
        return response()->json([
            'message' => 'File uploaded successfully',
            'file_name' => $filename
        ]);
    }

    public function getAllTripDetails(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $userRole = DB::table('employee')
                ->where('id_user', $userId)
                ->value('id_role');
            $rolePriority = DB::table('role')
                ->where('id', $userRole)
                ->value('priority');
            if ($rolePriority < 3) {
                $tripDetails = BusinessTrip::with(['companyCity.company', 'companyCity.city', 'users'])->get();
            } else {
                $tripDetails = BusinessTrip::with(['companyCity.company', 'companyCity.city', 'users'])
                    ->whereHas('users', function ($query) use ($userId) {
                        $query->where('users.id', $userId);
                    })
                    ->get();
            }
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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTripsStartingToday(): JsonResponse
    {
        try {
            $today = Carbon::now()->startOfDay();
            $tripDetails = BusinessTrip::with(['companyCity.company', 'companyCity.city', 'users'])
                ->whereDate('start_date', $today)
                ->get();
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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'id_company_city' => 'required|exists:company_city,id',
            'note' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'departure_from' => 'required|string',
            'extend_day' => 'nullable|integer',
        ]);
        try {
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

    public function updateExtendDay(Request $request, $id): JsonResponse
    {
        $validatedData = $request->validate([
            'extend_day' => 'required|integer',
        ]);
        try {
            $businessTrip = BusinessTrip::findOrFail($id);
            $businessTrip->extend_day = $validatedData['extend_day'];
            $businessTrip->save();
            return response()->json(['message' => 'Extend day updated successfully', 'data' => $businessTrip], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCategories(): JsonResponse
    {
        try {
            $categories = DB::table('category_expenditure')
                ->select('id', 'name')
                ->get();
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getNominalPlanning(Request $request, $id): JsonResponse
    {
        try {
            $idBusinessTrip = (int) $id;
            $planningData = DB::table('planning_realization_header')
                ->join('category_expenditure', 'planning_realization_header.id_category_expenditure', '=', 'category_expenditure.id')
                ->select('id_category_expenditure', 'category_expenditure.name as category_expenditure_name', 'planning_realization_header.keterangan', 'planning_realization_header.nominal')
                ->where('id_business_trip', $idBusinessTrip)
                ->where('type', 1) 
                ->get();
            return response()->json($planningData, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getNominalRealization(Request $request, $id): JsonResponse
    {
        try {
            $idBusinessTrip = (int) $id;
            $realizationData = DB::table('planning_realization_header')
                ->join('category_expenditure', 'planning_realization_header.id_category_expenditure', '=', 'category_expenditure.id')
                ->select('planning_realization_header.id', 'id_category_expenditure', 'category_expenditure.name as category_expenditure_name', 'planning_realization_header.keterangan', 'planning_realization_header.nominal')
                ->where('id_business_trip', $idBusinessTrip)
                ->where('type', 0)
                ->get();
            return response()->json($realizationData, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getNominalRealizationById(Request $request, $id): JsonResponse
    {
        try {
            $idRealizationHeader = (int) $id;
            $realizationData = DB::table('planning_realization_header')
                ->join('category_expenditure', 'planning_realization_header.id_category_expenditure', '=', 'category_expenditure.id')
                ->select(
                    'planning_realization_header.id_category_expenditure',
                    'category_expenditure.name as category_expenditure_name',
                    'planning_realization_header.keterangan',
                    'planning_realization_header.nominal',
                    'planning_realization_header.photo_proof'
                )
                ->where('planning_realization_header.id', $idRealizationHeader)
                ->first(); 
            if ($realizationData) {
                return response()->json($realizationData, 200);
            } else {
                return response()->json(['error' => 'Data not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addRealization(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'id_business_trip' => 'required|integer',
                'id_category_expenditure' => 'required|integer',
                'nominal' => 'required|numeric',
                'photo_proof' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
                'keterangan' => 'nullable|string',
            ]);
            if ($request->hasFile('photo_proof')) {
                $file = $request->file('photo_proof');
                $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/photo_proofs', $filename); 
                $validatedData['photo_proof'] = $filename;
            } else {
                $validatedData['photo_proof'] = null; 
            }
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

    public function updateRealization(Request $request, $id)
    {
        $validatedData = $request->validate([
            'keterangan' => 'required|string',
            'nominal' => 'required|integer',
            'photo_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        try {
            $realization = PlanningRealizationHeader::findOrFail($id);
            if ($request->hasFile('photo_proof')) {
                if ($realization->photo_proof) {
                    Storage::delete('public/photo_proofs/' . $realization->photo_proof);
                }
                $file = $request->file('photo_proof');
                $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/photo_proofs', $filename);
                $realization->photo_proof = $filename;
            }
            $realization->keterangan = $validatedData['keterangan'];
            $realization->nominal = $validatedData['nominal'];
            $realization->save();
            return response()->json(['message' => 'Data updated successfully', 'data' => $realization], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function calculate(Request $request, $id): JsonResponse
    {
        try {
            $idBusinessTrip = (int) $id;
            $totalPlanning = DB::table('planning_realization_header')
                ->where('id_business_trip', $idBusinessTrip)
                ->where('type', 1)
                ->sum(DB::raw('CAST(nominal AS DECIMAL(15,2))'));
            $totalRealization = DB::table('planning_realization_header')
                ->where('id_business_trip', $idBusinessTrip)
                ->where('type', 0)
                ->sum(DB::raw('CAST(nominal AS DECIMAL(15,2))'));
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
            $categories = DB::table('planning_realization_header')
                ->select('id_category_expenditure')
                ->where('id_business_trip', $id)
                ->groupBy('id_category_expenditure')
                ->get();
            $results = [];
            foreach ($categories as $category) {
                $totalPlanning = DB::table('planning_realization_header')
                    ->where('id_business_trip', $id)
                    ->where('id_category_expenditure', $category->id_category_expenditure)
                    ->where('type', 1) 
                    ->sum(DB::raw('CAST(nominal AS DECIMAL(15,2))'));
                $totalRealization = DB::table('planning_realization_header')
                    ->where('id_business_trip', $id)
                    ->where('id_category_expenditure', $category->id_category_expenditure)
                    ->where('type', 0)
                    ->sum(DB::raw('CAST(nominal AS DECIMAL(15,2))'));
                $percentage = $totalPlanning > 0
                    ? (($totalPlanning - $totalRealization) / $totalPlanning) * 100
                    : 0;
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