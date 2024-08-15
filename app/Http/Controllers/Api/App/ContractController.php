<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\Contract; // Ensure you have a Contract model

class ContractController extends Controller
{
    public function index()
    {
        // Fetch all contracts from the database
        $contracts = Contract::all();

        // Return a JSON response with the contracts data
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
        // Fetch all contracts for the given user ID
        $contracts = Contract::where('id_user', $userId)->get();

        if ($contracts->isEmpty()) {
            return response()->json([
                'message' => 'No contracts found for this user.'
            ], 404);
        }

        // Return a JSON response with the contracts data wrapped in an 'contracts' key
        return response()->json([
            'contracts' => $contracts
        ]);
    }



}