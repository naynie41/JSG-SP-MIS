<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Access\Models\Mda;
use App\Domain\Programme\Enums\ProgrammeStatus;
use App\Domain\Programme\Enums\ProgrammeType;
use App\Domain\Programme\Models\Programme;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Synthetic programmes for tests/seeds.
 *
 * @extends Factory<Programme>
 */
class ProgrammeFactory extends Factory
{
    protected $model = Programme::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_mda_id' => Mda::factory(),
            'name' => fake()->unique()->words(3, true),
            'objective' => fake()->sentence(),
            'type' => fake()->randomElement(ProgrammeType::cases()),
            'eligibility' => [['label' => 'LGA', 'value' => 'dutse']],
            'funding_source' => fake()->randomElement(['State budget', 'World Bank', 'UNICEF', 'Internal']),
            'budget_amount' => fake()->numberBetween(1_000_000, 500_000_000), // kobo
            'starts_on' => now()->toDateString(),
            'ends_on' => now()->addYear()->toDateString(),
            'status' => ProgrammeStatus::Active,
        ];
    }

    public function household(): static
    {
        return $this->state(fn () => ['type' => ProgrammeType::Household]);
    }

    public function individual(): static
    {
        return $this->state(fn () => ['type' => ProgrammeType::Individual]);
    }
}
