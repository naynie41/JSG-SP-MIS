<?php

declare(strict_types=1);

namespace Tests\Feature\Access;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ServiceRequest;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The MDA Officer / MDA Admin permission split, asserted as a matrix.
 *
 * The MDA console ships ONE navigation for both roles, gated per item, which only works
 * if the split is exactly what the console assumes: Officer's permissions are a strict
 * SUBSET of Admin's, and the difference is a known, small set. This test pins that shape
 * so a future permission change cannot silently widen an Officer's reach — and pins the
 * server-side refusal on every route the difference guards, because the UI gate is a
 * courtesy and the route is the boundary (SECURITY.md §3).
 */
class MdaRoleMatrixTest extends TestCase
{
    use RefreshDatabase;

    /**
     * What MDA Admin holds and MDA Officer does not. Anything added here must be a
     * deliberate decision, not a seeder accident.
     *
     * @var list<string>
     */
    private const ADMIN_ONLY = [
        'beneficiary.approve',        // decide a request-to-serve / ownership transfer
        'beneficiary.export',         // bulk export of citizen PII (SECURITY.md matrix)
        'beneficiary.access_request', // DSAR — the owner MDA is the data controller
        'user.create',
        'user.edit',
        'role.view',
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

        $this->users['officer'] = $this->user($this->mda, RoleKey::MdaOfficer);
        $this->users['admin'] = $this->user($this->mda, RoleKey::MdaAdmin);
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
        $keys = Role::where('key', $role->value)->firstOrFail()
            ->permissions->pluck('key')->all();
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

    /* --------------------------------------------------------------- the shape */

    public function test_officer_permissions_are_a_strict_subset_of_admin(): void
    {
        $officer = $this->permissionsOf(RoleKey::MdaOfficer);
        $admin = $this->permissionsOf(RoleKey::MdaAdmin);

        // One nav for both roles is only sound if the Officer can never do something the
        // Admin cannot — otherwise the rail would need to branch by role.
        $this->assertSame([], array_values(array_diff($officer, $admin)), 'an Officer must never hold a permission the Admin lacks');
        $this->assertNotSame($officer, $admin, 'the two roles must not be identical');
    }

    public function test_the_admin_only_difference_is_exactly_the_documented_set(): void
    {
        $difference = array_values(array_diff(
            $this->permissionsOf(RoleKey::MdaAdmin),
            $this->permissionsOf(RoleKey::MdaOfficer),
        ));
        sort($difference);

        $expected = self::ADMIN_ONLY;
        sort($expected);

        $this->assertSame($expected, $difference);
    }

    public function test_neither_mda_role_holds_a_cross_mda_or_reveal_permission(): void
    {
        foreach ([RoleKey::MdaOfficer, RoleKey::MdaAdmin] as $role) {
            $keys = $this->permissionsOf($role);
            // An MDA never sees outside itself, and never exports unmasked identifiers.
            $this->assertNotContains('cross-mda.view', $keys, $role->value.' must not see across MDAs');
            $this->assertNotContains('export.reveal_pii', $keys, $role->value.' must never reveal raw identifiers');
        }
    }

    /* ------------------------------------------------- Service Delivery module */

    public function test_only_an_admin_may_decide_a_request_to_serve(): void
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id]);
        $request = ServiceRequest::create([
            'beneficiary_id' => $beneficiary->id,
            'from_mda_id' => $this->otherMda->id,
            'to_mda_id' => $this->mda->id,
            'status' => 'pending',
            'reason' => 'Serving under a feeding programme',
        ]);

        $this->send('officer', 'POST', "/api/v1/service-requests/{$request->id}/accept")->assertStatus(403);
        $this->send('officer', 'POST', "/api/v1/service-requests/{$request->id}/decline", ['reason' => 'No'])->assertStatus(403);
        $this->assertSame('pending', $request->fresh()->status->value);

        $this->send('admin', 'POST', "/api/v1/service-requests/{$request->id}/accept")->assertOk();
        $this->assertSame('accepted', $request->fresh()->status->value);
    }

    public function test_both_roles_may_see_the_queue_they_cannot_both_action(): void
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id]);
        ServiceRequest::create([
            'beneficiary_id' => $beneficiary->id,
            'from_mda_id' => $this->otherMda->id,
            'to_mda_id' => $this->mda->id,
            'status' => 'pending',
            'reason' => 'x',
        ]);

        // Visibility is shared so the MDA can see its own workload; only the decision is
        // restricted. This is what lets the Overview counter be role-independent.
        foreach (['officer', 'admin'] as $who) {
            $rows = $this->send($who, 'GET', '/api/v1/service-requests/inbox')->assertOk()->json('data.service_requests');
            $this->assertCount(1, $rows);
        }
    }

    /* ------------------------------------------- Beneficiaries / Reports modules */

    public function test_only_an_admin_may_bulk_export_beneficiaries(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id]);

        $this->send('officer', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertStatus(403);
        $this->send('admin', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertSuccessful();
    }

    public function test_both_roles_may_run_an_aggregate_report(): void
    {
        // Aggregate reporting carries no personal record, so it is NOT part of the
        // Admin-only difference — an Officer must be able to do their own analysis.
        foreach (['officer', 'admin'] as $who) {
            $this->send($who, 'POST', '/api/v1/reports/adhoc/preview', [
                'dataset' => 'benefits', 'group_by' => ['lga'], 'measures' => ['count'],
            ])->assertOk();
        }
    }

    public function test_only_an_admin_may_raise_a_data_access_request(): void
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id]);

        $this->send('officer', 'GET', "/api/v1/beneficiaries/{$beneficiary->id}/access-request")->assertStatus(403);
        $this->send('admin', 'GET', "/api/v1/beneficiaries/{$beneficiary->id}/access-request")->assertSuccessful();
    }

    /* ----------------------------------------------- outside the six modules */

    public function test_neither_role_can_administer_the_platform(): void
    {
        // User and role administration is the System Administrator console, not an MDA
        // module — an MDA Admin manages users only through that console's own routes.
        foreach (['officer', 'admin'] as $who) {
            $this->send($who, 'GET', '/api/v1/admin/settings')->assertStatus(403);
            $this->send($who, 'POST', '/api/v1/notifications/broadcast', ['subject' => 'x', 'body' => 'y'])->assertStatus(403);
        }
    }

    public function test_an_officer_cannot_reach_user_management_at_all(): void
    {
        $this->send('officer', 'POST', '/api/v1/users', [
            'name' => 'New', 'email' => 'new@example.test', 'role_id' => Role::where('key', RoleKey::MdaOfficer->value)->firstOrFail()->id,
        ])->assertStatus(403);
    }

    /* ------------------------------------------------------- granting the delta */

    public function test_granting_export_to_the_officer_role_takes_effect_immediately(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id]);
        $this->send('officer', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertStatus(403);

        // The only grant mechanism that exists is role-level (there is no per-user
        // permission table); SECURITY.md's "granted per user" is served by granting the
        // Officer role the permission through the admin console's matrix editor.
        Role::where('key', RoleKey::MdaOfficer->value)->firstOrFail()
            ->permissions()->syncWithoutDetaching([Permission::where('key', 'beneficiary.export')->firstOrFail()->id]);

        $this->send('officer', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertSuccessful();
    }

    public function test_a_grant_never_widens_scope_beyond_the_officers_own_mda(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id, 'lga' => 'dutse']);
        Beneficiary::factory()->create(['owner_mda_id' => $this->otherMda->id, 'lga' => 'hadejia']);

        Role::where('key', RoleKey::MdaOfficer->value)->firstOrFail()
            ->permissions()->syncWithoutDetaching([Permission::where('key', 'beneficiary.export')->firstOrFail()->id]);

        $body = $this->send('officer', 'GET', '/api/v1/beneficiaries/export?format=csv')
            ->assertSuccessful()->streamedContent();

        $this->assertStringContainsString('dutse', $body);
        $this->assertStringNotContainsString('hadejia', $body, 'granting export must not grant reach');
    }
}
