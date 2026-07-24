<?php

use App\Http\Controllers\Api\PayrollController;
use Illuminate\Support\Facades\Route;

Route::prefix('payroll')->name('api.payroll.')->group(function () {
    Route::get('/', [PayrollController::class, 'index'])->name('index');
    Route::post('/process', [PayrollController::class, 'process'])->name('process');
    Route::post('/generate', [PayrollController::class, 'process'])->name('generate');
    Route::post('/{payroll}/review', [PayrollController::class, 'submitReview'])->name('review');
    Route::post('/{payroll}/approve', [PayrollController::class, 'approve'])->name('approve');
    Route::post('/{payroll}/mark-paid', [PayrollController::class, 'markPaid'])->name('mark-paid');
    Route::patch('/{payroll}/status', [PayrollController::class, 'updateStatus'])->name('status');
    Route::get('/{payroll}/payslip', [PayrollController::class, 'payslip'])->name('payslip');
    Route::get('/{payroll}', [PayrollController::class, 'show'])->name('show');
});
