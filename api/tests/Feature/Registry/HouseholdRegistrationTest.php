<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Enums\HouseholdRole;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Household membership lifecycle (PRD §9): add/move/remove with history retention,
 * the single-open-membership rule, head designation, ownership, scoping, and
 * audit. Households are set up via factories (no manual create path).
 */
class HouseholdRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private Beneficiary $benA1;

    private Beneficiary $benA2;

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

        $this->benA1 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $this->benA2 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function tokenFor(string $key): string
    {
        return $this->users[$key]->createToken('test')->plainTextToken;
    }

    private function openMembership(Household $household, Beneficiary $beneficiary, HouseholdRole $role = HouseholdRole::Other): HouseholdMembership
    {
        return HouseholdMembership::factory()->create([
            'household_id' => $household->id,
            'beneficiary_id' => $beneficiary->id,
            'role_in_household' => $role,
            'left_at' => null,
        ]);
    }

    public function test_officer_can_add_a_member(): void
    {
        $household = Household::factory()->create(['owner_mda_id' => $this->mdaA->id]);

        $this->withToken($this->tokenFor('officerA'))
            ->postJson("/api/v1/households/{$household->id}/members", [
                'beneficiary_id' => $this->benA1->id,
                'role_in_household' => 'head',
            ])
            ->assertCreated()
            ->assertJsonPath('data.household_id', $household->id)
            ->assertJsonPath('data.is_open', true);

        $this->assertDatabaseHas('household_memberships', [
            'household_id' => $household->id,
            'beneficiary_id' => $this->benA1->id,
            'left_at' => null,
        ]);
    }

    public function test_second_concurrent_open_membership_is_rejected(): void
    {
        $h1 = Household::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $h2 = Household::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $this->openMembership($h1, $this->benA1);

        $this->withToken($this->tokenFor('officerA'))
            ->postJson("/api/v1/households/{$h2->id}/members", [
                'beneficiary_id' => $this->benA1->id,
                'role_in_household' => 'other',
            ])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'MEMBERSHIP_INVALID');

        // Still exactly one open membership, still in h1.
        $this->assertSame(1, HouseholdMembership::query()->where('beneficiary_id', $this->benA1->id)->whereNull('left_at')->count());
    }

    public function test_member_can_be_moved_with_history_retained(): void
    {
        $h1 = Household::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $h2 = Household::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $this->openMembership($h1, $this->benA1);

        $this->withToken($this->tokenFor('officerA'))
            ->postJson("/api/v1/households/{$h2->id}/members/move", [
                'beneficiary_id' => $this->benA1->id,
                'role_in_household' => 'other',
            ])
            ->assertCreated()
            ->assertJsonPath('data.household_id', $h2->id)
            ->assertJsonPath('data.is_open', true);

        $memberships = HouseholdMembership::query()->where('beneficiary_id', $this->benA1->id)->get();

        // History retained: two rows, exactly one open, and it is in h2.
        $this->assertCount(2, $memberships);
        $open = $memberships->whereNull('left_at');
        $this->assertCount(1, $open);
        $this->assertSame($h2->id, $open->first()->household_id);
        $this->assertNotNull($memberships->firstWhere('household_id', $h1->id)->left_at);

        $this->assertDatabaseHas('audit_log', ['action' => 'household.member_moved']);
    }

    public function test_member_can_be_removed_preserving_history(): void
    {
        $household = Household::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $this->openMembership($household, $this->benA1);

        $this->withToken($this->tokenFor('officerA'))
            ->deleteJson("/api/v1/households/{$household->id}/members/{$this->benA1->id}")
            ->assertOk();

        // The membership row is kept (history) but closed.
        $this->assertSame(1, HouseholdMembership::query()->where('beneficiary_id', $this->benA1->id)->count());
        $this->assertSame(0, HouseholdMembership::query()->where('beneficiary_id', $this->benA1->id)->whereNull('left_at')->count());
        $this->assertDatabaseHas('audit_log', ['action' => 'household.member_removed']);
    }

    public function test_head_can_be_designated_from_members_only(): void
    {
        $household = Household::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $this->openMembership($household, $this->benA1, HouseholdRole::Child);

        $this->withToken($this->tokenFor('officerA'))
            ->postJson("/api/v1/households/{$household->id}/head", ['beneficiary_id' => $this->benA1->id])
            ->assertOk()
            ->assertJsonPath('data.head_beneficiary_id', $this->benA1->id);

        $this->assertDatabaseHas('household_memberships', [
            'household_id' => $household->id,
            'beneficiary_id' => $this->benA1->id,
            'role_in_household' => HouseholdRole::Head->value,
            'left_at' => null,
        ]);

        $this->app['auth']->forgetGuards();

        // A non-member cannot be made head.
        $this->withToken($this->tokenFor('officerA'))
            ->postJson("/api/v1/households/{$household->id}/head", ['beneficiary_id' => $this->benA2->id])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'HOUSEHOLD_INVALID');
    }

    public function test_non_owner_cannot_mutate_household_or_membership(): void
    {
        $household = Household::factory()->create(['owner_mda_id' => $this->mdaA->id]);

        // Non-owner add member → 403 (authorized before any beneficiary lookup).
        $this->withToken($this->tokenFor('officerB'))
            ->postJson("/api/v1/households/{$household->id}/members", [
                'beneficiary_id' => $this->benA1->id,
                'role_in_household' => 'head',
            ])
            ->assertStatus(403)
            ->assertJsonPath('error.code', 'FORBIDDEN');

        $this->app['auth']->forgetGuards();

        // Non-owner edit → 403.
        $this->withToken($this->tokenFor('officerB'))
            ->patchJson("/api/v1/households/{$household->id}", ['ward' => 'Ward 9'])
            ->assertStatus(403);

        $this->app['auth']->forgetGuards();

        // Non-owner delete → 403.
        $this->withToken($this->tokenFor('officerB'))
            ->deleteJson("/api/v1/households/{$household->id}")
            ->assertStatus(403);
    }

    public function test_list_and_show_are_owner_scoped(): void
    {
        Household::factory()->count(2)->create(['owner_mda_id' => $this->mdaA->id]);
        $householdB = Household::factory()->create(['owner_mda_id' => $this->mdaB->id]);

        $response = $this->withToken($this->tokenFor('officerA'))
            ->getJson('/api/v1/households')
            ->assertOk()
            ->assertJsonPath('meta.pagination.total', 2);

        foreach ($response->json('data') as $row) {
            $this->assertSame($this->mdaA->id, $row['owner_mda_id']);
        }

        $this->app['auth']->forgetGuards();

        $this->withToken($this->tokenFor('officerA'))
            ->getJson("/api/v1/households/{$householdB->id}")
            ->assertStatus(404);
    }
}
