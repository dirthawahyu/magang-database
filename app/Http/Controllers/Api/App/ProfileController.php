<?php


namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\EmployeeGroup;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        $rolePriority = $user->employee ? Role::find($user->employee->id_role)->priority : null;
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
            'priority' => $rolePriority,
            'employee_group' => $employeeGroupName,
            'nip' => $user->employee->nip
        ];
        return response()->json($profile);
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::delete('public/profile_photos/' . $user->profile_photo);
            }
            $file = $request->file('profile_photo');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/profile_photos', $filename);
            $user->profile_photo = $filename;
            $user->save();
            return response()->json([
                'message' => 'Profile photo updated successfully.',
                'profile_photo_url' => Storage::url('profile_photos/' . $filename),
            ], 200);
        }
        return response()->json(['message' => 'Profile photo not uploaded.'], 400);
    }
}
