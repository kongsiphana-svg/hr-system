<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDashboardController;
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
    if (Auth::check()) {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isEmployee()) {
            return redirect()->route('employee.dashboard');
        }

        return redirect()->route('dashboard');
    }

    return view('portal');
})->name('home');

// Auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
// Registration removed — only admin can add employees via the admin panel.
// Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
// Route::post('/register', [AuthController::class, 'register']);
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

/*
|--------------------------------------------------------------------------
| Admin Routes (requires role: admin) — same route names as before for BC
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('/admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('admin.settings.index');

    // Employees
    Route::get('/employees/export', [EmployeeController::class, 'export'])->name('admin.employees.export');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('admin.employees.create');
    Route::post('/employees/create', [EmployeeController::class, 'storeStep1'])->name('admin.employees.create.store');
    Route::get('/employees/create/contact', [EmployeeController::class, 'createContact'])->name('admin.employees.create.contact');
    Route::post('/employees/create/contact', [EmployeeController::class, 'storeContact'])->name('admin.employees.create.contact.store');
    Route::get('/employees/create/job', [EmployeeController::class, 'createJob'])->name('admin.employees.create.job');
    Route::post('/employees/create/job', [EmployeeController::class, 'store'])->name('admin.employees.store');
    Route::resource('employees', EmployeeController::class)->except(['create', 'store'])->names([
        'index' => 'admin.employees.index',
        'show' => 'admin.employees.show',
        'edit' => 'admin.employees.edit',
        'update' => 'admin.employees.update',
        'destroy' => 'admin.employees.destroy',
    ]);
    Route::patch('/employees/{employee}/deactivate', [EmployeeController::class, 'deactivate'])->name('admin.employees.deactivate');

    // Employee account creation
    Route::get('/employees/{employee}/create-account', [EmployeeController::class, 'createAccountForm'])->name('admin.employees.create-account');
    Route::post('/employees/{employee}/create-account', [EmployeeController::class, 'createAccount']);

    // Payroll
    Route::get('/payroll', [PayrollPageController::class, 'index'])->name('admin.payroll.index');
    Route::get('/payroll/export', [PayrollPageController::class, 'export'])->name('admin.payroll.export');
    Route::get('/payroll/process', [PayrollPageController::class, 'process'])->name('admin.payroll.process');

    // Schedule
    Route::get('/schedule', [ScheduleController::class, 'pageIndex'])->name('admin.schedule.index');
    Route::get('/schedule/calendar', [ScheduleController::class, 'pageCalendar'])->name('admin.schedule.calendar');
    Route::get('/schedule/create', [ScheduleController::class, 'pageCreate'])->name('admin.schedule.create');

    // Leave requests
    Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('admin.leave-requests.index');
    Route::get('/leaves', [LeaveRequestController::class, 'index'])->name('admin.leaves.index');
    Route::post('/leaves', [LeaveRequestController::class, 'store'])->name('admin.leaves.store');
    Route::post('/leaves/{id}/status/{status}', [LeaveRequestController::class, 'updateStatus'])->name('admin.leaves.status');

    // Schedule (API-style resource endpoints)
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('admin.schedules.index');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('admin.schedules.store');
    Route::get('/schedules/{schedule}', [ScheduleController::class, 'show'])->name('admin.schedules.show');
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('admin.schedules.update');
    Route::patch('/schedules/{schedule}', [ScheduleController::class, 'update']);
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('admin.schedules.destroy');
});

/*
|--------------------------------------------------------------------------
| Employee Routes (requires role: employee)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:employee'])->prefix('/employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/payroll', [EmployeeDashboardController::class, 'payroll'])->name('payroll');
    Route::get('/payroll/{payroll}', [EmployeeDashboardController::class, 'payrollShow'])->name('payroll.show');
    Route::get('/leaves', [EmployeeDashboardController::class, 'leaves'])->name('leaves');
    Route::get('/leaves/create', [EmployeeDashboardController::class, 'leavesCreate'])->name('leaves.create');
    Route::post('/leaves/store', [EmployeeDashboardController::class, 'leavesStore'])->name('leaves.store');
    Route::get('/settings', [EmployeeDashboardController::class, 'settings'])->name('settings');
    Route::post('/settings/profile', [EmployeeDashboardController::class, 'updateProfile'])->name('settings.profile');
    Route::get('/payroll/{payroll}/download', [EmployeeDashboardController::class, 'payrollDownload'])->name('payroll.download');
    Route::get('/schedule', [EmployeeDashboardController::class, 'schedule'])->name('schedule');
});

/*
|--------------------------------------------------------------------------
| Backward-compatible redirects (so old URLs & bookmarks still work)
|--------------------------------------------------------------------------
*/
Route::redirect('/dashboard', '/admin/dashboard')->name('admin.dashboard.redirect');
Route::redirect('/employees', '/admin/employees')->name('admin.employees.index.redirect');
Route::redirect('/payroll', '/admin/payroll')->name('admin.payroll.index.redirect');
Route::redirect('/schedule', '/admin/schedule')->name('admin.schedule.index.redirect');
Route::redirect('/leave-requests', '/admin/leave-requests')->name('admin.leave-requests.redirect');

/*
|--------------------------------------------------------------------------
| Shared Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile.index');
    Route::post('/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

