<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Benefit\Models\BenefitImportBatch;
use App\Domain\Benefit\Models\BenefitImportRow;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BenefitImportRow>
 */
class BenefitImportRowFactory extends Factory
{
    protected $model = BenefitImportRow::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'benefit_import_batch_id' => BenefitImportBatch::factory(),
            'resolved_beneficiary_id' => Beneficiary::factory(),
            'row_number' => fake()->numberBetween(1, 100),
            'is_valid' => true,
            'errors' => null,
            'eligibility_flagged' => false,
            'payload' => [
                'benefit_type' => 'cash',
                'quantity' => '1',
                'unit' => 'transfer',
                'monetary_value' => 500_000,
                'funding_source' => 'State budget',
                'delivery_date' => now()->toDateString(),
                'verification_method' => 'none',
                'verification_reference' => null,
                'notes' => null,
            ],
        ];
    }
}
