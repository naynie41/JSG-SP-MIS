<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Benefit\Models\BenefitImportBatch;
use App\Domain\Programme\Models\Activity;
use App\Domain\Registry\Enums\ImportStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BenefitImportBatch>
 */
class BenefitImportBatchFactory extends Factory
{
    protected $model = BenefitImportBatch::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'activity_id' => Activity::factory(),
            'original_filename' => 'deliveries.csv',
            'stored_path' => 'benefit-imports/'.fake()->uuid().'.csv',
            'source' => 'csv',
            'status' => ImportStatus::PreviewReady,
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (BenefitImportBatch $batch): void {
            if ($batch->activity_id !== null && ($batch->mda_id === null || $batch->programme_id === null)) {
                $activity = Activity::withoutGlobalScopes()->find($batch->activity_id);
                $batch->mda_id ??= $activity?->owner_mda_id;
                $batch->programme_id ??= $activity?->programme_id;
            }
        });
    }
}
