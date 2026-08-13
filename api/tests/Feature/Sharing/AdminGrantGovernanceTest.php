<?php

declare(strict_types=1);

namespace Tests\Feature\Sharing;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\MdaAccessGrant;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Registry\Enums\ConsentStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Sharing\DataSharingGuard;
use App\Domain\Sharing\SharingBasis;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The ADMIN cross-MDA grant ({@see MdaAccessGrant}) as a governed sharing basis
 * (FR-UAM-03, FR-DSH-01).
 *
 * FR-UAM-03 sanctions an explicit cross-MDA grant beyond request-to-serve, so this path
 * is legitimate. FR-DSH-01 requires that all cross-MDA sharing be governed by the
 * ownership + consent rules — which means it has to resolve through the ONE
 * {@see DataSharingGuard} like every other basis, be visible to oversight, and be
 * subject to the consent gate where the DPO requires it.
 *
 * Before this suite the two enforcement layers disagreed: `MdaScope` widened list
 * queries to include a granted MDA's rows, while the guard did not recognise the grant
 * at all — so a grantee could LIST another MDA's beneficiaries but got 404 on the
 * detail, and the grant appeared nowhere in the oversight report.
 */
class AdminGrantGovernanceTest extends TestCase
{
    use RefreshDatabase;

    private Mda $owner;

    private Mda $granted;

    private Mda $outsider;

    /** @var array<string, User> */
    private array $users = [];

    private Beneficiary $beneficiary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->owner = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->granted = Mda::factory()->create(['name' => 'Ministry of Education']);
        $this->outsider = Mda::factory()->create(['name' => 'Ministry of Works']);

        $this->users['owner'] = $this->user($this->owner, RoleKey::MdaAdmin);
        $this->users['grantee'] = $this->user($this->granted, RoleKey::MdaAdmin);
        $this->users['outsider'] = $this->user($this->outsider, RoleKey::MdaAdmin);
        $this->users['oversight'] = $this->user($this->owner, RoleKey::SpCoordination);
        $this->users['sysadmin'] = $this->user(null, RoleKey::SystemAdministrator);

        $this->beneficiary = Beneficiary::factory()->create([
            'owner_mda_id' => $this->owner->id,
            'first_name' => 'Amina',
            'last_name' => 'Bello',
            'sharing_consent' => ConsentStatus::Granted,
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
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /** An admin grant giving the grantee's user access to the OWNER MDA's data. */
    private function grantAdminAccess(?string $expiresAt = null): MdaAccessGrant
    {
        return MdaAccessGrant::create([
            'user_id' => $this->users['grantee']->id,
            'mda_id' => $this->owner->id,
            'granted_by' => $this->users['sysadmin']->id,
            'reason' => 'Joint cash-transfer verification exercise',
            'expires_at' => $expiresAt,
        ]);
    }

    private function guard(): DataSharingGuard
    {
        return app(DataSharingGuard::class);
    }

    private function freshGrantee(): User
    {
        // accessibleMdaIds() memoises per instance; reload so a new grant is seen.
        return User::query()->findOrFail($this->users['grantee']->id);
    }

    /* --------------------------------------------- the basis is named, not invisible */

    public function test_an_admin_grant_resolves_to_its_own_named_basis(): void
    {
        $this->assertSame(
            SharingBasis::None,
            $this->guard()->basisFor($this->freshGrantee(), $this->beneficiary),
            'without a grant there is no basis',
        );

        $this->grantAdminAccess();

        // Every cross-MDA read must be attributable to exactly one basis. Before this,
        // the guard returned None while MdaScope silently allowed the row through.
        $this->assertSame(
            SharingBasis::AdminGrant,
            $this->guard()->basisFor($this->freshGrantee(), $this->beneficiary),
        );
    }

    public function test_the_two_enforcement_layers_agree(): void
    {
        $this->grantAdminAccess();

        // Listing shows the row (MdaScope widened)…
        $names = array_column(
            $this->send('grantee', 'GET', '/api/v1/beneficiaries')->assertOk()->json('data'),
            'first_name',
        );
        $this->assertContains('Amina', $names);

        // …and the detail read must therefore succeed too. A layer that lists a record
        // it will not open is incoherent, and leaks summary PII with no recognised basis.
        $this->send('grantee', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}")->assertOk();
    }

    public function test_an_expired_grant_confers_nothing(): void
    {
        $this->grantAdminAccess(expiresAt: now()->subDay()->toDateTimeString());

        $this->assertSame(SharingBasis::None, $this->guard()->basisFor($this->freshGrantee(), $this->beneficiary));
        $this->send('grantee', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}")->assertNotFound();
    }

    public function test_a_grant_to_one_mda_does_not_open_another(): void
    {
        $this->grantAdminAccess();

        $othersRecord = Beneficiary::factory()->create(['owner_mda_id' => $this->outsider->id]);

        // The grant names ONE MDA; it is not a general cross-MDA key.
        $this->assertSame(SharingBasis::None, $this->guard()->basisFor($this->freshGrantee(), $othersRecord));
        $this->send('grantee', 'GET', "/api/v1/beneficiaries/{$othersRecord->id}")->assertNotFound();
    }

    /* ------------------------------------------------------------- the consent gate */

    public function test_an_admin_grant_is_consent_gated_when_the_dpo_requires_it(): void
    {
        config()->set('sharing.admin_grant_requires_consent', true);
        $this->grantAdminAccess();

        $this->assertSame(SharingBasis::AdminGrant, $this->guard()->basisFor($this->freshGrantee(), $this->beneficiary));

        // Withdrawing consent closes the grant's effect, exactly as for a service grant.
        $this->beneficiary->forceFill(['sharing_consent' => ConsentStatus::Withdrawn])->save();

        $this->assertSame(
            SharingBasis::None,
            $this->guard()->basisFor($this->freshGrantee(), $this->beneficiary->fresh()),
        );
        $this->send('grantee', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}")->assertNotFound();
    }

    public function test_the_consent_gate_can_be_switched_off_by_configuration(): void
    {
        // Whether an administrative grant is consent-gated is a DPO decision, not a
        // hard-coded rule (CLAUDE.md §8) — so it must be switchable both ways.
        config()->set('sharing.admin_grant_requires_consent', false);
        $this->grantAdminAccess();
        $this->beneficiary->forceFill(['sharing_consent' => ConsentStatus::Withdrawn])->save();

        $this->assertSame(
            SharingBasis::AdminGrant,
            $this->guard()->basisFor($this->freshGrantee(), $this->beneficiary->fresh()),
        );
    }

    public function test_owner_and_oversight_are_never_consent_gated(): void
    {
        config()->set('sharing.admin_grant_requires_consent', true);
        $this->beneficiary->forceFill(['sharing_consent' => ConsentStatus::Withdrawn])->save();

        // The owner already holds the data; oversight is a legal M&E mandate.
        $this->send('owner', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}")->assertOk();
        $this->send('oversight', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}")->assertOk();
    }

    /* ------------------------------------------------------------------- oversight */

    public function test_the_oversight_report_shows_admin_grants_with_their_basis(): void
    {
        $this->grantAdminAccess();

        $rows = $this->send('oversight', 'GET', '/api/v1/data-sharing/grants')->assertOk()->json('data.grants');
        $adminRows = array_values(array_filter($rows, static fn (array $r): bool => $r['basis'] === 'admin_grant'));

        // "Who can access what, and why" is incomplete if the widest grant type — a
        // whole-MDA administrative grant — is missing from it.
        $this->assertCount(1, $adminRows, 'an admin grant must appear in the data-sharing report');
        $this->assertSame($this->owner->id, $adminRows[0]['owner_mda']['id']);
        $this->assertSame($this->granted->id, $adminRows[0]['granted_mda']['id']);
        $this->assertSame('Joint cash-transfer verification exercise', $adminRows[0]['reason']);
    }

    public function test_the_report_distinguishes_a_whole_mda_grant_from_a_per_beneficiary_one(): void
    {
        $this->grantAdminAccess();

        $row = collect($this->send('oversight', 'GET', '/api/v1/data-sharing/grants')->assertOk()->json('data.grants'))
            ->firstWhere('basis', 'admin_grant');

        // The scope difference is the whole point: a service grant opens ONE record, an
        // admin grant opens an MDA. An oversight report that blurred them would hide it.
        $this->assertSame('mda', $row['scope']);
        $this->assertNull($row['beneficiary_id'], 'a whole-MDA grant is not about one beneficiary');
    }

    public function test_the_report_is_oversight_only(): void
    {
        $this->grantAdminAccess();

        foreach (['owner', 'grantee', 'outsider'] as $who) {
            $this->send($who, 'GET', '/api/v1/data-sharing/grants')->assertStatus(403);
        }
    }

    /* ----------------------------------------------------------------------- audit */

    public function test_a_cross_mda_read_under_an_admin_grant_is_audited_with_its_basis(): void
    {
        $this->grantAdminAccess();

        $this->send('grantee', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}")->assertOk();

        $entry = AuditLog::query()
            ->where('action', 'beneficiary.cross_mda_read')
            ->latest('id')
            ->first();

        $this->assertNotNull($entry, 'a cross-MDA read must be logged');
        $this->assertSame('admin_grant', $entry->after['basis'] ?? null);
        $this->assertSame($this->users['grantee']->id, $entry->actor_id);

        $json = (string) json_encode($entry->after);
        $this->assertStringNotContainsString('Amina', $json, 'the audit entry must not carry the subject’s PII');
    }

    public function test_an_owner_read_is_not_logged_as_cross_mda(): void
    {
        $this->send('owner', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}")->assertOk();

        // Reading your own MDA's record is not a sharing event; logging it would bury
        // the cross-MDA reads that matter.
        $this->assertDatabaseMissing('audit_log', ['action' => 'beneficiary.cross_mda_read']);
    }
}
