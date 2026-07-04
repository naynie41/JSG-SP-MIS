<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Benefit\Models\DoubleDippingRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DoubleDippingRule>
 */
class DoubleDippingRuleFactory extends Factory
{
    protected $model = DoubleDippingRule::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'period_days' => 30,
            'benefit_types' => null, // all types
            'is_active' => true,
            'description' => 'Flag the same benefit type from multiple MDAs within the window.',
        ];
    }
}
