<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Programme\Enums\EnrollmentStatus;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Synthetic enrollments. Defaults to an individual-programme enrollment of a
 * beneficiary; `mda_id` inherits the programme owner (the enrolling MDA).
 *
 * @extends Factory<Enrollment>
 */
class EnrollmentFactory extends Factory
{
    protected $model = Enrollment::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'programme_id' => Programme::factory()->individual(),
            'beneficiary_id' => Beneficiary::factory(),
            'household_id' => null,
            'status' => EnrollmentStatus::Enrolled,
            'enrolled_on' => now()->toDateString(),
            'eligibility_flagged' => false,
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Enrollment $enrollment): void {
            if ($enrollment->mda_id === null && $enrollment->programme_id !== null) {
                $enrollment->mda_id = Programme::withoutGlobalScopes()
                    ->whereKey($enrollment->programme_id)
                    ->value('owner_mda_id');
            }
        });
    }
}
