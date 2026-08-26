<?php

declare(strict_types=1);

namespace Tests\Feature\Matching;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Matching\Engine\MatchingEngine;
use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Services\MatchingConfigService;
use App\Domain\Registry\Support\BeneficiaryRules;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Matching Rules & Registry Config section of the administration console. The console
 * COMPOSES the existing Phase 3 engine: edits go through `/matching/config`, produce a
 * new immutable version, are audited, and — the point of the section — actually change
 * how the engine bands a comparison. There is no second config store, and the registry
 * validation rules are exposed read-only from the canonical rule class.
 */
class AdminMatchingConfigTest extends TestCase
{
    use RefreshDatabase;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $mda = Mda::factory()->create();
        $this->users['admin'] = $this->user(null, RoleKey::SystemAdministrator);
        $this->users['officer'] = $this->user($mda, RoleKey::MdaAdmin);
        $this->users['coordination'] = $this->user(null, RoleKey::SpCoordination);
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function as(string $key, string $method, string $url, array $data = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)
            ->json($method, $url, $data);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /**
     * A config payload with a tunable review threshold; everything else fixed so the
     * only variable across publishes is the threshold under test.
     *
     * @return array<string, mixed>
     */
    private function payload(float $review, ?float $autoAccept = null): array
    {
        return [
            'deterministic_rules' => [['nin'], ['bvn']],
            'fuzzy_fields' => [
                ['field' => 'last_name', 'comparator' => 'jaro_winkler', 'weight' => 0.5],
                ['field' => 'first_name', 'comparator' => 'jaro_winkler', 'weight' => 0.5],
            ],
            'review_threshold' => $review,
            'auto_accept_threshold' => $autoAccept,
            'exact_match_behaviour' => 'confirm',
            'description' => "Review threshold {$review}",
        ];
    }

    /**
     * Two records with SIMILAR-but-different names and no NIN/BVN — a purely fuzzy
     * comparison whose composite lands mid-range, so thresholds can be set either side
     * of it (identical names would score 1.0, which no valid threshold can exceed).
     */
    private function comparison(): array
    {
        return [
            'candidate' => ['first_name' => 'Amina', 'last_name' => 'Bello'],
            'existing' => [['reference' => 'ben-1', 'first_name' => 'Fatima', 'last_name' => 'Danjuma']],
        ];
    }

    /* ----------------------------------------------------- reuse of the engine */

    public function test_publishing_through_the_console_creates_a_new_audited_version(): void
    {
        $before = MatchingConfig::query()->where('is_active', true)->firstOrFail();

        $published = $this->as('admin', 'PUT', '/api/v1/matching/config', $this->payload(0.8))
            ->assertOk()->json('data');

        // A NEW immutable version supersedes the old one — no in-place edit.
        $this->assertSame($before->version + 1, $published['version']);
        $this->assertTrue($published['is_active']);
        $this->assertFalse($before->fresh()->is_active);
        $this->assertSame(2, MatchingConfig::query()->count());

        // The active config the engine reads is the one just published.
        $this->assertSame($published['id'], app(MatchingConfigService::class)->active()->id);

        // Audited (FR-AUD-01) — the model's Auditable trait records the publish.
        $this->assertTrue(
            AuditLog::query()->where('action', 'matching_config.created')->exists(),
            'Publishing a matching configuration must be audited.',
        );

        // History exposes both versions, newest first.
        $versions = $this->as('admin', 'GET', '/api/v1/matching/config/versions')->assertOk()->json('data.versions');
        $this->assertCount(2, $versions);
        $this->assertSame($published['version'], $versions[0]['version']);
    }

    public function test_a_published_threshold_change_takes_effect_in_the_matching_engine(): void
    {
        $engine = app(MatchingEngine::class);
        $configs = app(MatchingConfigService::class);
        ['candidate' => $candidate, 'existing' => $existing] = $this->comparison();

        // Measure the pair's composite under a permissive config, so the thresholds
        // below are derived from real scorer output rather than a guessed constant.
        $this->as('admin', 'PUT', '/api/v1/matching/config', $this->payload(0.01))->assertOk();
        $score = $engine->match($candidate, $existing, $configs->active())[0]->score->composite;
        $this->assertGreaterThan(0.1, $score, 'Fixture must score above 0 to be bandable.');
        $this->assertLessThan(0.85, $score, 'Fixture must leave headroom above the score.');

        $above = round($score + 0.05, 4);
        $below = round($score - 0.05, 4);

        // Publish a threshold ABOVE the score → the engine stops calling it a match.
        $this->as('admin', 'PUT', '/api/v1/matching/config', $this->payload($above))->assertOk();
        $this->assertSame(MatchBand::None, $engine->match($candidate, $existing, $configs->active())[0]->band);

        // Publish a LOWER threshold through the SAME endpoint → the identical pair now
        // bands as probable. The console changed engine behaviour, not a copy of it.
        $this->as('admin', 'PUT', '/api/v1/matching/config', $this->payload($below))->assertOk();
        $this->assertSame(MatchBand::Probable, $engine->match($candidate, $existing, $configs->active())[0]->band);

        // An auto-accept threshold at or below the score promotes it to exact.
        $this->as('admin', 'PUT', '/api/v1/matching/config', $this->payload($below, $below))->assertOk();
        $this->assertSame(MatchBand::Exact, $engine->match($candidate, $existing, $configs->active())[0]->band);
    }

    public function test_the_cascade_order_and_weights_round_trip(): void
    {
        $payload = $this->payload(0.7);
        $payload['deterministic_rules'] = [['bvn'], ['nin'], ['phone', 'last_name']];

        $data = $this->as('admin', 'PUT', '/api/v1/matching/config', $payload)->assertOk()->json('data');

        // Priority/cascade order is preserved exactly as published.
        $this->assertSame([['bvn'], ['nin'], ['phone', 'last_name']], $data['deterministic_rules']);
        $this->assertSame(0.5, $data['fuzzy_fields'][0]['weight']);
        $this->assertSame('confirm', $data['exact_match_behaviour']);
    }

    public function test_invalid_configurations_are_rejected_by_the_existing_validation(): void
    {
        // Auto-accept below review is incoherent — the existing request rules catch it.
        $this->as('admin', 'PUT', '/api/v1/matching/config', $this->payload(0.8, 0.5))
            ->assertStatus(422)->assertJsonPath('error.code', 'VALIDATION_ERROR');

        // An unknown field can never enter the cascade.
        $bad = $this->payload(0.7);
        $bad['deterministic_rules'] = [['not_a_field']];
        $this->as('admin', 'PUT', '/api/v1/matching/config', $bad)->assertStatus(422);

        // Nothing was published by the rejected attempts.
        $this->assertSame(1, MatchingConfig::query()->count());
    }

    /* ------------------------------------------------------------------ gating */

    public function test_only_matching_admins_may_publish(): void
    {
        foreach (['officer', 'coordination'] as $key) {
            $this->as($key, 'PUT', '/api/v1/matching/config', $this->payload(0.8))->assertStatus(403);
        }

        // Read access follows the existing matching.view permission.
        $this->as('coordination', 'GET', '/api/v1/matching/config')->assertStatus(403);
        $this->as('admin', 'GET', '/api/v1/matching/config')->assertOk();

        $this->assertSame(1, MatchingConfig::query()->count());
    }

    /* --------------------------------------------- registry validation rules */

    public function test_registry_validation_rules_are_exposed_read_only_from_the_canonical_source(): void
    {
        $data = $this->as('admin', 'GET', '/api/v1/admin/registry-rules')->assertOk()->json('data');

        // Explicitly not editable — identity handling is a locked decision (§9).
        $this->assertFalse($data['editable']);
        $this->assertSame(BeneficiaryRules::IDENTITY_FIELDS, $data['identity_fields']);
        $this->assertSame(BeneficiaryRules::NON_IDENTITY_FIELDS, $data['non_identity_fields']);

        $byField = collect($data['fields'])->keyBy('field');
        $this->assertTrue($byField['nin']['identity']);
        $this->assertContains('digits:11', $byField['nin']['constraints']);
        $this->assertFalse($byField['lga']['identity']);
        $this->assertTrue($byField['last_name']['required']);

        // Phone became a rule OBJECT when its format was made concrete. The page must
        // still read as a SHAPE, because that is the only thing it exists to publish.
        $this->assertContains('nigerian_phone:11', $byField['phone']['constraints']);

        // The guard for the next one: a rule object rendered by class name would still
        // fill this page while telling an admin nothing about what is enforced.
        foreach ($data['fields'] as $entry) {
            foreach ($entry['constraints'] as $token) {
                $this->assertDoesNotMatchRegularExpression(
                    '/^[A-Z]\w*$/',
                    (string) $token,
                    "{$entry['field']} publishes a bare class name instead of the shape it enforces",
                );
            }
        }

        // Every registration rule is represented — the console cannot drift.
        $this->assertSame(
            array_keys(BeneficiaryRules::forRegistration()),
            array_column($data['fields'], 'field'),
        );
    }

    public function test_registry_rules_are_console_only(): void
    {
        foreach (['officer', 'coordination'] as $key) {
            $this->as($key, 'GET', '/api/v1/admin/registry-rules')->assertStatus(403);
        }
    }
}
