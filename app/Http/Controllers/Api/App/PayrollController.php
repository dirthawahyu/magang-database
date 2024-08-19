<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Payroll;

class PayrollController extends Controller
{
    public function getByUserId($userId)
    {
        // Fetch employee by user ID
        $employee = Employee::where('id_user', $userId)->first();

        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found.'
            ], 404);
        }

        // Fetch all payrolls for the given employee ID with related categories
        $payrolls = Payroll::where('id_employee', $employee->id)
            ->with(['masterCategory', 'payrollDetails.masterCategory']) // Include master category from both payroll header and lines
            ->get();

        // Transform payrolls to include master category names
        $payrolls = $payrolls->map(function ($payroll) {
            return [
                'id' => $payroll->id,
                'id_employee' => $payroll->id_employee,
                'master_category' => $payroll->masterCategory ? $payroll->masterCategory->name : null,
                'payroll_date' => $payroll->payroll_date,
                'net_amount' => $payroll->net_amount,
                'lines' => $payroll->payrollDetails->map(function ($line) { // Corrected to payrollDetails
                    return [
                        'line_master_category' => $line->masterCategory ? $line->masterCategory->name : null,
                        'nominal' => $line->nominal,
                        'note' => $line->note,
                    ];
                }),
            ];
        });

        // Return a JSON response with the payrolls data
        return response()->json(['payrolls' => $payrolls]);
    }

    public function getDetailByPayrollId($payrollId)
{
    // Ambil payroll berdasarkan ID payroll header
    $payroll = Payroll::where('id', $payrollId)
        ->with(['masterCategory', 'payrollDetails.masterCategory'])
        ->first();

    if (!$payroll) {
        return response()->json(['message' => 'Payroll not found.'], 404);
    }

    // Transformasi payroll menjadi format yang diinginkan
    $payrollDetail = [
        'id' => $payroll->id,
        'id_employee' => $payroll->id_employee,
        'master_category' => $payroll->masterCategory ? $payroll->masterCategory->name : null,
        'payroll_date' => $payroll->payroll_date,
        'net_amount' => $payroll->net_amount,
        'lines' => $payroll->payrollDetails->map(function ($line) {
            return [
                'line_master_category' => $line->masterCategory ? $line->masterCategory->name : null,
                'nominal' => $line->nominal,
                'note' => $line->note,
            ];
        }),
    ];

    return response()->json(['payroll' => $payrollDetail]);
}

}
