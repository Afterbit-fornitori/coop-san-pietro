<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:SUPER_ADMIN'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Companies Management
    Route::resource('admin/companies', CompanyController::class)->names([
        'index' => 'admin.companies.index',
        'create' => 'admin.companies.create',
        'store' => 'admin.companies.store',
        'show' => 'admin.companies.show',
        'edit' => 'admin.companies.edit',
        'update' => 'admin.companies.update',
        'destroy' => 'admin.companies.destroy',
    ]);

    // Users Management
    Route::resource('admin/users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
});
