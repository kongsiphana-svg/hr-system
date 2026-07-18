<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

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
    return redirect()->route('employees.index');
});