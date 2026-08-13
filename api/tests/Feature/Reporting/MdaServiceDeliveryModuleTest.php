<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Referral\Models\Referral;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ServiceRequest;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\Feature\Access\MdaRoleMatrixTest;
use Tests\Feature\Benefit\BenefitLedgerTest;
use Tests\Feature\Referral\ReferralTest;
use Tests\Feature\Registry\OwnershipTest;
use Tests\TestCase;

/**
 * The MDA console's Service Delivery module, at the level the module itself introduces.
 *
 * The underlying behaviours are covered where they live — the serving-MDA delivery gate
 * in {@see BenefitLedgerTest}, the referral lifecycle in {@see ReferralTest}, and
 * request-to-serve read access in {@see OwnershipTest}. What is asserted here is only
 * what the module newly depends on:
 *
 *  1. The request-to-serve DECISION belongs to the OWNER MDA. Since the Officer/Admin
 *     merge (FR-UAM-01) any of its users may take it; another MDA's never can.
 *  2. The Overview's action-required counters reconcile with the module through the real
 *     API route, not just a direct status write.
 *  3. Sent and received referrals are directional and two-party, so the module's two
 *     tables cannot show each other's work or a third MDA's.
 */
class MdaServiceDeliveryModuleTest extends TestCase
{
    use RefreshDatabase;

    private Mda $owner;

    private Mda $requester;

    private Mda $stranger;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->owner = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->requester = Mda::factory()->create(['name' => 'Ministry of Education']);
        $this->stranger = Mda::factory()->create(['name' => 'Ministry of Works']);

        $this->users['ownerOfficer'] = $this->user($this->owner, RoleKey::MdaAdmin);
        $this->users['ownerAdmin'] = $this->user($this->owner, RoleKey::MdaAdmin);
        $this->users['requesterOfficer'] = $this->user($this->requester, RoleKey::MdaAdmin);
        $this->users['strangerOfficer'] = $this->user($this->stranger, RoleKey::MdaAdmin);
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

    private function pendingRequest(): ServiceRequest
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->owner->id]);

        return ServiceRequest::create([
            'beneficiary_id' => $beneficiary->id,
            'from_mda_id' => $this->requester->id,
            'to_mda_id' => $this->owner->id,
            'status' => 'pending',
            'reason' => 'Enrolling her in a school feeding activity',
        ]);
    }

    /* -------------------------------------------- the approval is Admin-only */

    /**
     * Since the Officer/Admin merge (FR-UAM-01) the decision is no longer split between
     * two MDA roles — ANY user of the owner MDA may take it. What still bounds it is
     * OWNERSHIP, asserted below and in {@see MdaRoleMatrixTest}.
     */
    public function test_any_user_of_the_owner_mda_may_decide_a_request_to_serve(): void
    {
        $accept = $this->pendingRequest();
        $this->send('ownerOfficer', 'POST', "/api/v1/service-requests/{$accept->id}/accept")->assertOk();
        $this->assertSame('accepted', $accept->fresh()->status->value);

        $decline = $this->pendingRequest();
        $this->send('ownerAdmin', 'POST', "/api/v1/service-requests/{$decline->id}/decline", ['reason' => 'Already served'])
            ->assertOk();
        $this->assertSame('declined', $decline->fresh()->status->value);
    }

    public function test_the_owner_mda_admin_can_approve_and_ownership_does_not_move(): void
    {
        $request = $this->pendingRequest();
        $ownerBefore = $request->beneficiary_id;

        $this->send('ownerAdmin', 'POST', "/api/v1/service-requests/{$request->id}/accept")->assertOk();

        $this->assertSame('accepted', $request->fresh()->status->value);
        // FR-OWN-02: accepting opens READ access, it never reassigns the record.
        $this->assertSame(
            $this->owner->id,
            Beneficiary::withoutGlobalScopes()->findOrFail($ownerBefore)->owner_mda_id,
            'accepting a request-to-serve must never transfer ownership',
        );
    }

    public function test_an_admin_of_another_mda_cannot_decide_someone_elses_request(): void
    {
        $request = $this->pendingRequest();
        $requesterAdmin = $this->user($this->requester, RoleKey::MdaAdmin);
        $this->users['requesterAdmin'] = $requesterAdmin;

        // Holding beneficiary.approve is not enough — the decision belongs to the OWNER.
        $this->send('requesterAdmin', 'POST', "/api/v1/service-requests/{$request->id}/accept")
            ->assertStatus(403);

        $this->assertSame('pending', $request->fresh()->status->value);
    }

    /* ------------------------------------------- counters reconcile with the module */

    public function test_approving_through_the_api_clears_the_overview_counter(): void
    {
        $request = $this->pendingRequest();

        $this->send('ownerOfficer', 'GET', '/api/v1/mda/action-required')
            ->assertOk()->assertJsonPath('data.pending_service_requests', 1);

        // Through the real route an Admin actually uses — not a direct status write. The
        // module's queue and the Overview's counter read the same rows, so a decision
        // has to move both.
        $this->send('ownerAdmin', 'POST', "/api/v1/service-requests/{$request->id}/accept")->assertOk();

        $this->send('ownerOfficer', 'GET', '/api/v1/mda/action-required')
            ->assertOk()->assertJsonPath('data.pending_service_requests', 0);
    }

    public function test_the_counter_matches_the_rows_the_module_lists(): void
    {
        $this->pendingRequest();
        $this->pendingRequest();
        // A decided one, which belongs in history but not in the queue.
        $decided = $this->pendingRequest();
        $this->send('ownerAdmin', 'POST', "/api/v1/service-requests/{$decided->id}/decline", ['reason' => 'Already served'])
            ->assertOk();

        $counter = $this->send('ownerOfficer', 'GET', '/api/v1/mda/action-required')
            ->assertOk()->json('data.pending_service_requests');

        $inbox = $this->send('ownerOfficer', 'GET', '/api/v1/service-requests/inbox')
            ->assertOk()->json('data.service_requests');
        $pendingRows = array_values(array_filter($inbox, static fn (array $r): bool => $r['status'] === 'pending'));

        // The number on the Overview is exactly what the module's Pending view shows.
        $this->assertSame(2, $counter);
        $this->assertCount(2, $pendingRows);
        $this->assertCount(3, $inbox, 'history keeps the decided request');
    }

    public function test_accepting_an_inbound_referral_clears_its_counter(): void
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->requester->id]);
        $referral = Referral::create([
            'beneficiary_id' => $beneficiary->id,
            'from_mda_id' => $this->requester->id,
            'to_mda_id' => $this->owner->id,
            'need' => 'Health service',
            'status' => 'created',
        ]);

        $this->send('ownerOfficer', 'GET', '/api/v1/mda/action-required')
            ->assertOk()->assertJsonPath('data.pending_referrals', 1);

        $this->send('ownerOfficer', 'POST', "/api/v1/referrals/{$referral->id}/accept")->assertOk();

        // Accepted is no longer "awaiting your response" — the ball has moved.
        $this->send('ownerOfficer', 'GET', '/api/v1/mda/action-required')
            ->assertOk()->assertJsonPath('data.pending_referrals', 0);
    }

    /* --------------------------------------------------- referrals are directional */

    public function test_sent_and_received_are_separate_directions_of_the_same_endpoint(): void
    {
        $mine = Beneficiary::factory()->create(['owner_mda_id' => $this->owner->id]);

        // Received by the owner MDA.
        Referral::create(['beneficiary_id' => $mine->id, 'from_mda_id' => $this->requester->id, 'to_mda_id' => $this->owner->id, 'need' => 'Health service', 'status' => 'created']);
        // Sent by the owner MDA.
        Referral::create(['beneficiary_id' => $mine->id, 'from_mda_id' => $this->owner->id, 'to_mda_id' => $this->requester->id, 'need' => 'Nutrition support', 'status' => 'created']);

        $incoming = $this->send('ownerOfficer', 'GET', '/api/v1/referrals?filter[direction]=incoming')->assertOk()->json('data');
        $outgoing = $this->send('ownerOfficer', 'GET', '/api/v1/referrals?filter[direction]=outgoing')->assertOk()->json('data');

        $this->assertSame(['Health service'], array_column($incoming, 'need'));
        $this->assertSame(['Nutrition support'], array_column($outgoing, 'need'));
    }

    public function test_a_third_mda_sees_neither_side_of_a_referral(): void
    {
        $mine = Beneficiary::factory()->create(['owner_mda_id' => $this->owner->id]);
        Referral::create(['beneficiary_id' => $mine->id, 'from_mda_id' => $this->requester->id, 'to_mda_id' => $this->owner->id, 'need' => 'Health service', 'status' => 'created']);

        // A referral is visible to its two parties only (ReferralScope).
        $this->assertSame([], $this->send('strangerOfficer', 'GET', '/api/v1/referrals')->assertOk()->json('data'));
        $this->send('strangerOfficer', 'GET', '/api/v1/mda/action-required')
            ->assertOk()->assertJsonPath('data.pending_referrals', 0);
    }

    public function test_a_referral_never_transfers_ownership(): void
    {
        $mine = Beneficiary::factory()->create(['owner_mda_id' => $this->owner->id]);
        $referral = Referral::create([
            'beneficiary_id' => $mine->id,
            'from_mda_id' => $this->owner->id,
            'to_mda_id' => $this->requester->id,
            'need' => 'Health service',
            'status' => 'created',
        ]);

        // Drive it all the way to completion from the receiving side.
        $this->send('requesterOfficer', 'POST', "/api/v1/referrals/{$referral->id}/accept")->assertOk();
        $this->send('requesterOfficer', 'POST', "/api/v1/referrals/{$referral->id}/start")->assertOk();
        $this->send('requesterOfficer', 'POST', "/api/v1/referrals/{$referral->id}/complete")->assertOk();

        $this->assertSame(
            $this->owner->id,
            Beneficiary::withoutGlobalScopes()->findOrFail($mine->id)->owner_mda_id,
            'a completed referral must leave ownership with the referring MDA',
        );
    }
}
