<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', function () {
    return redirect()->route('payroll.index');
})->name('login');

Route::get('/register', function () {
    return redirect()->route('payroll.index');
})->name('register');

Route::get('/payroll', function () {
    return view('Payroll.index');
})->name('payroll.index');

Route::get('/payroll/process', function () {
    return view('Payroll.process');
})->name('payroll.process');
