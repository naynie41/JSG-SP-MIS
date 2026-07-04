<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Benefit\Enums\BenefitStatus;
use App\Domain\Benefit\Enums\BenefitType;
use App\Domain\Benefit\Enums\VerificationMethod;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Synthetic benefit-ledger entries. `mda_id` (the delivering MDA) inherits the
 * programme owner by default.
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

    public function configure(): static
    {
        return $this->afterMaking(function (Benefit $benefit): void {
            if ($benefit->mda_id === null && $benefit->programme_id !== null) {
                $benefit->mda_id = Programme::withoutGlobalScopes()
                    ->whereKey($benefit->programme_id)
                    ->value('owner_mda_id');
            }
        });
    }
}
