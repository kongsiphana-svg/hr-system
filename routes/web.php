<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
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
