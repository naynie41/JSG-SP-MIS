<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Programme\Enums\ProgrammeStatus;
use App\Domain\Programme\Enums\ProgrammeType;
use App\Domain\Programme\Models\Programme;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Synthetic GLOBAL catalog programmes for tests/seeds (§10) — no owner MDA; budget,
 * funding and period live on activities, not here.
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
            'name' => fake()->unique()->words(3, true),
            'objective' => fake()->sentence(),
            'type' => fake()->randomElement(ProgrammeType::cases()),
            'benefit_category' => fake()->randomElement(['cash', 'in_kind', 'service']),
            'eligibility' => [['label' => 'LGA', 'value' => 'dutse']],
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
