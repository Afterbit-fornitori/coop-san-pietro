<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Multitenancy\Models\Tenant;
use App\Models\Company;

class MultitenancyServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind Company model come implementazione di Tenant
        $this->app->bind(Tenant::class, Company::class);

        // Registrazione del currentTenant per il sistema multi-tenant
        $this->app->singleton('currentTenant', function ($app) {
            try {
                if (auth()->check()) {
                    $user = auth()->user();

                    // SUPER_ADMIN non ha tenant specifico
                    if ($user->hasRole('SUPER_ADMIN')) {
                        return null;
                    }

                    return $user->company;
                }
            } catch (\Exception $e) {
                // Log l'errore ma non interrompere l'applicazione
                \Log::warning('Error resolving currentTenant: ' . $e->getMessage());
            }

            return null;
        });
    }

    public function boot()
    {
        //
    }
}
