<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Company\DashboardController as CompanyDashboardController;
use App\Http\Controllers\Company\InvitationController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransportDocumentController;
use App\Http\Controllers\WeeklyRecordController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductionZoneController;
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

// Dashboard principale - reindirizza basato sul ruolo
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    // ==============================================
    // PROFILO UTENTE
    // ==============================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==============================================
    // AREA AMMINISTRATIVA - SOLO SUPER_ADMIN
    // ==============================================
    Route::prefix('admin')->name('admin.')->middleware(['role:SUPER_ADMIN'])->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Gestione Aziende (Companies)
        Route::resource('companies', CompanyController::class)->names([
            'index' => 'companies.index',
            'create' => 'companies.create',
            'store' => 'companies.store',
            'show' => 'companies.show',
            'edit' => 'companies.edit',
            'update' => 'companies.update',
            'destroy' => 'companies.destroy',
        ]);
        Route::patch('/companies/{company}/toggle-status', [CompanyController::class, 'toggleStatus'])->name('companies.toggle-status');

        // Gestione Utenti
        Route::resource('users', UserController::class)->names([
            'index' => 'users.index',
            'create' => 'users.create',
            'store' => 'users.store',
            'show' => 'users.show',
            'edit' => 'users.edit',
            'update' => 'users.update',
            'destroy' => 'users.destroy',
        ]);
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    // ==============================================
    // AREA AZIENDA - COMPANY_ADMIN
    // ==============================================
    Route::prefix('company')->name('company.')->middleware(['role:COMPANY_ADMIN', 'tenant', 'multitenancy.group'])->group(function () {

        // Dashboard Azienda
        Route::get('/dashboard', [CompanyDashboardController::class, 'index'])->name('dashboard');

        // Gestione Inviti (solo COMPANY_ADMIN)
        Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations.index');
        Route::get('/invitations/create', [InvitationController::class, 'create'])->name('invitations.create');
        Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');
        Route::get('/invitations/{invitation}', [InvitationController::class, 'show'])->name('invitations.show');
        Route::post('/invitations/{invitation}/resend', [InvitationController::class, 'resend'])->name('invitations.resend');
        Route::delete('/invitations/{invitation}', [InvitationController::class, 'destroy'])->name('invitations.destroy');
    });

    // ==============================================
    // AREA OPERATIVA - COMPANY_ADMIN | COMPANY_USER
    // ==============================================
    Route::middleware(['role:COMPANY_ADMIN|COMPANY_USER', 'tenant', 'multitenancy.group'])->group(function () {

        // Gestione Soci (Members)
        Route::resource('members', MemberController::class)->names([
            'index' => 'members.index',
            'create' => 'members.create',
            'store' => 'members.store',
            'show' => 'members.show',
            'edit' => 'members.edit',
            'update' => 'members.update',
            'destroy' => 'members.destroy',
        ]);


        // Record Settimanali (Weekly Records)
        Route::resource('weekly-records', WeeklyRecordController::class)->names([
            'index' => 'weekly-records.index',
            'create' => 'weekly-records.create',
            'store' => 'weekly-records.store',
            'show' => 'weekly-records.show',
            'edit' => 'weekly-records.edit',
            'update' => 'weekly-records.update',
            'destroy' => 'weekly-records.destroy',
        ]);

        // Documenti di Trasporto
        Route::resource('transport-documents', TransportDocumentController::class)->names([
            'index' => 'transport-documents.index',
            'create' => 'transport-documents.create',
            'store' => 'transport-documents.store',
            'show' => 'transport-documents.show',
            'edit' => 'transport-documents.edit',
            'update' => 'transport-documents.update',
            'destroy' => 'transport-documents.destroy',
        ]);

        // Clienti
        Route::resource('clients', ClientController::class)->names([
            'index' => 'clients.index',
            'create' => 'clients.create',
            'store' => 'clients.store',
            'show' => 'clients.show',
            'edit' => 'clients.edit',
            'update' => 'clients.update',
            'destroy' => 'clients.destroy',
        ]);

        // Zone di Produzione
        Route::resource('production-zones', ProductionZoneController::class)->names([
            'index' => 'production-zones.index',
            'create' => 'production-zones.create',
            'store' => 'production-zones.store',
            'show' => 'production-zones.show',
            'edit' => 'production-zones.edit',
            'update' => 'production-zones.update',
            'destroy' => 'production-zones.destroy',
        ]);
    });
});

require __DIR__ . '/auth.php';
