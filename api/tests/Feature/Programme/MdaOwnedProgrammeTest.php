<?php

declare(strict_types=1);

namespace Tests\Feature\Programme;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Notification\Models\Notification;
use App\Domain\Programme\Enums\ProgrammeApproval;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * A programme an MDA creates for itself (PRD §10, revised).
 *
 * The rules this pins down, in the order they matter:
 *
 *  1. An MDA creates a programme owned by ITS OWN MDA, waiting for approval.
 *  2. No other MDA can see it — at the query level, not by a controller filter, so
 *     a guessed id gets the same answer as a listing.
 *  3. It carries NO work until approved: no activity, no import, no enrolment.
 *  4. Only the System Administrator decides; a rejection must say why, and the MDA
 *     can act on it and submit again.
 *  5. The central catalog behaves exactly as it always did.
 */
class MdaOwnedProgrammeTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mineMda;

    private Mda $otherMda;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mineMda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->otherMda = Mda::factory()->create(['name' => 'Ministry of Education']);

        $this->users['mine'] = $this->user($this->mineMda, RoleKey::MdaAdmin);
        $this->users['other'] = $this->user($this->otherMda, RoleKey::MdaAdmin);
        $this->users['sysadmin'] = $this->user(null, RoleKey::SystemAdministrator);
        $this->users['coordination'] = $this->user(null, RoleKey::SpCoordination);
        $this->users['executive'] = $this->user(null, RoleKey::Executive);
    }

    /* ------------------------------------------------------------------ create */

    public function test_an_mda_creates_its_own_programme_waiting_for_approval(): void
    {
        $body = $this->send('mine', 'POST', '/api/v1/programmes', $this->payload())
            ->assertCreated()
            ->assertJsonPath('data.owner_mda_id', $this->mineMda->id)
            ->assertJsonPath('data.approval_status', 'pending')
            ->assertJsonPath('data.approval_label', 'Waiting for approval')
            ->json('data');

        $programme = Programme::query()->withoutGlobalScopes()->findOrFail($body['id']);
        $this->assertSame($this->users['mine']->id, $programme->created_by);
        $this->assertNotNull($programme->submitted_at);
        $this->assertNull($programme->approved_at);
    }

    public function test_the_system_administrator_is_told_a_programme_is_waiting(): void
    {
        $this->send('mine', 'POST', '/api/v1/programmes', $this->payload())->assertCreated();

        $this->assertDatabaseHas('notifications', [
            'recipient_user_id' => $this->users['sysadmin']->id,
            'type' => 'programme.submitted',
        ]);
        // The MDA that submitted it is not notified about its own submission.
        $this->assertSame(0, Notification::query()->withoutGlobalScopes()->where('recipient_user_id', $this->users['mine']->id)->count());
    }

    /* -------------------------------------------------------------- visibility */

    public function test_no_other_mda_can_see_it_by_listing_or_by_id(): void
    {
        $mine = $this->mdaProgramme();

        $listed = $this->send('other', 'GET', '/api/v1/programmes')->assertOk()->json('data');
        $this->assertSame([], array_values(array_filter($listed, fn (array $row): bool => $row['id'] === $mine->id)));

        // A guessed id answers the same way a listing does — 404, not 403, which
        // would confirm the programme exists.
        $this->send('other', 'GET', "/api/v1/programmes/{$mine->id}")->assertStatus(404);
    }

    public function test_the_owning_mda_and_oversight_can_see_it(): void
    {
        $mine = $this->mdaProgramme();

        foreach (['mine', 'sysadmin', 'executive'] as $who) {
            $this->send($who, 'GET', "/api/v1/programmes/{$mine->id}")->assertOk();
        }
    }

    public function test_the_owning_mda_sees_its_submission_in_the_programmes_it_runs(): void
    {
        // A brand-new programme has no activities yet, so "participating" has to mean
        // more than "has an activity" or its own author could not find it.
        $mine = $this->mdaProgramme();

        $rows = $this->send('mine', 'GET', '/api/v1/programmes?filter[participating]=1')->assertOk()->json('data');

        $this->assertContains($mine->id, array_column($rows, 'id'));
    }

    public function test_the_pending_queue_lists_what_is_waiting(): void
    {
        $this->mdaProgramme();
        Programme::factory()->create(); // a central entry, already approved

        $rows = $this->send('sysadmin', 'GET', '/api/v1/programmes?filter[approval]=pending')->assertOk()->json('data');

        $this->assertCount(1, $rows);
        $this->assertSame('Ministry of Health', $rows[0]['owner_mda']['name']);
    }

    /* ----------------------------------------------------------- pending blocks */

    public function test_a_pending_programme_carries_no_activity(): void
    {
        $mine = $this->mdaProgramme();

        $this->send('mine', 'POST', '/api/v1/activities', [
            'programme_id' => $mine->id,
            'name' => 'Round one',
            'involves_beneficiaries' => false,
        ])
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'programme_id']);

        $this->assertDatabaseCount('activities', 0);
    }

    public function test_another_mdas_programme_cannot_be_used_even_with_its_id(): void
    {
        // `exists:programmes,id` reads the table directly and so ignores the MDA
        // scope. Without the scoped check in IsRunnableProgramme a leaked id would
        // be enough to attach an activity to someone else's programme.
        $theirs = Programme::factory()->ownedBy($this->otherMda->id)->create();

        $this->send('mine', 'POST', '/api/v1/activities', [
            'programme_id' => $theirs->id,
            'name' => 'Round one',
            'involves_beneficiaries' => false,
        ])
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'programme_id']);
    }

    public function test_a_pending_programme_carries_no_enrolment(): void
    {
        $mine = $this->mdaProgramme(['type' => 'individual']);
        $person = Beneficiary::factory()->create(['owner_mda_id' => $this->mineMda->id]);

        // The enrolment endpoint resolves the programme UNSCOPED so that reads keep
        // working for people already enrolled. That makes the route param the way in,
        // and it has to be gated separately from the listing.
        $this->send('mine', 'POST', "/api/v1/programmes/{$mine->id}/enrollments", ['beneficiary_id' => $person->id])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'PROGRAMME_NOT_APPROVED');

        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_another_mdas_programme_takes_no_enrolment(): void
    {
        $theirs = Programme::factory()->ownedBy($this->otherMda->id)->create(['type' => 'individual']);
        $person = Beneficiary::factory()->create(['owner_mda_id' => $this->mineMda->id]);

        $this->send('mine', 'POST', "/api/v1/programmes/{$theirs->id}/enrollments", ['beneficiary_id' => $person->id])
            ->assertStatus(404)
            ->assertJsonPath('error.code', 'PROGRAMME_NOT_AVAILABLE');
    }

    public function test_a_pending_programme_carries_no_benefit(): void
    {
        $mine = $this->mdaProgramme(['type' => 'individual']);
        $person = Beneficiary::factory()->create(['owner_mda_id' => $this->mineMda->id]);

        $this->send('mine', 'POST', '/api/v1/benefits', [
            'programme_id' => $mine->id,
            'beneficiary_id' => $person->id,
            'benefit_type' => 'cash',
            'amount' => 1000,
            'delivered_at' => now()->toDateString(),
        ])
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'programme_id']);
    }

    public function test_an_approved_programme_carries_activities(): void
    {
        $mine = $this->mdaProgramme();
        $this->send('sysadmin', 'POST', "/api/v1/programmes/{$mine->id}/approve")->assertOk();

        $this->send('mine', 'POST', '/api/v1/activities', [
            'programme_id' => $mine->id,
            'name' => 'Round one',
            'involves_beneficiaries' => false,
        ])->assertCreated();
    }

    /* -------------------------------------------------------------- the decision */

    public function test_only_the_system_administrator_decides(): void
    {
        $mine = $this->mdaProgramme();

        // Not the MDA that submitted it, and not SP Coordination, who administer the
        // central catalog but do not decide another MDA's submissions.
        $this->send('mine', 'POST', "/api/v1/programmes/{$mine->id}/approve")->assertStatus(403);
        $this->send('coordination', 'POST', "/api/v1/programmes/{$mine->id}/approve")->assertStatus(403);

        $this->assertSame(ProgrammeApproval::Pending, $mine->fresh()->approval_status);
    }

    public function test_approval_records_the_decision_and_the_participation(): void
    {
        $mine = $this->mdaProgramme();

        $this->send('sysadmin', 'POST', "/api/v1/programmes/{$mine->id}/approve")
            ->assertOk()
            ->assertJsonPath('data.approval_status', 'approved');

        $fresh = $mine->fresh();
        $this->assertSame($this->users['sysadmin']->id, $fresh->approved_by);
        $this->assertNotNull($fresh->approved_at);

        // The MDA is now recorded as RUNNING it, so every "programmes this MDA runs"
        // query keeps working without knowing about approvals at all.
        $this->assertDatabaseHas('mda_programme', [
            'mda_id' => $this->mineMda->id,
            'programme_id' => $mine->id,
            'source' => 'mda',
        ]);

        $this->assertDatabaseHas('notifications', [
            'recipient_user_id' => $this->users['mine']->id,
            'type' => 'programme.approved',
        ]);
    }

    public function test_approving_twice_does_not_duplicate_the_participation(): void
    {
        $mine = $this->mdaProgramme();

        $this->send('sysadmin', 'POST', "/api/v1/programmes/{$mine->id}/approve")->assertOk();
        $this->send('sysadmin', 'POST', "/api/v1/programmes/{$mine->id}/approve")->assertOk();

        $this->assertSame(1, DB::table('mda_programme')->where('programme_id', $mine->id)->count());
    }

    public function test_a_rejection_must_say_why(): void
    {
        $mine = $this->mdaProgramme();

        $this->send('sysadmin', 'POST', "/api/v1/programmes/{$mine->id}/reject")
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'decision_note']);

        $this->assertSame(ProgrammeApproval::Pending, $mine->fresh()->approval_status);
    }

    public function test_a_rejected_programme_can_be_corrected_and_submitted_again(): void
    {
        $mine = $this->mdaProgramme();

        $this->send('sysadmin', 'POST', "/api/v1/programmes/{$mine->id}/reject", [
            'decision_note' => 'Name it after the benefit, not the office.',
        ])
            ->assertOk()
            ->assertJsonPath('data.approval_status', 'rejected')
            ->assertJsonPath('data.decision_note', 'Name it after the benefit, not the office.');

        $this->assertDatabaseHas('notifications', [
            'recipient_user_id' => $this->users['mine']->id,
            'type' => 'programme.rejected',
        ]);

        // The MDA may edit its own programme while the decision is open, then offer
        // it again — the reason is cleared with the new submission.
        $this->send('mine', 'PATCH', "/api/v1/programmes/{$mine->id}", ['name' => 'School Feeding'])->assertOk();
        $this->send('mine', 'POST', "/api/v1/programmes/{$mine->id}/submit")
            ->assertOk()
            ->assertJsonPath('data.approval_status', 'pending')
            ->assertJsonPath('data.decision_note', null);
    }

    public function test_an_mda_cannot_edit_its_programme_once_approved(): void
    {
        $mine = $this->mdaProgramme();
        $this->send('sysadmin', 'POST', "/api/v1/programmes/{$mine->id}/approve")->assertOk();

        // Editing an approved programme would quietly undo the decision that was made
        // about it, so the MDA has to ask for a new one.
        $this->send('mine', 'PATCH', "/api/v1/programmes/{$mine->id}", ['name' => 'Something else'])->assertStatus(403);
        $this->assertSame($mine->name, $mine->fresh()->name);
    }

    public function test_an_mda_cannot_touch_another_mdas_programme(): void
    {
        $theirs = Programme::factory()->ownedBy($this->otherMda->id)->pending()->create();

        $this->send('mine', 'PATCH', "/api/v1/programmes/{$theirs->id}", ['name' => 'Mine now'])->assertStatus(404);
        $this->send('mine', 'POST', "/api/v1/programmes/{$theirs->id}/submit")->assertStatus(404);
    }

    public function test_the_decision_cannot_be_smuggled_in_through_an_edit(): void
    {
        $mine = $this->mdaProgramme();

        $this->send('mine', 'PATCH', "/api/v1/programmes/{$mine->id}", [
            'name' => 'Still pending',
            'approval_status' => 'approved',
        ])->assertOk();

        $this->assertSame(ProgrammeApproval::Pending, $mine->fresh()->approval_status);
    }

    /* ------------------------------------------------------------ central catalog */

    public function test_a_central_entry_is_approved_the_moment_it_is_created(): void
    {
        $body = $this->send('coordination', 'POST', '/api/v1/programmes', $this->payload())
            ->assertCreated()
            ->assertJsonPath('data.owner_mda_id', null)
            ->assertJsonPath('data.approval_status', 'approved')
            ->json('data');

        // Nothing to decide about a central entry, so the decision route refuses it.
        $this->send('sysadmin', 'POST', "/api/v1/programmes/{$body['id']}/approve")->assertStatus(403);
    }

    public function test_a_pending_programme_is_not_counted_as_one_of_the_states_programmes(): void
    {
        Programme::factory()->create(['status' => 'active']);       // central, approved
        $this->mdaProgramme(['status' => 'active']);                // waiting on a decision

        $metrics = $this->send('executive', 'GET', '/api/v1/dashboard')->assertOk()->json('data.metrics.programmes');

        $this->assertSame(1, $metrics['total']);
        $this->assertSame(1, $metrics['active']);
    }

    /* ---------------------------------------------------------------- helpers */

    /** @param array<string, mixed> $overrides */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Maternal Cash Support',
            'objective' => 'Support mothers through the first year',
            'type' => 'individual',
            'benefit_category' => 'cash',
            'status' => 'active',
        ], $overrides);
    }

    /** @param array<string, mixed> $attributes */
    private function mdaProgramme(array $attributes = []): Programme
    {
        return Programme::factory()
            ->ownedBy($this->mineMda->id)
            ->pending()
            ->create(array_merge(['created_by' => $this->users['mine']->id], $attributes));
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    /** @param array<string, mixed> $body */
    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }
}
