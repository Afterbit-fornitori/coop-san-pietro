<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Company::class => \App\Policies\CompanyPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Member::class => \App\Policies\MemberPolicy::class,
        \App\Models\WeeklyRecord::class => \App\Policies\WeeklyRecordPolicy::class,
        \App\Models\Client::class => \App\Policies\ClientPolicy::class,
        \App\Models\TransportDocument::class => \App\Policies\TransportDocumentPolicy::class,
        \App\Models\ProductionZone::class => \App\Policies\ProductionZonePolicy::class,
        \App\Models\Product::class => \App\Policies\ProductPolicy::class,
        \App\Models\Production::class => \App\Policies\ProductionPolicy::class,
        \App\Models\LoadingUnloadingRegister::class => \App\Policies\LoadingUnloadingRegisterPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
