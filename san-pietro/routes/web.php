<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Company\DashboardController as CompanyDashboardController;
use App\Http\Controllers\Company\InvitationController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransportDocumentController;
use App\Http\Controllers\TransportDocumentItemController;
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

// Route pubblica per accettare inviti (non richiede autenticazione)
Route::get('/invitations/accept/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');

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
    // AREA AMMINISTRATIVA - SUPER_ADMIN e COMPANY_ADMIN (San Pietro)
    // ==============================================
    Route::prefix('admin')->name('admin.')->middleware(['role:SUPER_ADMIN|COMPANY_ADMIN'])->group(function () {

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

        // Gestione Aziende (San Pietro può gestire aziende child)
        Route::resource('companies', CompanyController::class)->names([
            'index' => 'companies.index',
            'create' => 'companies.create',
            'store' => 'companies.store',
            'show' => 'companies.show',
            'edit' => 'companies.edit',
            'update' => 'companies.update',
            'destroy' => 'companies.destroy',
        ])->except(['destroy']); // San Pietro non può eliminare aziende, solo SUPER_ADMIN
        Route::patch('/companies/{company}/toggle-status', [CompanyController::class, 'toggleStatus'])->name('companies.toggle-status');

        // Gestione Utenti (della propria rete aziendale)
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

        // Gestione Inviti (solo COMPANY_ADMIN) - SOLO VISUALIZZAZIONE e GESTIONE
        // Gli inviti vengono creati automaticamente in Admin\CompanyController
        Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations.index');
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

        // Documenti di Trasporto (Alias per compatibilità)
        Route::resource('documents', TransportDocumentController::class)->names([
            'index' => 'documents.index',
            'create' => 'documents.create',
            'store' => 'documents.store',
            'show' => 'documents.show',
            'edit' => 'documents.edit',
            'update' => 'documents.update',
            'destroy' => 'documents.destroy',
        ]);

        // Documenti di Trasporto (Rotte originali)
        Route::resource('transport-documents', TransportDocumentController::class)->names([
            'index' => 'transport-documents.index',
            'create' => 'transport-documents.create',
            'store' => 'transport-documents.store',
            'show' => 'transport-documents.show',
            'edit' => 'transport-documents.edit',
            'update' => 'transport-documents.update',
            'destroy' => 'transport-documents.destroy',
        ]);

        // Route PDF per Documenti di Trasporto
        Route::get('/transport-documents/{transport_document}/pdf', [TransportDocumentController::class, 'viewPdf'])->name('transport-documents.pdf.view');
        Route::get('/transport-documents/{transport_document}/pdf/download', [TransportDocumentController::class, 'downloadPdf'])->name('transport-documents.pdf.download');

        // Cestino DDT
        Route::get('/transport-documents-trashed', [TransportDocumentController::class, 'trashed'])->name('transport-documents.trashed');
        Route::post('/transport-documents/{id}/restore', [TransportDocumentController::class, 'restore'])->name('transport-documents.restore');
        Route::delete('/transport-documents/{id}/force-delete', [TransportDocumentController::class, 'forceDestroy'])->name('transport-documents.force-destroy');

        // Gestione Items (Prodotti) di Documenti di Trasporto (AJAX)
        Route::post('/transport-document-items', [TransportDocumentItemController::class, 'store'])->name('transport-document-items.store');
        Route::delete('/transport-document-items/{transport_document_item}', [TransportDocumentItemController::class, 'destroy'])->name('transport-document-items.destroy');

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
        ])->parameter('production-zones', 'zone');

        // Gestione Produzioni
        Route::resource('production', \App\Http\Controllers\ProductionController::class)->names([
            'index' => 'production.index',
            'create' => 'production.create',
            'store' => 'production.store',
            'show' => 'production.show',
            'edit' => 'production.edit',
            'update' => 'production.update',
            'destroy' => 'production.destroy',
        ]);

        // Prodotti
        Route::resource('products', \App\Http\Controllers\ProductController::class)->names([
            'index' => 'products.index',
            'create' => 'products.create',
            'store' => 'products.store',
            'show' => 'products.show',
            'edit' => 'products.edit',
            'update' => 'products.update',
            'destroy' => 'products.destroy',
        ]);

        // Registro Carico/Scarico
        Route::resource('loading-unloading', \App\Http\Controllers\LoadingUnloadingRegisterController::class)->names([
            'index' => 'loading-unloading.index',
            'create' => 'loading-unloading.create',
            'store' => 'loading-unloading.store',
            'show' => 'loading-unloading.show',
            'edit' => 'loading-unloading.edit',
            'update' => 'loading-unloading.update',
            'destroy' => 'loading-unloading.destroy',
        ]);
    });
});

require __DIR__ . '/auth.php';
