<?php

namespace App\Multitenancy;

use App\Models\Company;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder;
use Illuminate\Http\Request;

class DomainTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?Tenant
    {
        $host = $request->getHost();

        // Cerca prima il dominio esatto
        $tenant = Company::query()
            ->where('domain', $host)
            ->first();

        if ($tenant) {
            return $tenant;
        }

        // Se non trova un match esatto, cerca senza subdomain
        return Company::query()
            ->where('domain', $this->getMainDomain($host))
            ->first();
    }

    protected function getMainDomain(string $host): string
    {
        $parts = explode('.', $host);
        
        // If we have a subdomain (e.g., sub.example.com)
        if (count($parts) > 2) {
            // Remove the first part (subdomain) and join the rest
            array_shift($parts);
            return implode('.', $parts);
        }

        return $host;
    }
}
