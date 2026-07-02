<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Access\Models\Mda;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Household;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Household>
 */
class HouseholdFactory extends Factory
{
    protected $model = Household::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_mda_id' => Mda::factory(),
            'registration_source' => RegistrationSource::Manual,
            'registration_date' => now()->toDateString(),
            'address' => fake()->streetAddress(),
            'lga' => fake()->city(),
            'ward' => 'Ward '.fake()->numberBetween(1, 12),
        ];
    }
}
