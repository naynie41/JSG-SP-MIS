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
            'type' => fake()->randomElement(MdaType::cases()),
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
}
