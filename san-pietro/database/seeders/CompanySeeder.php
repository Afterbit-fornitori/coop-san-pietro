<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        // Crea o recupera la company principale (San Pietro)
        $sanPietro = Company::firstOrCreate(
            ['domain' => 'san-pietro.test'],
            [
                'name' => 'Cooperativa San Pietro',
                'type' => 'parent',
                'is_active' => true
            ]
        );

        // Crea il company admin per San Pietro
        $companyAdmin = User::create([
            'name' => 'Company Admin',
            'email' => 'company@san-pietro.test',
            'password' => bcrypt('password'),
            'company_id' => $sanPietro->id
        ]);
        $companyAdmin->assignRole('company-admin');

        // Crea alcuni utenti standard per San Pietro
        for ($i = 1; $i <= 3; $i++) {
            $user = User::create([
                'name' => "User $i",
                'email' => "user$i@san-pietro.test",
                'password' => bcrypt('password'),
                'company_id' => $sanPietro->id
            ]);
            $user->assignRole('user');
        }

        // Crea due company figlie
        $childCompanies = [
            [
                'name' => 'Cooperativa Rosa dei Venti',
                'domain' => 'rosa.local',
                'type' => 'child'
            ],
            [
                'name' => 'Cooperativa Mosè',
                'domain' => 'mose.local',
                'type' => 'child'
            ]
        ];

        foreach ($childCompanies as $childData) {
            $child = Company::create([
                'name' => $childData['name'],
                'domain' => $childData['domain'],
                'type' => $childData['type'],
                'parent_id' => $sanPietro->id,
                'settings' => [
                    'features' => ['members', 'ddt', 'production']
                ]
            ]);

            // Crea un admin per ogni company figlia
            $childAdmin = User::create([
                'name' => "Admin " . $childData['name'],
                'email' => "admin@" . $childData['domain'],
                'password' => bcrypt('password'),
                'company_id' => $child->id
            ]);
            $childAdmin->assignRole('company-admin');

            // Crea un user per ogni company figlia
            $user = User::create([
                'name' => "User " . $childData['name'],
                'email' => "user@" . $childData['domain'],
                'password' => bcrypt('password'),
                'company_id' => $child->id
            ]);
            $user->assignRole('user');
        }
    }
}
