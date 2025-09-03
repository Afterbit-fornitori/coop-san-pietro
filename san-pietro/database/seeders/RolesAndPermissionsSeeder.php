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
        $superAdmin = Role::findOrCreate('super-admin');
        $superAdmin->syncPermissions(Permission::all());

        $companyAdmin = Role::findOrCreate('company-admin');
        $companyAdmin->syncPermissions([
            'create companies', 'edit companies', // Solo l'admin di San Pietro può gestire le aziende
            'manage company users', // Ogni admin può gestire i propri utenti
            'view ddt', 'create ddt', 'edit ddt', 'delete ddt', 'approve ddt',
            'view products', 'create products', 'edit products', 'delete products',
            'view suppliers', 'create suppliers', 'edit suppliers', 'delete suppliers',
            'view reports', 'create reports', 'export reports'
        ]);

        $user = Role::findOrCreate('user');
        $user->syncPermissions([
            'view ddt', 'create ddt',
            'view products',
            'view suppliers',
            'view reports'
        ]);

        // Crea la company principale (San Pietro) se non esiste già
        $sanPietro = \App\Models\Company::firstOrCreate(
            ['domain' => 'san-pietro.test'],
            [
                'name' => 'Cooperativa San Pietro',
                'type' => 'parent',
                'settings' => [
                    'allowed_child_companies' => 10,
                    'features' => ['members', 'ddt', 'production']
                ]
            ]
        );

        // Creazione dell'utente super-admin (Loris) se non esiste già
        $superAdminUser = \App\Models\User::updateOrCreate(
            ['email' => 'loris@example.com'],
            [
                'name' => 'Loris',
                'password' => bcrypt('password'), // Password semplificata per test
                'company_id' => $sanPietro->id
            ]
        );
        $superAdminUser->syncRoles(['super-admin']); // Assicura che abbia SOLO il ruolo super-admin

        // Creazione dell'admin di San Pietro se non esiste già
        $sanPietroAdmin = \App\Models\User::firstOrCreate(
            ['email' => 'manuel.sgarella@gmail.com'],
            [
                'name' => 'Manuel',
                'password' => bcrypt('password'),
                'company_id' => $sanPietro->id
            ]
        );
        $sanPietroAdmin->assignRole('company-admin');
    }
}
