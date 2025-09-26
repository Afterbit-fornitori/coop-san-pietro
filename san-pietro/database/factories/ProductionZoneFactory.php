<?php

namespace Database\Factories;

use App\Models\ProductionZone;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionZoneFactory extends Factory
{
    protected $model = ProductionZone::class;

    public function definition(): array
    {
        // Classi sanitarie (A = migliore, C = peggiore)
        $sanitaryClasses = ['A', 'B', 'C'];

        // Genera codice ministeriale realistico
        $ministerialCode = $this->generateMinisterialCode();

        // Nome zona di produzione
        $zoneName = $this->faker->randomElement([
            'Zona Nord Sacca di Scardovari',
            'Zona Sud Sacca di Scardovari',
            'Zona Est Sacca degli Scardovari',
            'Zona Ovest Sacca degli Scardovari',
            'Zona Centrale Porto Garibaldi',
            'Zona Marina di Comacchio',
            'Zona Lidi di Comacchio',
            'Zona Valli di Comacchio Nord',
            'Zona Valli di Comacchio Sud',
            'Zona Foce Po di Volano',
        ]);

        // Superficie in metri quadrati (zone marine tipiche)
        $surfaceArea = $this->faker->numberBetween(10000, 500000);

        $sanitaryClass = $this->faker->randomElement($sanitaryClasses);
        $isTemporarilyDeclassified = $this->faker->boolean(20); // 20% temporaneamente declassificate

        return [
            'company_id' => Company::factory(),
            'codice' => $ministerialCode,
            'nome' => $zoneName,
            'mq' => $surfaceArea,
            'classe_sanitaria' => $sanitaryClass,
            'declassificazione_temporanea' => $isTemporarilyDeclassified,
            'data_declassificazione' => $isTemporarilyDeclassified ?
                $this->faker->dateTimeBetween('-6 months', 'now') : null,
            'is_active' => $this->faker->boolean(95), // 95% attive
        ];
    }

    /**
     * Zona attiva
     */
    public function active(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true,
            ];
        });
    }

    /**
     * Zona con classe sanitaria A (migliore)
     */
    public function classeA(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'classe_sanitaria' => 'A',
                'declassificazione_temporanea' => false,
                'data_declassificazione' => null,
            ];
        });
    }

    /**
     * Zona con classe sanitaria B
     */
    public function classeB(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'classe_sanitaria' => 'B',
            ];
        });
    }

    /**
     * Zona con classe sanitaria C
     */
    public function classeC(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'classe_sanitaria' => 'C',
            ];
        });
    }

    /**
     * Zona temporaneamente declassificata
     */
    public function temporarilyDeclassified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'declassificazione_temporanea' => true,
                'data_declassificazione' => $this->faker->dateTimeBetween('-3 months', 'now'),
            ];
        });
    }

    /**
     * Zone per San Pietro
     */
    public function forSanPietro(): Factory
    {
        return $this->state(function (array $attributes) {
            $sanPietro = Company::where('name', 'Cooperativa San Pietro')->first();
            if (!$sanPietro) {
                $sanPietro = Company::factory()->create([
                    'name' => 'Cooperativa San Pietro',
                    'type' => 'main',
                    'domain' => 'sanpietro.test'
                ]);
            }

            // Zone specifiche di San Pietro dalla documentazione Excel
            static $sanPietroZones = [
                ['codice' => '006FE156-LI-FE6-81M/182807/2016', 'nome' => 'Sacca di Scardovari Nord', 'mq' => 250000],
                ['codice' => '006FE157-LI-FE6-82M/182808/2016', 'nome' => 'Sacca di Scardovari Sud', 'mq' => 180000],
                ['codice' => '006FE158-LI-FE6-83M/182809/2016', 'nome' => 'Sacca di Scardovari Est', 'mq' => 320000],
                ['codice' => '006FE159-LI-FE6-84M/182810/2016', 'nome' => 'Porto Garibaldi Marina', 'mq' => 150000],
            ];

            static $usedZones = [];

            // Trova la prima zona non ancora utilizzata
            $zone = null;
            foreach ($sanPietroZones as $index => $potentialZone) {
                if (!in_array($index, $usedZones)) {
                    $zone = $potentialZone;
                    $usedZones[] = $index;
                    break;
                }
            }

            // Se tutte le zone sono state utilizzate, genera un codice unico
            if (!$zone) {
                $zone = [
                    'codice' => $this->generateUniqueMinisterialCode($sanPietro->id),
                    'nome' => 'Zona Aggiuntiva San Pietro ' . count($usedZones),
                    'mq' => $this->faker->numberBetween(100000, 300000)
                ];
            }

            return [
                'company_id' => $sanPietro->id,
                'codice' => $zone['codice'],
                'nome' => $zone['nome'],
                'mq' => $zone['mq'],
            ];
        });
    }

    /**
     * Zona grande (per San Pietro)
     */
    public function large(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'mq' => $this->faker->numberBetween(200000, 500000),
            ];
        });
    }

    /**
     * Zona media
     */
    public function medium(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'mq' => $this->faker->numberBetween(50000, 200000),
            ];
        });
    }

    /**
     * Zona piccola
     */
    public function small(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'mq' => $this->faker->numberBetween(10000, 50000),
            ];
        });
    }

    /**
     * Genera codice ministeriale realistico
     */
    private function generateMinisterialCode(): string
    {
        return $this->generateUniqueMinisterialCode();
    }

    /**
     * Genera codice ministeriale unico
     */
    private function generateUniqueMinisterialCode($companyId = null): string
    {
        static $generatedCodes = [];

        $maxAttempts = 100;
        $attempt = 0;

        do {
            // Formato: NNNAANNNN-LL-AAA-NNM/NNNNNN/NNNN
            // Esempio: 006FE156-LI-FE6-81M/182807/2016

            $region = sprintf('%03d', $this->faker->numberBetween(1, 999));
            $province = $this->faker->randomElement(['FE', 'RO', 'VE', 'RA', 'FC']);
            $progressive = sprintf('%03d', $this->faker->numberBetween(1, 999));

            $location = 'LI'; // Liguria/Litorale
            $area = $province . $this->faker->numberBetween(1, 9);

            $category = $this->faker->numberBetween(10, 99);
            $type = $this->faker->randomElement(['M', 'C', 'A']); // M=Marine, C=Costiere, A=Acque interne

            $license = sprintf('%06d', $this->faker->numberBetween(100000, 999999));
            $year = $this->faker->numberBetween(2010, 2024);

            $code = "{$region}{$province}{$progressive}-{$location}-{$area}-{$category}{$type}/{$license}/{$year}";

            $key = $companyId ? "{$companyId}:{$code}" : $code;

            $attempt++;
        } while (in_array($key, $generatedCodes) && $attempt < $maxAttempts);

        if ($attempt >= $maxAttempts) {
            // Fallback: aggiungi timestamp per garantire unicità
            $code .= '-' . time() . '-' . $attempt;
            $key = $companyId ? "{$companyId}:{$code}" : $code;
        }

        $generatedCodes[] = $key;

        return $code;
    }
}