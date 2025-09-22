<?php

use App\Http\Controllers\Company\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:COMPANY_ADMIN'])->group(function () {
    Route::get('/company', [DashboardController::class, 'index'])->name('company.dashboard');
});
