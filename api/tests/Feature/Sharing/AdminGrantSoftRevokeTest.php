<?php

declare(strict_types=1);

namespace Tests\Feature\Sharing;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\MdaAccessGrant;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Sharing\DataSharingGuard;
use App\Domain\Sharing\SharingBasis;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * A cross-MDA access grant is REVOKED, never deleted (NFR-PRV-01, FR-AUD-01).
 *
 * Revoking used to hard-delete the row. That erased the evidence that the access had
 * ever existed: an auditor asking "did anyone outside this MDA hold access to these
 * records, and when did it end?" would find nothing — and nothing would distinguish that
 * from access never having been granted at all. For a trail whose entire purpose is
 * accounting for who could read citizens' data, deleting the record of access is the
 * one thing it must not do.
 *
 * The history is therefore retained and the row is treated as INACTIVE instead. That
 * makes the gates' behaviour the load-bearing part: a retained row must never keep
 * opening access.
 */
class AdminGrantSoftRevokeTest extends TestCase
{
    use RefreshDatabase;

    private Mda $ownerMda;

    private Mda $otherMda;

    /** @var array<string, User> */
    private array $users = [];

    private Beneficiary $beneficiary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->ownerMda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->otherMda = Mda::factory()->create(['name' => 'Ministry of Education']);

        $this->users['admin'] = $this->user(null, RoleKey::SystemAdministrator);
        $this->users['outsider'] = $this->user($this->otherMda, RoleKey::MdaAdmin);

        $this->beneficiary = Beneficiary::factory()->create([
            'owner_mda_id' => $this->ownerMda->id,
            'sharing_consent' => 'granted',
        ]);
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
        $this->app['auth']->forgetGuards();

        return $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
    }

    /** Grant the outsider access to the owner MDA, through the real endpoint. */
    private function grant(): MdaAccessGrant
    {
        $this->send('admin', 'POST', '/api/v1/mda-access-grants', [
            'user_id' => $this->users['outsider']->id,
            'mda_id' => $this->ownerMda->id,
            'reason' => 'M&E review',
        ])->assertCreated();

        return MdaAccessGrant::query()
            ->where('user_id', $this->users['outsider']->id)
            ->whereNull('revoked_at')
            ->firstOrFail();
    }

    private function revoke(MdaAccessGrant $grant, array $body = []): TestResponse
    {
        return $this->send('admin', 'DELETE', "/api/v1/mda-access-grants/{$grant->id}", $body);
    }

    /** Can the outsider still read the owner MDA's beneficiary? */
    private function canRead(): bool
    {
        $user = $this->users['outsider']->fresh();

        return app(DataSharingGuard::class)->basisFor($user, $this->beneficiary->fresh()) !== SharingBasis::None;
    }

    /* ----------------------------------------------------- the row is retained */

    public function test_revoking_keeps_the_grant_row_with_its_revocation_recorded(): void
    {
        $grant = $this->grant();

        $this->revoke($grant, ['reason' => 'Review concluded'])->assertOk();

        // The row survives — this is the evidence that access existed and ended.
        $this->assertDatabaseHas('mda_access_grants', ['id' => $grant->id]);

        $fresh = $grant->fresh();
        $this->assertNotNull($fresh->revoked_at);
        $this->assertSame($this->users['admin']->id, $fresh->revoked_by);
        $this->assertSame('Review concluded', $fresh->revocation_reason);
    }

    public function test_the_full_history_stays_queryable_after_revocation(): void
    {
        $grant = $this->grant();
        $this->revoke($grant, ['reason' => 'Review concluded'])->assertOk();

        $row = collect($this->send('admin', 'GET', '/api/v1/mda-access-grants')->assertOk()->json('data.grants'))
            ->firstWhere('id', $grant->id);

        // Granted when/by/why AND revoked when/by/why — the whole episode, not just its end.
        $this->assertNotNull($row['granted_at']);
        $this->assertSame($this->users['admin']->name, $row['granted_by']);
        $this->assertSame('M&E review', $row['reason']);
        $this->assertNotNull($row['revoked_at']);
        $this->assertSame($this->users['admin']->name, $row['revoked_by']);
        $this->assertSame('Review concluded', $row['revocation_reason']);
    }

    public function test_a_revoked_grant_never_reports_as_active(): void
    {
        $grant = $this->grant();
        $this->revoke($grant)->assertOk();

        $row = collect($this->send('admin', 'GET', '/api/v1/mda-access-grants')->assertOk()->json('data.grants'))
            ->firstWhere('id', $grant->id);

        // Computed from the model, not from expires_at — a grant with no expiry would
        // otherwise still read as live after being withdrawn.
        $this->assertFalse($row['active']);
        $this->assertFalse($grant->fresh()->isActive());
    }

    /* ------------------------------------------------- the gates deny on the row */

    public function test_the_read_gate_denies_once_the_grant_is_revoked(): void
    {
        $grant = $this->grant();
        $this->assertTrue($this->canRead(), 'the grant should open access while active');

        $this->revoke($grant)->assertOk();

        // The load-bearing assertion: a RETAINED row must not keep opening access.
        $this->assertFalse($this->canRead());
    }

    public function test_the_revoked_mda_drops_out_of_the_users_accessible_scope(): void
    {
        $grant = $this->grant();
        $this->assertContains($this->ownerMda->id, $this->users['outsider']->fresh()->accessibleMdaIds());

        $this->revoke($grant)->assertOk();

        // `accessibleMdaIds()` previously relied on the row being GONE. Retaining it
        // without teaching the scope about `revoked_at` would have silently preserved
        // cross-MDA access — the exact opposite of revoking it.
        $this->assertNotContains($this->ownerMda->id, $this->users['outsider']->fresh()->accessibleMdaIds());
    }

    public function test_a_revoked_grant_disappears_from_the_oversight_view(): void
    {
        $grant = $this->grant();
        $this->revoke($grant)->assertOk();

        $grants = $this->send('admin', 'GET', '/api/v1/data-sharing/grants')->assertOk()->json('data.grants');

        // That report answers "who can read this today", not "who ever could".
        $this->assertNotContains($grant->id, array_column($grants, 'id'));
    }

    /* ------------------------------------------------------------- re-granting */

    public function test_re_granting_creates_a_new_row_and_keeps_the_old_one(): void
    {
        $first = $this->grant();
        $this->revoke($first, ['reason' => 'First episode ended'])->assertOk();

        $second = $this->grant();

        // A new grant, not a resurrected one — so the timeline reads as two separate
        // access episodes rather than one continuous grant that never ended.
        $this->assertNotSame($first->id, $second->id);
        $this->assertNotNull($first->fresh()->revoked_at);
        $this->assertNull($second->revoked_at);
        $this->assertSame(
            2,
            MdaAccessGrant::query()->where('user_id', $this->users['outsider']->id)->count(),
        );

        // …and access is genuinely restored.
        $this->assertTrue($this->canRead());
    }

    /* ------------------------------------------------------------ idempotency */

    public function test_revoking_twice_changes_nothing(): void
    {
        $grant = $this->grant();
        $this->revoke($grant, ['reason' => 'First'])->assertOk()->assertJsonPath('data.revoked', true);
        $first = $grant->fresh();

        $this->revoke($grant, ['reason' => 'Second'])
            ->assertOk()
            ->assertJsonPath('data.revoked', false);

        // The FIRST withdrawal ended the access; a repeat must not overwrite that fact.
        $second = $grant->fresh();
        $this->assertEquals($first->revoked_at, $second->revoked_at);
        $this->assertSame('First', $second->revocation_reason);
    }

    /* ----------------------------------------------------------------- audit */

    public function test_the_revocation_is_audited(): void
    {
        $grant = $this->grant();
        $this->revoke($grant, ['reason' => 'Review concluded'])->assertOk();

        $this->assertDatabaseHas('audit_log', ['action' => 'cross_mda.revoked']);
        $this->assertSame(
            1,
            AuditLog::query()->where('action', 'cross_mda.revoked')->count(),
            'one withdrawal, one entry',
        );
    }

    public function test_a_repeat_revoke_writes_no_second_audit_entry(): void
    {
        $grant = $this->grant();
        $this->revoke($grant)->assertOk();
        $this->revoke($grant)->assertOk();

        $this->assertSame(1, AuditLog::query()->where('action', 'cross_mda.revoked')->count());
    }

    /* ------------------------------------------------------- no hard-delete path */

    public function test_no_access_grant_is_ever_hard_deleted(): void
    {
        $grant = $this->grant();
        $this->revoke($grant)->assertOk();

        // Guards the whole point of this change: if a future edit reintroduces a
        // `delete()` on the revoke path, the row vanishes and this fails.
        $this->assertSame(1, MdaAccessGrant::query()->count());
    }
}
