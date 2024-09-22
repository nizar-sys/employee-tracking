<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\AuthenticatedSessionController;
use App\Http\Controllers\Api\DesignationController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\Task\TaskController;
use App\Http\Controllers\Api\Task\TaskReportController;
use App\Http\Controllers\Api\UserController;
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
    return responseJson($request->user());
});

Route::get('/search', [SearchController::class, 'index']);
Route::post('login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');

Route::middleware('auth:sanctum')->name('api.')->group(function () {

    Route::middleware('role:Administrator')->group(function () {
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('users', UserController::class);
        Route::apiResource('designations', DesignationController::class);
        Route::resource('employees', EmployeeController::class);
    });

    Route::resource('leaves', LeaveController::class)->parameter('leaves', 'leave');
    Route::resource('attendances', AttendanceController::class);
    Route::resource('tasks', TaskController::class);
    Route::resource('task-reports', TaskReportController::class);
});
