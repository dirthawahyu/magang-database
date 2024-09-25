<?php

use App\Http\Controllers\Api\App\BusinessTripController;
use App\Http\Controllers\Api\App\CheckInActivityController;
use App\Http\Controllers\Api\App\CompanyCityController;
use App\Http\Controllers\Api\App\ContractController;
use App\Http\Controllers\Api\App\EditController;
use App\Http\Controllers\Api\App\LeaveController;
use App\Http\Controllers\Api\App\LoginController;
use App\Http\Controllers\Api\App\OtpController;
use App\Http\Controllers\Api\App\PasswordController;
use App\Http\Controllers\Api\App\PayrollController;
use App\Http\Controllers\Api\App\ProfileController;
use App\Http\Controllers\Api\App\UserController;
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
    Route::post('app/leave/make/', [LeaveController::class, 'store'])->name('store');
    Route::get('app/leave/user/{id}', [LeaveController::class, 'getByUserId']);
    Route::get('app/leave/category', [LeaveController::class, 'leaveCategory']);
    Route::put('app/leave/update/{id}', [LeaveController::class, 'updateStatus']);
    Route::delete('app/leave/{id}', [LeaveController::class, 'destroy']);

    //Business Trip
    Route::get('app/business/', [BusinessTripController::class, 'getAllTripDetails']);
    Route::put('app/business/extend/{id}', [BusinessTripController::class, 'updateExtendDay']);
    Route::post('app/business/make/', [BusinessTripController::class, 'store']);
    Route::post('app/business/trip', [BusinessTripController::class, 'storeTripDetail']);
    Route::get('app/business/planning/{id}', [BusinessTripController::class, 'getNominalPlanning']);
    Route::get('app/business/realization/{id}', [BusinessTripController::class, 'getNominalRealization']);
    Route::get('app/business/calculate/{id}', [BusinessTripController::class, 'calculate']);
    Route::get('app/business/percentage/{id}', [BusinessTripController::class, 'getPercentage']);
    Route::post('app/business/add', [BusinessTripController::class, 'addRealization']);
    Route::post('app/business/update/{id}', [BusinessTripController::class, 'updateRealization']);
    Route::get('app/business/categories', [BusinessTripController::class, 'getCategories']);
    Route::get('app/business/realizationid/{id}', [BusinessTripController::class, 'getNominalRealizationById']);
    Route::post('app/business/upload-file/{id}', [BusinessTripController::class, 'uploadFile']);
    Route::get('app/business/trip-today', [BusinessTripController::class, 'getTripsStartingToday']);

    //Company City
    Route::get('app/companycity/company', [CompanyCityController::class, 'company']);
    Route::get('app/companycity/city', [CompanyCityController::class, 'city']);
    Route::get('app/companycity/companycity', [CompanyCityController::class, 'getCompanyCity']);


    //User
    Route::get('app/user/users/fullname', [UserController::class, 'getUsersFullName']);


    //Check In
    Route::post('app/checkin', [CheckInActivityController::class, 'checkIn']);
    Route::get('app/checkin/today/{user}', [CheckinActivityController::class, 'getTodayActivities']);

    //Otp
    Route::post('app/otp/send', [OtpController::class, 'sendOtp']);
    Route::post('app/otp/verify', [OtpController::class, 'verifyOtp']);

});