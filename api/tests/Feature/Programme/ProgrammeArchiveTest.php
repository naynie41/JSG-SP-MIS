<?php

declare(strict_types=1);

namespace Tests\Feature\Programme;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Enums\ProgrammeStatus;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Archive-as-delete for the programme catalog (PRD §10).
 *
 * A programme carries activities, enrolments, ledger entries and graduation events,
 * so it is never destroyed. Archiving hides it from the lists people select from and
 * blocks NEW work, while every historical reference keeps resolving.
 */
class ProgrammeArchiveTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $mdaAdmin;

    private Mda $mda;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        Http::fake(['*' => Http::response('', 200)]);

        $this->mda = Mda::factory()->create(['name' => 'MDA A']);
        $roleId = fn (RoleKey $k) => Role::where('key', $k->value)->firstOrFail()->id;

        $this->admin = User::factory()->create(['role_id' => $roleId(RoleKey::SystemAdministrator)]);
        $this->mdaAdmin = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => $roleId(RoleKey::MdaAdmin),
        ]);
    }

    private function token(User $user): string
    {
        return $user->createToken('test')->plainTextToken;
    }

    private function programme(array $attributes = []): Programme
    {
        return Programme::factory()->create($attributes + ['status' => ProgrammeStatus::Active]);
    }

    private function activity(Programme $programme, ActivityStatus $status): Activity
    {
        return Activity::factory()->create([
            'programme_id' => $programme->id,
            'owner_mda_id' => $this->mda->id,
            'status' => $status,
        ]);
    }

    // ------------------------------------------------- the active-activity block

    public function test_archiving_is_blocked_while_an_active_activity_runs(): void
    {
        $programme = $this->programme(['name' => 'Cash Transfer']);
        $this->activity($programme, ActivityStatus::Active);

        $response = $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/programmes/{$programme->id}/archive")
            ->assertStatus(409)
            ->assertJsonPath('error.code', 'PROGRAMME_HAS_ACTIVE_ACTIVITIES');

        // The message must NAME the programme and the blocking work — "some
        // activities exist" is not something an administrator can act on.
        $this->assertStringContainsString('Cash Transfer', $response->json('error.message'));
        $this->assertNotEmpty($response->json('error.details'));

        $this->assertNull($programme->fresh()->archived_at);
    }

    public function test_a_draft_activity_also_blocks_archiving(): void
    {
        // A draft is work an MDA is still preparing; archiving out from under it
        // destroys that preparation.
        $programme = $this->programme();
        $this->activity($programme, ActivityStatus::Draft);

        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/programmes/{$programme->id}/archive")
            ->assertStatus(409);
    }

    public function test_completed_and_archived_activities_do_not_block(): void
    {
        $programme = $this->programme();
        $this->activity($programme, ActivityStatus::Completed);
        $this->activity($programme, ActivityStatus::Archived);

        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/programmes/{$programme->id}/archive")
            ->assertOk();

        $this->assertNotNull($programme->fresh()->archived_at);
    }

    public function test_the_block_sees_other_mdas_activities(): void
    {
        // The blocking activity usually belongs to a DIFFERENT MDA than the catalog
        // admin. If MdaScope applied, the admin would see none, archive, and strand
        // every one of them.
        $otherMda = Mda::factory()->create(['name' => 'MDA B']);
        $programme = $this->programme();
        Activity::factory()->create([
            'programme_id' => $programme->id,
            'owner_mda_id' => $otherMda->id,
            'status' => ActivityStatus::Active,
        ]);

        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/programmes/{$programme->id}/archive")
            ->assertStatus(409);
    }

    // ------------------------------------------------------------ archive effects

    public function test_archiving_records_who_when_and_why(): void
    {
        $programme = $this->programme();

        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/programmes/{$programme->id}/archive", [
                'reason' => 'Superseded by the consolidated cash transfer.',
            ])->assertOk();

        $fresh = $programme->fresh();

        $this->assertNotNull($fresh->archived_at);
        $this->assertSame($this->admin->id, $fresh->archived_by);
        $this->assertSame('Superseded by the consolidated cash transfer.', $fresh->archive_reason);
        $this->assertSame(ProgrammeStatus::Archived, $fresh->status);
    }

    public function test_archived_programmes_drop_out_of_the_catalog_list(): void
    {
        $live = $this->programme(['name' => 'Live Programme']);
        $archived = $this->programme(['name' => 'Archived Programme']);

        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/programmes/{$archived->id}/archive")->assertOk();

        $names = collect($this->withToken($this->token($this->admin))
            ->getJson('/api/v1/programmes')->json('data'))->pluck('name');

        $this->assertContains('Live Programme', $names);
        $this->assertNotContains('Archived Programme', $names);
    }

    public function test_archived_programmes_remain_queryable_in_history(): void
    {
        $programme = $this->programme(['name' => 'Retired Scheme']);

        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/programmes/{$programme->id}/archive")->assertOk();

        // The dedicated archive view...
        $names = collect($this->withToken($this->token($this->admin))
            ->getJson('/api/v1/programmes/archived')->json('data'))->pluck('name');
        $this->assertContains('Retired Scheme', $names);

        // ...and the record itself still resolves, so historical references to it
        // never dangle.
        $this->withToken($this->token($this->admin))
            ->getJson("/api/v1/programmes/{$programme->id}")
            ->assertOk()
            ->assertJsonPath('data.is_archived', true);
    }

    // ------------------------------------------------------------- new work gate

    public function test_an_archived_programme_cannot_take_a_new_activity(): void
    {
        $programme = $this->programme();
        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/programmes/{$programme->id}/archive")->assertOk();

        $this->withToken($this->token($this->mdaAdmin))
            ->postJson('/api/v1/activities', [
                'programme_id' => $programme->id,
                'involves_beneficiaries' => false,
                'name' => 'New Activity',
            ])
            ->assertStatus(422)
            ->assertJsonPath('error.details.0.field', 'programme_id');
    }

    public function test_a_live_programme_still_takes_a_new_activity(): void
    {
        // Guards against the gate being too broad and blocking normal work.
        $programme = $this->programme();

        $this->withToken($this->token($this->mdaAdmin))
            ->postJson('/api/v1/activities', [
                'programme_id' => $programme->id,
                'involves_beneficiaries' => false,
                'name' => 'New Activity',
            ])->assertCreated();
    }

    // ------------------------------------------------------------- no hard delete

    public function test_no_hard_delete_route_exists_for_programmes(): void
    {
        $programme = $this->programme();

        $this->withToken($this->token($this->admin))
            ->deleteJson("/api/v1/programmes/{$programme->id}")
            ->assertStatus(405);

        $this->assertDatabaseHas('programmes', ['id' => $programme->id]);
    }

    public function test_archiving_cannot_be_smuggled_through_a_plain_update(): void
    {
        // The block is worthless if PATCH can set status=archived directly.
        $programme = $this->programme();
        $this->activity($programme, ActivityStatus::Active);

        $this->withToken($this->token($this->admin))
            ->patchJson("/api/v1/programmes/{$programme->id}", [
                'status' => ProgrammeStatus::Archived->value,
            ])->assertStatus(422);

        $this->assertNull($programme->fresh()->archived_at);
        $this->assertNotSame(ProgrammeStatus::Archived, $programme->fresh()->status);
    }

    // ----------------------------------------------------------------- unarchive

    public function test_unarchiving_restores_the_programme_and_is_audited(): void
    {
        $programme = $this->programme();

        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/programmes/{$programme->id}/archive")->assertOk();
        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/programmes/{$programme->id}/unarchive")->assertOk();

        $fresh = $programme->fresh();
        $this->assertNull($fresh->archived_at);
        $this->assertNull($fresh->archived_by);
        // Back to draft, not straight to active — runnability is a deliberate choice.
        $this->assertSame(ProgrammeStatus::Draft, $fresh->status);

        $this->assertDatabaseHas('audit_log', [
            'action' => 'programme.unarchived',
            'entity_id' => $programme->id,
        ]);
    }

    public function test_archive_and_unarchive_are_audited(): void
    {
        $programme = $this->programme();

        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/programmes/{$programme->id}/archive")->assertOk();

        $this->assertDatabaseHas('audit_log', [
            'action' => 'programme.archived',
            'entity_id' => $programme->id,
            'actor_id' => $this->admin->id,
        ]);
    }

    public function test_an_mda_admin_cannot_archive_a_catalog_programme(): void
    {
        // The catalog is global and admin-owned (§10); archiving is a catalog act.
        $programme = $this->programme();

        $this->withToken($this->token($this->mdaAdmin))
            ->postJson("/api/v1/programmes/{$programme->id}/archive")
            ->assertForbidden();

        $this->assertNull($programme->fresh()->archived_at);
    }
}
