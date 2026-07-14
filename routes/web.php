<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/payroll', function () {
    return view('Payroll.index');
})->name('payroll.index');

Route::get('/payroll/process', function () {
    return view('Payroll.process');
})->name('payroll.process');
