<?php

declare(strict_types=1);

namespace Tests\Feature\Grievance;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Grievance\Models\Grievance;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Grievance redress (PRD FR-GRM-01/02, §8.4): staff-logged (no citizen self-service),
 * assigned + tracked to resolution with notes + timestamps; guarded transitions;
 * MDA scoping; audit; and notifications on assignment + resolution.
 */
class GrievanceTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    /** @var array<string, User> */
    private array $users = [];

    private Beneficiary $beneficiary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['staffA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);   // logs + handles
        $this->users['agentA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);   // assignee
        $this->users['staffB'] = $this->user($this->mdaB, RoleKey::MdaOfficer);   // other MDA
        $this->users['oversight'] = $this->user($this->mdaB, RoleKey::Executive); // cross-mda.view

        $this->beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function log(array $overrides = []): string
    {
        return $this->send('staffA', 'POST', '/api/v1/grievances', array_merge([
            'category' => 'payment',
            'channel' => 'walk_in',
            'beneficiary_id' => $this->beneficiary->id,
            'description' => 'Did not receive the last cash transfer.',
        ], $overrides))->assertCreated()->json('data.id');
    }

    public function test_staff_logs_a_grievance_with_and_without_a_beneficiary_link(): void
    {
        $id = $this->log();
        $this->assertDatabaseHas('grievances', [
            'id' => $id, 'handling_mda_id' => $this->mdaA->id, 'beneficiary_id' => $this->beneficiary->id,
            'category' => 'payment', 'channel' => 'walk_in', 'status' => 'open', 'submitted_by' => $this->users['staffA']->id,
        ]);
        $this->assertDatabaseHas('audit_log', ['action' => 'grievance.created', 'entity_id' => $id]);

        // Anonymous / general grievance (no beneficiary link).
        $anon = $this->log(['beneficiary_id' => null]);
        $this->assertDatabaseHas('grievances', ['id' => $anon, 'beneficiary_id' => null]);
    }

    public function test_grievance_is_scoped_to_the_handling_mda(): void
    {
        $id = $this->log();

        $this->send('staffA', 'GET', "/api/v1/grievances/{$id}")->assertOk()->assertJsonPath('data.status', 'open');
        $this->send('oversight', 'GET', "/api/v1/grievances/{$id}")->assertOk();     // oversight sees it
        $this->send('staffB', 'GET', "/api/v1/grievances/{$id}")->assertStatus(404); // other MDA cannot
        $this->send('staffB', 'GET', '/api/v1/grievances')->assertOk()->assertJsonCount(0, 'data');
    }

    public function test_assign_track_and_resolve_with_notes_timestamps_and_notifications(): void
    {
        $id = $this->log();

        // Assign to a user in the handling MDA → Assigned + assignee notified.
        $this->send('staffA', 'POST', "/api/v1/grievances/{$id}/assign", ['assignee_user_id' => $this->users['agentA']->id])
            ->assertOk()->assertJsonPath('data.status', 'assigned')->assertJsonPath('data.assignee_user_id', $this->users['agentA']->id);
        $this->assertNotNull(Grievance::query()->withoutGlobalScopes()->find($id)->assigned_at);
        $this->assertDatabaseHas('audit_log', ['action' => 'grievance.assigned', 'entity_id' => $id]);
        $this->assertDatabaseHas('notifications', ['recipient_user_id' => $this->users['agentA']->id, 'type' => 'grievance.assigned']);

        // Start → InProgress.
        $this->send('staffA', 'POST', "/api/v1/grievances/{$id}/start")->assertOk()->assertJsonPath('data.status', 'in_progress');

        // Resolve with notes → Resolved + relevant parties notified.
        $this->send('staffA', 'POST', "/api/v1/grievances/{$id}/resolve", ['resolution_notes' => 'Payment re-issued.'])
            ->assertOk()->assertJsonPath('data.status', 'resolved')->assertJsonPath('data.resolution_notes', 'Payment re-issued.');

        $model = Grievance::query()->withoutGlobalScopes()->findOrFail($id);
        $this->assertNotNull($model->started_at);
        $this->assertNotNull($model->resolved_at);
        $this->assertDatabaseHas('audit_log', ['action' => 'grievance.resolved', 'entity_id' => $id]);
        // The staff member who logged it is notified of resolution.
        $this->assertDatabaseHas('notifications', ['recipient_user_id' => $this->users['staffA']->id, 'type' => 'grievance.resolved']);
    }

    public function test_resolve_requires_notes(): void
    {
        $id = $this->log();
        $this->send('staffA', 'POST', "/api/v1/grievances/{$id}/assign", ['assignee_user_id' => $this->users['agentA']->id])->assertOk();

        $this->send('staffA', 'POST', "/api/v1/grievances/{$id}/resolve")
            ->assertStatus(422)->assertJsonPath('error.code', 'VALIDATION_ERROR');
    }

    public function test_invalid_transitions_and_bad_assignment_are_rejected(): void
    {
        $id = $this->log();

        // Cannot start an unassigned (open) grievance.
        $this->send('staffA', 'POST', "/api/v1/grievances/{$id}/start")
            ->assertStatus(422)->assertJsonPath('error.code', 'INVALID_TRANSITION');

        // Cannot assign to a user outside the handling MDA (that user is invisible → 404).
        $this->send('staffA', 'POST', "/api/v1/grievances/{$id}/assign", ['assignee_user_id' => $this->users['staffB']->id])
            ->assertStatus(404);

        // Another MDA cannot act on it at all (scope → 404).
        $this->send('staffB', 'POST', "/api/v1/grievances/{$id}/assign", ['assignee_user_id' => $this->users['staffB']->id])
            ->assertStatus(404);
    }
}
