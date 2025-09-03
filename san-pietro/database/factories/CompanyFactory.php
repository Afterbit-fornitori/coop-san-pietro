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
            'domain' => $this->faker->unique()->domainName(),
            'type' => $this->faker->randomElement(['parent', 'child']),
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
            ]
        ];
    }
}
