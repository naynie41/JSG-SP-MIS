<?php

declare(strict_types=1);

namespace Tests\Feature\Benefit;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Benefit\Models\BenefitFlag;
use App\Domain\Benefit\Models\DoubleDippingRule;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Double-dipping detection (PRD FR-BEN-05): the same benefit type delivered to a
 * beneficiary by different MDAs within a configurable window is FLAGGED — never
 * blocked — and the flag is surfaced for review. Configurable + audited.
 */
class DoubleDippingTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private Beneficiary $beneficiary;

    private Programme $programmeA;

    private Programme $programmeB;

    private DoubleDippingRule $rule;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);
        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaOfficer);
        $this->users['spcoord'] = $this->user($this->mdaA, RoleKey::SpCoordination);
        $this->users['outsider'] = $this->user(Mda::factory()->create(), RoleKey::MdaOfficer);

        $this->rule = DoubleDippingRule::factory()->create(['period_days' => 30, 'benefit_types' => null]);

        $this->beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $this->programmeA = Programme::factory()->individual()->create(['owner_mda_id' => $this->mdaA->id]);
        $this->programmeB = Programme::factory()->individual()->create(['owner_mda_id' => $this->mdaB->id]);
        Enrollment::factory()->create(['programme_id' => $this->programmeA->id, 'mda_id' => $this->mdaA->id, 'beneficiary_id' => $this->beneficiary->id]);
        Enrollment::factory()->create(['programme_id' => $this->programmeB->id, 'mda_id' => $this->mdaB->id, 'beneficiary_id' => $this->beneficiary->id]);
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

    private function deliverCash(string $officer, Programme $programme): void
    {
        $this->send($officer, 'POST', '/api/v1/benefits', [
            'beneficiary_id' => $this->beneficiary->id,
            'programme_id' => $programme->id,
            'benefit_type' => 'cash',
            'monetary_value' => 500_000,
            'delivery_date' => now()->toDateString(),
        ])->assertCreated();
    }

    public function test_same_type_from_two_mdas_within_period_is_flagged_but_not_blocked(): void
    {
        $this->deliverCash('officerA', $this->programmeA); // first — no conflict yet
        $this->deliverCash('officerB', $this->programmeB); // second — flags against the first

        // Both deliveries were recorded — nothing was blocked.
        $this->assertSame(2, Benefit::query()->withoutGlobalScopes()->count());

        $flag = BenefitFlag::query()->firstOrFail();
        $this->assertSame('cash', $flag->benefit_type);
        $this->assertSame($this->mdaB->id, $flag->from_mda_id);
        $this->assertSame($this->mdaA->id, $flag->other_mda_id);
        $this->assertSame('open', $flag->status->value);
        $this->assertDatabaseHas('audit_log', ['action' => 'benefit_flag.created', 'entity_id' => $flag->id]);
    }

    public function test_no_flag_when_the_rule_does_not_apply_to_the_type(): void
    {
        $this->rule->update(['benefit_types' => ['food']]); // cash not covered

        $this->deliverCash('officerA', $this->programmeA);
        $this->deliverCash('officerB', $this->programmeB);

        $this->assertSame(0, BenefitFlag::query()->count());
    }

    public function test_flags_are_listable_and_reviewable_by_involved_mdas(): void
    {
        $this->deliverCash('officerA', $this->programmeA);
        $this->deliverCash('officerB', $this->programmeB);
        $flag = BenefitFlag::query()->firstOrFail();

        // Both involved MDAs see it; an unrelated MDA does not.
        $this->send('officerA', 'GET', '/api/v1/benefit-flags')->assertOk()->assertJsonCount(1, 'data');
        $this->send('officerB', 'GET', '/api/v1/benefit-flags')->assertOk()->assertJsonCount(1, 'data');
        $this->send('outsider', 'GET', '/api/v1/benefit-flags')->assertOk()->assertJsonCount(0, 'data');

        // An involved MDA reviews (dismiss).
        $this->send('officerA', 'POST', "/api/v1/benefit-flags/{$flag->id}/review", ['status' => 'dismissed', 'review_note' => 'Different sub-programme'])
            ->assertOk()
            ->assertJsonPath('data.status', 'dismissed');
        $this->assertSame($this->users['officerA']->id, $flag->fresh()->reviewed_by);
    }

    public function test_rules_are_admin_editable_and_gated(): void
    {
        // SP Coordination configures rules.
        $this->send('spcoord', 'POST', '/api/v1/double-dipping-rules', ['name' => 'Cash 60d', 'period_days' => 60, 'benefit_types' => ['cash']])
            ->assertCreated()
            ->assertJsonPath('data.period_days', 60);

        // An MDA officer cannot.
        $this->send('officerA', 'POST', '/api/v1/double-dipping-rules', ['name' => 'X', 'period_days' => 10])->assertStatus(403);
        $this->send('officerA', 'GET', '/api/v1/double-dipping-rules')->assertStatus(403);
    }
}
