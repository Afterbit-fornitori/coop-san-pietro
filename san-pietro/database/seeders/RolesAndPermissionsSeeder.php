<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Lista di tutti i permessi necessari
        $permissions = [
            // Permessi per le companies
            'view companies',
            'create companies',
            'edit companies',
            'delete companies',
            'manage company users',

            // Permessi per i DDT
            'view ddt',
            'create ddt',
            'edit ddt',
            'delete ddt',
            'approve ddt',

            // Permessi per i prodotti
            'view products',
            'create products',
            'edit products',
            'delete products',

            // Permessi per i fornitori
            'view suppliers',
            'create suppliers',
            'edit suppliers',
            'delete suppliers',

            // Permessi per i report
            'view reports',
            'create reports',
            'export reports'
        ];

        // Crea i permessi se non esistono già
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Creazione ruoli e assegnazione permessi se non esistono già
        $superAdmin = Role::findOrCreate('SUPER_ADMIN');
        $superAdmin->syncPermissions(Permission::all());

        $companyAdmin = Role::findOrCreate('COMPANY_ADMIN');
        $companyAdmin->syncPermissions([
            'create companies',
            'edit companies', // Solo l'admin di San Pietro può gestire le aziende
            'manage company users', // Ogni admin può gestire i propri utenti
            'view ddt',
            'create ddt',
            'edit ddt',
            'delete ddt',
            'approve ddt',
            'view products',
            'create products',
            'edit products',
            'delete products',
            'view suppliers',
            'create suppliers',
            'edit suppliers',
            'delete suppliers',
            'view reports',
            'create reports',
            'export reports'
        ]);

        $companyUser = Role::findOrCreate('COMPANY_USER');
        $companyUser->syncPermissions([
            'view ddt',
            'create ddt',
            'view products',
            'view suppliers',
            'view reports'
        ]);

        // Crea la company principale (San Pietro) se non esiste già
        $sanPietro = \App\Models\Company::firstOrCreate(
            ['name' => 'Cooperativa San Pietro'],
            [
                'type' => 'parent',
                'parent_company_id' => null,
                'vat_number' => '12345678901',
                'tax_code' => 'CSPXXXXXXX01',
                'address' => 'Via delle Vongole 1',
                'city' => 'Comacchio',
                'province' => 'FE',
                'zip_code' => '44022',
                'is_active' => true,
                'settings' => [
                    'allowed_child_companies' => 10,
                    'features' => ['members', 'ddt', 'production']
                ]
            ]
        );

        // Creazione dell'utente super-admin se non esiste già
        $superAdminUser = \App\Models\User::updateOrCreate(
            ['email' => 'super@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'), // Password semplificata per test
                'company_id' => null, // SUPER_ADMIN non appartiene a nessuna company specifica
                'is_active' => true,
                'email_verified_at' => now()
            ]
        );
        $superAdminUser->syncRoles(['SUPER_ADMIN']); // Assicura che abbia SOLO il ruolo super-admin

        // Creazione dell'admin di San Pietro se non esiste già
        $sanPietroAdmin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@sanpietro.com'],
            [
                'name' => 'Admin San Pietro',
                'password' => bcrypt('password'),
                'company_id' => $sanPietro->id,
                'is_active' => true,
                'email_verified_at' => now()
            ]
        );
        $sanPietroAdmin->syncRoles(['COMPANY_ADMIN']);
    }
}
