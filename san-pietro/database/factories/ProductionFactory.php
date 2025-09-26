<?php

namespace Database\Factories;

use App\Models\Production;
use App\Models\Company;
use App\Models\Member;
use App\Models\Product;
use App\Models\ProductionZone;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionFactory extends Factory
{
    protected $model = Production::class;

    public function definition(): array
    {
        // Tipi di produzione da Excel: internal_reimmersion, resale_reimmersion, consumption
        $productionTypes = ['internal_reimmersion', 'resale_reimmersion', 'consumption'];
        $categories = ['micro', 'small', 'medium', 'large', 'super'];
        $statuses = ['available', 'sold', 'reimmersed'];

        $quantityKg = $this->faker->randomFloat(2, 10, 500);
        $unitPrice = $this->faker->randomFloat(2, 2.50, 15.00);

        return [
            'company_id' => Company::factory(),
            'production_zone_id' => ProductionZone::factory(),
            'member_id' => Member::factory(),
            'product_id' => Product::factory(),
            'production_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'production_type' => $this->faker->randomElement($productionTypes),
            'category' => $this->faker->randomElement($categories),
            'quantity_kg' => $quantityKg,
            'unit_price' => $unitPrice,
            'total' => $quantityKg * $unitPrice,
            'status' => $this->faker->randomElement($statuses),
            'notes' => $this->faker->optional()->sentence(),
            'transport_document_id' => null, // Viene assegnato quando usato in DDT
            'weekly_record_id' => null, // Viene assegnato quando incluso in record settimanale
        ];
    }

    /**
     * Production for internal reimmersion (micro/small)
     */
    public function internalReimmersion(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'production_type' => 'internal_reimmersion',
                'category' => $this->faker->randomElement(['micro', 'small']),
                'unit_price' => $this->faker->randomFloat(2, 3.00, 6.00), // Prezzo tipico per reimmersione interna
            ];
        });
    }

    /**
     * Production for resale reimmersion (micro/small)
     */
    public function resaleReimmersion(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'production_type' => 'resale_reimmersion',
                'category' => $this->faker->randomElement(['micro', 'small']),
                'unit_price' => $this->faker->randomFloat(2, 2.50, 5.50), // Prezzo tipico per reimmersione rivendita
            ];
        });
    }

    /**
     * Production for consumption (medium/large/super)
     */
    public function consumption(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'production_type' => 'consumption',
                'category' => $this->faker->randomElement(['medium', 'large', 'super']),
                'unit_price' => $this->faker->randomFloat(2, 8.00, 15.00), // Prezzo tipico per consumo
            ];
        });
    }

    /**
     * Available production (not yet sold or used)
     */
    public function available(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'available',
                'transport_document_id' => null,
            ];
        });
    }

    /**
     * Sold production (used in transport document)
     */
    public function sold(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'sold',
                'transport_document_id' => null, // Sarà assegnato dal TransportDocument
            ];
        });
    }

    /**
     * Production for San Pietro company (main)
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
}