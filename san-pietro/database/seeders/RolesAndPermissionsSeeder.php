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

        // Lista di tutti i permessi necessari basata sulla struttura Excel
        $permissions = [
            // Gestione Aziende (solo SUPER_ADMIN e COMPANY_ADMIN di San Pietro)
            'view companies',
            'create companies',
            'edit companies',
            'delete companies',
            'invite companies',

            // Gestione Utenti
            'view users',
            'create users',
            'edit users',
            'delete users',
            'assign roles',
            'toggle user status',

            // Gestione Soci (Members - da Excel SOCI)
            'view members',
            'create members',
            'edit members',
            'delete members',

            // Record Settimanali (da Excel PER FARE LA FATTURA)
            'view weekly records',
            'create weekly records',
            'edit weekly records',
            'delete weekly records',

            // Clienti (da Excel DATI CLIENTI)
            'view clients',
            'create clients',
            'edit clients',
            'delete clients',

            // Documenti di Trasporto DDT/DTN/DDR (da Excel COOPERATIVA)
            'view transport documents',
            'create transport documents',
            'edit transport documents',
            'delete transport documents',
            'print transport documents',

            // Zone di Produzione (da Excel AREE-MQ)
            'view production zones',
            'create production zones',
            'edit production zones',
            'delete production zones',

            // Registro Carico/Scarico (da Excel CARICO SCARICO)
            'view loading unloading',
            'create loading unloading',
            'edit loading unloading',
            'delete loading unloading',

            // Prodotti
            'view products',
            'create products',
            'edit products',
            'delete products',

            // Report e Analytics
            'view reports',
            'export reports',
            'view analytics',

            // Impostazioni Sistema
            'manage settings',
            'manage roles',
            'manage permissions',
        ];

        // Crea i permessi se non esistono già
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // SUPER_ADMIN: Accesso completo alla piattaforma
        $superAdmin = Role::findOrCreate('SUPER_ADMIN');
        $superAdmin->syncPermissions(Permission::all());

        // COMPANY_ADMIN: San Pietro (principale) - può invitare altre aziende e vedere i loro dati
        $companyAdmin = Role::findOrCreate('COMPANY_ADMIN');
        $companyAdmin->syncPermissions([
            // Gestione aziende (solo San Pietro può invitare)
            'view companies',
            'create companies',
            'edit companies',
            'invite companies',

            // Gestione utenti della propria azienda e aziende invitate
            'view users',
            'create users',
            'edit users',
            'delete users',
            'assign roles',

            // Tutte le funzionalità operative
            'view members',
            'create members',
            'edit members',
            'delete members',

            'view weekly records',
            'create weekly records',
            'edit weekly records',
            'delete weekly records',

            'view clients',
            'create clients',
            'edit clients',
            'delete clients',

            'view transport documents',
            'create transport documents',
            'edit transport documents',
            'delete transport documents',
            'print transport documents',

            'view production zones',
            'create production zones',
            'edit production zones',
            'delete production zones',

            'view loading unloading',
            'create loading unloading',
            'edit loading unloading',
            'delete loading unloading',

            'view products',
            'create products',
            'edit products',
            'delete products',

            'view reports',
            'export reports',
            'view analytics',
        ]);

        // COMPANY_USER: Aziende invitate (Rosa, Mosè e B.) - accesso isolato ai propri dati
        $companyUser = Role::findOrCreate('COMPANY_USER');
        $companyUser->syncPermissions([
            // Solo visualizzazione e gestione dei propri dati operativi
            'view members',
            'create members',
            'edit members',

            'view weekly records',
            'create weekly records',
            'edit weekly records',

            'view clients',
            'create clients',
            'edit clients',

            'view transport documents',
            'create transport documents',
            'edit transport documents',
            'print transport documents',

            'view production zones',
            'create production zones',
            'edit production zones',

            'view loading unloading',
            'create loading unloading',
            'edit loading unloading',

            'view products',
            'create products',
            'edit products',

            'view reports',
            'export reports',
        ]);

        // Crea la company principale (San Pietro) se non esiste già
        $sanPietro = \App\Models\Company::firstOrCreate(
            ['name' => 'Cooperativa San Pietro'],
            [
                'type' => 'main',
                'parent_company_id' => null,
                'vat_number' => '12345678901',
                'tax_code' => 'CSPXXXXXXX01',
                'address' => 'Via delle Vongole 1',
                'city' => 'Comacchio',
                'province' => 'FE',
                'postal_code' => '44022',
                'phone' => '0533123456',
                'email' => 'info@sanpietro.it',
                'pec' => 'sanpietro@pec.it',
                'is_active' => true,
                'impostazioni' => [
                    'allowed_child_companies' => 10,
                    'features' => ['members', 'ddt', 'production']
                ]
            ]
        );

        // Creazione dell'utente super-admin se non esiste già (manteniamo campi users in inglese)
        $superAdminUser = \App\Models\User::updateOrCreate(
            ['email' => 'super@admin.com'],
            [
                'name' => 'Super Admin', // manteniamo name per users
                'password' => bcrypt('password'),
                'company_id' => null, // SUPER_ADMIN non appartiene a nessuna company specifica
                'email_verified_at' => now() // manteniamo per users
            ]
        );
        $superAdminUser->syncRoles(roles: ['SUPER_ADMIN']);

        // Creazione dell'admin di San Pietro se non esiste già
        $sanPietroAdmin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@sanpietro.com'],
            [
                'name' => 'Admin San Pietro', // manteniamo name per users
                'password' => bcrypt('password'),
                'company_id' => $sanPietro->id,
                'email_verified_at' => now() // manteniamo per users
            ]
        );
        $sanPietroAdmin->syncRoles(['COMPANY_ADMIN']);
    }
}
