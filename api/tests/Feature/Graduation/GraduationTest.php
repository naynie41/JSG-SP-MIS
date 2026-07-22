<?php

declare(strict_types=1);

namespace Tests\Feature\Graduation;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Graduation\Enums\CriteriaLogic;
use App\Domain\Graduation\Models\GraduationCriteria;
use App\Domain\Programme\Enums\EnrollmentStatus;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Graduation management (FR-GRD-01, FR-GRD-02): per-programme criteria are admin-editable
 * config; progress is tracked against the real ledger/enrolment; recording a graduation
 * flips the ENROLMENT status, is audited + notified, and NEVER deletes the beneficiary or
 * their benefit ledger. Covers config, progress, graduation events, and history preservation.
 */
class GraduationTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA; // enrolling / owner MDA

    private Mda $mdaB; // other MDA

    /** @var array<string, User> */
    private array $users = [];

    private Programme $programme;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['adminA'] = $this->user($this->mdaA, RoleKey::MdaAdmin);     // view + edit
        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer); // view + edit
        $this->users['adminB'] = $this->user($this->mdaB, RoleKey::MdaAdmin);
        $this->users['oversight'] = $this->user($this->mdaA, RoleKey::SpCoordination); // view only, cross-mda
        $this->users['partner'] = $this->user($this->mdaA, RoleKey::DevelopmentPartner); // no graduation perms

        $this->programme = Programme::factory()->individual()->create();
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /**
     * @param  list<array{type: string, threshold?: int|float}>  $rules
     */
    private function criteriaFor(Mda $mda, array $rules, CriteriaLogic $logic = CriteriaLogic::All): GraduationCriteria
    {
        return GraduationCriteria::factory()->create([
            'programme_id' => $this->programme->id,
            'owner_mda_id' => $mda->id,
            'logic' => $logic,
            'rules' => $rules,
            'is_active' => true,
        ]);
    }

    private function enrollmentFor(Beneficiary $beneficiary, int $monthsAgo = 4): Enrollment
    {
        return Enrollment::factory()->create([
            'programme_id' => $this->programme->id,
            'mda_id' => $this->mdaA->id,
            'beneficiary_id' => $beneficiary->id,
            'enrolled_on' => now()->subMonths($monthsAgo)->toDateString(),
        ]);
    }

    private function benefit(Beneficiary $beneficiary, int $value = 500_000): Benefit
    {
        return Benefit::factory()->create([
            'beneficiary_id' => $beneficiary->id,
            'programme_id' => $this->programme->id,
            'mda_id' => $this->mdaA->id,
            'monetary_value' => $value,
        ]);
    }

    /* ------------------------------------------------------------ criteria config */

    public function test_an_mda_defines_and_activates_criteria_per_programme(): void
    {
        $payload = [
            'name' => 'Standard graduation',
            'logic' => 'all',
            'rules' => [
                ['type' => 'benefits_received', 'threshold' => 2],
                ['type' => 'months_enrolled', 'threshold' => 3],
            ],
        ];

        $this->send('adminA', 'POST', "/api/v1/programmes/{$this->programme->id}/graduation-criteria", $payload)
            ->assertStatus(201)
            ->assertJsonPath('data.name', 'Standard graduation')
            ->assertJsonPath('data.logic', 'all')
            ->assertJsonPath('data.owner_mda_id', $this->mdaA->id)
            ->assertJsonPath('data.rules.0.type', 'benefits_received')
            ->assertJsonPath('data.rules.0.label', 'Benefits received');

        $this->assertDatabaseHas('graduation_criteria', [
            'programme_id' => $this->programme->id,
            'owner_mda_id' => $this->mdaA->id,
            'is_active' => true,
        ]);

        // A second active set deactivates the previous one (one active set per programme/MDA).
        $first = GraduationCriteria::query()->firstOrFail();
        $this->send('adminA', 'POST', "/api/v1/programmes/{$this->programme->id}/graduation-criteria", $payload)
            ->assertStatus(201);

        $this->assertDatabaseHas('graduation_criteria', ['id' => $first->id, 'is_active' => false]);
        $this->assertSame(1, GraduationCriteria::query()->where('is_active', true)->count());
    }

    public function test_an_automatic_criterion_requires_a_positive_threshold(): void
    {
        $this->send('adminA', 'POST', "/api/v1/programmes/{$this->programme->id}/graduation-criteria", [
            'name' => 'Bad',
            'logic' => 'all',
            'rules' => [['type' => 'benefits_received', 'threshold' => 0]],
        ])->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR')
            ->assertJsonPath('error.details.0.field', 'rules.0.threshold');
    }

    public function test_defining_criteria_requires_edit_permission(): void
    {
        $this->send('oversight', 'POST', "/api/v1/programmes/{$this->programme->id}/graduation-criteria", [
            'name' => 'X',
            'logic' => 'all',
            'rules' => [['type' => 'benefits_received', 'threshold' => 1]],
        ])->assertStatus(403);
    }

    /* ---------------------------------------------------------- progress tracking */

    public function test_progress_is_tracked_against_criteria_and_the_ledger(): void
    {
        $this->criteriaFor($this->mdaA, [
            ['type' => 'benefits_received', 'threshold' => 2],
            ['type' => 'months_enrolled', 'threshold' => 3],
        ]);
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $enrollment = $this->enrollmentFor($beneficiary, monthsAgo: 4);
        $this->benefit($beneficiary);

        // 1 benefit of 2 required, but 4 >= 3 months → benefits rule unmet → not eligible (logic=all).
        $this->send('adminA', 'GET', "/api/v1/enrollments/{$enrollment->id}/graduation")
            ->assertOk()
            ->assertJsonPath('data.progress.eligible', false)
            ->assertJsonPath('data.progress.rules.0.type', 'benefits_received')
            ->assertJsonPath('data.progress.rules.0.current', 1)
            ->assertJsonPath('data.progress.rules.0.met', false)
            ->assertJsonPath('data.progress.rules.1.met', true);

        // Deliver the second benefit → both rules met → eligible.
        $this->benefit($beneficiary);
        $this->send('adminA', 'GET', "/api/v1/enrollments/{$enrollment->id}/graduation")
            ->assertOk()
            ->assertJsonPath('data.progress.eligible', true)
            ->assertJsonPath('data.progress.rules.0.current', 2)
            ->assertJsonPath('data.progress.rules.0.met', true);
    }

    public function test_viewing_progress_requires_view_permission(): void
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $enrollment = $this->enrollmentFor($beneficiary);

        $this->send('partner', 'GET', "/api/v1/enrollments/{$enrollment->id}/graduation")->assertStatus(403);
    }

    /* --------------------------------------------------------- recording graduation */

    public function test_recording_a_graduation_updates_status_audits_notifies_and_preserves_history(): void
    {
        $criteria = $this->criteriaFor($this->mdaA, [['type' => 'benefits_received', 'threshold' => 1]]);
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $enrollment = $this->enrollmentFor($beneficiary);
        $this->benefit($beneficiary);
        $this->benefit($beneficiary);

        $response = $this->send('adminA', 'POST', "/api/v1/enrollments/{$enrollment->id}/graduate", [
            'reason' => 'Household income sustained above threshold.',
        ])->assertStatus(201)
            ->assertJsonPath('data.enrollment_id', $enrollment->id)
            ->assertJsonPath('data.beneficiary_id', $beneficiary->id);

        $eventId = $response->json('data.id');

        // The ENROLMENT status changed to graduated.
        $this->assertSame(EnrollmentStatus::Graduated, $enrollment->fresh()->status);

        // A permanent, audited graduation record exists (tied to the criteria used).
        $this->assertDatabaseHas('graduation_events', [
            'id' => $eventId,
            'enrollment_id' => $enrollment->id,
            'criteria_id' => $criteria->id,
            'decided_by' => $this->users['adminA']->id,
        ]);
        $this->assertDatabaseHas('audit_log', [
            'action' => 'graduation.recorded',
            'entity_id' => $eventId,
            'actor_id' => $this->users['adminA']->id,
        ]);

        // Relevant parties are notified (FR-GRD-02) — no PII in the notification body.
        $this->assertDatabaseHas('notifications', [
            'type' => 'graduation.recorded',
            'recipient_user_id' => $this->users['adminA']->id,
        ]);

        // History preserved: the beneficiary, their ledger, and the enrolment all remain.
        $this->assertDatabaseHas('beneficiaries', ['id' => $beneficiary->id]);
        $this->assertSame(2, Benefit::query()->where('beneficiary_id', $beneficiary->id)->count());
        $this->assertDatabaseHas('enrollments', ['id' => $enrollment->id, 'deleted_at' => null]);
    }

    public function test_a_serving_mda_graduation_notifies_the_owner_mda(): void
    {
        // MDA A serves (enrols) a beneficiary OWNED by MDA B, then graduates them.
        $this->criteriaFor($this->mdaA, [['type' => 'benefits_received', 'threshold' => 1]]);
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id]);
        $enrollment = $this->enrollmentFor($beneficiary);

        $this->send('adminA', 'POST', "/api/v1/enrollments/{$enrollment->id}/graduate")->assertStatus(201);

        // The OWNER MDA's graduation viewers are notified even though A acted.
        $this->assertDatabaseHas('notifications', [
            'type' => 'graduation.recorded',
            'recipient_user_id' => $this->users['adminB']->id,
        ]);
    }

    public function test_a_graduated_enrolment_cannot_be_graduated_again(): void
    {
        $this->criteriaFor($this->mdaA, [['type' => 'manual']]);
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $enrollment = $this->enrollmentFor($beneficiary);

        $this->send('adminA', 'POST', "/api/v1/enrollments/{$enrollment->id}/graduate")->assertStatus(201);
        $this->send('adminA', 'POST', "/api/v1/enrollments/{$enrollment->id}/graduate")
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'GRADUATION_INVALID');
    }

    public function test_recording_a_graduation_requires_edit_permission(): void
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $enrollment = $this->enrollmentFor($beneficiary);

        // Oversight can view but not record.
        $this->send('oversight', 'POST', "/api/v1/enrollments/{$enrollment->id}/graduate")->assertStatus(403);
    }

    public function test_an_mda_cannot_graduate_another_mdas_enrolment(): void
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $enrollment = $this->enrollmentFor($beneficiary);

        // MDA B has no scope over MDA A's enrolment → the route binding hides it (404).
        $this->send('adminB', 'POST', "/api/v1/enrollments/{$enrollment->id}/graduate")->assertStatus(404);
    }

    /* ------------------------------------------------------------------ history */

    public function test_graduation_history_is_scoped_to_the_caller(): void
    {
        $this->criteriaFor($this->mdaA, [['type' => 'manual']]);
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $enrollment = $this->enrollmentFor($beneficiary);
        $this->send('adminA', 'POST', "/api/v1/enrollments/{$enrollment->id}/graduate")->assertStatus(201);

        // The acting MDA sees the graduation in its history.
        $this->send('adminA', 'GET', '/api/v1/graduation-events')
            ->assertOk()
            ->assertJsonPath('data.0.enrollment_id', $enrollment->id);

        // Another MDA (no oversight) does not.
        $this->send('adminB', 'GET', '/api/v1/graduation-events')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }
}
