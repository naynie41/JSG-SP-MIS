<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Access\Models\Mda;
use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\ActivityLocation;
use App\Domain\Programme\Models\Programme;
use App\Domain\Reference\Models\Lga;
use App\Domain\Reference\Models\Ward;
use App\Domain\Registry\Enums\Lga as LgaEnum;
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
            'involves_beneficiaries' => true,
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->sentence(),
            'target_beneficiaries' => fake()->numberBetween(50, 5000),
            // No lga/ward: location is now the `activity_locations` set. Use
            // ->inLga() / ->inWards() to declare one, so a test that does not care
            // about location does not silently depend on reference data existing.
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

    /**
     * Declare whole-LGA coverage by LGA CODE, creating the lookup row if absent.
     *
     * The convenience that replaces the old `create(['lga' => 'dutse'])`. Resolving the
     * row from {@see LgaEnum} is not invented data — the 27 LGAs are committed,
     * authoritative reference already used for validation. Wards are never conjured
     * this way; a test that needs one creates it explicitly.
     */
    public function inLgaCode(string ...$codes): static
    {
        return $this->afterCreating(function (Activity $activity) use ($codes): void {
            foreach ($codes as $code) {
                $enum = LgaEnum::tryFrom($code);
                $lga = Lga::query()->firstOrCreate(
                    ['code' => $code],
                    ['name' => $enum?->label() ?? $code, 'state' => 'Jigawa'],
                );

                ActivityLocation::query()->firstOrCreate([
                    'activity_id' => $activity->id,
                    'lga_id' => $lga->id,
                    'ward_id' => null,
                ]);
            }
        });
    }

    /**
     * Declare whole-LGA coverage for one or more LGAs (a null-ward row each).
     */
    public function inLga(Lga|string ...$lgas): static
    {
        return $this->afterCreating(function (Activity $activity) use ($lgas): void {
            foreach ($lgas as $lga) {
                ActivityLocation::query()->create([
                    'activity_id' => $activity->id,
                    'lga_id' => $lga instanceof Lga ? $lga->id : $lga,
                    'ward_id' => null,
                ]);
            }
        });
    }

    /**
     * Declare specific wards within one LGA. Wards are NOT checked against the LGA
     * here — the factory builds fixtures, including deliberately invalid ones a test
     * may need; the request layer is what enforces the relationship.
     *
     * @param  list<Ward|string>  $wards
     */
    public function inWards(Lga|string $lga, array $wards): static
    {
        return $this->afterCreating(function (Activity $activity) use ($lga, $wards): void {
            foreach ($wards as $ward) {
                ActivityLocation::query()->create([
                    'activity_id' => $activity->id,
                    'lga_id' => $lga instanceof Lga ? $lga->id : $lga,
                    'ward_id' => $ward instanceof Ward ? $ward->id : $ward,
                ]);
            }
        });
    }
}
