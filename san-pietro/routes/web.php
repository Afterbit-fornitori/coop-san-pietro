<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes per area amministrativa

    Route::prefix('admin')->name('admin.')->middleware(['check.company.access'])->group(function () {
        // Dashboard route
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Companies routes
        Route::get('/companies', [App\Http\Controllers\Admin\CompanyController::class, 'index'])->name('companies.index');
        Route::get('/companies/create', [App\Http\Controllers\Admin\CompanyController::class, 'create'])->name('companies.create');
        Route::post('/companies', [App\Http\Controllers\Admin\CompanyController::class, 'store'])->name('companies.store');
        Route::get('/companies/{company}', [App\Http\Controllers\Admin\CompanyController::class, 'show'])->name('companies.show');
        Route::get('/companies/{company}/edit', [App\Http\Controllers\Admin\CompanyController::class, 'edit'])->name('companies.edit');
        Route::put('/companies/{company}', [App\Http\Controllers\Admin\CompanyController::class, 'update'])->name('companies.update');
        Route::delete('/companies/{company}', [App\Http\Controllers\Admin\CompanyController::class, 'destroy'])->name('companies.destroy');
        Route::patch('/companies/{company}/toggle-status', [App\Http\Controllers\Admin\CompanyController::class, 'toggleStatus'])->name('companies.toggle-status');

        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    // Routes per Company Admin e User
    Route::middleware(['tenant'])->group(function () {
        Route::get('/members', [App\Http\Controllers\Company\MemberController::class, 'index'])->name('members.index');
        Route::get('/members/create', [App\Http\Controllers\Company\MemberController::class, 'create'])->name('members.create');
        Route::post('/members', [App\Http\Controllers\Company\MemberController::class, 'store'])->name('members.store');
        Route::get('/members/{member}/edit', [App\Http\Controllers\Company\MemberController::class, 'edit'])->name('members.edit');
        Route::put('/members/{member}', [App\Http\Controllers\Company\MemberController::class, 'update'])->name('members.update');
        Route::delete('/members/{member}', [App\Http\Controllers\Company\MemberController::class, 'destroy'])->name('members.destroy');

        Route::get('/production', [App\Http\Controllers\Company\ProductionController::class, 'index'])->name('production.index');
        Route::get('/production/create', [App\Http\Controllers\Company\ProductionController::class, 'create'])->name('production.create');
        Route::post('/production', [App\Http\Controllers\Company\ProductionController::class, 'store'])->name('production.store');
        Route::get('/production/{production}/edit', [App\Http\Controllers\Company\ProductionController::class, 'edit'])->name('production.edit');
        Route::put('/production/{production}', [App\Http\Controllers\Company\ProductionController::class, 'update'])->name('production.update');

        Route::get('/documents', [App\Http\Controllers\Company\DocumentController::class, 'index'])->name('documents.index');
        Route::get('/documents/create', [App\Http\Controllers\Company\DocumentController::class, 'create'])->name('documents.create');
        Route::post('/documents', [App\Http\Controllers\Company\DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{document}/edit', [App\Http\Controllers\Company\DocumentController::class, 'edit'])->name('documents.edit');
        Route::put('/documents/{document}', [App\Http\Controllers\Company\DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/documents/{document}', [App\Http\Controllers\Company\DocumentController::class, 'destroy'])->name('documents.destroy');

        Route::get('/invitations', [App\Http\Controllers\Company\InvitationController::class, 'index'])->name('invitations.index');
        Route::get('/invitations/create', [App\Http\Controllers\Company\InvitationController::class, 'create'])->name('invitations.create');
        Route::post('/invitations', [App\Http\Controllers\Company\InvitationController::class, 'store'])->name('invitations.store');
        Route::get('/invitations/{invitation}', [App\Http\Controllers\Company\InvitationController::class, 'show'])->name('invitations.show');
        Route::post('/invitations/{invitation}/resend', [App\Http\Controllers\Company\InvitationController::class, 'resend'])->name('invitations.resend');
        Route::delete('/invitations/{invitation}', [App\Http\Controllers\Company\InvitationController::class, 'destroy'])->name('invitations.destroy');

        // Weekly Records routes
        Route::get('/weekly-records', [App\Http\Controllers\Company\WeeklyRecordController::class, 'index'])->name('weekly-records.index');
        Route::get('/weekly-records/create', [App\Http\Controllers\Company\WeeklyRecordController::class, 'create'])->name('weekly-records.create');
        Route::post('/weekly-records', [App\Http\Controllers\Company\WeeklyRecordController::class, 'store'])->name('weekly-records.store');
        Route::get('/weekly-records/{weeklyRecord}', [App\Http\Controllers\Company\WeeklyRecordController::class, 'show'])->name('weekly-records.show');
        Route::get('/weekly-records/{weeklyRecord}/edit', [App\Http\Controllers\Company\WeeklyRecordController::class, 'edit'])->name('weekly-records.edit');
        Route::put('/weekly-records/{weeklyRecord}', [App\Http\Controllers\Company\WeeklyRecordController::class, 'update'])->name('weekly-records.update');
        Route::delete('/weekly-records/{weeklyRecord}', [App\Http\Controllers\Company\WeeklyRecordController::class, 'destroy'])->name('weekly-records.destroy');

        // Clients routes
        Route::get('/clients', [App\Http\Controllers\Company\ClientController::class, 'index'])->name('clients.index');
        Route::get('/clients/create', [App\Http\Controllers\Company\ClientController::class, 'create'])->name('clients.create');
        Route::post('/clients', [App\Http\Controllers\Company\ClientController::class, 'store'])->name('clients.store');
        Route::get('/clients/{client}', [App\Http\Controllers\Company\ClientController::class, 'show'])->name('clients.show');
        Route::get('/clients/{client}/edit', [App\Http\Controllers\Company\ClientController::class, 'edit'])->name('clients.edit');
        Route::put('/clients/{client}', [App\Http\Controllers\Company\ClientController::class, 'update'])->name('clients.update');
        Route::delete('/clients/{client}', [App\Http\Controllers\Company\ClientController::class, 'destroy'])->name('clients.destroy');
    });
});

require __DIR__ . '/auth.php';
