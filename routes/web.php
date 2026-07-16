<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveRequestController;

Route::get('/', function () { return view('welcome'); })->name('portal');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

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
});