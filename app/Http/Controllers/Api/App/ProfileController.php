<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = User::with(['employee' => function ($query) {
            $query->select('id', 'id_user', 'id_role', 'id_employee_group', 'id_position');
        }])->get(['id', 'name', 'gender_status', 'religion']);

        $profiles = $profiles->map(function ($user) {
            return [
                'name' => $user->name,
                'gender' => $user->gender_status,
                'religion' => $user->religion,
                'role' => $user->employee ? $user->employee->id_role : null,
                'employee_group' => $user->employee ? $user->employee->id_employee_group : null,
                'position' => $user->employee ? $user->employee->id_position : null,
            ];
        });

        return response()->json($profiles);
    }

    public function show($id)
    {
        $user = User::with(['employee' => function ($query) {
            $query->select('id', 'id_user', 'id_role', 'id_employee_group', 'id_position','nip');
        }])->find($id, ['id', 'name', 'gender_status', 'religion','phone_number', 'address', 'email']);

        if (!$user) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $profile = [
            'name' => $user->name,
            'gender' => $user->gender_status,
            'religion' => $user->religion,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
            'email' => $user->email,
            'role' => $user->employee->id_role,
            'employee_group' =>$user->employee->id_employee_group,
            'position' =>$user->employee->id_position,
            'nip' => $user->employee->nip
        ];

        return response()->json($profile);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required',
            'gender_status' => 'required|in:Tidak Diketahui,Laki-Laki,Perempuan',
            'religion' => 'nullable|string',
            'id_role' => 'required|exists:role,id',
            'id_employee_group' => 'required|exists:employee_group,id',
            'id_position' => 'required|exists:position,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userData = $request->only(['name', 'gender_status', 'religion', 'email', 'password']);
        $userData['password'] = bcrypt($userData['password']);

        $user = User::create($userData);

        $employeeData = $request->only(['id_role', 'id_employee_group', 'id_position']);
        $employeeData['id_user'] = $user->id;

        $employee = Employee::create($employeeData);

        $profile = [
            'name' => $user->name,
            'gender' => $user->gender_status,
            'religion' => $user->religion,
            'role' => $employee->id_role,
            'employee_group' => $employee->id_employee_group,
            'position' => $employee->id_position,
        ];

        return response()->json($profile, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:6',
            'gender_status' => 'required|in:Tidak Diketahui,Laki-Laki,Perempuan',
            'religion' => 'nullable|string',
            'id_role' => 'required|exists:role,id',
            'id_employee_group' => 'required|exists:employee_group,id',
            'id_position' => 'required|exists:position,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userData = $request->only(['name', 'gender_status', 'religion', 'email', 'password']);
        if (isset($userData['password'])) {
            $userData['password'] = bcrypt($userData['password']);
        }

        $user->update($userData);

        $employeeData = $request->only(['id_role', 'id_employee_group', 'id_position']);
        if ($user->employee) {
            $user->employee->update($employeeData);
        } else {
            $employeeData['id_user'] = $user->id;
            Employee::create($employeeData);
        }

        $profile = [
            'name' => $user->name,
            'gender' => $user->gender_status,
            'religion' => $user->religion,
            'role' => $user->employee ? $user->employee->id_role : null,
            'employee_group' => $user->employee ? $user->employee->id_employee_group : null,
            'position' => $user->employee ? $user->employee->id_position : null,
        ];

        return response()->json($profile);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        if ($user->employee) {
            $user->employee()->delete();
        }
        $user->delete();

        return response()->json(['message' => 'Profile deleted successfully']);
    }
}

