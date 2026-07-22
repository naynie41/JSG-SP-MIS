<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Access\Models\Mda;
use App\Domain\Graduation\Enums\CriteriaLogic;
use App\Domain\Graduation\Enums\GraduationCriterionType;
use App\Domain\Graduation\Models\GraduationCriteria;
use App\Domain\Programme\Models\Programme;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GraduationCriteria>
 */
class GraduationCriteriaFactory extends Factory
{
    protected $model = GraduationCriteria::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'programme_id' => Programme::factory(),
            'owner_mda_id' => Mda::factory(),
            'name' => 'Standard graduation',
            'logic' => CriteriaLogic::All,
            'rules' => [
                ['type' => GraduationCriterionType::BenefitsReceived->value, 'threshold' => 3],
                ['type' => GraduationCriterionType::MonthsEnrolled->value, 'threshold' => 6],
            ],
            'is_active' => true,
        ];
    }
}
