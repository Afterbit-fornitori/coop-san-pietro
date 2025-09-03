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
    }

    public function boot()
    {
        //
    }
}
