<?php

declare(strict_types=1);

namespace Tests\Feature\Sync;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Sync\Enums\SyncTrigger;
use App\Domain\Sync\Models\SyncConnector;
use App\Domain\Sync\Services\ConnectorMappingService;
use App\Domain\Sync\Services\SyncEngine;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\Concerns\ConfirmsImportMapping;
use Tests\TestCase;

/**
 * Standing column-mapping confirmation for unattended ingestion (CLAUDE.md §11).
 *
 * The §11 rule cannot be met literally by a scheduled connector — nobody is present at
 * 02:00 to say which field holds the NIN. Dropping it for sync would be worse than for a
 * file: a connector ingests continuously, so a wrong identity mapping merges citizens on
 * every run rather than once.
 *
 * The resolution: the confirmation is given once at configuration time and STANDS, and
 * it is bounded by the source's shape. What is preserved is the property that matters —
 * no record reaches the duplicate cascade under an identity mapping no person approved.
 */
class ConnectorMappingTest extends TestCase
{
    use ConfirmsImportMapping, RefreshDatabase;

    private Mda $mda;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->users['sysAdmin'] = User::factory()->create([
            'mda_id' => null,
            'role_id' => Role::where('key', RoleKey::SystemAdministrator->value)->firstOrFail()->id,
        ]);
        $this->users['officer'] = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);
    }

    /**
     * A newly-configured connector: not yet enabled, because the order is configure →
     * confirm the mapping → enable. The run guard covers a connector that was somehow
     * enabled without a mapping ({@see test_a_connector_without_a_confirmed_mapping_refuses_to_run}).
     */
    private function connector(): SyncConnector
    {
        return SyncConnector::factory()->create([
            'owner_mda_id' => $this->mda->id,
            'source' => RegistrationSource::Socu,
            'enabled' => false,
        ]);
    }

    /** @param array<int, array<string, mixed>> $records */
    private function mockSocu(array $records): void
    {
        config(['sync.mock_records.socu' => $records]);
    }

    /** @param array<string, mixed> $body */
    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $this->app['auth']->forgetGuards();

        return $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
    }

    /** A complete, valid mapping for the SOCU mock's field names. */
    private function goodMap(): array
    {
        return [
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'nin' => 'nin',
            'bvn' => null,
            'phone' => 'phone',
        ];
    }

    /* ------------------------------------------------------------- the gate */

    public function test_a_connector_without_a_confirmed_mapping_refuses_to_run(): void
    {
        $connector = $this->connector();
        $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'id' => 'S-1']]);

        $run = app(SyncEngine::class)->runConnector($connector, SyncTrigger::Scheduled);

        // Not silently skipped: a connector that quietly stops ingesting is
        // indistinguishable from one with nothing to ingest.
        $this->assertSame('failed', $run->status->value);
        $this->assertStringContainsString('no confirmed column mapping', (string) $run->error);
        $this->assertSame(0, Beneficiary::query()->withoutGlobalScopes()->count());
        $this->assertDatabaseHas('audit_log', ['action' => 'sync.run_blocked']);
    }

    public function test_a_manual_trigger_is_refused_with_a_clear_reason(): void
    {
        // Enabled but never mapped — the state every connector configured before this
        // rule existed is in, so the guard has to hold for them too.
        $connector = SyncConnector::factory()->create([
            'owner_mda_id' => $this->mda->id,
            'source' => RegistrationSource::Socu,
            'enabled' => true,
        ]);

        // Reported to the person who pressed the button, not buried in a queued run.
        $this->send('sysAdmin', 'POST', "/api/v1/sync/connectors/{$connector->id}/run")
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'MAPPING_NOT_CONFIRMED');
    }

    public function test_the_mapping_cannot_be_confirmed_while_an_identity_field_is_unanswered(): void
    {
        $connector = $this->connector();
        $map = $this->goodMap();
        unset($map['nin']);

        $this->send('sysAdmin', 'PUT', "/api/v1/sync/connectors/{$connector->id}/mapping", ['column_map' => $map])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'MAPPING_INCOMPLETE');

        $this->assertNull($connector->fresh()->mapping_confirmed_at);
    }

    /* ----------------------------------------------- confirming, and then running */

    public function test_a_confirmed_connector_runs_and_applies_the_mapping(): void
    {
        $connector = $this->connector();
        $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'id' => 'S-1']]);

        $this->send('sysAdmin', 'PUT', "/api/v1/sync/connectors/{$connector->id}/mapping", [
            'column_map' => $this->goodMap(),
        ])->assertOk();

        $run = app(SyncEngine::class)->runConnector($connector->fresh(), SyncTrigger::Scheduled);

        $this->assertSame('completed', $run->status->value);
        $this->assertSame(1, $run->created_count);
        $this->assertSame('Ada', Beneficiary::query()->withoutGlobalScopes()->firstOrFail()->first_name);
    }

    public function test_the_confirmation_stands_for_later_runs(): void
    {
        $connector = $this->confirmConnectorMapping($this->connector(), $this->users['sysAdmin']);

        // The whole point of moving the confirmation to configuration time: a scheduled
        // run at 02:00 does not stop to ask again.
        foreach (['S-1', 'S-2'] as $id) {
            $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '2000000000'.substr($id, 2), 'id' => $id]]);
            $run = app(SyncEngine::class)->runConnector($connector->fresh(), SyncTrigger::Scheduled);
            $this->assertSame('completed', $run->status->value);
        }

        $this->assertSame(2, Beneficiary::query()->withoutGlobalScopes()->count());
    }

    /* --------------------------------------------- bounded by the source's shape */

    public function test_a_changed_source_shape_stops_the_connector_until_re_confirmed(): void
    {
        $connector = $this->connector();
        $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'id' => 'S-1']]);

        $this->send('sysAdmin', 'PUT', "/api/v1/sync/connectors/{$connector->id}/mapping", [
            'column_map' => $this->goodMap(),
        ])->assertOk();

        // The source starts returning a different set of fields. The standing approval
        // was given for the OLD shape and cannot speak for this one.
        $this->mockSocu([['forename' => 'Ada', 'family_name' => 'Okoye', 'national_id' => '20000000001', 'id' => 'S-2']]);

        $run = app(SyncEngine::class)->runConnector($connector->fresh(), SyncTrigger::Scheduled);

        $this->assertSame('failed', $run->status->value);
        $this->assertStringContainsString('fields have changed', (string) $run->error);
        $this->assertSame(0, Beneficiary::query()->withoutGlobalScopes()->count());
    }

    public function test_re_confirming_against_the_new_shape_lets_it_run_again(): void
    {
        $connector = $this->connector();
        $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'id' => 'S-1']]);
        $this->send('sysAdmin', 'PUT', "/api/v1/sync/connectors/{$connector->id}/mapping", ['column_map' => $this->goodMap()])
            ->assertOk();

        $this->mockSocu([['forename' => 'Ada', 'family_name' => 'Okoye', 'national_id' => '20000000001', 'id' => 'S-2']]);

        // A person looks at the new shape and maps it.
        $this->send('sysAdmin', 'PUT', "/api/v1/sync/connectors/{$connector->id}/mapping", [
            'column_map' => [
                'first_name' => 'forename',
                'last_name' => 'family_name',
                'nin' => 'national_id',
                'bvn' => null,
                'phone' => null,
            ],
        ])->assertOk();

        $run = app(SyncEngine::class)->runConnector($connector->fresh(), SyncTrigger::Scheduled);

        $this->assertSame('completed', $run->status->value);
        $this->assertSame('Ada', Beneficiary::query()->withoutGlobalScopes()->firstOrFail()->first_name);
    }

    /* ------------------------------------------- staleness is a persisted state */

    public function test_a_shape_change_marks_the_connector_stale_and_holds_the_next_sync(): void
    {
        $connector = $this->connector();
        $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'id' => 'S-1']]);
        $this->send('sysAdmin', 'PUT', "/api/v1/sync/connectors/{$connector->id}/mapping", ['column_map' => $this->goodMap()])
            ->assertOk();

        $this->mockSocu([['forename' => 'Ada', 'family_name' => 'Okoye', 'id' => 'S-2']]);
        app(SyncEngine::class)->runConnector($connector->fresh(), SyncTrigger::Scheduled);

        // Recorded, not merely detected in flight — the console has to be able to show
        // that this feed stopped, and why, between runs.
        $stale = $connector->fresh();
        $this->assertSame('stale', $stale->mappingStatus());
        $this->assertNotNull($stale->mapping_stale_at);
        $this->assertDatabaseHas('audit_log', ['action' => 'sync.mapping_stale']);

        // The NEXT run is held on the stored flag, without contacting the source at all.
        $held = app(SyncEngine::class)->runConnector($stale, SyncTrigger::Scheduled);
        $this->assertSame('failed', $held->status->value);
        $this->assertStringContainsString('needs review', (string) $held->error);
        $this->assertSame(0, Beneficiary::query()->withoutGlobalScopes()->count());
    }

    public function test_re_confirming_clears_the_stale_flag(): void
    {
        $connector = $this->connector();
        $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'id' => 'S-1']]);
        $this->send('sysAdmin', 'PUT', "/api/v1/sync/connectors/{$connector->id}/mapping", ['column_map' => $this->goodMap()])->assertOk();

        $this->mockSocu([['forename' => 'Ada', 'family_name' => 'Okoye', 'id' => 'S-2']]);
        app(SyncEngine::class)->runConnector($connector->fresh(), SyncTrigger::Scheduled);
        $this->assertSame('stale', $connector->fresh()->mappingStatus());

        // Re-confirming IS the review the connector was waiting for.
        $this->send('sysAdmin', 'PUT', "/api/v1/sync/connectors/{$connector->id}/mapping", [
            'column_map' => ['first_name' => 'forename', 'last_name' => 'family_name', 'nin' => null, 'bvn' => null, 'phone' => null],
        ])->assertOk();

        $this->assertSame('confirmed', $connector->fresh()->mappingStatus());
        $this->assertNull($connector->fresh()->mapping_stale_at);
    }

    public function test_the_three_mapping_states_are_distinguishable(): void
    {
        $connector = $this->connector();
        // Never configured and stale need DIFFERENT remedies — a first mapping vs a
        // review of one that used to be right — so they are not one "not ready" state.
        $this->assertSame('never_configured', $connector->mappingStatus());

        $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'id' => 'S-1']]);
        $this->send('sysAdmin', 'PUT', "/api/v1/sync/connectors/{$connector->id}/mapping", ['column_map' => $this->goodMap()])->assertOk();
        $this->assertSame('confirmed', $connector->fresh()->mappingStatus());

        $this->mockSocu([['forename' => 'Ada', 'id' => 'S-2']]);
        app(SyncEngine::class)->runConnector($connector->fresh(), SyncTrigger::Scheduled);
        $this->assertSame('stale', $connector->fresh()->mappingStatus());
    }

    /* ----------------------------------------------- the guard at CONFIG time */

    public function test_a_connector_cannot_be_enabled_without_a_confirmed_mapping(): void
    {
        $connector = $this->connector();

        // The refusal lands where the decision is made, not silently at 02:00.
        $this->send('sysAdmin', 'PUT', "/api/v1/sync/connectors/{$connector->id}/enabled", ['enabled' => true])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'MAPPING_NOT_CONFIRMED');

        $this->assertFalse($connector->fresh()->enabled);
    }

    public function test_a_stale_connector_cannot_be_re_enabled_until_reviewed(): void
    {
        $connector = $this->connector();
        $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'id' => 'S-1']]);
        $this->send('sysAdmin', 'PUT', "/api/v1/sync/connectors/{$connector->id}/mapping", ['column_map' => $this->goodMap()])->assertOk();

        $this->mockSocu([['forename' => 'Ada', 'id' => 'S-2']]);
        app(SyncEngine::class)->runConnector($connector->fresh(), SyncTrigger::Scheduled);

        $this->send('sysAdmin', 'PUT', "/api/v1/sync/connectors/{$connector->id}/enabled", ['enabled' => true])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'MAPPING_NOT_CONFIRMED');
    }

    public function test_a_confirmed_connector_can_be_enabled_and_the_change_is_audited(): void
    {
        $connector = $this->confirmConnectorMapping($this->connector(), $this->users['sysAdmin']);

        $this->send('sysAdmin', 'PUT', "/api/v1/sync/connectors/{$connector->id}/enabled", ['enabled' => true])
            ->assertOk()
            ->assertJsonPath('data.enabled', true);

        $this->assertTrue($connector->fresh()->enabled);
        $this->assertDatabaseHas('audit_log', ['action' => 'sync.connector_enabled']);
    }

    public function test_disabling_is_always_allowed(): void
    {
        $connector = $this->connector();

        // Turning a connector OFF must never be blocked by a mapping problem — that
        // would trap an administrator with a feed they cannot stop.
        $this->send('sysAdmin', 'PUT', "/api/v1/sync/connectors/{$connector->id}/enabled", ['enabled' => false])
            ->assertOk();

        $this->assertFalse($connector->fresh()->enabled);
    }

    public function test_the_connector_list_reports_mapping_status_and_who_confirmed_it(): void
    {
        $connector = $this->connector();
        $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'id' => 'S-1']]);
        $this->send('sysAdmin', 'PUT', "/api/v1/sync/connectors/{$connector->id}/mapping", ['column_map' => $this->goodMap()])->assertOk();

        $row = collect($this->send('sysAdmin', 'GET', '/api/v1/sync/connectors')->assertOk()->json('data.connectors'))
            ->firstWhere('id', $connector->id);

        $this->assertSame('confirmed', $row['mapping']['status']);
        $this->assertSame($this->users['sysAdmin']->name, $row['mapping']['confirmed_by']);
        $this->assertNotNull($row['mapping']['confirmed_at']);
        $this->assertTrue($row['mapping']['can_enable']);
    }

    /* ------------------------------------------------------- the mapping screen */

    public function test_the_proposal_samples_the_source_and_suggests_a_mapping(): void
    {
        $connector = $this->connector();
        $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Okoye', 'national_id' => '20000000001', 'id' => 'S-1']]);

        $data = $this->send('sysAdmin', 'GET', "/api/v1/sync/connectors/{$connector->id}/mapping")
            ->assertOk()->json('data');

        $this->assertContains('national_id', $data['detected_fields']);
        $this->assertSame('national_id', $data['suggestions']['nin']['header']);
        // Same caution as a file import: an ambiguous identifier header is not confident.
        $this->assertSame('low', $data['suggestions']['nin']['confidence']);
        $this->assertSame(['20000000001'], $data['samples']['national_id']);
        $this->assertContains('nin', $data['unconfirmed_identity_fields']);
    }

    public function test_the_proposal_reports_a_shape_change(): void
    {
        $connector = $this->connector();
        $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'id' => 'S-1']]);
        $this->send('sysAdmin', 'PUT', "/api/v1/sync/connectors/{$connector->id}/mapping", ['column_map' => $this->goodMap()])
            ->assertOk();

        $this->mockSocu([['forename' => 'Ada', 'family_name' => 'Okoye', 'id' => 'S-2']]);

        $data = $this->send('sysAdmin', 'GET', "/api/v1/sync/connectors/{$connector->id}/mapping")
            ->assertOk()->json('data');

        $this->assertTrue($data['signature_changed'], 'the screen must say WHY the connector stopped');
    }

    /* -------------------------------------------------------------- permissions */

    public function test_confirming_a_mapping_needs_the_sync_run_permission(): void
    {
        $connector = $this->connector();

        // An MDA officer holds no sync permission — this is System-Admin territory.
        $this->send('officer', 'PUT', "/api/v1/sync/connectors/{$connector->id}/mapping", [
            'column_map' => $this->goodMap(),
        ])->assertStatus(403);
    }

    /* ------------------------------------------------------------------- audit */

    public function test_the_standing_confirmation_is_audited_with_the_identity_mapping(): void
    {
        $connector = $this->connector();
        $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'id' => 'S-1']]);

        $this->send('sysAdmin', 'PUT', "/api/v1/sync/connectors/{$connector->id}/mapping", ['column_map' => $this->goodMap()])
            ->assertOk();

        $entry = AuditLog::query()->where('action', 'sync.mapping_confirmed')->firstOrFail();

        // A standing approval is a larger commitment than a one-off import mapping, so
        // it is recorded with the same detail.
        $this->assertSame($this->users['sysAdmin']->id, $entry->actor_id);
        $this->assertSame('nin', $entry->after['identity_fields']['nin'] ?? null);
        $this->assertSame('not present', $entry->after['identity_fields']['bvn'] ?? null);
        $this->assertNotNull($entry->after['source_signature'] ?? null);
    }

    /* --------------------------------------------- offline batches are unaffected */

    public function test_an_offline_batch_does_not_need_a_connector_mapping(): void
    {
        // An offline batch is posted by a person, in the capture app's own known shape —
        // it has no connector and no standing approval to give.
        $response = $this->send('officer', 'POST', '/api/v1/sync/offline-batches', [
            'source' => 'kobo',
            'records' => [['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', '_id' => 'OFF-1']],
        ]);

        $response->assertSuccessful();
        $this->assertSame(1, Beneficiary::query()->withoutGlobalScopes()->count());
    }

    public function test_the_blocked_reason_is_null_once_confirmed(): void
    {
        $connector = $this->connector();
        $this->assertNotNull(app(ConnectorMappingService::class)->blockedReason($connector));

        $confirmed = $this->confirmConnectorMapping($connector, $this->users['sysAdmin']);
        $this->assertNull(app(ConnectorMappingService::class)->blockedReason($confirmed));
    }
}
