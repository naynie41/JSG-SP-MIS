<?php

declare(strict_types=1);

namespace Tests\Feature\Access;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ServiceRequest;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The single MDA role (FR-UAM-01).
 *
 * MDA Officer was merged into MDA Admin, which already held a superset of its
 * permissions. This file previously pinned the boundary BETWEEN the two roles; it now
 * pins the shape of the one that remains, because collapsing two roles into the wider
 * of them moves capability toward every MDA user and that has to be deliberate and
 * visible rather than incidental.
 *
 * Two things it asserts above all:
 *  - the MDA role can decide a request-to-serve and export its own beneficiaries —
 *    the capabilities that used to be Admin-only now reach every MDA user;
 *  - it can NOT manage users, because account administration is centralised with the
 *    System Administrator.
 */
class MdaRoleMatrixTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User administration, deliberately withheld from the MDA role. An MDA cannot
     * enrol its own staff or change anyone's role — that is System-Administrator work.
     *
     * @var list<string>
     */
    private const WITHHELD_FROM_MDA = [
        'user.create',
        'user.edit',
        'role.view',
    ];

    /**
     * Capabilities the merge moved to every MDA user. Listed explicitly so a future
     * reader sees this was a decision, not a drift.
     *
     * @var list<string>
     */
    private const MERGED_IN = [
        'beneficiary.approve',        // decide a request-to-serve / ownership transfer
        'beneficiary.export',         // bulk export of citizen PII (SECURITY.md matrix)
        'beneficiary.access_request', // DSAR — the owner MDA is the data controller
    ];

    private Mda $mda;

    private Mda $otherMda;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->otherMda = Mda::factory()->create(['name' => 'Ministry of Education']);

        $this->users['mda'] = $this->user($this->mda, RoleKey::MdaAdmin);
        $this->users['otherMda'] = $this->user($this->otherMda, RoleKey::MdaAdmin);
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    /** @return list<string> */
    private function permissionsOf(RoleKey $role): array
    {
        $keys = Role::where('key', $role->value)->firstOrFail()->permissions->pluck('key')->all();
        sort($keys);

        return $keys;
    }

    /** @param array<string, mixed> $body */
    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /* ------------------------------------------------------- there is ONE MDA role */

    public function test_mda_officer_no_longer_exists(): void
    {
        $this->assertNull(Role::where('key', 'mda_officer')->first(), 'the MDA Officer role must be gone');
        $this->assertSame([], array_filter(
            RoleKey::cases(),
            static fn (RoleKey $case): bool => $case->value === 'mda_officer',
        ));
    }

    public function test_mda_admin_is_the_only_mda_role(): void
    {
        $mdaRoles = Role::query()->where('key', 'like', 'mda%')->pluck('key')->all();

        $this->assertSame([RoleKey::MdaAdmin->value], $mdaRoles);
    }

    public function test_the_merged_capabilities_now_reach_every_mda_user(): void
    {
        $keys = $this->permissionsOf(RoleKey::MdaAdmin);

        // These were Admin-only before the merge. Every MDA user holds them now — which
        // is the substantive consequence of collapsing the roles.
        foreach (self::MERGED_IN as $permission) {
            $this->assertContains($permission, $keys);
        }
    }

    public function test_user_administration_is_withheld_from_the_mda_role(): void
    {
        $keys = $this->permissionsOf(RoleKey::MdaAdmin);

        foreach (self::WITHHELD_FROM_MDA as $permission) {
            $this->assertNotContains($permission, $keys, "user administration is System-Administrator-only: {$permission}");
        }

        // `user.view` stays: an MDA still needs to see who belongs to it.
        $this->assertContains('user.view', $keys);
    }

    public function test_the_mda_role_holds_no_cross_mda_or_reveal_permission(): void
    {
        $keys = $this->permissionsOf(RoleKey::MdaAdmin);

        $this->assertNotContains('cross-mda.view', $keys, 'an MDA never sees outside itself');
        $this->assertNotContains('export.reveal_pii', $keys, 'unmasked identifiers are never an MDA capability');
    }

    /* ------------------------------------------------ what the MDA role can now do */

    public function test_the_mda_role_decides_a_request_to_serve(): void
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id]);
        $request = ServiceRequest::create([
            'beneficiary_id' => $beneficiary->id,
            'from_mda_id' => $this->otherMda->id,
            'to_mda_id' => $this->mda->id,
            'status' => 'pending',
            'reason' => 'Serving under a feeding programme',
        ]);

        $this->send('mda', 'POST', "/api/v1/service-requests/{$request->id}/accept")->assertOk();
        $this->assertSame('accepted', $request->fresh()->status->value);
    }

    public function test_the_decision_still_belongs_to_the_owner_mda_only(): void
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id]);
        $request = ServiceRequest::create([
            'beneficiary_id' => $beneficiary->id,
            'from_mda_id' => $this->otherMda->id,
            'to_mda_id' => $this->mda->id,
            'status' => 'pending',
            'reason' => 'x',
        ]);

        // Holding the permission is not enough — merging the roles did not merge MDAs.
        $this->send('otherMda', 'POST', "/api/v1/service-requests/{$request->id}/accept")->assertStatus(403);
        $this->assertSame('pending', $request->fresh()->status->value);
    }

    public function test_the_mda_role_exports_its_own_beneficiaries_only(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id, 'lga' => 'dutse']);
        Beneficiary::factory()->create(['owner_mda_id' => $this->otherMda->id, 'lga' => 'hadejia']);

        $body = $this->send('mda', 'GET', '/api/v1/beneficiaries/export?format=csv')
            ->assertSuccessful()
            ->streamedContent();

        $this->assertStringContainsString('dutse', $body);
        $this->assertStringNotContainsString('hadejia', $body, 'export inherits MDA scope');
    }

    public function test_the_mda_role_raises_a_data_access_request(): void
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id]);

        $this->send('mda', 'GET', "/api/v1/beneficiaries/{$beneficiary->id}/access-request")->assertSuccessful();
    }

    public function test_the_mda_role_runs_an_aggregate_report(): void
    {
        $this->send('mda', 'POST', '/api/v1/reports/adhoc/preview', [
            'dataset' => 'benefits', 'group_by' => ['lga'], 'measures' => ['count'],
        ])->assertOk();
    }

    /* ------------------------------------------------ what it still cannot do */

    public function test_the_mda_role_cannot_create_or_edit_users(): void
    {
        $roleId = Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id;

        // The centralisation this change exists for: an MDA cannot enrol its own staff.
        $this->send('mda', 'POST', '/api/v1/users', [
            'name' => 'New Staffer',
            'email' => 'new.staffer@example.test',
            'password' => 'ChangeMe!Strong12345',
            'role_id' => $roleId,
            'mda_id' => $this->mda->id,
        ])->assertStatus(403);

        $this->send('mda', 'PATCH', "/api/v1/users/{$this->users['mda']->id}", ['name' => 'Renamed'])
            ->assertStatus(403);
    }

    public function test_the_mda_role_cannot_list_roles(): void
    {
        $this->send('mda', 'GET', '/api/v1/roles')->assertStatus(403);
    }

    public function test_the_mda_role_cannot_administer_the_platform(): void
    {
        $this->send('mda', 'GET', '/api/v1/admin/settings')->assertStatus(403);
        $this->send('mda', 'POST', '/api/v1/notifications/broadcast', ['subject' => 'x', 'body' => 'y'])
            ->assertStatus(403);
    }
}
