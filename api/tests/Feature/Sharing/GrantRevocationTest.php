<?php

declare(strict_types=1);

namespace Tests\Feature\Sharing;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Benefit\Services\DeliveryAuthorization;
use App\Domain\Notification\Models\Notification;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Services\EnrollmentService;
use App\Domain\Registry\Enums\ServiceRequestStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\BeneficiaryServiceGrant;
use App\Domain\Registry\Models\ServiceRequest;
use App\Domain\Registry\Services\ServiceRequestService;
use App\Domain\Sharing\DataSharingGuard;
use App\Domain\Sharing\SharingBasis;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Revoking the cross-MDA read grant opened by an accepted Service Request (FR-OWN-07).
 *
 * `revoked_at` was always honoured by the gates but nothing could set it, so access
 * opened by an acceptance could never be withdrawn. These tests cover the closing of
 * that gap: who may revoke, that all three serve gates deny afterwards, that revocation
 * is forward-only (past deliveries and ownership survive), that it is idempotent, and
 * that it is audited and notified.
 */
class GrantRevocationTest extends TestCase
{
    use RefreshDatabase;

    private Mda $owner;

    private Mda $server;

    private Mda $stranger;

    /** @var array<string, User> */
    private array $users = [];

    private Beneficiary $beneficiary;

    private Programme $programme;

    private Activity $activity;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->owner = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->server = Mda::factory()->create(['name' => 'Ministry of Education']);
        $this->stranger = Mda::factory()->create(['name' => 'Ministry of Works']);

        $this->users['owner'] = $this->user($this->owner, RoleKey::MdaAdmin);
        $this->users['server'] = $this->user($this->server, RoleKey::MdaAdmin);
        $this->users['stranger'] = $this->user($this->stranger, RoleKey::MdaAdmin);
        $this->users['sysAdmin'] = $this->user(null, RoleKey::SystemAdministrator);

        $this->beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->owner->id]);
        $this->programme = Programme::factory()->individual()->create();
        $this->activity = Activity::factory()->forProgramme($this->programme, $this->server)->create();
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

    /** Accept a request-to-serve, producing the grant under test. */
    private function grant(): BeneficiaryServiceGrant
    {
        $request = ServiceRequest::create([
            'beneficiary_id' => $this->beneficiary->id,
            'from_mda_id' => $this->server->id,
            'to_mda_id' => $this->owner->id,
            'status' => ServiceRequestStatus::Pending,
            'reason' => 'Enrolling her in a feeding activity',
        ]);

        app(ServiceRequestService::class)->accept($request, $this->users['owner']);

        // The ACTIVE grant specifically — after a revoke-then-re-grant there are two rows
        // for this pair, and the revoked one must not be mistaken for the live grant.
        return BeneficiaryServiceGrant::query()->withoutGlobalScopes()
            ->where('beneficiary_id', $this->beneficiary->id)
            ->where('mda_id', $this->server->id)
            ->whereNull('revoked_at')
            ->firstOrFail();
    }

    /**
     * The three serve gates' verdicts for the serving MDA.
     *
     * @return array{read: bool, enroll: bool, deliver: bool}
     */
    private function verdicts(): array
    {
        $user = $this->users['server'];
        $beneficiary = $this->beneficiary->fresh();

        return [
            'read' => app(DataSharingGuard::class)->basisFor($user, $beneficiary) !== SharingBasis::None,
            'enroll' => app(EnrollmentService::class)->canServe($user, $beneficiary),
            'deliver' => app(DeliveryAuthorization::class)->basisFor((string) $user->mda_id, $beneficiary) !== null,
        ];
    }

    /* -------------------------------------------- the owner's view of its own grants */

    public function test_the_owner_sees_who_holds_access_to_its_beneficiary(): void
    {
        $grant = $this->grant();

        $rows = $this->send('owner', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}/service-grants")
            ->assertOk()->json('data.grants');

        $this->assertCount(1, $rows);
        $this->assertSame($grant->id, $rows[0]['id']);
        $this->assertSame($this->server->id, $rows[0]['mda']['id']);
        $this->assertSame('Ministry of Education', $rows[0]['mda']['name']);
        $this->assertSame($grant->service_request_id, $rows[0]['service_request_id']);
        $this->assertTrue($rows[0]['active']);
        $this->assertNotNull($rows[0]['granted_at']);
    }

    public function test_the_list_keeps_revoked_grants_as_history(): void
    {
        $grant = $this->grant();
        $this->send('owner', 'POST', "/api/v1/service-grants/{$grant->id}/revoke", ['reason' => 'Episode closed'])
            ->assertOk();

        $rows = $this->send('owner', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}/service-grants")
            ->assertOk()->json('data.grants');

        // Who COULD read this record is as much the owner's business as who can today.
        $this->assertCount(1, $rows);
        $this->assertFalse($rows[0]['active']);
        $this->assertNotNull($rows[0]['revoked_at']);
        $this->assertSame($this->users['owner']->name, $rows[0]['revoked_by']);
        $this->assertSame('Episode closed', $rows[0]['revocation_reason']);
    }

    public function test_a_non_owner_cannot_list_the_grants(): void
    {
        $this->grant();

        // Not even the MDA that HOLDS the grant: knowing who else was given access to a
        // record it does not own is the owner's information, not the grantee's.
        $this->send('server', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}/service-grants")
            ->assertStatus(403);
        $this->send('stranger', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}/service-grants")
            ->assertStatus(403);
    }

    public function test_the_system_administrator_may_list_the_grants(): void
    {
        $this->grant();

        $this->send('sysAdmin', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}/service-grants")
            ->assertOk()->assertJsonCount(1, 'data.grants');
    }

    /* --------------------------------------------------------------- authorization */

    public function test_the_owner_mda_can_revoke_the_grant_it_opened(): void
    {
        $grant = $this->grant();

        $this->send('owner', 'POST', "/api/v1/service-grants/{$grant->id}/revoke", ['reason' => 'Service episode ended'])
            ->assertOk()
            ->assertJsonPath('data.revoked', true);

        $grant->refresh();
        $this->assertNotNull($grant->revoked_at);
        $this->assertSame($this->users['owner']->id, $grant->revoked_by);
        $this->assertSame('Service episode ended', $grant->revocation_reason);
    }

    public function test_the_system_administrator_can_revoke_as_an_override(): void
    {
        $grant = $this->grant();

        $this->send('sysAdmin', 'POST', "/api/v1/service-grants/{$grant->id}/revoke")
            ->assertOk()
            ->assertJsonPath('data.revoked', true);

        $this->assertNotNull($grant->fresh()->revoked_at);
    }

    public function test_the_serving_mda_cannot_revoke_its_own_grant(): void
    {
        $grant = $this->grant();

        // The grant belongs to the serving MDA by scope, but the DECISION belongs to the
        // owner — otherwise the party being restricted could manage its own access.
        $this->send('server', 'POST', "/api/v1/service-grants/{$grant->id}/revoke")->assertStatus(403);

        $this->assertNull($grant->fresh()->revoked_at);
    }

    public function test_an_unrelated_mda_cannot_revoke(): void
    {
        $grant = $this->grant();

        // 403 from the policy, not 404 from the MDA scope — the grant is resolved
        // unscoped on purpose so authorization decides, not visibility.
        $this->send('stranger', 'POST', "/api/v1/service-grants/{$grant->id}/revoke")->assertStatus(403);

        $this->assertNull($grant->fresh()->revoked_at);
    }

    public function test_the_reason_is_optional(): void
    {
        $grant = $this->grant();

        $this->send('owner', 'POST', "/api/v1/service-grants/{$grant->id}/revoke")->assertOk();

        $this->assertNotNull($grant->fresh()->revoked_at);
        $this->assertNull($grant->fresh()->revocation_reason);
    }

    public function test_an_over_long_reason_is_rejected(): void
    {
        $grant = $this->grant();

        $this->send('owner', 'POST', "/api/v1/service-grants/{$grant->id}/revoke", ['reason' => str_repeat('x', 1001)])
            ->assertStatus(422);

        $this->assertNull($grant->fresh()->revoked_at, 'a rejected request must not revoke');
    }

    /* -------------------------------------------------------------- re-granting */

    /**
     * Access can be granted again after being revoked.
     *
     * The partial unique index on `(beneficiary_id, mda_id) WHERE revoked_at IS NULL` is
     * what makes this possible: the revoked row stays as history while the pair is freed.
     * Worth an explicit test because the predicate is easy to lose — a migration that
     * rebuilds the table can silently bring the index back WITHOUT the `WHERE`, at which
     * point a single revocation would bar that MDA from the beneficiary forever.
     */
    public function test_access_can_be_re_granted_after_revocation(): void
    {
        $first = $this->grant();
        $this->send('owner', 'POST', "/api/v1/service-grants/{$first->id}/revoke")->assertOk();
        $this->assertSame(['read' => false, 'enroll' => false, 'deliver' => false], $this->verdicts());

        $second = $this->grant();

        $this->assertNotSame($first->id, $second->id, 'a re-grant is a new row, not a revived one');
        $this->assertSame(['read' => true, 'enroll' => true, 'deliver' => true], $this->verdicts());

        // The revoked grant survives — the history of the first access episode is intact.
        $this->assertNotNull(
            BeneficiaryServiceGrant::query()->withoutGlobalScopes()->find($first->id)?->revoked_at,
        );
        $this->assertSame(
            2,
            BeneficiaryServiceGrant::query()->withoutGlobalScopes()
                ->where('beneficiary_id', $this->beneficiary->id)
                ->where('mda_id', $this->server->id)->count(),
        );
    }

    /* ----------------------------------------------------- enforcement, all 3 gates */

    public function test_all_three_serve_gates_deny_after_revocation(): void
    {
        $grant = $this->grant();
        $this->assertSame(['read' => true, 'enroll' => true, 'deliver' => true], $this->verdicts());

        $this->send('owner', 'POST', "/api/v1/service-grants/{$grant->id}/revoke")->assertOk();

        // Driven through the real endpoint, not a direct column write: every gate reads
        // the grant via hasActiveGrant(), so no gate needed changing — verify, don't add.
        $this->assertSame(['read' => false, 'enroll' => false, 'deliver' => false], $this->verdicts());
    }

    public function test_the_serving_mda_loses_the_read_endpoint(): void
    {
        $grant = $this->grant();
        $this->send('server', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}")->assertOk();

        $this->send('owner', 'POST', "/api/v1/service-grants/{$grant->id}/revoke")->assertOk();

        $this->send('server', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}")->assertStatus(404);
    }

    /* ------------------------------------------------------------ effect boundary */

    public function test_revocation_does_not_delete_interventions_already_recorded(): void
    {
        $grant = $this->grant();

        $outcome = app(EnrollmentService::class)->enroll(
            $this->programme,
            $this->beneficiary,
            $this->activity->id,
            $this->users['server'],
        );
        $this->assertSame('enrolled', $outcome['status'], 'the grant must permit serving before we revoke it');

        $enrollmentsBefore = $this->beneficiary->enrollments()->withoutGlobalScopes()->count();
        $this->assertSame(1, $enrollmentsBefore);

        $this->send('owner', 'POST', "/api/v1/service-grants/{$grant->id}/revoke")->assertOk();

        // The delivery happened under a valid authorization. The ledger is history, not
        // a permission cache — withdrawing access must never rewrite the past.
        $this->assertSame(
            $enrollmentsBefore,
            $this->beneficiary->enrollments()->withoutGlobalScopes()->count(),
            'revocation must not remove work already recorded',
        );
    }

    public function test_revocation_does_not_change_ownership(): void
    {
        $grant = $this->grant();

        $this->send('owner', 'POST', "/api/v1/service-grants/{$grant->id}/revoke")->assertOk();

        $this->assertSame(
            $this->owner->id,
            Beneficiary::query()->withoutGlobalScopes()->findOrFail($this->beneficiary->id)->owner_mda_id,
        );
    }

    public function test_revocation_leaves_the_service_request_accepted(): void
    {
        $grant = $this->grant();

        $this->send('owner', 'POST', "/api/v1/service-grants/{$grant->id}/revoke")->assertOk();

        // It WAS accepted. Rewriting that to `declined` would falsify a decision record.
        $this->assertSame(
            ServiceRequestStatus::Accepted,
            ServiceRequest::query()->withoutGlobalScopes()->findOrFail($grant->service_request_id)->status,
        );
    }

    /* ----------------------------------------------------------------- idempotency */

    public function test_revoking_twice_is_a_no_op_with_a_clear_response(): void
    {
        $grant = $this->grant();

        $this->send('owner', 'POST', "/api/v1/service-grants/{$grant->id}/revoke", ['reason' => 'First'])
            ->assertOk()->assertJsonPath('data.revoked', true);
        $first = $grant->fresh();

        $this->send('owner', 'POST', "/api/v1/service-grants/{$grant->id}/revoke", ['reason' => 'Second'])
            ->assertOk()
            ->assertJsonPath('data.revoked', false)
            ->assertJsonPath('data.message', 'This access was already revoked; nothing changed.');

        // The FIRST withdrawal is the one that took access away — a repeat must not
        // re-stamp the actor, time or reason over the real event.
        $second = $grant->fresh();
        $this->assertEquals($first->revoked_at, $second->revoked_at);
        $this->assertSame('First', $second->revocation_reason);
    }

    public function test_a_repeat_revoke_writes_only_one_audit_entry(): void
    {
        $grant = $this->grant();

        $this->send('owner', 'POST', "/api/v1/service-grants/{$grant->id}/revoke")->assertOk();
        $this->send('owner', 'POST', "/api/v1/service-grants/{$grant->id}/revoke")->assertOk();

        $this->assertSame(1, AuditLog::query()->where('action', 'beneficiary.access_revoked')->count());
    }

    /* ------------------------------------------------------------ audit + notify */

    public function test_the_revocation_is_audited_with_actor_and_context(): void
    {
        $grant = $this->grant();

        $this->send('owner', 'POST', "/api/v1/service-grants/{$grant->id}/revoke", ['reason' => 'Episode closed'])
            ->assertOk();

        $entry = AuditLog::query()->where('action', 'beneficiary.access_revoked')->firstOrFail();

        $this->assertSame($this->users['owner']->id, $entry->actor_id);
        $this->assertSame($this->owner->id, $entry->actor_mda_id);
        $this->assertSame($this->beneficiary->id, $entry->entity_id);
        $this->assertSame($this->server->id, $entry->after['revoked_mda_id'] ?? null);
        $this->assertSame($grant->id, $entry->after['grant_id'] ?? null);
        $this->assertSame('Episode closed', $entry->after['reason'] ?? null);
    }

    public function test_the_serving_mda_is_notified_without_naming_the_beneficiary(): void
    {
        $grant = $this->grant();

        $this->send('owner', 'POST', "/api/v1/service-grants/{$grant->id}/revoke", ['reason' => 'Episode closed'])
            ->assertOk();

        $notifications = Notification::query()->withoutGlobalScopes()
            ->where('type', 'beneficiary.access_revoked')->get();

        $this->assertNotEmpty($notifications, 'the serving MDA must be told its access ended');
        $this->assertSame(
            [$this->server->id],
            $notifications->pluck('recipient_mda_id')->unique()->values()->all(),
            'only the serving MDA is notified — never the owner, and never a third party',
        );

        // As of the revocation the recipient may no longer read the record, so the
        // notification must not restate the identity it just withdrew.
        $text = $notifications->map(fn ($n): string => $n->subject.' '.$n->body)->implode(' ');
        foreach ([$this->beneficiary->first_name, $this->beneficiary->last_name] as $piece) {
            $this->assertStringNotContainsString((string) $piece, $text);
        }
    }
}
