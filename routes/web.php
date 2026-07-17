<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayrollPageController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/payroll', [PayrollPageController::class, 'index'])->name('payroll.index');

Route::get('/payroll/process', function () {
    return view('Payroll.process');
})->name('payroll.process');
