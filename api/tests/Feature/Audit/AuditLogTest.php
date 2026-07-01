<?php

declare(strict_types=1);

namespace Tests\Feature\Audit;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Audit\Services\AuditLogger;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use RuntimeException;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        return User::factory()->create([
            'role_id' => Role::where('key', RoleKey::SystemAdministrator->value)->firstOrFail()->id,
        ]);
    }

    private function assertNotInAuditLog(string $needle): void
    {
        foreach (AuditLog::all() as $row) {
            $this->assertStringNotContainsString(
                $needle,
                (string) json_encode($row->getAttributes()),
                "Sensitive value leaked into audit_log: {$needle}",
            );
        }
    }

    public function test_model_creation_is_audited_with_actor(): void
    {
        $admin = $this->admin();
        Sanctum::actingAs($admin);

        $mda = Mda::create(['name' => 'Health Ministry', 'type' => 'ministry']);

        $entry = AuditLog::where('action', 'mda.created')->where('entity_id', $mda->id)->first();

        $this->assertNotNull($entry);
        $this->assertSame('mda', $entry->entity_type);
        $this->assertSame($admin->id, $entry->actor_id);
        $this->assertNull($entry->before);
        // An MDA's name is not PII — it stays readable in the audit snapshot.
        $this->assertSame('Health Ministry', $entry->after['name']);
    }

    public function test_update_records_before_and_after(): void
    {
        Sanctum::actingAs($this->admin());

        $role = Role::create(['key' => 'temp_role', 'name' => 'Temp', 'description' => 'first']);
        $role->update(['description' => 'second']);

        $entry = AuditLog::where('action', 'role.updated')->where('entity_id', $role->id)->latest('created_at')->first();

        $this->assertNotNull($entry);
        $this->assertSame('first', $entry->before['description']);
        $this->assertSame('second', $entry->after['description']);
        $this->assertArrayNotHasKey('created_at', $entry->after); // timestamps excluded
    }

    public function test_delete_is_audited(): void
    {
        Sanctum::actingAs($this->admin());

        $mda = Mda::create(['name' => 'To Delete', 'type' => 'agency']);
        $mda->delete();

        $this->assertDatabaseHas('audit_log', ['action' => 'mda.deleted', 'entity_id' => $mda->id]);
    }

    public function test_secrets_redacted_and_pii_masked_no_leakage(): void
    {
        Sanctum::actingAs($this->admin());

        $user = User::create([
            'name' => 'Aisha Mohammed',
            'email' => 'leak@example.test',
            'password' => 'Sup3rSecret12345',
            'role_id' => Role::where('key', RoleKey::MdaOfficer->value)->firstOrFail()->id,
        ]);

        $entry = AuditLog::where('action', 'user.created')->where('entity_id', $user->id)->first();
        $this->assertNotNull($entry);

        $this->assertSame('[redacted]', $entry->after['password']);
        $this->assertNotSame('leak@example.test', $entry->after['email']);
        $this->assertNotSame('Aisha Mohammed', $entry->after['name']);

        // The raw secret and PII never appear anywhere in the audit log.
        $this->assertNotInAuditLog('Sup3rSecret12345');
        $this->assertNotInAuditLog('leak@example.test');
        $this->assertNotInAuditLog('Aisha Mohammed');
    }

    public function test_audit_rows_cannot_be_updated(): void
    {
        $entry = app(AuditLogger::class)->record('test.action');

        $this->expectException(RuntimeException::class);
        $entry->update(['action' => 'tampered']);
    }

    public function test_audit_rows_cannot_be_deleted(): void
    {
        $entry = app(AuditLogger::class)->record('test.action');

        $this->expectException(RuntimeException::class);
        $entry->delete();
    }

    public function test_login_is_audited_with_correlation_id(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create([
            'email' => 'officer@example.test',
            'password' => 'Sup3rStr0ng!Pass',
            'role_id' => Role::where('key', RoleKey::MdaOfficer->value)->firstOrFail()->id,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'officer@example.test',
            'password' => 'Sup3rStr0ng!Pass',
        ])->assertOk();

        $correlationId = $response->headers->get('X-Correlation-Id');
        $this->assertNotNull($correlationId);

        $entry = AuditLog::where('action', 'auth.login')->where('actor_id', $user->id)->first();
        $this->assertNotNull($entry);
        $this->assertSame($correlationId, $entry->correlation_id);
        $this->assertNotNull($entry->ip_address);
    }

    public function test_failed_login_is_audited_without_storing_email(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        User::factory()->create([
            'email' => 'officer@example.test',
            'password' => 'Sup3rStr0ng!Pass',
            'role_id' => Role::where('key', RoleKey::MdaOfficer->value)->firstOrFail()->id,
        ]);

        $this->postJson('/api/v1/auth/login', [
            'email' => 'officer@example.test',
            'password' => 'wrong-password',
        ])->assertStatus(401);

        $this->assertDatabaseHas('audit_log', ['action' => 'auth.login_failed']);
        $this->assertNotInAuditLog('officer@example.test');
    }

    public function test_lockout_is_audited(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        User::factory()->create([
            'email' => 'officer@example.test',
            'password' => 'Sup3rStr0ng!Pass',
            'role_id' => Role::where('key', RoleKey::MdaOfficer->value)->firstOrFail()->id,
        ]);

        // Rotate IPs to isolate account lockout from the per-IP login throttle.
        for ($i = 1; $i <= 5; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.'.$i])
                ->postJson('/api/v1/auth/login', [
                    'email' => 'officer@example.test',
                    'password' => 'wrong-password',
                ]);
        }

        $this->assertDatabaseHas('audit_log', ['action' => 'auth.account_locked']);
    }

    public function test_cross_mda_grant_is_audited(): void
    {
        $admin = $this->admin();
        $mda = Mda::factory()->create();
        $officer = User::factory()->create([
            'role_id' => Role::where('key', RoleKey::MdaOfficer->value)->firstOrFail()->id,
        ]);

        $this->withToken($admin->createToken('test')->plainTextToken)
            ->postJson('/api/v1/mda-access-grants', [
                'user_id' => $officer->id,
                'mda_id' => $mda->id,
            ])->assertStatus(201);

        $entry = AuditLog::where('action', 'cross_mda.granted')->first();
        $this->assertNotNull($entry);
        $this->assertSame($admin->id, $entry->actor_id);
        $this->assertSame($mda->id, $entry->after['mda_id']);
    }
}
