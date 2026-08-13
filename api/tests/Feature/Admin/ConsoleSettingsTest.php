<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Notification\Models\Notification;
use App\Domain\Registry\Export\BeneficiaryListExport;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The administration console's Settings page and the two controls it owns: the
 * PERMISSION MATRIX editor (writes the existing RBAC pivot) and system BROADCASTS
 * (sent through the Phase 5 notifier). Also covers the console boundary — Settings is
 * a projection of existing configuration, never a second settings store.
 */
class ConsoleSettingsTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'MDA A']);

        $this->users['admin'] = $this->user(null, RoleKey::SystemAdministrator);
        $this->users['exec'] = $this->user(null, RoleKey::Executive);
        $this->users['officer'] = $this->user($this->mda, RoleKey::MdaAdmin);
        $this->users['mdaAdmin'] = $this->user($this->mda, RoleKey::MdaAdmin);
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
            'status' => UserStatus::Active,
        ]);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function roleId(RoleKey $key): string
    {
        return (string) Role::where('key', $key->value)->value('id');
    }

    /* ------------------------------------------------------------- settings */

    public function test_settings_are_readable_only_by_a_system_administrator(): void
    {
        $this->send('admin', 'GET', '/api/v1/admin/settings')->assertOk();

        foreach (['exec', 'officer', 'mdaAdmin'] as $who) {
            $this->send($who, 'GET', '/api/v1/admin/settings')->assertStatus(403);
        }
    }

    public function test_settings_report_the_effective_configuration_with_its_source(): void
    {
        $body = $this->send('admin', 'GET', '/api/v1/admin/settings')->assertOk()->json('data');

        // Every general row names the key that sets it, so an admin knows where to change it.
        foreach ($body['general'] as $row) {
            $this->assertArrayHasKey('source', $row);
            $this->assertNotSame('', $row['source']);
        }

        $labels = array_column($body['security']['policy'], 'label');
        $this->assertContains('MFA enforced', $labels);
        $this->assertContains('Lockout after (failed attempts)', $labels);

        // Registry identity validation is reported as LOCKED, never as a control.
        $this->assertTrue($body['registry']['locked']);
        $this->assertContains('nin', $body['registry']['identity_fields']);

        // Channel availability comes from the channels themselves.
        $channels = collect($body['notifications'])->keyBy('key');
        $this->assertTrue((bool) $channels['in_app']['available']);
        $this->assertFalse((bool) $channels['sms']['available'], 'the SMS stub must report itself unavailable');
    }

    public function test_settings_carry_no_infrastructure_or_system_health_data(): void
    {
        $json = (string) json_encode($this->send('admin', 'GET', '/api/v1/admin/settings')->assertOk()->json());

        // The console is governance, not ops (no uptime/CPU/queue-depth widgets).
        foreach (['cpu', 'memory_usage', 'disk', 'uptime', 'queue_depth', 'server_load'] as $infra) {
            $this->assertStringNotContainsString($infra, $json);
        }

        // Nor any secret, even though the config it reads holds several.
        foreach (['password', 'secret', 'DB_PASSWORD', 'APP_KEY', 'token'] as $secret) {
            $this->assertStringNotContainsString($secret, $json);
        }
    }

    /* ------------------------------------------------- permission matrix editor */

    public function test_matrix_carries_the_policy_for_each_permission_and_role(): void
    {
        $body = $this->send('admin', 'GET', '/api/v1/access/matrix')->assertOk()->json('data');

        $permissions = collect($body['permissions'])->keyBy('key');
        $this->assertFalse(
            (bool) $permissions[BeneficiaryListExport::REVEAL_PERMISSION]['role_grantable'],
            'export.reveal_pii must be reported as never grantable to a role',
        );
        $this->assertTrue((bool) $permissions['beneficiary.export']['role_grantable']);
        $this->assertTrue((bool) $permissions['beneficiary.export']['sensitive']);

        $roles = collect($body['roles'])->keyBy('key');
        $this->assertFalse((bool) $roles['system_administrator']['editable']);
        $this->assertTrue((bool) $roles['mda_admin']['editable']);
    }

    public function test_granting_a_permission_takes_effect_immediately(): void
    {
        // A permission the MDA role does NOT hold, so the grant is observable.
        // `beneficiary.export` can no longer serve here: the Officer/Admin merge
        // (FR-UAM-01) gave it to every MDA user.
        $this->assertFalse($this->users['officer']->fresh()->hasPermission('double-dipping.view'));

        $current = Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()
            ->permissions()->pluck('key')->all();

        $this->send('admin', 'PUT', '/api/v1/roles/'.$this->roleId(RoleKey::MdaAdmin).'/permissions', [
            'permissions' => [...$current, 'double-dipping.view'],
        ])->assertOk();

        // The SAME RBAC the authorization layer reads — no second store to sync.
        $this->assertTrue($this->users['officer']->fresh()->hasPermission('double-dipping.view'));
    }

    public function test_revoking_a_permission_takes_effect_immediately(): void
    {
        $this->assertTrue($this->users['mdaAdmin']->fresh()->hasPermission('beneficiary.export'));

        $keep = Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()
            ->permissions()->pluck('key')->reject(fn (string $k): bool => $k === 'beneficiary.export')->values()->all();

        $this->send('admin', 'PUT', '/api/v1/roles/'.$this->roleId(RoleKey::MdaAdmin).'/permissions', [
            'permissions' => $keep,
        ])->assertOk();

        $this->assertFalse($this->users['mdaAdmin']->fresh()->hasPermission('beneficiary.export'));
    }

    public function test_reveal_pii_can_never_be_granted_to_a_role(): void
    {
        $this->send('admin', 'PUT', '/api/v1/roles/'.$this->roleId(RoleKey::MdaAdmin).'/permissions', [
            'permissions' => ['beneficiary.view', BeneficiaryListExport::REVEAL_PERMISSION],
        ])->assertStatus(422)->assertJsonPath('error.code', 'PERMISSION_NOT_GRANTABLE');

        $this->assertFalse(
            Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()
                ->permissions()->where('key', BeneficiaryListExport::REVEAL_PERMISSION)->exists(),
        );
    }

    public function test_the_system_administrator_role_cannot_be_edited(): void
    {
        $before = Role::where('key', RoleKey::SystemAdministrator->value)->firstOrFail()
            ->permissions()->count();

        $this->send('admin', 'PUT', '/api/v1/roles/'.$this->roleId(RoleKey::SystemAdministrator).'/permissions', [
            'permissions' => ['beneficiary.view'],
        ])->assertStatus(422)->assertJsonPath('error.code', 'PERMISSION_NOT_GRANTABLE');

        // An admin cannot strip their own administration rights.
        $this->assertSame($before, Role::where('key', RoleKey::SystemAdministrator->value)->firstOrFail()
            ->permissions()->count());
        $this->assertTrue($this->users['admin']->fresh()->hasPermission('role.edit'));
    }

    public function test_an_unknown_permission_is_rejected(): void
    {
        $this->send('admin', 'PUT', '/api/v1/roles/'.$this->roleId(RoleKey::MdaAdmin).'/permissions', [
            'permissions' => ['beneficiary.view', 'made.up'],
        ])->assertStatus(422)->assertJsonPath('error.code', 'PERMISSION_NOT_GRANTABLE');
    }

    public function test_matrix_edits_are_audited_with_what_changed(): void
    {
        $current = Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()
            ->permissions()->pluck('key')->all();

        // `cross-mda.view` is both ABSENT from the MDA role and on the sensitive list,
        // so one grant exercises the diff and the DPO call-out together.
        // `beneficiary.export` can no longer serve: the Officer/Admin merge (FR-UAM-01)
        // put it on the MDA role, so granting it there is a no-op with an empty diff.
        $this->send('admin', 'PUT', '/api/v1/roles/'.$this->roleId(RoleKey::MdaAdmin).'/permissions', [
            'permissions' => [...$current, 'cross-mda.view'],
        ])->assertOk();

        $this->assertDatabaseHas('audit_log', ['action' => 'role.permissions_updated']);

        $entry = AuditLog::query()
            ->where('action', 'role.permissions_updated')->latest('created_at')->firstOrFail();

        $this->assertContains('cross-mda.view', $entry->after['granted']);
        // Sensitive grants carry a DPO obligation, so they are called out for review.
        $this->assertContains('cross-mda.view', $entry->after['sensitive_granted']);
        $this->assertSame($this->users['admin']->id, $entry->actor_id);
    }

    public function test_only_a_system_administrator_may_edit_the_matrix(): void
    {
        $url = '/api/v1/roles/'.$this->roleId(RoleKey::MdaAdmin).'/permissions';

        foreach (['exec', 'officer', 'mdaAdmin'] as $who) {
            $this->send($who, 'PUT', $url, ['permissions' => ['beneficiary.view']])->assertStatus(403);
        }
    }

    /* ---------------------------------------------------------------- broadcast */

    public function test_broadcast_reaches_every_active_user_via_the_notifier(): void
    {
        $response = $this->send('admin', 'POST', '/api/v1/notifications/broadcast', [
            'subject' => 'Scheduled maintenance',
            'body' => 'The system will be unavailable on Saturday from 02:00.',
        ])->assertCreated();

        $count = (int) $response->json('data.recipient_count');
        $this->assertSame(4, $count); // all four seeded users are active

        // Delivered to the in-app inbox — the Phase 5 channel, not a new one.
        $this->assertSame($count, Notification::query()->where('type', 'system.broadcast')->count());
        $this->assertDatabaseHas('notifications', ['subject' => 'Scheduled maintenance']);
    }

    public function test_broadcast_can_be_narrowed_to_a_role(): void
    {
        $this->send('admin', 'POST', '/api/v1/notifications/broadcast', [
            'subject' => 'MDA staff only',
            'role_key' => RoleKey::MdaAdmin->value,
        ])->assertCreated()->assertJsonPath('data.recipient_count', 2);

        // Both MDA users share the single MDA role since the merge (FR-UAM-01).
        $this->assertSame(2, Notification::query()->where('subject', 'MDA staff only')->count());
    }

    public function test_broadcast_skips_inactive_accounts(): void
    {
        $this->users['officer']->forceFill(['status' => UserStatus::Suspended->value])->save();

        $this->send('admin', 'POST', '/api/v1/notifications/broadcast', [
            'subject' => 'Active only',
        ])->assertCreated()->assertJsonPath('data.recipient_count', 3);
    }

    public function test_broadcast_is_audited_without_listing_recipients(): void
    {
        $this->send('admin', 'POST', '/api/v1/notifications/broadcast', ['subject' => 'Notice'])->assertCreated();

        $entry = AuditLog::query()
            ->where('action', 'notification.broadcast')->latest('created_at')->firstOrFail();

        $this->assertSame('Notice', $entry->after['subject']);
        $this->assertSame(4, $entry->after['recipient_count']);
        // A count, never a recipient list.
        $this->assertArrayNotHasKey('recipients', $entry->after);
    }

    public function test_broadcast_requires_a_subject_and_the_admin_role(): void
    {
        $this->send('admin', 'POST', '/api/v1/notifications/broadcast', ['body' => 'no subject'])
            ->assertStatus(422);

        foreach (['exec', 'officer', 'mdaAdmin'] as $who) {
            $this->send($who, 'POST', '/api/v1/notifications/broadcast', ['subject' => 'Nope'])->assertStatus(403);
        }
    }

    public function test_audience_count_previews_the_reach_before_sending(): void
    {
        $this->send('admin', 'GET', '/api/v1/notifications/broadcast/audience')
            ->assertOk()->assertJsonPath('data.recipient_count', 4);

        // Two of the four are MDA users, both on the single MDA role (FR-UAM-01).
        $this->send('admin', 'GET', '/api/v1/notifications/broadcast/audience?role_key=mda_admin')
            ->assertOk()->assertJsonPath('data.recipient_count', 2);

        // A role key that no longer exists reaches nobody rather than everybody.
        $this->send('admin', 'GET', '/api/v1/notifications/broadcast/audience?role_key=mda_officer')
            ->assertOk()->assertJsonPath('data.recipient_count', 0);

        // Previewing sends nothing.
        $this->assertSame(0, Notification::query()->where('type', 'system.broadcast')->count());
    }
}
