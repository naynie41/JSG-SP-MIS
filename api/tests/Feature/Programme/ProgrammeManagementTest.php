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
 * Programme CATALOG management (PRD §10, ARCH §12.4). The CENTRAL catalog is global and
 * unowned: only catalog admins (System Administrator / SP Coordination) create or edit
 * it, and every authenticated role reads it. An MDA creating a programme of its own is
 * a different thing entirely and lives in {@see MdaOwnedProgrammeTest}; what this file
 * holds is that the central half did not change when that arrived.
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
        $this->users['admin'] = $this->user(null, RoleKey::SpCoordination); // catalog admin
        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaAdmin);
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaAdmin);
        $this->users['viewer'] = $this->user($this->mdaA, RoleKey::MneOfficer); // programme.view only
        $this->users['oversight'] = $this->user($this->mdaB, RoleKey::Executive); // cross-mda.view
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
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
            'benefit_category' => 'cash',
            'eligibility' => [['label' => 'LGA', 'value' => 'dutse']],
            'status' => 'active',
        ], $overrides);
    }

    public function test_catalog_admin_can_create_a_global_programme(): void
    {
        $this->send('admin', 'POST', '/api/v1/programmes', $this->validPayload())
            ->assertCreated()
            ->assertJsonPath('data.name', 'Conditional Cash Transfer')
            ->assertJsonPath('data.type', 'individual')
            ->assertJsonPath('data.benefit_category', 'cash')
            // A CENTRAL catalog entry has no owning MDA, and no budget/funding on the
            // programme. Created by a catalog admin, it needs no further approval —
            // the administrator creating it is the approval.
            ->assertJsonPath('data.owner_mda_id', null)
            ->assertJsonPath('data.is_central', true)
            ->assertJsonPath('data.approval_status', 'approved')
            ->assertJsonMissingPath('data.budget_amount');

        $programme = Programme::query()->firstOrFail();
        $this->assertSame($this->users['admin']->id, $programme->created_by);

        $this->assertDatabaseHas('audit_log', [
            'action' => 'programme.created',
            'entity_id' => $programme->id,
            'actor_id' => $this->users['admin']->id,
        ]);
    }

    public function test_an_mda_creates_a_programme_of_its_own_never_a_catalog_entry(): void
    {
        // What an MDA creates is owned BY THAT MDA and waits for a decision. The
        // owner comes from the authenticated user: naming another MDA in the body
        // changes nothing.
        $this->send('officerA', 'POST', '/api/v1/programmes', $this->validPayload([
            'owner_mda_id' => $this->mdaB->id,
        ]))
            ->assertCreated()
            ->assertJsonPath('data.owner_mda_id', $this->mdaA->id)
            ->assertJsonPath('data.is_central', false)
            ->assertJsonPath('data.approval_status', 'pending');

        $programme = Programme::query()->firstOrFail();
        $this->assertSame($this->mdaA->id, $programme->owner_mda_id);
        $this->assertNotNull($programme->submitted_at);
    }

    public function test_an_mda_can_never_edit_or_archive_the_central_catalog(): void
    {
        $central = Programme::factory()->create();

        $this->send('officerA', 'PATCH', "/api/v1/programmes/{$central->id}", ['name' => 'Hijacked'])->assertStatus(403);
        $this->send('officerA', 'POST', "/api/v1/programmes/{$central->id}/archive")->assertStatus(403);
        $this->assertSame($central->name, $central->fresh()->name);
    }

    public function test_non_admin_roles_cannot_create(): void
    {
        // M&E / oversight hold programme.view but are not catalog admins.
        $this->send('viewer', 'POST', '/api/v1/programmes', $this->validPayload())->assertStatus(403);
        $this->send('oversight', 'POST', '/api/v1/programmes', $this->validPayload())->assertStatus(403);
        $this->assertSame(0, Programme::query()->count());
    }

    public function test_create_validation(): void
    {
        $this->send('admin', 'POST', '/api/v1/programmes', $this->validPayload(['name' => '']))
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR')
            ->assertJsonFragment(['field' => 'name']);

        $this->send('admin', 'POST', '/api/v1/programmes', $this->validPayload(['type' => 'group']))
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'type']);
    }

    public function test_the_catalog_is_globally_visible_to_every_role(): void
    {
        Programme::factory()->create();
        Programme::factory()->count(2)->create();

        // No MDA scoping — every authenticated role sees the whole catalog.
        foreach (['officerA', 'officerB', 'viewer', 'oversight'] as $key) {
            $this->send($key, 'GET', '/api/v1/programmes')
                ->assertOk()
                ->assertJsonCount(3, 'data');
        }
    }

    public function test_catalog_admin_can_update_and_archive(): void
    {
        $programme = Programme::factory()->create(['status' => 'draft']);

        $this->send('admin', 'PATCH', "/api/v1/programmes/{$programme->id}", ['status' => 'active', 'objective' => 'Revised'])
            ->assertOk()
            ->assertJsonPath('data.status', 'active')
            ->assertJsonPath('data.objective', 'Revised');

        $this->send('admin', 'POST', "/api/v1/programmes/{$programme->id}/archive")
            ->assertOk()
            ->assertJsonPath('data.status', 'archived');

        $this->assertDatabaseHas('audit_log', ['action' => 'programme.updated', 'entity_id' => $programme->id]);
    }
}
