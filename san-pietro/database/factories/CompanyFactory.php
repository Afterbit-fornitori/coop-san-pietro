<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            // 'domain' è stato rimosso, quindi lo togliamo anche da qui.

            // Usiamo i nuovi tipi definiti nella migrazione.
            'type' => $this->faker->randomElement(['main', 'invited']),

            // Aggiungiamo i nuovi campi, rendendoli nullabili come da migrazione.
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->companyEmail(),
            'pec' => $this->faker->unique()->safeEmail(),

            'vat_number' => $this->faker->numerify('###########'),
            'tax_code' => $this->faker->numerify('################'),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'province' => $this->faker->lexify('??'),
            'zip_code' => $this->faker->postcode(),
            'is_active' => true,
            'settings' => [
                'allowed_child_companies' => 5,
                'features' => ['members', 'ddt', 'production']
            ],
            // 'parent_company_id' è null di default. Lo gestiremo con uno state.
            'parent_company_id' => null,
        ];
    }

    /**
     * Indica che l'azienda è una figlia e necessita di un genitore.
     */
    public function isChild(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'invited',
                // Crea una company 'main' come genitore se non ne viene passata una.
                'parent_company_id' => Company::factory()->create(['type' => 'main']),
            ];
        });
    }
}
