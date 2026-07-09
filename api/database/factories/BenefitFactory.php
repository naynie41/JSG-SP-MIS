<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Access\Models\Mda;
use App\Domain\Benefit\Enums\BenefitStatus;
use App\Domain\Benefit\Enums\BenefitType;
use App\Domain\Benefit\Enums\VerificationMethod;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Synthetic benefit-ledger entries. `mda_id` is the delivering MDA — its own value
 * (§10: programmes are a global catalog with no owner), defaulting to a fresh MDA
 * when a caller doesn't pin it.
 *
 * @extends Factory<Benefit>
 */
class BenefitFactory extends Factory
{
    protected $model = Benefit::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'beneficiary_id' => Beneficiary::factory(),
            'programme_id' => Programme::factory()->individual(),
            'mda_id' => Mda::factory(),
            'benefit_type' => fake()->randomElement(BenefitType::cases()),
            'quantity' => fake()->randomFloat(2, 1, 50),
            'unit' => 'units',
            'monetary_value' => fake()->numberBetween(100_000, 5_000_000), // kobo
            'funding_source' => 'State budget',
            'delivery_date' => now()->toDateString(),
            'lga' => 'dutse',
            'ward' => 'Ward 1',
            'status' => BenefitStatus::Recorded,
            'verification_method' => VerificationMethod::None,
        ];
    }
}
