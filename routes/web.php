<?php

use App\Http\Controllers\Console\DesignationController;
use App\Http\Controllers\Console\EmployeeController;
use App\Http\Controllers\Console\RoleController;
use App\Http\Controllers\Console\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Hr\AttendanceController;
use App\Http\Controllers\Hr\LeaveController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Task\TaskReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::prefix('console')->middleware(['auth', 'verified'])->group(function () {

    Route::middleware('role:Administrator')->group(function () {
        Route::get('/tasks/{task}/employees', [TaskReportController::class, 'getEmployeesByTask'])->name('task.employees.get');
        Route::resource('roles', RoleController::class);
        Route::patch('/users/profile/{id}', [UserController::class, 'updateDetail'])->name('users.profile.update');
        Route::resource('users', UserController::class);

        Route::resource('designations', DesignationController::class);
        Route::resource('employees', EmployeeController::class);

        Route::resource('tasks', TaskController::class);
        Route::resource('task-reports', TaskReportController::class)->parameter('task-reports', 'taskReport');
    });

    Route::resource('leaves', LeaveController::class)->parameter('leaves', 'leave');
    Route::resource('attendances', AttendanceController::class);
});
