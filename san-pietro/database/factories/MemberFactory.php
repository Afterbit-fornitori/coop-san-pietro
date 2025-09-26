<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition(): array
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();

        return [
            'company_id' => Company::factory(),
            'last_name' => $lastName,
            'first_name' => $firstName,
            'tax_code' => $this->generateItalianTaxCode($firstName, $lastName),
            'birth_date' => $this->faker->dateTimeBetween('-70 years', '-18 years'),
            'birth_place' => $this->faker->city(),
            'rpm_registration' => $this->faker->unique()->numerify('RPM####'),
            'rpm_registration_date' => $this->faker->dateTimeBetween('-20 years', '-1 year'),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->optional(0.7)->safeEmail(),
            'is_active' => $this->faker->boolean(90), // 90% attivi
        ];
    }

    /**
     * Indica che il membro è attivo
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
     * Indica che il membro è inattivo
     */
    public function inactive(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

    /**
     * Membro per San Pietro
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
     * Genera un codice fiscale italiano fittizio
     */
    private function generateItalianTaxCode(string $firstName, string $lastName): string
    {
        $consonants = 'BCDFGHJKLMNPQRSTVWXYZ';
        $vowels = 'AEIOU';

        // Genera le prime 3 lettere dal cognome
        $lastNameCode = $this->extractLetters($lastName, $consonants, $vowels, 3);

        // Genera le successive 3 lettere dal nome
        $firstNameCode = $this->extractLetters($firstName, $consonants, $vowels, 3);

        // Anno di nascita (ultime 2 cifre)
        $birthYear = substr((string) $this->faker->year(), -2);

        // Mese di nascita (A=Gennaio, B=Febbraio, etc.)
        $months = 'ABCDEHLMPRST';
        $birthMonth = $months[$this->faker->numberBetween(0, 11)];

        // Giorno di nascita (01-31 per maschi, 41-71 per femmine)
        $birthDay = sprintf('%02d', $this->faker->numberBetween(1, 31));
        if ($this->faker->boolean()) {
            $birthDay = sprintf('%02d', (int)$birthDay + 40); // Femmine
        }

        // Codice comune (4 caratteri fittizi)
        $cityCode = $this->faker->regexify('[A-Z][0-9]{3}');

        // Carattere di controllo (fittizio)
        $checkChar = $this->faker->randomElement(str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ'));

        return $lastNameCode . $firstNameCode . $birthYear . $birthMonth . $birthDay . $cityCode . $checkChar;
    }

    /**
     * Estrae lettere per il codice fiscale
     */
    private function extractLetters(string $name, string $consonants, string $vowels, int $length): string
    {
        $name = strtoupper($name);
        $result = '';

        // Prima prendi le consonanti
        for ($i = 0; $i < strlen($name) && strlen($result) < $length; $i++) {
            if (strpos($consonants, $name[$i]) !== false) {
                $result .= $name[$i];
            }
        }

        // Poi le vocali se necessario
        for ($i = 0; $i < strlen($name) && strlen($result) < $length; $i++) {
            if (strpos($vowels, $name[$i]) !== false) {
                $result .= $name[$i];
            }
        }

        // Completa con X se necessario
        while (strlen($result) < $length) {
            $result .= 'X';
        }

        return $result;
    }
}