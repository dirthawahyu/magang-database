<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\Contract;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::all();
        return response()->json($contracts);
    }

    public function show($id)
    {
        $contract = Contract::find($id);
        if (!$contract) {
            return response()->json([
                'message' => 'Contract not found.'
            ], 404);
        }
        return response()->json([
            'contract' => [
                'id_user' => $contract->id_user,
                'status' => $contract->status,
                'start_date' => $contract->start_date,
                'end_date' => $contract->end_date,
            ]
        ]);
    }

    public function getByUserId($userId)
    {
        $contracts = Contract::where('id_user', $userId)->get();
        if ($contracts->isEmpty()) {
            return response()->json([
                'message' => 'No contracts found for this user.'
            ], 404);
        }
        return response()->json([
            'contracts' => $contracts
        ]);
    }
}