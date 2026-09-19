<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Programme\Enums\ProgrammeApproval;
use App\Domain\Programme\Enums\ProgrammeStatus;
use App\Domain\Programme\Enums\ProgrammeType;
use App\Domain\Programme\Models\Programme;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Synthetic catalog programmes for tests/seeds (§10). The default is a CENTRAL
 * entry: unowned and approved, which is what every programme was before an MDA
 * could create one. Use ownedBy()/pending()/rejected() for the MDA case. Budget,
 * funding and period live on activities, not here.
 *
 * @extends Factory<Programme>
 */
class ProgrammeFactory extends Factory
{
    protected $model = Programme::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'objective' => fake()->sentence(),
            'type' => fake()->randomElement(ProgrammeType::cases()),
            'benefit_category' => fake()->randomElement(['cash', 'in_kind', 'service']),
            'eligibility' => [['label' => 'LGA', 'value' => 'dutse']],
            'status' => ProgrammeStatus::Active,
        ];
    }

    public function household(): static
    {
        return $this->state(fn () => ['type' => ProgrammeType::Household]);
    }

    public function individual(): static
    {
        return $this->state(fn () => ['type' => ProgrammeType::Individual]);
    }

    /** Owned by one MDA rather than the central catalog (§10, revised). */
    public function ownedBy(string $mdaId): static
    {
        return $this->state(fn () => ['owner_mda_id' => $mdaId]);
    }

    /** An MDA's submission, still waiting on the System Administrator. */
    public function pending(): static
    {
        return $this->state(fn () => [
            'approval_status' => ProgrammeApproval::Pending,
            'submitted_at' => now(),
        ]);
    }

    /** Sent back with a reason, for the MDA to address and submit again. */
    public function rejected(string $reason = 'Needs a clearer objective.'): static
    {
        return $this->state(fn () => [
            'approval_status' => ProgrammeApproval::Rejected,
            'submitted_at' => now()->subDay(),
            'approved_at' => now(),
            'decision_note' => $reason,
        ]);
    }
}
