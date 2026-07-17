<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
<<<<<<< HEAD
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveRequestController;

<<<<<<< HEAD
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/employees/export', [EmployeeController::class, 'export'])->name('employees.export');

// Add Employee wizard: Personal Information -> Contact Details -> Job Details
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees/create', [EmployeeController::class, 'storeStep1'])->name('employees.create.store');
Route::get('/employees/create/contact', [EmployeeController::class, 'createContact'])->name('employees.create.contact');
Route::post('/employees/create/contact', [EmployeeController::class, 'storeContact'])->name('employees.create.contact.store');
Route::get('/employees/create/job', [EmployeeController::class, 'createJob'])->name('employees.create.job');
Route::post('/employees/create/job', [EmployeeController::class, 'store'])->name('employees.store');

Route::resource('employees', EmployeeController::class)->except(['create', 'store']);
Route::patch('/employees/{employee}/deactivate', [EmployeeController::class, 'deactivate'])->name('employees.deactivate');

Route::get('/', function () {
<<<<<<< HEAD
    return redirect()->route('employees.index');
=======
Route::get('/', function () { return view('welcome'); })->name('portal');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Only authenticated admins can visit these screens
Route::middleware(['auth'])->group(function () {
    // Core Dashboard & Leaves
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/leaves', [LeaveRequestController::class, 'index'])->name('leaves.index');
    Route::post('/leaves', [LeaveRequestController::class, 'store'])->name('leaves.store');
    Route::post('/leaves/{id}/status/{status}', [LeaveRequestController::class, 'updateStatus'])->name('leaves.status');

    // New Sections & Profile Management
    Route::get('/employees', [DashboardController::class, 'employees'])->name('employees.index');
    Route::get('/schedule', [DashboardController::class, 'schedule'])->name('schedule.index');
    Route::get('/payroll', [DashboardController::class, 'payroll'])->name('payroll.index');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings.index');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile.index');
    Route::post('/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
>>>>>>> origin/feat/be-leave-chenglim
});
=======
use App\Http\Controllers\PayrollPageController;
=======
use App\Http\Controllers\ScheduleController;
>>>>>>> origin/feat/be-schedule-phanna

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/employees/export', [EmployeeController::class, 'export'])->name('employees.export');

// Add Employee wizard: Personal Information -> Contact Details -> Job Details
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees/create', [EmployeeController::class, 'storeStep1'])->name('employees.create.store');
Route::get('/employees/create/contact', [EmployeeController::class, 'createContact'])->name('employees.create.contact');
Route::post('/employees/create/contact', [EmployeeController::class, 'storeContact'])->name('employees.create.contact.store');
Route::get('/employees/create/job', [EmployeeController::class, 'createJob'])->name('employees.create.job');
Route::post('/employees/create/job', [EmployeeController::class, 'store'])->name('employees.store');

Route::resource('employees', EmployeeController::class)->except(['create', 'store']);
Route::patch('/employees/{employee}/deactivate', [EmployeeController::class, 'deactivate'])->name('employees.deactivate');

Route::get('/', function () {
<<<<<<< HEAD
    return view('welcome');
})->name('home');

Route::get('/login', function () {
    return redirect()->route('payroll.index');
})->name('login');

Route::get('/register', function () {
    return redirect()->route('payroll.index');
})->name('register');

<<<<<<< HEAD
<<<<<<< HEAD
Route::get('/payroll', [PayrollPageController::class, 'index'])->name('payroll.index');

Route::get('/payroll/process', function () {
    return view('Payroll.process');
})->name('payroll.process');
>>>>>>> origin/feat/be-payroll-phanna
=======
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::post('/schedules', [ScheduleController::class, 'store']);
Route::get('/schedules/{schedule}', [ScheduleController::class, 'show']);
Route::put('/schedules/{schedule}', [ScheduleController::class, 'update']);
Route::patch('/schedules/{schedule}', [ScheduleController::class, 'update']);
Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy']);
>>>>>>> origin/feat/be-schedule-phanna
=======
    return redirect()->route('employees.index');
});
>>>>>>> origin/feat/fe-employee-visal
=======
Route::view('/leave-requests', 'leave-requests.index')
    ->name('leave-requests.index');
>>>>>>> origin/feat/fe-leave-sokheng
=======
    return view('welcome');
})->name('home');

Route::get('/schedule', function () {
    return view('Schedule.index');
})->name('schedule.index');

Route::get('/schedule/calendar', function () {
    return view('Schedule.calendar');
})->name('schedule.calendar');

Route::get('/schedule/create', function () {
    return view('Schedule.create');
})->name('schedule.create');
>>>>>>> origin/feat/fe-schedule-viphou
