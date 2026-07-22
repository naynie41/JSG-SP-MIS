<?php

declare(strict_types=1);

namespace Tests\Feature\Ops;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Health, readiness and metrics (NFR-AVAIL-01). The readiness probe is public and
 * reports dependency + statelessness posture; metrics are permission-gated and PII-free.
 */
class HealthTest extends TestCase
{
    use RefreshDatabase;

    public function test_readiness_probe_reports_dependency_and_runtime_state(): void
    {
        $this->getJson('/api/v1/health')
            ->assertOk()
            ->assertJsonPath('data.status', 'ok')
            ->assertJsonPath('data.checks.database.connected', true)
            ->assertJsonPath('data.checks.cache.ok', true)
            ->assertJsonStructure(['data' => ['runtime' => ['session_driver', 'cache_store', 'queue_connection', 'stateless']]]);
    }

    public function test_metrics_require_permission_and_expose_no_pii(): void
    {
        $this->getJson('/api/v1/health/metrics')->assertStatus(401);

        $this->seed(RolesAndPermissionsSeeder::class);
        $admin = User::factory()->create([
            'mda_id' => Mda::factory()->create()->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);

        $this->withToken($admin->createToken('t')->plainTextToken)
            ->getJson('/api/v1/health/metrics')
            ->assertOk()
            ->assertJsonStructure(['data' => [
                'backups' => ['last_success_at', 'rpo_hours'],
                'dashboard_snapshots' => ['last_computed_at'],
                'volumes' => ['beneficiaries', 'benefits', 'audit_entries'],
            ]]);
    }
}
