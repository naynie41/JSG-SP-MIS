<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Registry\Enums\HouseholdRole;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HouseholdMembership>
 */
class HouseholdMembershipFactory extends Factory
{
    protected $model = HouseholdMembership::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'household_id' => Household::factory(),
            'beneficiary_id' => Beneficiary::factory(),
            'role_in_household' => HouseholdRole::Head,
            'joined_at' => now(),
            'left_at' => null,
        ];
    }

    public function closed(): static
    {
        return $this->state(fn () => ['left_at' => now()]);
    }
}
