<?php


namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\EmployeeGroup;
use App\Models\Role;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = User::with(['employee' => function ($query) {
            $query->select('id', 'id_user', 'id_role', 'id_employee_group');
        }])->get(['id', 'first_name', 'last_name', 'gender_status', 'religion', 'profile_photo']);

        $profiles = $profiles->map(function ($user) {
            $roleName = $user->employee ? Role::find($user->employee->id_role)->name : null;

            return [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'gender' => $user->gender_status,
                'religion' => $user->religion,
                'profile_photo' => $user->profile_photo,
                'role' => $roleName,
                'employee_group' => $user->employee ? $user->employee->id_employee_group : null,
            ];
        });

        return response()->json($profiles);
    }

    public function show($id)
    {
        $user = User::with(['employee' => function ($query) {
            $query->select('id', 'id_user', 'id_role', 'id_employee_group', 'nip');
        }])->find($id, ['id', 'first_name', 'last_name', 'gender_status', 'religion', 'phone_number', 'address', 'email', 'profile_photo']);

        if (!$user) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $roleName = $user->employee ? Role::find($user->employee->id_role)->name : null;
        $employeeGroupName = $user->employee ? EmployeeGroup::find($user->employee->id_employee_group)->code : null;

        $profile = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'gender' => $user->gender_status,
            'religion' => $user->religion,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
            'email' => $user->email,
            'profile_photo' => $user->profile_photo,
            'role' => $roleName,
            'employee_group' => $employeeGroupName,
            'nip' => $user->employee->nip
        ];

        return response()->json($profile);
    }
}
