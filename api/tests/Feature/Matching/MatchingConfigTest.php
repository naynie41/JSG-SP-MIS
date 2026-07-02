<?php

declare(strict_types=1);

namespace Tests\Feature\Matching;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Admin management of the matching configuration (PRD FR-DUP-02): read + publish,
 * versioned + audited, System-Administrator-only.
 */
class MatchingConfigTest extends TestCase
{
    use RefreshDatabase;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->users['admin'] = $this->user(RoleKey::SystemAdministrator);
        $this->users['officer'] = $this->user(RoleKey::MdaOfficer);
    }

    private function user(RoleKey $role): User
    {
        return User::factory()->create([
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
            'mda_id' => Mda::factory()->create()->id,
        ]);
    }

    private function tokenFor(string $key): string
    {
        return $this->users[$key]->createToken('test')->plainTextToken;
    }

    /**
     * @return array<string, mixed>
     */
    private function validPayload(): array
    {
        return [
            'deterministic_rules' => [['nin'], ['bvn']],
            'fuzzy_fields' => [
                ['field' => 'phone', 'comparator' => 'exact', 'weight' => 0.3],
                ['field' => 'last_name', 'comparator' => 'jaro_winkler', 'weight' => 0.4],
                ['field' => 'first_name', 'comparator' => 'jaro_winkler', 'weight' => 0.3],
            ],
            'review_threshold' => 0.70,
            'auto_accept_threshold' => 0.90,
            'exact_match_behaviour' => 'auto_link',
            'description' => 'Tuned weights',
        ];
    }

    public function test_admin_can_read_the_active_config(): void
    {
        $this->withToken($this->tokenFor('admin'))
            ->getJson('/api/v1/matching/config')
            ->assertOk()
            ->assertJsonPath('data.version', 1)
            ->assertJsonPath('data.is_active', true)
            ->assertJsonPath('data.review_threshold', 0.75)
            ->assertJsonPath('data.deterministic_rules', [['nin'], ['bvn']]);
    }

    public function test_admin_can_publish_a_new_version_which_is_audited_and_supersedes_the_old(): void
    {
        $response = $this->withToken($this->tokenFor('admin'))
            ->putJson('/api/v1/matching/config', $this->validPayload())
            ->assertOk()
            ->assertJsonPath('data.version', 2)
            ->assertJsonPath('data.is_active', true)
            ->assertJsonPath('data.exact_match_behaviour', 'auto_link')
            ->assertJsonPath('data.review_threshold', 0.7);

        $newId = $response->json('data.id');

        // Previous version deactivated; new version active (single active).
        $this->assertDatabaseHas('matching_configs', ['version' => 1, 'is_active' => false]);
        $this->assertDatabaseHas('matching_configs', ['version' => 2, 'is_active' => true]);

        // The publish is audited.
        $this->assertDatabaseHas('audit_log', [
            'action' => 'matching_config.created',
            'entity_type' => 'matching_config',
            'entity_id' => $newId,
        ]);
    }

    public function test_versions_endpoint_lists_history_newest_first(): void
    {
        $this->withToken($this->tokenFor('admin'))
            ->putJson('/api/v1/matching/config', $this->validPayload())
            ->assertOk();
        $this->app['auth']->forgetGuards();

        $this->withToken($this->tokenFor('admin'))
            ->getJson('/api/v1/matching/config/versions')
            ->assertOk()
            ->assertJsonCount(2, 'data.versions')
            ->assertJsonPath('data.versions.0.version', 2)
            ->assertJsonPath('data.versions.1.version', 1);
    }

    public function test_invalid_payload_is_rejected_with_the_standard_envelope(): void
    {
        $payload = $this->validPayload();
        $payload['auto_accept_threshold'] = 0.5; // below review_threshold → invalid

        $this->withToken($this->tokenFor('admin'))
            ->putJson('/api/v1/matching/config', $payload)
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR')
            ->assertJsonFragment(['field' => 'auto_accept_threshold']);
    }

    public function test_non_admin_cannot_view_or_configure_matching(): void
    {
        $this->withToken($this->tokenFor('officer'))
            ->getJson('/api/v1/matching/config')
            ->assertStatus(403);
        $this->app['auth']->forgetGuards();

        $this->withToken($this->tokenFor('officer'))
            ->putJson('/api/v1/matching/config', $this->validPayload())
            ->assertStatus(403);
    }
}
