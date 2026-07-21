<?php

declare(strict_types=1);

namespace Tests\Feature\Sharing;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Enums\ConsentStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ServiceRequest;
use App\Domain\Registry\Services\ServiceRequestService;
use App\Domain\Sharing\DataSharingGuard;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Cross-MDA data-sharing governance (FR-DSH-01, NFR-PRV-01): every cross-MDA read/serve
 * resolves through the ONE {@see DataSharingGuard} (owner / oversight / consent-gated
 * Service-Request grant); consent is captured, enforced, and audited; ungoverned access
 * is denied and logged; oversight can see who can access what and why.
 */
class DataSharingGovernanceTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA; // owner

    private Mda $mdaB; // granted requester

    private Mda $mdaC; // outsider (no grant)

    /** @var array<string, User> */
    private array $users = [];

    private Beneficiary $beneficiary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);
        $this->mdaC = Mda::factory()->create(['name' => 'MDA C']);

        $this->users['ownerA'] = $this->user($this->mdaA, RoleKey::MdaAdmin);   // can edit + record consent
        $this->users['requesterB'] = $this->user($this->mdaB, RoleKey::MdaOfficer);
        $this->users['outsiderC'] = $this->user($this->mdaC, RoleKey::MdaOfficer);
        $this->users['oversight'] = $this->user($this->mdaA, RoleKey::SpCoordination); // cross-mda.view

        $this->beneficiary = Beneficiary::factory()->create([
            'owner_mda_id' => $this->mdaA->id,
            'first_name' => 'Amina',
            'last_name' => 'Bello',
            'sharing_consent' => ConsentStatus::Granted,
        ]);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /** Open a real read/serve grant for the requesting MDA via an accepted Service Request. */
    private function grantToRequesterB(): void
    {
        $sr = ServiceRequest::create([
            'beneficiary_id' => $this->beneficiary->id,
            'from_mda_id' => $this->mdaB->id,
            'to_mda_id' => $this->mdaA->id,
            'status' => 'pending',
            'requested_by' => $this->users['requesterB']->id,
        ]);
        app(ServiceRequestService::class)->accept($sr, $this->users['ownerA']);
        $this->app['auth']->forgetGuards();
    }

    /* ------------------------------------------------------------ governed reads */

    public function test_owner_and_oversight_read_the_full_record(): void
    {
        $this->send('ownerA', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}")
            ->assertOk()->assertJsonPath('data.full_name', 'Amina Bello');

        $this->send('oversight', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}")
            ->assertOk()->assertJsonPath('data.id', $this->beneficiary->id);
    }

    public function test_granted_mda_reads_only_while_consent_is_satisfied(): void
    {
        $this->grantToRequesterB();

        // Grant + consent granted → the requester reads the full record.
        $this->send('requesterB', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}")->assertOk();

        // Withdraw consent → the SAME grant is now ineffective (consent gate).
        $this->beneficiary->update(['sharing_consent' => ConsentStatus::Withdrawn]);
        $this->send('requesterB', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}")->assertStatus(404);
    }

    /* -------------------------------------------------- ungoverned access denied */

    public function test_ungoverned_cross_mda_read_is_denied_and_logged(): void
    {
        // MDA C has no ownership, no oversight, no grant → denied (404, not leaked) + logged.
        $this->send('outsiderC', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}")->assertStatus(404);

        $this->assertDatabaseHas('audit_log', [
            'action' => 'beneficiary.access_denied',
            'entity_id' => $this->beneficiary->id,
            'actor_id' => $this->users['outsiderC']->id,
        ]);
    }

    /* ------------------------------------------------------------ serve consent gate */

    public function test_serving_via_grant_requires_consent(): void
    {
        $this->grantToRequesterB();
        $guard = app(DataSharingGuard::class);

        $this->assertTrue($guard->mdaMayServeViaGrant($this->mdaB->id, $this->beneficiary->fresh()));

        $this->beneficiary->update(['sharing_consent' => ConsentStatus::Withdrawn]);
        $this->assertFalse($guard->mdaMayServeViaGrant($this->mdaB->id, $this->beneficiary->fresh()));

        // A grantless MDA is never authorized to serve.
        $this->assertFalse($guard->mdaMayServeViaGrant($this->mdaC->id, $this->beneficiary->fresh()));
    }

    /* --------------------------------------------------------------- consent lifecycle */

    public function test_owner_records_and_withdraws_consent_with_audit_and_history(): void
    {
        $id = $this->beneficiary->id;

        $this->send('ownerA', 'PUT', "/api/v1/beneficiaries/{$id}/consent", ['status' => 'granted', 'basis' => 'Signed consent form'])
            ->assertOk()->assertJsonPath('data.sharing_consent', 'granted');
        $this->assertDatabaseHas('audit_log', ['action' => 'consent.recorded', 'entity_id' => $id]);
        $this->assertDatabaseHas('beneficiary_consents', ['beneficiary_id' => $id, 'status' => 'granted']);

        $this->send('ownerA', 'PUT', "/api/v1/beneficiaries/{$id}/consent", ['status' => 'withdrawn'])
            ->assertOk()->assertJsonPath('data.sharing_consent', 'withdrawn');
        $this->assertDatabaseHas('audit_log', ['action' => 'consent.withdrawn', 'entity_id' => $id]);
    }

    public function test_only_the_owner_may_record_consent(): void
    {
        // A non-owner (even one holding a serve grant) cannot change consent.
        $this->grantToRequesterB();
        $this->send('requesterB', 'PUT', "/api/v1/beneficiaries/{$this->beneficiary->id}/consent", ['status' => 'withdrawn'])
            ->assertStatus(403);
    }

    /* ---------------------------------------------------------- data-sharing oversight */

    public function test_oversight_sees_who_can_access_what_and_why(): void
    {
        $this->grantToRequesterB();

        $this->send('oversight', 'GET', '/api/v1/data-sharing/grants')
            ->assertOk()
            ->assertJsonPath('data.0.basis', 'service_request')
            ->assertJsonPath('data.0.granted_mda.name', 'MDA B')
            ->assertJsonPath('data.0.owner_mda.name', 'MDA A')
            ->assertJsonPath('data.0.consent.status', 'granted')
            ->assertJsonPath('data.0.consent.effective', true);

        // Not an oversight surface for an ordinary MDA officer.
        $this->send('requesterB', 'GET', '/api/v1/data-sharing/grants')->assertStatus(403);
    }
}
