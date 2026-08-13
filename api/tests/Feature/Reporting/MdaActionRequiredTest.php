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
use Tests\TestCase;

/**
 * The MDA console's action-required counters. Two properties matter and neither is
 * expressible through the Phase 6 snapshot: the counts are DIRECTIONAL (work awaiting
 * THIS MDA, not work it is merely party to) and LIVE (a 15-minute-stale work queue is
 * misleading). They are counts only — no beneficiary data rides along.
 */
class MdaActionRequiredTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['adminA'] = $this->user($this->mdaA, RoleKey::MdaAdmin);
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaOfficer);
        $this->users['exec'] = $this->user(null, RoleKey::Executive);
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function send(string $key, string $url = '/api/v1/mda/action-required'): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->getJson($url);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function beneficiaryOwnedBy(Mda $mda): Beneficiary
    {
        return Beneficiary::factory()->create(['owner_mda_id' => $mda->id]);
    }

    /* ------------------------------------------------------------- referrals */

    public function test_counts_only_referrals_awaiting_this_mda(): void
    {
        $ben = $this->beneficiaryOwnedBy($this->mdaA);

        // Inbound to A, still open → counts for A.
        Referral::create(['beneficiary_id' => $ben->id, 'from_mda_id' => $this->mdaB->id, 'to_mda_id' => $this->mdaA->id, 'need' => 'Health', 'status' => 'created']);
        Referral::create(['beneficiary_id' => $ben->id, 'from_mda_id' => $this->mdaB->id, 'to_mda_id' => $this->mdaA->id, 'need' => 'Cash', 'status' => 'more_info_requested']);

        // OUTBOUND from A → A is party to it, but the ball is not in A's court.
        Referral::create(['beneficiary_id' => $ben->id, 'from_mda_id' => $this->mdaA->id, 'to_mda_id' => $this->mdaB->id, 'need' => 'Skills', 'status' => 'created']);

        // Inbound to A but already resolved → not outstanding.
        Referral::create(['beneficiary_id' => $ben->id, 'from_mda_id' => $this->mdaB->id, 'to_mda_id' => $this->mdaA->id, 'need' => 'Food', 'status' => 'completed']);

        $this->send('officerA')->assertOk()->assertJsonPath('data.pending_referrals', 2);

        // B sees only the one A sent it.
        $this->send('officerB')->assertOk()->assertJsonPath('data.pending_referrals', 1);
    }

    /* ------------------------------------------------------ service requests */

    public function test_counts_only_pending_service_requests_this_mda_must_decide(): void
    {
        $ownedByA = $this->beneficiaryOwnedBy($this->mdaA);
        $ownedByB = $this->beneficiaryOwnedBy($this->mdaB);

        // B asks to serve A's beneficiary → awaiting A.
        ServiceRequest::create(['beneficiary_id' => $ownedByA->id, 'from_mda_id' => $this->mdaB->id, 'to_mda_id' => $this->mdaA->id, 'status' => 'pending', 'reason' => 'Cash top-up']);
        // Already decided → not outstanding.
        ServiceRequest::create(['beneficiary_id' => $ownedByA->id, 'from_mda_id' => $this->mdaB->id, 'to_mda_id' => $this->mdaA->id, 'status' => 'accepted', 'reason' => 'Done']);
        // A asks to serve B's beneficiary → awaiting B, not A.
        ServiceRequest::create(['beneficiary_id' => $ownedByB->id, 'from_mda_id' => $this->mdaA->id, 'to_mda_id' => $this->mdaB->id, 'status' => 'pending', 'reason' => 'Skills']);

        $this->send('officerA')->assertOk()->assertJsonPath('data.pending_service_requests', 1);
        $this->send('officerB')->assertOk()->assertJsonPath('data.pending_service_requests', 1);
    }

    /* ------------------------------------------------------------ scope + shape */

    public function test_an_mda_never_sees_another_mdas_queue(): void
    {
        $ownedByB = $this->beneficiaryOwnedBy($this->mdaB);
        ServiceRequest::create(['beneficiary_id' => $ownedByB->id, 'from_mda_id' => $this->mdaA->id, 'to_mda_id' => $this->mdaB->id, 'status' => 'pending', 'reason' => 'x']);
        Referral::create(['beneficiary_id' => $ownedByB->id, 'from_mda_id' => $this->mdaA->id, 'to_mda_id' => $this->mdaB->id, 'need' => 'Health', 'status' => 'created']);

        // Everything above is B's work. A's queue is empty.
        $this->send('officerA')->assertOk()
            ->assertJsonPath('data.pending_referrals', 0)
            ->assertJsonPath('data.pending_service_requests', 0);
    }

    public function test_officer_and_admin_of_the_same_mda_see_the_same_queue(): void
    {
        $ben = $this->beneficiaryOwnedBy($this->mdaA);
        ServiceRequest::create(['beneficiary_id' => $ben->id, 'from_mda_id' => $this->mdaB->id, 'to_mda_id' => $this->mdaA->id, 'status' => 'pending', 'reason' => 'x']);

        // The COUNT is a shared view of the MDA's workload; only acting on it needs
        // beneficiary.approve, which the officer does not hold.
        $this->send('officerA')->assertOk()->assertJsonPath('data.pending_service_requests', 1);
        $this->send('adminA')->assertOk()->assertJsonPath('data.pending_service_requests', 1);

        $this->assertFalse($this->users['officerA']->fresh()->hasPermission('beneficiary.approve'));
        $this->assertTrue($this->users['adminA']->fresh()->hasPermission('beneficiary.approve'));
    }

    public function test_a_user_without_an_mda_has_an_empty_queue(): void
    {
        $this->send('exec')->assertOk()
            ->assertJsonPath('data.pending_referrals', 0)
            ->assertJsonPath('data.pending_service_requests', 0)
            ->assertJsonPath('data.mda_id', null);
    }

    public function test_the_response_carries_counts_only_and_no_beneficiary_data(): void
    {
        $ben = $this->beneficiaryOwnedBy($this->mdaA);
        ServiceRequest::create(['beneficiary_id' => $ben->id, 'from_mda_id' => $this->mdaB->id, 'to_mda_id' => $this->mdaA->id, 'status' => 'pending', 'reason' => 'x']);

        $json = (string) json_encode($this->send('officerA')->assertOk()->json());

        // Sizing the queue must not drag PII onto the Overview.
        foreach ([$ben->id, 'first_name', 'last_name', 'nin', 'bvn', 'phone', 'beneficiary'] as $leak) {
            $this->assertStringNotContainsString((string) $leak, $json);
        }
    }

    public function test_it_reflects_a_decision_immediately(): void
    {
        $ben = $this->beneficiaryOwnedBy($this->mdaA);
        $request = ServiceRequest::create(['beneficiary_id' => $ben->id, 'from_mda_id' => $this->mdaB->id, 'to_mda_id' => $this->mdaA->id, 'status' => 'pending', 'reason' => 'x']);

        $this->send('officerA')->assertOk()->assertJsonPath('data.pending_service_requests', 1);

        // Live, not snapshotted: the counter must move the moment the work is done.
        $request->forceFill(['status' => 'accepted'])->save();

        $this->send('officerA')->assertOk()->assertJsonPath('data.pending_service_requests', 0);
    }
}
