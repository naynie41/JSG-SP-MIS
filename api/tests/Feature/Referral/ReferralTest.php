<?php

declare(strict_types=1);

namespace Tests\Feature\Referral;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Referral\Models\Referral;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Referral creation + lifecycle (PRD FR-REF-01/02/04, §8.2): create; receiving-MDA
 * accept/reject(reason)/request-info; originating respond; working states; transition
 * guards; two-party scoping; and per-transition timestamp + audit.
 */
class ReferralTest extends TestCase
{
    use RefreshDatabase;

    private Mda $fromMda; // originating

    private Mda $toMda;   // receiving

    private Mda $otherMda;

    /** @var array<string, User> */
    private array $users = [];

    private Beneficiary $beneficiary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->fromMda = Mda::factory()->create(['name' => 'From MDA']);
        $this->toMda = Mda::factory()->create(['name' => 'To MDA']);
        $this->otherMda = Mda::factory()->create(['name' => 'Other MDA']);

        $this->users['from'] = $this->user($this->fromMda, RoleKey::MdaOfficer);
        $this->users['to'] = $this->user($this->toMda, RoleKey::MdaOfficer);
        $this->users['other'] = $this->user($this->otherMda, RoleKey::MdaOfficer);
        $this->users['oversight'] = $this->user($this->otherMda, RoleKey::Executive); // cross-mda.view

        $this->beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->fromMda->id]);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function createReferral(): string
    {
        return $this->send('from', 'POST', '/api/v1/referrals', [
            'beneficiary_id' => $this->beneficiary->id,
            'to_mda_id' => $this->toMda->id,
            'need' => 'Cash transfer support',
            'notes' => 'Vulnerable household',
        ])->assertCreated()->json('data.id');
    }

    public function test_originating_mda_creates_a_referral_and_both_parties_see_it(): void
    {
        $id = $this->createReferral();

        $this->assertDatabaseHas('referrals', [
            'id' => $id, 'from_mda_id' => $this->fromMda->id, 'to_mda_id' => $this->toMda->id,
            'status' => 'created', 'need' => 'Cash transfer support',
        ]);
        $this->assertDatabaseHas('audit_log', ['action' => 'referral.created', 'entity_id' => $id]);

        // Both parties + oversight can see it; an unrelated MDA cannot.
        $this->send('from', 'GET', "/api/v1/referrals/{$id}")->assertOk()->assertJsonPath('data.status', 'created');
        $this->send('to', 'GET', "/api/v1/referrals/{$id}")->assertOk();
        $this->send('oversight', 'GET', "/api/v1/referrals/{$id}")->assertOk();
        $this->send('other', 'GET', "/api/v1/referrals/{$id}")->assertStatus(404);
    }

    public function test_cannot_refer_to_your_own_mda(): void
    {
        $this->send('from', 'POST', '/api/v1/referrals', [
            'beneficiary_id' => $this->beneficiary->id,
            'to_mda_id' => $this->fromMda->id, // same MDA
            'need' => 'x',
        ])->assertStatus(422)->assertJsonPath('error.code', 'REFERRAL_INVALID');
    }

    public function test_receiving_mda_accepts_then_works_the_referral_with_timestamps_and_audit(): void
    {
        $id = $this->createReferral();

        $this->send('to', 'POST', "/api/v1/referrals/{$id}/accept")
            ->assertOk()->assertJsonPath('data.status', 'accepted');
        $this->assertNotNull(Referral::query()->withoutGlobalScopes()->find($id)->accepted_at);
        $this->assertDatabaseHas('audit_log', ['action' => 'referral.accepted', 'entity_id' => $id]);

        $this->send('to', 'POST', "/api/v1/referrals/{$id}/start")->assertOk()->assertJsonPath('data.status', 'in_progress');
        $this->send('to', 'POST', "/api/v1/referrals/{$id}/complete", ['outcome' => 'Enrolled in CCT'])
            ->assertOk()->assertJsonPath('data.status', 'completed')->assertJsonPath('data.outcome', 'Enrolled in CCT');

        $model = Referral::query()->withoutGlobalScopes()->findOrFail($id);
        $this->assertNotNull($model->started_at);
        $this->assertNotNull($model->completed_at);
        $this->assertDatabaseHas('audit_log', ['action' => 'referral.completed', 'entity_id' => $id]);
    }

    public function test_receiving_mda_rejects_with_a_required_reason(): void
    {
        $id = $this->createReferral();

        // Reason is required.
        $this->send('to', 'POST', "/api/v1/referrals/{$id}/reject")
            ->assertStatus(422)->assertJsonPath('error.code', 'VALIDATION_ERROR');

        $this->send('to', 'POST', "/api/v1/referrals/{$id}/reject", ['reason' => 'Out of catchment area'])
            ->assertOk()->assertJsonPath('data.status', 'rejected')->assertJsonPath('data.reason', 'Out of catchment area');
        $this->assertNotNull(Referral::query()->withoutGlobalScopes()->find($id)->rejected_at);
        $this->assertDatabaseHas('audit_log', ['action' => 'referral.rejected', 'entity_id' => $id]);
    }

    public function test_more_info_request_and_originating_response_round_trip(): void
    {
        $id = $this->createReferral();

        $this->send('to', 'POST', "/api/v1/referrals/{$id}/request-info", ['note' => 'Which programme?'])
            ->assertOk()->assertJsonPath('data.status', 'more_info_requested')->assertJsonPath('data.info_request', 'Which programme?');

        // The originating MDA responds → back to created for re-review.
        $this->send('from', 'POST', "/api/v1/referrals/{$id}/respond-info", ['note' => 'Cash transfer'])
            ->assertOk()->assertJsonPath('data.status', 'created')->assertJsonPath('data.info_response', 'Cash transfer');

        // Now the receiving MDA can accept.
        $this->send('to', 'POST', "/api/v1/referrals/{$id}/accept")->assertOk()->assertJsonPath('data.status', 'accepted');
        $this->assertDatabaseHas('audit_log', ['action' => 'referral.info_requested', 'entity_id' => $id]);
        $this->assertDatabaseHas('audit_log', ['action' => 'referral.info_responded', 'entity_id' => $id]);
    }

    public function test_invalid_transitions_are_rejected(): void
    {
        $id = $this->createReferral();

        // Cannot start before accepting.
        $this->send('to', 'POST', "/api/v1/referrals/{$id}/start")
            ->assertStatus(422)->assertJsonPath('error.code', 'INVALID_TRANSITION');

        // Reject → terminal; cannot accept afterwards.
        $this->send('to', 'POST', "/api/v1/referrals/{$id}/reject", ['reason' => 'No'])->assertOk();
        $this->send('to', 'POST', "/api/v1/referrals/{$id}/accept")
            ->assertStatus(422)->assertJsonPath('error.code', 'INVALID_TRANSITION');
    }

    public function test_party_guards_enforce_who_can_act(): void
    {
        $id = $this->createReferral();

        // The originating MDA cannot accept (that is the receiving MDA's action).
        $this->send('from', 'POST', "/api/v1/referrals/{$id}/accept")->assertStatus(403);
        // An unrelated MDA cannot act at all (scope → 404).
        $this->send('other', 'POST', "/api/v1/referrals/{$id}/accept")->assertStatus(404);

        // The receiving MDA cannot respond to a more-info request (originating action).
        $this->send('to', 'POST', "/api/v1/referrals/{$id}/request-info")->assertOk();
        $this->send('to', 'POST', "/api/v1/referrals/{$id}/respond-info", ['note' => 'x'])->assertStatus(403);
    }

    public function test_list_is_two_party_scoped_with_direction_filter(): void
    {
        $id = $this->createReferral();

        $this->send('from', 'GET', '/api/v1/referrals?filter[direction]=outgoing')->assertOk()->assertJsonCount(1, 'data');
        $this->send('to', 'GET', '/api/v1/referrals?filter[direction]=incoming')->assertOk()->assertJsonCount(1, 'data');
        // The unrelated MDA sees nothing.
        $this->send('other', 'GET', '/api/v1/referrals')->assertOk()->assertJsonCount(0, 'data');
    }
}
