<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\Employee; // Pastikan model Employee diimpor
use App\Models\Payroll;
class PayrollController extends Controller
{
    public function index()
    {
        // Fetch all payrolls from the database
        $payrolls = Payroll::with('masterCategory')->get();

        // Transform payrolls to include master category names
        $payrolls = $payrolls->map(function ($payroll) {
            return [
                'id' => $payroll->id,
                'id_employee' => $payroll->id_employee,
                'master_category' => $payroll->masterCategory ? $payroll->masterCategory->name : null,
                'payroll_date' => $payroll->payroll_date,
                'net_amount' => $payroll->net_amount,
            ];
        });

        // Return a JSON response with the payrolls data
        return response()->json(['payrolls' => $payrolls]);
    }

    public function show($id)
    {
        $payroll = Payroll::with('masterCategory')->find($id);

        if (!$payroll) {
            return response()->json([
                'message' => 'Payroll not found.'
            ], 404);
        }

        return response()->json([
            'payroll' => [
                'id_employee' => $payroll->id_employee,
                'master_category' => $payroll->masterCategory ? $payroll->masterCategory->name : null,
                'payroll_date' => $payroll->payroll_date,
                'net_amount' => $payroll->net_amount,
            ]
        ]);
    }

    public function getByUserId($userId)
    {
        // Fetch employee by user ID
        $employee = Employee::where('id_user', $userId)->first();

        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found.'
            ], 404);
        }

        // Fetch all payrolls for the given employee ID
        $payrolls = Payroll::where('id_employee', $employee->id)->with('masterCategory')->get();

        // Transform payrolls to include master category names
        $payrolls = $payrolls->map(function ($payroll) {
            return [
                'id' => $payroll->id,
                'id_employee' => $payroll->id_employee,
                'master_category' => $payroll->masterCategory ? $payroll->masterCategory->name : null,
                'payroll_date' => $payroll->payroll_date,
                'net_amount' => $payroll->net_amount,
            ];
        });

        // Return a JSON response with the payrolls data
        return response()->json(['payrolls' => $payrolls]);
    }
}
