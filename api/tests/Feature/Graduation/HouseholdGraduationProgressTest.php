<?php

declare(strict_types=1);

namespace Tests\Feature\Graduation;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Graduation\Models\GraduationCriteria;
use App\Domain\Graduation\Services\GraduationProgressService;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Graduation progress for a HOUSEHOLD enrolment (FR-GRD-02).
 *
 * A household programme enrols the household, but benefits are always recorded against a
 * person — `benefits` has no household column. So a household's ledger is its members'
 * ledger, and progress had to be taught to look there: it read `enrollment->beneficiary_id`,
 * which is null on a household enrolment, and returned zero.
 *
 * Zero is the dangerous answer rather than an error. A household that had received a
 * year of support showed no progress at all, and under `all` logic could never become
 * eligible — the criteria silently could not be satisfied, and nothing said so.
 */
class HouseholdGraduationProgressTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private Programme $programme;

    private Household $household;

    private User $officer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->officer = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);

        $this->programme = Programme::factory()->create();
        $this->household = Household::factory()->create(['owner_mda_id' => $this->mda->id]);
    }

    /** @param array<int, array<string, mixed>> $rules */
    private function criteria(array $rules, string $logic = 'all'): GraduationCriteria
    {
        return GraduationCriteria::query()->create([
            'name' => 'Graduation criteria',
            'programme_id' => $this->programme->id,
            'owner_mda_id' => $this->mda->id,
            'logic' => $logic,
            'rules' => $rules,
            'is_active' => true,
            'created_by' => $this->officer->id,
        ]);
    }

    private function householdEnrollment(int $monthsAgo = 4): Enrollment
    {
        return Enrollment::factory()->create([
            'programme_id' => $this->programme->id,
            'mda_id' => $this->mda->id,
            'beneficiary_id' => null,
            'household_id' => $this->household->id,
            'enrolled_on' => now()->subMonths($monthsAgo)->toDateString(),
        ]);
    }

    /** A member of the household, optionally one who has since left. */
    private function member(bool $left = false): Beneficiary
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id]);

        HouseholdMembership::query()->create([
            'household_id' => $this->household->id,
            'beneficiary_id' => $beneficiary->id,
            'role_in_household' => 'dependent',
            'joined_at' => now()->subMonths(6),
            'left_at' => $left ? now()->subMonth() : null,
        ]);

        return $beneficiary;
    }

    private function benefit(Beneficiary $beneficiary, int $value = 500_000): Benefit
    {
        return Benefit::factory()->create([
            'beneficiary_id' => $beneficiary->id,
            'programme_id' => $this->programme->id,
            'mda_id' => $this->mda->id,
            'monetary_value' => $value,
        ]);
    }

    private function progress(Enrollment $enrollment): array
    {
        return app(GraduationProgressService::class)->forEnrollment($enrollment);
    }

    /* ------------------------------------------------------- the household ledger */

    public function test_household_progress_counts_the_benefits_its_members_received(): void
    {
        $this->criteria([['type' => 'benefits_received', 'threshold' => 3]]);
        $first = $this->member();
        $second = $this->member();

        $this->benefit($first);
        $this->benefit($first);
        $this->benefit($second);

        $progress = $this->progress($this->householdEnrollment());
        $rule = collect($progress['rules'])->firstWhere('type', 'benefits_received');

        $this->assertSame(3.0, $rule['current'], 'A household ledger is the sum of its members.');
        $this->assertTrue($rule['met']);
        $this->assertTrue($progress['eligible']);
    }

    public function test_household_progress_sums_the_value_its_members_received(): void
    {
        $this->criteria([['type' => 'total_benefit_value', 'threshold' => 900_000]]);
        $member = $this->member();
        $this->benefit($member, 500_000);
        $this->benefit($member, 400_000);

        $rule = collect($this->progress($this->householdEnrollment())['rules'])
            ->firstWhere('type', 'total_benefit_value');

        $this->assertSame(900_000.0, $rule['current']);
        $this->assertTrue($rule['met']);
    }

    public function test_a_household_with_no_deliveries_is_not_eligible(): void
    {
        // The zero must still be reachable — it was the WRONG zero that was the bug.
        $this->criteria([['type' => 'benefits_received', 'threshold' => 1]]);
        $this->member();

        $progress = $this->progress($this->householdEnrollment());

        $this->assertSame(0.0, collect($progress['rules'])->firstWhere('type', 'benefits_received')['current']);
        $this->assertFalse($progress['eligible']);
    }

    public function test_a_departed_members_history_does_not_count_toward_the_household(): void
    {
        // Support follows the person. Someone who has left is no longer part of this
        // household, so crediting the household for what they received would graduate it
        // on the strength of help it no longer contains.
        $this->criteria([['type' => 'benefits_received', 'threshold' => 2]]);
        $stayed = $this->member();
        $departed = $this->member(left: true);

        $this->benefit($stayed);
        $this->benefit($departed);
        $this->benefit($departed);

        $rule = collect($this->progress($this->householdEnrollment())['rules'])
            ->firstWhere('type', 'benefits_received');

        $this->assertSame(1.0, $rule['current']);
        $this->assertFalse($rule['met']);
    }

    public function test_another_programmes_deliveries_do_not_count(): void
    {
        $this->criteria([['type' => 'benefits_received', 'threshold' => 1]]);
        $member = $this->member();

        Benefit::factory()->create([
            'beneficiary_id' => $member->id,
            'programme_id' => Programme::factory()->create()->id,
            'mda_id' => $this->mda->id,
            'monetary_value' => 500_000,
        ]);

        $rule = collect($this->progress($this->householdEnrollment())['rules'])
            ->firstWhere('type', 'benefits_received');

        $this->assertSame(0.0, $rule['current'], 'Graduation is from ONE programme.');
    }

    /* ------------------------------------------------ the individual path is intact */

    public function test_an_individual_enrolment_still_counts_only_that_person(): void
    {
        $this->criteria([['type' => 'benefits_received', 'threshold' => 1]]);
        $member = $this->member();
        $other = $this->member();
        $this->benefit($other);

        $enrollment = Enrollment::factory()->create([
            'programme_id' => $this->programme->id,
            'mda_id' => $this->mda->id,
            'beneficiary_id' => $member->id,
            'household_id' => null,
            'enrolled_on' => now()->subMonths(4)->toDateString(),
        ]);

        $rule = collect($this->progress($enrollment)['rules'])->firstWhere('type', 'benefits_received');

        $this->assertSame(0.0, $rule['current'], 'A person is not credited with their household’s deliveries.');
    }
}
