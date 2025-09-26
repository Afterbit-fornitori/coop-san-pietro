<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Member;
use App\Models\Product;
use App\Models\ProductionZone;
use App\Models\Production;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cerca San Pietro o la crea
        $sanPietro = Company::where('name', 'Cooperativa San Pietro')->first();
        if (!$sanPietro) {
            $sanPietro = Company::factory()->create([
                'name' => 'Cooperativa San Pietro',
                'type' => 'main',
                'domain' => 'sanpietro.test',
                'vat_number' => '12345678901',
                'tax_code' => 'CSPXXXXXXX01',
                'address' => 'Via delle Vongole 1',
                'city' => 'Comacchio',
                'province' => 'FE',
                'postal_code' => '44022',
                'is_active' => true,
            ]);
        }

        // Crea aziende figlie se non esistono
        $childCompanies = [];
        $childNames = ['Cooperativa Rosa', 'Cooperativa Mosè e B.'];

        foreach ($childNames as $name) {
            $company = Company::where('name', $name)->first();
            if (!$company) {
                $company = Company::factory()->create([
                    'name' => $name,
                    'type' => 'invited',
                    'parent_company_id' => $sanPietro->id,
                    'domain' => strtolower(str_replace(' ', '', str_replace('Cooperativa ', '', $name))) . '.test',
                    'is_active' => true,
                ]);
            }
            $childCompanies[] = $company;
        }

        $allCompanies = collect([$sanPietro])->concat($childCompanies);

        $this->command->info("Creando dati di produzione per " . $allCompanies->count() . " aziende...");

        foreach ($allCompanies as $company) {
            $this->command->info("- Processando {$company->name}...");

            // Crea zone di produzione per ogni azienda
            if ($company->name === 'Cooperativa San Pietro') {
                // Zone specifiche per San Pietro - create manualmente per evitare duplicati
                $sanPietroZones = [
                    ['codice' => '006FE156-LI-FE6-81M/182807/2016', 'nome' => 'Sacca di Scardovari Nord', 'mq' => 250000],
                    ['codice' => '006FE157-LI-FE6-82M/182808/2016', 'nome' => 'Sacca di Scardovari Sud', 'mq' => 180000],
                    ['codice' => '006FE158-LI-FE6-83M/182809/2016', 'nome' => 'Sacca di Scardovari Est', 'mq' => 320000],
                    ['codice' => '006FE159-LI-FE6-84M/182810/2016', 'nome' => 'Porto Garibaldi Marina', 'mq' => 150000],
                ];

                $productionZones = collect();
                foreach ($sanPietroZones as $zoneData) {
                    $zone = ProductionZone::create([
                        'company_id' => $company->id,
                        'codice' => $zoneData['codice'],
                        'nome' => $zoneData['nome'],
                        'mq' => $zoneData['mq'],
                        'classe_sanitaria' => 'A',
                        'declassificazione_temporanea' => false,
                        'data_declassificazione' => null,
                        'is_active' => true,
                    ]);
                    $productionZones->push($zone);
                }
            } else {
                // Zone generiche per altre aziende (2-3 zone)
                $zoneCount = rand(2, 3);
                $productionZones = ProductionZone::factory()
                    ->count($zoneCount)
                    ->state(['company_id' => $company->id])
                    ->create();
            }

            // Crea membri per ogni azienda (5-15 membri)
            $memberCount = $company->name === 'Cooperativa San Pietro' ? 15 : rand(5, 10);

            $members = Member::factory()
                ->count($memberCount)
                ->state(['company_id' => $company->id])
                ->active()
                ->create();

            // Crea prodotti per ogni azienda
            $products = collect();

            // Vongole (prodotto principale)
            $vongoleProducts = Product::factory()
                ->count(8)
                ->vongole()
                ->state(['company_id' => $company->id])
                ->active()
                ->create();

            // Cozze
            $cozzeProducts = Product::factory()
                ->count(4)
                ->cozze()
                ->state(['company_id' => $company->id])
                ->active()
                ->create();

            // Ostriche (se San Pietro)
            $ostricheProducts = collect();
            if ($company->name === 'Cooperativa San Pietro') {
                $ostricheProducts = Product::factory()
                    ->count(3)
                    ->ostriche()
                    ->state(['company_id' => $company->id])
                    ->active()
                    ->create();
            }

            $products = $vongoleProducts->concat($cozzeProducts)->concat($ostricheProducts);

            // Crea produzioni per gli ultimi 6 mesi
            $productionCount = $company->name === 'Cooperativa San Pietro' ? 200 : rand(50, 120);

            $this->command->info("  - Creando {$productionCount} record di produzione...");

            // Produzioni per reimmersione interna (30%)
            Production::factory()
                ->count((int)($productionCount * 0.3))
                ->internalReimmersion()
                ->available()
                ->create([
                    'company_id' => $company->id,
                    'production_zone_id' => $productionZones->random()->id,
                    'member_id' => $members->random()->id,
                    'product_id' => $products->random()->id,
                ]);

            // Produzioni per reimmersione rivendita (25%)
            Production::factory()
                ->count((int)($productionCount * 0.25))
                ->resaleReimmersion()
                ->available()
                ->create([
                    'company_id' => $company->id,
                    'production_zone_id' => $productionZones->random()->id,
                    'member_id' => $members->random()->id,
                    'product_id' => $products->random()->id,
                ]);

            // Produzioni per consumo (45%)
            Production::factory()
                ->count((int)($productionCount * 0.45))
                ->consumption()
                ->available()
                ->create([
                    'company_id' => $company->id,
                    'production_zone_id' => $productionZones->random()->id,
                    'member_id' => $members->random()->id,
                    'product_id' => $products->random()->id,
                ]);

            $this->command->info("  ✓ {$company->name}: {$productionZones->count()} zone, {$members->count()} membri, {$products->count()} prodotti, {$productionCount} produzioni");
        }

        $this->command->info("✅ Seeder ProductionSeeder completato!");
    }
}