<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Esegue il seeder per le company.
     *
     * Nota: Questo seeder si aspetta che RolesAndPermissionsSeeder
     * sia già stato eseguito e abbia creato la company principale
     * e i ruoli necessari.
     */
    public function run(): void
    {
        // 1. Recupera la company principale (creata nel RolesAndPermissionsSeeder)
        // Usiamo firstOrFail() per bloccare l'esecuzione se non la trova.
        $sanPietro = Company::where('name', 'Cooperativa San Pietro')->firstOrFail();

        // 2. Crea alcuni utenti standard per la company principale
        // Usiamo firstOrCreate() per rendere il seeder eseguibile più volte senza errori.
        for ($i = 1; $i <= 3; $i++) {
            $user = User::firstOrCreate(
                ['email' => "user{$i}@san-pietro.test"],
                [
                    'name'              => "User {$i} San Pietro",
                    'password'          => bcrypt('password'),
                    'company_id'        => $sanPietro->id,
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole('COMPANY_USER');
        }

        // 3. Definisce i dati per le company figlie
        $childCompaniesData = [
            ['name' => 'Cooperativa Rosa dei Venti'],
            ['name' => 'Cooperativa Mosè']
        ];

        // 4. Crea ogni company figlia e i suoi utenti
        foreach ($childCompaniesData as $childData) {
            $childCompany = Company::firstOrCreate(
                ['name' => $childData['name']],
                [
                    'type'              => 'invited', // Valore corretto per l'enum
                    'parent_company_id' => $sanPietro->id, // Nome della colonna corretto
                    'is_active'         => true,
                    'settings'          => ['features' => ['members', 'ddt', 'production']]
                ]
            );

            // Genera un indirizzo email univoco dal nome della company
            $emailDomain = strtolower(str_replace(['Cooperativa ', ' '], '', $childData['name'])) . ".test";

            // Crea un admin per la company figlia
            $childAdmin = User::firstOrCreate(
                ['email' => "admin@" . $emailDomain],
                [
                    'name'              => "Admin " . $childData['name'],
                    'password'          => bcrypt('password'),
                    'company_id'        => $childCompany->id,
                    'email_verified_at' => now(),
                ]
            );
            $childAdmin->assignRole('COMPANY_ADMIN');

            // Crea un utente standard per la company figlia
            $childUser = User::firstOrCreate(
                ['email' => "user@" . $emailDomain],
                [
                    'name'              => "User " . $childData['name'],
                    'password'          => bcrypt('password'),
                    'company_id'        => $childCompany->id,
                    'email_verified_at' => now(),
                ]
            );
            $childUser->assignRole('COMPANY_USER');
        }
    }
}
