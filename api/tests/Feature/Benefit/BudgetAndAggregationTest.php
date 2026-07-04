<?php

declare(strict_types=1);

namespace Tests\Feature\Benefit;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Enums\BenefitStatus;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Budget tracking + ledger aggregation (PRD FR-PRG-04, FR-BEN-03): allocated vs
 * utilised per programme/activity, and grouped totals by programme/MDA/LGA/
 * beneficiary — correct, reversed-excluded, and MDA-scoped.
 */
class BudgetAndAggregationTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private Programme $programmeA;

    private Activity $activityA;

    private Programme $programmeB;

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
        $this->users['oversight'] = $this->user($this->mdaB, RoleKey::Executive); // cross-mda.view

        $this->programmeA = Programme::factory()->individual()->create(['owner_mda_id' => $this->mdaA->id, 'budget_amount' => 10_000_000]);
        $this->activityA = Activity::factory()->forProgramme($this->programmeA)->create(['budget_amount' => 4_000_000]);
        $this->programmeB = Programme::factory()->individual()->create(['owner_mda_id' => $this->mdaB->id, 'budget_amount' => 5_000_000]);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    private function benefit(Programme $programme, string $mdaId, array $attrs = []): Benefit
    {
        return Benefit::factory()->create(array_merge([
            'programme_id' => $programme->id,
            'mda_id' => $mdaId,
            'beneficiary_id' => Beneficiary::factory()->create(['owner_mda_id' => $mdaId])->id,
            'status' => BenefitStatus::Verified,
        ], $attrs));
    }

    private function read(string $key, string $url): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->getJson($url);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    public function test_programme_budget_allocated_vs_utilized(): void
    {
        $this->benefit($this->programmeA, $this->mdaA->id, ['monetary_value' => 3_000_000, 'quantity' => 2]);
        $this->benefit($this->programmeA, $this->mdaA->id, ['monetary_value' => 2_000_000, 'quantity' => 3, 'status' => BenefitStatus::Recorded]);
        // Reversed → excluded from utilised.
        $this->benefit($this->programmeA, $this->mdaA->id, ['monetary_value' => 1_000_000, 'status' => BenefitStatus::Reversed]);

        $this->read('officerA', "/api/v1/programmes/{$this->programmeA->id}/budget")
            ->assertOk()
            ->assertJsonPath('data.allocated', 10_000_000)
            ->assertJsonPath('data.utilized_value', 5_000_000)
            ->assertJsonPath('data.benefit_count', 2)
            ->assertJsonPath('data.remaining', 5_000_000)
            ->assertJsonPath('data.utilization_rate', 0.5);
    }

    public function test_activity_budget_allocated_vs_utilized(): void
    {
        $this->benefit($this->programmeA, $this->mdaA->id, ['activity_id' => $this->activityA->id, 'monetary_value' => 1_500_000]);
        $this->benefit($this->programmeA, $this->mdaA->id, ['activity_id' => $this->activityA->id, 'monetary_value' => 500_000]);
        // A programme benefit not under the activity — must not count toward it.
        $this->benefit($this->programmeA, $this->mdaA->id, ['monetary_value' => 9_000_000]);

        $this->read('officerA', "/api/v1/activities/{$this->activityA->id}/budget")
            ->assertOk()
            ->assertJsonPath('data.allocated', 4_000_000)
            ->assertJsonPath('data.utilized_value', 2_000_000)
            ->assertJsonPath('data.remaining', 2_000_000);
    }

    public function test_aggregate_by_programme_and_mda(): void
    {
        $this->benefit($this->programmeA, $this->mdaA->id, ['monetary_value' => 3_000_000]);
        $this->benefit($this->programmeA, $this->mdaA->id, ['monetary_value' => 2_000_000]);
        $this->benefit($this->programmeB, $this->mdaB->id, ['monetary_value' => 3_000_000]);

        $byProgramme = collect($this->read('oversight', '/api/v1/benefits/aggregate?group_by=programme')->assertOk()->json('data.groups'));
        $this->assertSame(5_000_000, $byProgramme->firstWhere('key', $this->programmeA->id)['total_value']);
        $this->assertSame(3_000_000, $byProgramme->firstWhere('key', $this->programmeB->id)['total_value']);

        $this->read('oversight', '/api/v1/benefits/aggregate?group_by=mda')
            ->assertOk()
            ->assertJsonPath('data.totals.total_value', 8_000_000)
            ->assertJsonPath('data.totals.benefit_count', 3);
    }

    public function test_aggregate_by_lga_and_beneficiary(): void
    {
        $b1 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse']);
        $this->benefit($this->programmeA, $this->mdaA->id, ['beneficiary_id' => $b1->id, 'lga' => 'dutse', 'monetary_value' => 1_000_000]);
        $this->benefit($this->programmeA, $this->mdaA->id, ['beneficiary_id' => $b1->id, 'lga' => 'dutse', 'monetary_value' => 1_000_000]);
        $this->benefit($this->programmeA, $this->mdaA->id, ['lga' => 'gumel', 'monetary_value' => 500_000]);

        $byLga = collect($this->read('officerA', '/api/v1/benefits/aggregate?group_by=lga')->assertOk()->json('data.groups'));
        $this->assertSame(2_000_000, $byLga->firstWhere('key', 'dutse')['total_value']);
        $this->assertSame(500_000, $byLga->firstWhere('key', 'gumel')['total_value']);

        // The one beneficiary who received two benefits totals both.
        $byBen = collect($this->read('officerA', '/api/v1/benefits/aggregate?group_by=beneficiary')->assertOk()->json('data.groups'));
        $this->assertSame(2_000_000, $byBen->firstWhere('key', $b1->id)['total_value']);
        $this->assertSame(2, $byBen->firstWhere('key', $b1->id)['benefit_count']);
    }

    public function test_aggregation_and_budget_respect_mda_scoping(): void
    {
        $this->benefit($this->programmeA, $this->mdaA->id, ['monetary_value' => 5_000_000]);
        $this->benefit($this->programmeB, $this->mdaB->id, ['monetary_value' => 3_000_000]);

        // Each MDA only aggregates its own deliveries.
        $this->read('officerA', '/api/v1/benefits/aggregate?group_by=mda')
            ->assertOk()->assertJsonCount(1, 'data.groups')->assertJsonPath('data.totals.total_value', 5_000_000);
        $this->read('officerB', '/api/v1/benefits/aggregate?group_by=mda')
            ->assertOk()->assertJsonPath('data.totals.total_value', 3_000_000);
        // Oversight sees everything.
        $this->read('oversight', '/api/v1/benefits/aggregate?group_by=mda')
            ->assertOk()->assertJsonPath('data.totals.total_value', 8_000_000);

        // A different MDA cannot read MDA A's programme budget (scoped out → 404).
        $this->read('officerB', "/api/v1/programmes/{$this->programmeA->id}/budget")->assertStatus(404);
        $this->read('oversight', "/api/v1/programmes/{$this->programmeA->id}/budget")
            ->assertOk()->assertJsonPath('data.utilized_value', 5_000_000);
    }
}
