<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Access\Enums\MdaStatus;
use App\Domain\Access\Enums\MdaType;
use App\Domain\Access\Models\Mda;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mda>
 */
class MdaFactory extends Factory
{
    protected $model = Mda::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            // GOVERNMENT types only. This used to be `MdaType::cases()`, which the
            // moment `Partner` was added made a quarter of every factory-built MDA in
            // the suite a partner organisation — and partner-owned work follows
            // different funding rules, so unrelated tests began failing on a dice
            // roll. A partner organisation is a deliberate choice: ->partner().
            'type' => fake()->randomElement([MdaType::Ministry, MdaType::Department, MdaType::Agency]),
            'contact_person' => fake()->name(),
            'contact_email' => fake()->unique()->companyEmail(),
            'contact_phone' => fake()->numerify('080########'),
            'address' => fake()->address(),
            'status' => MdaStatus::Active,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => MdaStatus::Inactive,
        ]);
    }

    /**
     * A development partner that IMPLEMENTS: an owner of records like any MDA, but
     * not government. Pass the Development Partner account it funds through to link
     * its two identities.
     */
    public function partner(?string $funderUserId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => MdaType::Partner,
            'funder_user_id' => $funderUserId,
        ]);
    }
}
