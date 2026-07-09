<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Access\Models\Mda;
use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Synthetic activities (§10). `owner_mda_id` is the activity's OWN creating MDA —
 * independent of the (unowned, global) programme. Budget + funding live here.
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
            'owner_mda_id' => Mda::factory(),
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
            'funding_source' => fake()->randomElement(['State budget', 'World Bank', 'UNICEF', 'Internal']),
            'status' => ActivityStatus::Active,
        ];
    }

    /**
     * Run an existing catalog programme. Pass the owning MDA (the MDA creating the
     * activity); omit it to let the factory mint a fresh MDA.
     */
    public function forProgramme(Programme $programme, Mda|string|null $owner = null): static
    {
        return $this->state(function () use ($programme, $owner): array {
            $state = ['programme_id' => $programme->id];
            if ($owner !== null) {
                $state['owner_mda_id'] = $owner instanceof Mda ? $owner->id : $owner;
            }

            return $state;
        });
    }
}
