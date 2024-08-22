<?php

use App\Http\Controllers\Api\App\ContractController;
use App\Http\Controllers\Api\App\EditController;
use App\Http\Controllers\Api\App\LeaveController;
use App\Http\Controllers\Api\App\LoginController;
use App\Http\Controllers\Api\App\PasswordController;
use App\Http\Controllers\Api\App\PayrollController;
use App\Http\Controllers\Api\App\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', \App\Http\Controllers\Api\Auth\RegisterController::class);

//API APP
Route::post('app/login', [LoginController::class, 'loginApp']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('app/user', [LoginController::class, 'fetch'])->name('fetch');
    
    //Profile
    Route::get('app/profile', [ProfileController::class, 'index']);
    Route::get('app/profile/{id}', [ProfileController::class, 'show']);
    Route::post('app/profile/photo', [ProfileController::class, 'updateProfilePhoto'])->name('updateProfilePhoto');
    Route::post('app/edit', [EditController::class, 'editProfile'])->name('editProfile');
    Route::post('app/password', [PasswordController::class, 'changePassword'])->name('changePassword');
    Route::post('app/logout', [LoginController::class, 'logout'])->name('logout');
    //Contract
    Route::get('app/contract/', [ContractController::class, 'index']);
    Route::get('app/contract/{id}', [ContractController::class, 'getByUserId']);

    //Payroll
    Route::get('app/payrolls/user/{userId}', [PayrollController::class, 'getByUserId']);
    Route::get('app/payroll/{payrollId}', [PayrollController::class, 'getDetailByPayrollId']);


    //Leave
    Route::get('app/leave', [LeaveController::class, 'index']);
    Route::get('app/leave/detail/{id}', [LeaveController::class, 'getWithLeaveId']);
    Route::post('app/leave', [LeaveController::class, 'store'])->name('store');
    Route::get('app/leave/user/{id}', [LeaveController::class, 'getByUserId']);
    Route::get('app/leave/category', [LeaveController::class, 'leaveCategory']);
});