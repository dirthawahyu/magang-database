<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\LeaveCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Employee;
use Google_Client;

class LeaveController extends Controller
{
    public function index()
    {
        // Mengambil semua data leave beserta relasi ke user dan master category
        $leaves = Leave::with(['user', 'leaveCategory', 'employee'])->get();

        return response()->json([
            'leaves' => $leaves->map(function ($leave) {
                return [
                    'id' => $leave->id,
                    'id_user' => $leave->id_user,
                    'first_name' => $leave->user ? $leave->user->first_name : null,
                    'last_name' => $leave->user ? $leave->user->last_name : null,
                    'nip' => $leave->employee ? $leave->employee->nip : null,
                    'leave_category' => $leave->leaveCategory ? $leave->leaveCategory->name : null,
                    'reason_for_leave' => $leave->reason_for_leave,
                    'start_date' => $leave->start_date,
                    'end_date' => $leave->end_date,
                    'status' => $leave->status,
                ];
            })
        ]);
    }

    public function getWithLeaveId($id)
    {
        // Mencari data cuti berdasarkan ID
        $leave = Leave::with(['user', 'leaveCategory', 'employee'])->find($id);

        if (!$leave) {
            return response()->json([
                'message' => 'Leave not found.'
            ], 404);
        }

        return response()->json([
            'leave' => [
                'id_user' => $leave->id_user,
                'first_name' => $leave->user ? $leave->user->first_name : null,
                'last_name' => $leave->user ? $leave->user->last_name : null,
                'nip' => $leave->employee ? $leave->employee->nip : null,
                'leave_category' => $leave->leaveCategory ? $leave->leaveCategory->name : null,
                'reason_for_leave' => $leave->reason_for_leave,
                'start_date' => $leave->start_date,
                'end_date' => $leave->end_date,
                'status' => $leave->status,
            ]
        ]);
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'id_leave_category' => 'required|integer',
            'reason_for_leave' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'nullable|string',
        ]);

        try {
            $userId = auth()->user()->id;
            $userRole = DB::table('employee')
                ->where('id_user', $userId)
                ->value('id_role');
            $rolePriority = DB::table('role')
                ->where('id', $userRole)
                ->value('priority');

            // Set status
            $status = $rolePriority < 3 ? 'approved' : 'pending';

            // Simpan data leave ke database
            $leave = new Leave();
            $leave->id_user = $userId;
            $leave->id_leave_category = $request->id_leave_category;
            $leave->reason_for_leave = $request->reason_for_leave;
            $leave->start_date = $request->start_date;
            $leave->end_date = $request->end_date;
            $leave->status = $status;
            $leave->save();

            // Ambil employee dengan role 1 dan 2
            $employeesToNotify = Employee::whereIn('id_role', [1, 2])->get();

            // Kirim notifikasi ke employee yang role-nya 1 dan 2
            foreach ($employeesToNotify as $employee) {
                if ($employee->fcm_token) {
                    $this->sendFCMNotification(
                        $employee->fcm_token,
                        "New Leave Request",
                        "Employee with role 3, 4, 5, or 6 has requested leave."
                    );
                }
            }

            return response()->json([
                'message' => 'Leave created successfully, and notifications sent.',
                'leave' => $leave
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function sendFCMNotification($fcmToken, $title, $message)
    {
        // Ambil token akses dari Service Account
        $accessToken = $this->getAccessToken();

        // Mengirim request ke FCM API (V1)
        $notification = [
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body' => $message,
                ],
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/v1/projects/intern-nextbasis/messages:send', $notification);

        return $response->json();
    }

    private function getAccessToken()
    {
        // Gantikan dengan jalur ke file JSON kredensial Service Account Anda
        $serviceAccountPath = storage_path('app/firebase/serviceAccountKey.json');

        // Ambil token akses menggunakan Google Client Library
        $client = new \Google_Client();
        $client->setAuthConfig($serviceAccountPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $accessToken = $client->fetchAccessTokenWithAssertion();
        return $accessToken['access_token'];
    }


    public function getByUserId($userId)
    {
        // Fetch all leaves for the given user ID
        $leaves = Leave::with(['user', 'leaveCategory', 'employee'])
            ->where('id_user', $userId)
            ->get();

        if ($leaves->isEmpty()) {
            return response()->json([
                'message' => 'No leaves found for this user.'
            ], 404);
        }

        // Return a JSON response with the leaves data wrapped in an 'leaves' key
        return response()->json([
            'leaves' => $leaves->map(function ($leave) {
                return [
                    'id' => $leave->id,
                    'id_user' => $leave->id_user,
                    'first_name' => $leave->user ? $leave->user->first_name : null,
                    'last_name' => $leave->user ? $leave->user->last_name : null,
                    'nip' => $leave->employee ? $leave->employee->nip : null,
                    'leave_category' => $leave->leaveCategory ? $leave->leaveCategory->name : null,
                    'reason_for_leave' => $leave->reason_for_leave,
                    'start_date' => $leave->start_date,
                    'end_date' => $leave->end_date,
                    'status' => $leave->status,
                ];
            })
        ]);
    }

    public function LeaveCategory()
    {
        $leavesCategory = LeaveCategory::all();

        return response()->json($leavesCategory);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Approved,Declined,Canceled',
        ]);

        $leave = Leave::find($id);

        if (!$leave) {
            return response()->json([
                'message' => 'Leave not found.'
            ], 404);
        }

        $leave->status = $request->status;
        $leave->save();

        return response()->json([
            'message' => 'Leave status updated successfully.',
            'leave' => $leave
        ]);
    }

    public function destroy($id)
    {
        $leave = Leave::find($id);

        if (!$leave) {
            return response()->json([
                'message' => 'Leave not found.'
            ], 404);
        }

        $leave->delete();

        return response()->json([
            'message' => 'Leave deleted successfully.'
        ], 200);
    }
}
