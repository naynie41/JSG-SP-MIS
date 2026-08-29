<?php

declare(strict_types=1);

namespace Tests\Feature\Sync;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Sync\Enums\ConflictPolicy;
use App\Domain\Sync\Models\SyncConnector;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\Concerns\ConfirmsImportMapping;
use Tests\TestCase;

/**
 * Declaring a connector's target activity — the configuration half of activity-first.
 *
 * The binding is a standing decision, given once at configuration time, for the same
 * reason the identity mapping is: nobody is present at 02:00 to choose one. These tests
 * cover who may set it, what it may point at, and that the refusal reaches the person who
 * pressed the button rather than the queue.
 */
class ConnectorActivityConfigTest extends TestCase
{
    use ConfirmsImportMapping, RefreshDatabase;

    private Mda $mda;

    private Mda $otherMda;

    private Activity $activity;

    private SyncConnector $connector;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->otherMda = Mda::factory()->create(['name' => 'Ministry of Education']);

        $this->users['sysAdmin'] = $this->user(null, RoleKey::SystemAdministrator);
        $this->users['officer'] = $this->user($this->mda, RoleKey::MdaAdmin);

        $programme = Programme::factory()->create();
        $this->activity = Activity::factory()->forProgramme($programme, $this->mda)->create([
            'status' => 'active',
            'created_by' => $this->users['officer']->id,
        ]);

        $this->connector = $this->confirmConnectorMapping(
            SyncConnector::factory()->create([
                'owner_mda_id' => $this->mda->id,
                'source' => RegistrationSource::Socu,
                'conflict_policy' => ConflictPolicy::FlagForReview,
                'activity_id' => null,
                'enabled' => true,
            ]),
            $this->users['sysAdmin'],
        );
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    /** @param array<string, mixed> $body */
    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function setActivity(string $key, ?string $activityId): TestResponse
    {
        return $this->send($key, 'PUT', "/api/v1/sync/connectors/{$this->connector->id}/activity", [
            'activity_id' => $activityId,
        ]);
    }

    /* -------------------------------------------------------------- setting it */

    public function test_an_administrator_binds_the_connector_to_an_activity(): void
    {
        $this->setActivity('sysAdmin', $this->activity->id)
            ->assertOk()
            ->assertJsonPath('data.activity.id', $this->activity->id)
            ->assertJsonPath('data.activity.blocker', null);

        $this->assertSame($this->activity->id, $this->connector->fresh()->activity_id);
    }

    public function test_the_binding_is_audited(): void
    {
        $this->setActivity('sysAdmin', $this->activity->id)->assertOk();

        $this->assertDatabaseHas('audit_log', ['action' => 'sync.connector_activity_set']);
    }

    public function test_the_connector_reports_which_standing_decision_it_is_waiting_on(): void
    {
        // An administrator looking at a stalled feed needs to see WHY, not just that it
        // stopped. Unbound, the listing carries the same sentence the held run records.
        $row = collect($this->send('sysAdmin', 'GET', '/api/v1/sync/connectors')->assertOk()->json('data.connectors'))
            ->firstWhere('id', $this->connector->id);

        $this->assertNull($row['activity']['id']);
        $this->assertStringContainsString('no target activity', (string) $row['activity']['blocker']);
    }

    /* -------------------------------------------------------------- what it may be */

    public function test_a_connector_cannot_bind_to_another_mdas_activity(): void
    {
        // The connector ingests into ITS MDA's registry. Binding to another agency's
        // activity would attribute this delivery to an agency that never ran it.
        $foreign = Activity::factory()
            ->forProgramme(Programme::factory()->create(), $this->otherMda)
            ->create(['status' => 'active', 'created_by' => $this->users['officer']->id]);

        $this->setActivity('sysAdmin', $foreign->id)->assertStatus(422);
        $this->assertNull($this->connector->fresh()->activity_id);
    }

    public function test_the_binding_can_be_cleared(): void
    {
        // Clearing is legitimate — a programme ends. It stops the connector rather than
        // letting it keep ingesting against something that no longer applies.
        $this->setActivity('sysAdmin', $this->activity->id)->assertOk();
        $this->setActivity('sysAdmin', null)->assertOk();

        $this->assertNull($this->connector->fresh()->activity_id);
    }

    /* ------------------------------------------------------------- who may set it */

    public function test_setting_the_activity_needs_the_sync_run_permission(): void
    {
        $this->setActivity('officer', $this->activity->id)->assertStatus(403);
    }

    /* --------------------------------------------------- the refusal reaches a person */

    public function test_a_manual_trigger_on_an_unbound_connector_is_refused_at_the_button(): void
    {
        // Not on the queue at 02:00, where nobody would see it.
        $this->send('sysAdmin', 'POST', "/api/v1/sync/connectors/{$this->connector->id}/run")
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'ACTIVITY_NOT_BOUND');
    }

    public function test_a_bound_connector_triggers_normally(): void
    {
        $this->setActivity('sysAdmin', $this->activity->id)->assertOk();

        $this->send('sysAdmin', 'POST', "/api/v1/sync/connectors/{$this->connector->id}/run")->assertStatus(202);
    }
}
