<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Access\Models\Mda;
use App\Domain\Registry\Enums\BeneficiaryStatus;
use App\Domain\Registry\Enums\ConsentStatus;
use App\Domain\Registry\Enums\Gender;
use App\Domain\Registry\Enums\Lga;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Synthetic beneficiaries only — never real PII. Provenance is set explicitly so
 * the factory works even when model events are muted (e.g. under a seeder's
 * WithoutModelEvents).
 *
 * @extends Factory<Beneficiary>
 */
class BeneficiaryFactory extends Factory
{
    protected $model = Beneficiary::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lastName = fake()->lastName();
        $dob = fake()->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d');

        return [
            'owner_mda_id' => Mda::factory(),
            'registration_source' => RegistrationSource::Manual,
            'registration_date' => now()->toDateString(),
            'nin' => fake()->unique()->numerify('###########'),
            'bvn' => fake()->unique()->numerify('###########'),
            'phone' => fake()->numerify('080########'),
            'first_name' => fake()->firstName(),
            'last_name' => $lastName,
            'date_of_birth' => $dob,
            'gender' => fake()->randomElement(Gender::cases()),
            'address' => fake()->streetAddress(),
            'lga' => fake()->randomElement(Lga::cases())->value,
            'ward' => 'Ward '.fake()->numberBetween(1, 12),
            'status' => BeneficiaryStatus::Active,
            // Test default: consent captured at registration. Cross-MDA sharing tests
            // that exercise the consent gate set this to unknown/withdrawn explicitly.
            'sharing_consent' => ConsentStatus::Granted,
            // Populated here too so the blocking key exists even when a seeder
            // mutes model events (WithoutModelEvents); the model hook keeps it
            // fresh on later saves.
            'block_name_dob' => Beneficiary::blockNameDobFor($lastName, $dob),
        ];
    }

    public function withoutNin(): static
    {
        return $this->state(fn () => ['nin' => null]);
    }

    public function withoutBvn(): static
    {
        return $this->state(fn () => ['bvn' => null]);
    }
}
