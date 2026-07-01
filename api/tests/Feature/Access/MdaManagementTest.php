<?php

declare(strict_types=1);

namespace Tests\Feature\Access;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MdaManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;      // system administrator (cross-mda.view)

    private User $mdaAdmin;   // MDA-scoped admin in MDA A

    private Mda $mdaA;

    private Mda $mdaB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->admin = User::factory()->create([
            'role_id' => Role::where('key', RoleKey::SystemAdministrator->value)->firstOrFail()->id,
        ]);
        $this->mdaAdmin = User::factory()->create([
            'mda_id' => $this->mdaA->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);
    }

    private function token(User $user): string
    {
        return $user->createToken('test')->plainTextToken;
    }

    public function test_admin_can_create_mda_and_it_is_audited(): void
    {
        $response = $this->withToken($this->token($this->admin))->postJson('/api/v1/mdas', [
            'name' => 'Ministry of Health',
            'type' => 'ministry',
            'contact_email' => 'health@example.test',
        ])->assertStatus(201)->assertJsonPath('data.name', 'Ministry of Health');

        $id = $response->json('data.id');
        $this->assertDatabaseHas('mdas', ['id' => $id, 'name' => 'Ministry of Health']);
        $this->assertDatabaseHas('audit_log', ['action' => 'mda.created', 'entity_id' => $id]);
    }

    public function test_non_admin_cannot_create_mda(): void
    {
        // MDA Admin lacks mda.create.
        $this->withToken($this->token($this->mdaAdmin))->postJson('/api/v1/mdas', [
            'name' => 'Rogue MDA',
            'type' => 'agency',
        ])->assertStatus(403)->assertJsonPath('error.code', 'FORBIDDEN');

        $this->assertDatabaseMissing('mdas', ['name' => 'Rogue MDA']);
    }

    public function test_mda_list_is_scoped(): void
    {
        $adminNames = $this->withToken($this->token($this->admin))
            ->getJson('/api/v1/mdas')->assertOk()->json('data.mdas.*.name');
        $this->assertContains('MDA A', $adminNames);
        $this->assertContains('MDA B', $adminNames);

        $this->app['auth']->forgetGuards();
        $scopedNames = $this->withToken($this->token($this->mdaAdmin))
            ->getJson('/api/v1/mdas')->assertOk()->json('data.mdas.*.name');
        $this->assertContains('MDA A', $scopedNames);
        $this->assertNotContains('MDA B', $scopedNames);
    }

    public function test_show_out_of_scope_mda_returns_404(): void
    {
        $this->withToken($this->token($this->mdaAdmin))
            ->getJson("/api/v1/mdas/{$this->mdaB->id}")
            ->assertStatus(404)->assertJsonPath('error.code', 'NOT_FOUND');
    }

    public function test_admin_can_update_mda(): void
    {
        $this->withToken($this->token($this->admin))
            ->patchJson("/api/v1/mdas/{$this->mdaA->id}", ['name' => 'MDA A Renamed'])
            ->assertOk()->assertJsonPath('data.name', 'MDA A Renamed');

        $this->assertDatabaseHas('mdas', ['id' => $this->mdaA->id, 'name' => 'MDA A Renamed']);
        $this->assertDatabaseHas('audit_log', ['action' => 'mda.updated', 'entity_id' => $this->mdaA->id]);
    }

    public function test_admin_can_deactivate_and_activate_mda(): void
    {
        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/mdas/{$this->mdaA->id}/deactivate")
            ->assertOk()->assertJsonPath('data.status', 'inactive');
        $this->assertDatabaseHas('mdas', ['id' => $this->mdaA->id, 'status' => 'inactive']);

        $this->app['auth']->forgetGuards();
        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/mdas/{$this->mdaA->id}/activate")
            ->assertOk()->assertJsonPath('data.status', 'active');
    }

    public function test_create_validation_uses_standard_error_format(): void
    {
        $this->withToken($this->token($this->admin))->postJson('/api/v1/mdas', [
            'type' => 'not-a-valid-type',
        ])->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR')
            ->assertJsonStructure(['error' => ['code', 'message', 'details' => [['field', 'message']]]]);
    }
}
