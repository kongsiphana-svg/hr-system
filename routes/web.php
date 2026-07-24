<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\PayrollPageController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

// Auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Main admin console (shared shell)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/settings', [DashboardController::class, 'settings'])->name('settings.index');

// Employees
Route::get('/employees/export', [EmployeeController::class, 'export'])->name('employees.export');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees/create', [EmployeeController::class, 'storeStep1'])->name('employees.create.store');
Route::get('/employees/create/contact', [EmployeeController::class, 'createContact'])->name('employees.create.contact');
Route::post('/employees/create/contact', [EmployeeController::class, 'storeContact'])->name('employees.create.contact.store');
Route::get('/employees/create/job', [EmployeeController::class, 'createJob'])->name('employees.create.job');
Route::post('/employees/create/job', [EmployeeController::class, 'store'])->name('employees.store');
Route::resource('employees', EmployeeController::class)->except(['create', 'store']);
Route::patch('/employees/{employee}/deactivate', [EmployeeController::class, 'deactivate'])->name('employees.deactivate');

// Payroll
Route::get('/payroll', [PayrollPageController::class, 'index'])->name('payroll.index');
Route::get('/payroll/process', function () {
    return view('Payroll.process');
})->name('payroll.process');
Route::delete('/payroll/{id}/reset', [PayrollPageController::class, 'reset'])->name('payroll.reset');
Route::post('/payroll/process/{employeeId}', [PayrollPageController::class, 'storeProcess'])
    ->name('payroll.process.store');

// Schedule (UI)
Route::get('/schedule', [ScheduleController::class, 'pageIndex'])->name('schedule.index');
Route::get('/schedule/calendar', [ScheduleController::class, 'pageCalendar'])->name('schedule.calendar');
Route::get('/schedule/create', [ScheduleController::class, 'pageCreate'])->name('schedule.create');

// Schedule (API-style resource endpoints)
Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
Route::get('/schedules/{schedule}', [ScheduleController::class, 'show'])->name('schedules.show');
Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
Route::patch('/schedules/{schedule}', [ScheduleController::class, 'update']);
Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

// Leave requests
Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
Route::get('/leaves', [LeaveRequestController::class, 'index'])->name('leaves.index');
Route::post('/leaves', [LeaveRequestController::class, 'store'])->name('leaves.store');
Route::post('/leaves/{id}/status/{status}', [LeaveRequestController::class, 'updateStatus'])->name('leaves.status');

// Authenticated profile actions
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile.index');
    Route::post('/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
