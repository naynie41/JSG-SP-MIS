<?php

declare(strict_types=1);

namespace Tests\Feature\Programme;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Programme;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Programme management (PRD FR-PRG-01): CRUD, MDA scoping (owner-only mutation),
 * RBAC permissions, validation, and audit.
 */
class ProgrammeManagementTest extends TestCase
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
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaOfficer);
        $this->users['viewer'] = $this->user($this->mdaA, RoleKey::MneOfficer); // programme.view only
        $this->users['oversight'] = $this->user($this->mdaB, RoleKey::Executive); // cross-mda.view
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

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Conditional Cash Transfer',
            'objective' => 'Support the poorest households',
            'type' => 'individual',
            'eligibility' => [['label' => 'LGA', 'value' => 'dutse']],
            'funding_source' => 'State budget',
            'budget_amount' => 50_000_000,
            'starts_on' => '2026-01-01',
            'ends_on' => '2026-12-31',
            'status' => 'active',
        ], $overrides);
    }

    public function test_owner_mda_can_create_and_configure_a_programme(): void
    {
        $this->send('officerA', 'POST', '/api/v1/programmes', $this->validPayload())
            ->assertCreated()
            ->assertJsonPath('data.name', 'Conditional Cash Transfer')
            ->assertJsonPath('data.type', 'individual')
            ->assertJsonPath('data.owner_mda_id', $this->mdaA->id)
            ->assertJsonPath('data.budget_amount', 50_000_000);

        $programme = Programme::query()->firstOrFail();
        $this->assertSame($this->mdaA->id, $programme->owner_mda_id);
        $this->assertSame($this->users['officerA']->id, $programme->created_by);

        $this->assertDatabaseHas('audit_log', [
            'action' => 'programme.created',
            'entity_id' => $programme->id,
            'actor_id' => $this->users['officerA']->id,
        ]);
    }

    public function test_create_requires_the_create_permission(): void
    {
        $this->send('viewer', 'POST', '/api/v1/programmes', $this->validPayload())
            ->assertStatus(403);

        $this->assertSame(0, Programme::query()->count());
    }

    public function test_create_requires_an_mda(): void
    {
        $this->users['noMda'] = User::factory()->create([
            'mda_id' => null,
            'role_id' => Role::where('key', RoleKey::MdaOfficer->value)->firstOrFail()->id,
        ]);

        $this->send('noMda', 'POST', '/api/v1/programmes', $this->validPayload())
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'MDA_REQUIRED');
    }

    public function test_create_validation(): void
    {
        // Missing name.
        $this->send('officerA', 'POST', '/api/v1/programmes', $this->validPayload(['name' => '']))
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR')
            ->assertJsonFragment(['field' => 'name']);

        // Invalid type.
        $this->send('officerA', 'POST', '/api/v1/programmes', $this->validPayload(['type' => 'group']))
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'type']);

        // End date before start date.
        $this->send('officerA', 'POST', '/api/v1/programmes', $this->validPayload(['starts_on' => '2026-12-31', 'ends_on' => '2026-01-01']))
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'ends_on']);
    }

    public function test_list_is_mda_scoped_and_oversight_sees_all(): void
    {
        Programme::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        Programme::factory()->count(2)->create(['owner_mda_id' => $this->mdaB->id]);

        // Owner sees only its own.
        $this->send('officerA', 'GET', '/api/v1/programmes')
            ->assertOk()
            ->assertJsonCount(1, 'data');

        // Oversight (cross-mda.view) sees all three.
        $this->send('oversight', 'GET', '/api/v1/programmes')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_owner_can_update_and_archive(): void
    {
        $programme = Programme::factory()->create(['owner_mda_id' => $this->mdaA->id, 'status' => 'draft']);

        $this->send('officerA', 'PATCH', "/api/v1/programmes/{$programme->id}", ['status' => 'active', 'budget_amount' => 999])
            ->assertOk()
            ->assertJsonPath('data.status', 'active')
            ->assertJsonPath('data.budget_amount', 999);

        $this->send('officerA', 'POST', "/api/v1/programmes/{$programme->id}/archive")
            ->assertOk()
            ->assertJsonPath('data.status', 'archived');

        $this->assertDatabaseHas('audit_log', ['action' => 'programme.updated', 'entity_id' => $programme->id]);
    }

    public function test_another_mda_cannot_mutate_or_see_a_programme(): void
    {
        $programme = Programme::factory()->create(['owner_mda_id' => $this->mdaA->id]);

        // Not visible to a different MDA (scoped out → 404).
        $this->send('officerB', 'GET', "/api/v1/programmes/{$programme->id}")->assertStatus(404);

        // Cannot update or archive (policy → 403).
        $this->send('officerB', 'PATCH', "/api/v1/programmes/{$programme->id}", ['name' => 'Hijacked'])->assertStatus(403);
        $this->send('officerB', 'POST', "/api/v1/programmes/{$programme->id}/archive")->assertStatus(403);

        // Nothing changed.
        $this->assertSame($programme->name, $programme->fresh()->name);
        $this->assertNotSame('archived', $programme->fresh()->status->value);
    }

    public function test_oversight_can_view_but_not_mutate(): void
    {
        $programme = Programme::factory()->create(['owner_mda_id' => $this->mdaA->id]);

        // Executive (cross-mda.view) can read any programme…
        $this->send('oversight', 'GET', "/api/v1/programmes/{$programme->id}")->assertOk();
        // …but has no edit permission at all → 403.
        $this->send('oversight', 'PATCH', "/api/v1/programmes/{$programme->id}", ['name' => 'X'])->assertStatus(403);
    }
}
