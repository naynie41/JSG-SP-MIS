<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Synthetic activities. `owner_mda_id` is inherited from the parent programme so
 * the activity is scoped to the same MDA (matching the write path).
 *
 * @extends Factory<Activity>
 */
class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'programme_id' => Programme::factory(),
            // owner_mda_id inherited from the programme (see configure()).
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->sentence(),
            'target_count' => fake()->numberBetween(50, 5000),
            'lga' => 'dutse',
            'ward' => 'Ward '.fake()->numberBetween(1, 12),
            'location_description' => fake()->streetAddress(),
            'schedule' => ['cadence' => 'monthly'],
            'starts_on' => now()->toDateString(),
            'ends_on' => now()->addMonths(6)->toDateString(),
            'budget_amount' => fake()->numberBetween(500_000, 50_000_000),
            'status' => ActivityStatus::Active,
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Activity $activity): void {
            if ($activity->owner_mda_id === null && $activity->programme_id !== null) {
                $activity->owner_mda_id = Programme::withoutGlobalScopes()
                    ->whereKey($activity->programme_id)
                    ->value('owner_mda_id');
            }
        });
    }

    /**
     * Attach to an existing programme, inheriting its owner MDA.
     */
    public function forProgramme(Programme $programme): static
    {
        return $this->state(fn () => [
            'programme_id' => $programme->id,
            'owner_mda_id' => $programme->owner_mda_id,
        ]);
    }
}
