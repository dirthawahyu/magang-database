<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Payroll;

class PayrollController extends Controller
{
    public function getByUserId($userId)
    {
        $employee = Employee::where('id_user', $userId)->first();
        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found.'
            ], 404);
        }
        $payrolls = Payroll::where('id_employee', $employee->id)
            ->with(['masterCategory', 'payrollDetails.masterCategory'])
            ->get();
        $payrolls = $payrolls->map(function ($payroll) {
            return [
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
        });
        return response()->json(['payrolls' => $payrolls]);
    }

    public function getDetailByPayrollId($payrollId)
    {
        $payroll = Payroll::where('id', $payrollId)
            ->with(['masterCategory', 'payrollDetails.masterCategory'])
            ->first();
        if (!$payroll) {
            return response()->json(['message' => 'Payroll not found.'], 404);
        }
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
