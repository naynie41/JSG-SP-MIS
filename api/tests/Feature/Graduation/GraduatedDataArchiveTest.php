<?php

declare(strict_types=1);

namespace Tests\Feature\Graduation;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Graduation\Models\GraduationCriteria;
use App\Domain\Graduation\Models\GraduationEvent;
use App\Domain\Graduation\Services\GraduationService;
use App\Domain\Programme\Enums\EnrollmentStatus;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Graduated data is archived, never deleted (PRD §10, FR-GRD-01/02).
 *
 * Several of these assert behaviour that was ALREADY correct — graduation has always
 * flipped the enrolment and left the beneficiary and ledger alone. They are here so
 * that stays true: it is the kind of guarantee a later refactor breaks silently, and
 * "we checked once" is not a test.
 */
class GraduatedDataArchiveTest extends TestCase
{
    use RefreshDatabase;

    private User $mdaAdmin;

    private Mda $mda;

    private Programme $programme;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        Http::fake(['*' => Http::response('', 200)]);

        $this->mda = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaAdmin = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);
        $this->programme = Programme::factory()->create();
    }

    private function enrolledBeneficiary(): Enrollment
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id]);

        return Enrollment::factory()->create([
            'beneficiary_id' => $beneficiary->id,
            'programme_id' => $this->programme->id,
            'mda_id' => $this->mda->id,
            'status' => EnrollmentStatus::Enrolled,
        ]);
    }

    // ------------------------------------------------- graduation never destroys

    public function test_graduating_never_deletes_the_beneficiary(): void
    {
        $enrollment = $this->enrolledBeneficiary();
        $beneficiaryId = $enrollment->beneficiary_id;

        app(GraduationService::class)->graduate($enrollment, 'Met the threshold.', $this->mdaAdmin);

        // Present, and not soft-deleted either.
        $this->assertDatabaseHas('beneficiaries', ['id' => $beneficiaryId, 'deleted_at' => null]);
    }

    public function test_graduating_archives_the_enrolment_rather_than_removing_it(): void
    {
        $enrollment = $this->enrolledBeneficiary();

        app(GraduationService::class)->graduate($enrollment, 'Met the threshold.', $this->mdaAdmin);

        $fresh = $enrollment->fresh();
        $this->assertSame(EnrollmentStatus::Graduated, $fresh->status);
        $this->assertNull($fresh->deleted_at);
    }

    public function test_the_graduation_event_is_a_permanent_record(): void
    {
        $enrollment = $this->enrolledBeneficiary();

        app(GraduationService::class)->graduate($enrollment, 'Met the threshold.', $this->mdaAdmin);

        $this->assertDatabaseHas('graduation_events', [
            'enrollment_id' => $enrollment->id,
            'beneficiary_id' => $enrollment->beneficiary_id,
        ]);
    }

    public function test_graduated_history_remains_queryable(): void
    {
        $enrollment = $this->enrolledBeneficiary();
        app(GraduationService::class)->graduate($enrollment, 'Met the threshold.', $this->mdaAdmin);

        $token = $this->mdaAdmin->createToken('t')->plainTextToken;

        // This enrolment's own graduation history...
        $this->withToken($token)
            ->getJson("/api/v1/enrollments/{$enrollment->id}/graduation")
            ->assertOk();

        // ...and the cross-programme history view.
        $this->withToken($token)->getJson('/api/v1/graduation-events')->assertOk();

        $this->assertSame(1, GraduationEvent::query()->where('enrollment_id', $enrollment->id)->count());
    }

    // ------------------------------------------------------- criteria are archived

    public function test_retiring_criteria_archives_them_and_keeps_graduation_history_intact(): void
    {
        $criteria = GraduationCriteria::factory()->create([
            'programme_id' => $this->programme->id,
            'owner_mda_id' => $this->mda->id,
        ]);

        // The service resolves the ACTIVE criteria set itself, so creating it above is
        // what makes the graduation reference it.
        $enrollment = $this->enrolledBeneficiary();
        app(GraduationService::class)->graduate($enrollment, 'Met it.', $this->mdaAdmin);

        $this->withToken($this->mdaAdmin->createToken('t')->plainTextToken)
            ->deleteJson("/api/v1/graduation-criteria/{$criteria->id}")
            ->assertOk();

        // The row survives...
        $this->assertDatabaseHas('graduation_criteria', ['id' => $criteria->id]);

        // ...and crucially the graduation still records WHY it happened. A hard
        // delete would have nulled this via the nullOnDelete FK, silently.
        $this->assertDatabaseHas('graduation_events', [
            'enrollment_id' => $enrollment->id,
            'criteria_id' => $criteria->id,
        ]);
    }

    public function test_archived_criteria_drop_out_of_the_default_list(): void
    {
        $live = GraduationCriteria::factory()->create([
            'programme_id' => $this->programme->id,
            'owner_mda_id' => $this->mda->id,
            'name' => 'Live Criteria',
        ]);
        $retired = GraduationCriteria::factory()->create([
            'programme_id' => $this->programme->id,
            'owner_mda_id' => $this->mda->id,
            'name' => 'Retired Criteria',
        ]);

        $token = $this->mdaAdmin->createToken('t')->plainTextToken;
        $this->withToken($token)->deleteJson("/api/v1/graduation-criteria/{$retired->id}")->assertOk();

        $names = collect($this->withToken($token)
            ->getJson("/api/v1/programmes/{$this->programme->id}/graduation-criteria")
            ->json('data.criteria') ?? [])->pluck('name');

        $this->assertContains('Live Criteria', $names);
        $this->assertNotContains('Retired Criteria', $names);
        $this->assertNotNull($live->fresh());
    }

    public function test_archiving_criteria_is_audited(): void
    {
        $criteria = GraduationCriteria::factory()->create([
            'programme_id' => $this->programme->id,
            'owner_mda_id' => $this->mda->id,
        ]);

        $this->withToken($this->mdaAdmin->createToken('t')->plainTextToken)
            ->deleteJson("/api/v1/graduation-criteria/{$criteria->id}")->assertOk();

        $this->assertDatabaseHas('audit_log', [
            'action' => 'graduation.criteria_archived',
            'entity_id' => $criteria->id,
        ]);
    }
}
