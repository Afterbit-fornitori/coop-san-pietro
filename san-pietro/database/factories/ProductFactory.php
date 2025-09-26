<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $species = ['VONGOLE', 'COZZE', 'OSTRICHE', 'ALTRO'];
        $sizes = ['MICRO', 'PICCOLA', 'MEDIA', 'GROSSA', 'SUPER', 'SGRANATA', 'TRECCIA'];
        $destinations = ['CONSUMO', 'REIMMERSIONE', 'DEPURAZIONE'];
        $units = ['kg', 'pz', 'sacco'];

        $selectedSpecies = $this->faker->randomElement($species);
        $selectedSize = $this->faker->randomElement($sizes);
        $selectedDestination = $this->faker->randomElement($destinations);

        // Genera codice prodotto basato su specie e pezzatura
        $code = substr($selectedSpecies, 0, 3) . '_' . substr($selectedSize, 0, 3) . '_' . sprintf('%03d', $this->faker->numberBetween(1, 999));

        // Nome commerciale basato su specie e pezzatura
        $commercialName = $this->generateCommercialName($selectedSpecies, $selectedSize);

        // Nome scientifico basato sulla specie
        $scientificName = $this->generateScientificName($selectedSpecies);

        // Prezzo base variabile per specie e pezzatura
        $basePrice = $this->generateBasePrice($selectedSpecies, $selectedSize, $selectedDestination);

        return [
            'company_id' => Company::factory(),
            'codice' => $code,
            'nome_scientifico' => $scientificName,
            'nome_commerciale' => $commercialName,
            'specie' => $selectedSpecies,
            'pezzatura' => $selectedSize,
            'destinazione' => $selectedDestination,
            'prezzo_base' => $basePrice,
            'unita_misura' => $this->faker->randomElement($units),
            'is_active' => $this->faker->boolean(95), // 95% attivi
        ];
    }

    /**
     * Prodotto attivo
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
     * Vongole veraci
     */
    public function vongole(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'specie' => 'VONGOLE',
                'nome_scientifico' => 'Ruditapes philippinarum',
                'nome_commerciale' => 'Vongole Veraci',
            ];
        });
    }

    /**
     * Cozze
     */
    public function cozze(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'specie' => 'COZZE',
                'nome_scientifico' => 'Mytilus galloprovincialis',
                'nome_commerciale' => 'Cozze del Mediterraneo',
            ];
        });
    }

    /**
     * Ostriche
     */
    public function ostriche(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'specie' => 'OSTRICHE',
                'nome_scientifico' => 'Crassostrea gigas',
                'nome_commerciale' => 'Ostriche Pacific',
            ];
        });
    }

    /**
     * Prodotti per consumo diretto
     */
    public function consumo(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'destinazione' => 'CONSUMO',
                'pezzatura' => $this->faker->randomElement(['MEDIA', 'GROSSA', 'SUPER']),
            ];
        });
    }

    /**
     * Prodotti per reimmersione
     */
    public function reimmersione(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'destinazione' => 'REIMMERSIONE',
                'pezzatura' => $this->faker->randomElement(['MICRO', 'PICCOLA']),
            ];
        });
    }

    /**
     * Prodotti per San Pietro
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

            return [
                'company_id' => $sanPietro->id,
            ];
        });
    }

    /**
     * Genera nome commerciale
     */
    private function generateCommercialName(string $species, string $size): string
    {
        $names = [
            'VONGOLE' => [
                'MICRO' => 'Vongole Micro',
                'PICCOLA' => 'Vongole Piccole',
                'MEDIA' => 'Vongole Medie',
                'GROSSA' => 'Vongole Grosse',
                'SUPER' => 'Vongole Super',
                'SGRANATA' => 'Vongole Sgranate',
            ],
            'COZZE' => [
                'PICCOLA' => 'Cozze Piccole',
                'MEDIA' => 'Cozze Medie',
                'GROSSA' => 'Cozze Grosse',
                'TRECCIA' => 'Cozze a Treccia',
            ],
            'OSTRICHE' => [
                'MEDIA' => 'Ostriche Medie',
                'GROSSA' => 'Ostriche Grosse',
                'SUPER' => 'Ostriche Super',
            ],
            'ALTRO' => [
                'MEDIA' => 'Molluschi Vari',
            ]
        ];

        return $names[$species][$size] ?? $species . ' ' . $size;
    }

    /**
     * Genera nome scientifico
     */
    private function generateScientificName(string $species): string
    {
        $scientificNames = [
            'VONGOLE' => 'Ruditapes philippinarum',
            'COZZE' => 'Mytilus galloprovincialis',
            'OSTRICHE' => 'Crassostrea gigas',
            'ALTRO' => 'Mollusca spp.',
        ];

        return $scientificNames[$species] ?? 'Species unknown';
    }

    /**
     * Genera prezzo base
     */
    private function generateBasePrice(string $species, string $size, string $destination): float
    {
        // Prezzi base per specie (Euro/kg)
        $basePrices = [
            'VONGOLE' => [
                'MICRO' => ['REIMMERSIONE' => 3.50, 'DEPURAZIONE' => 4.00],
                'PICCOLA' => ['REIMMERSIONE' => 4.50, 'DEPURAZIONE' => 5.00, 'CONSUMO' => 6.00],
                'MEDIA' => ['CONSUMO' => 8.50, 'DEPURAZIONE' => 7.50],
                'GROSSA' => ['CONSUMO' => 12.00, 'DEPURAZIONE' => 10.00],
                'SUPER' => ['CONSUMO' => 15.00],
            ],
            'COZZE' => [
                'PICCOLA' => ['REIMMERSIONE' => 2.50, 'CONSUMO' => 3.50],
                'MEDIA' => ['CONSUMO' => 4.50, 'DEPURAZIONE' => 4.00],
                'GROSSA' => ['CONSUMO' => 6.00],
                'TRECCIA' => ['CONSUMO' => 5.50],
            ],
            'OSTRICHE' => [
                'MEDIA' => ['CONSUMO' => 18.00],
                'GROSSA' => ['CONSUMO' => 25.00],
                'SUPER' => ['CONSUMO' => 35.00],
            ],
        ];

        $price = $basePrices[$species][$size][$destination] ?? 5.00;

        // Aggiungi una piccola variazione random
        return round($price + $this->faker->randomFloat(2, -0.50, 0.50), 2);
    }
}