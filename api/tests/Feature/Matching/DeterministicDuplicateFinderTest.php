<?php

declare(strict_types=1);

namespace Tests\Feature\Matching;

use App\Domain\Access\Models\Mda;
use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Matching\Services\MatchingConfigService;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Services\DeterministicDuplicateFinder;
use Database\Seeders\MatchingConfigSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Deterministic matching against the existing registry (PRD FR-DUP-03):
 * index-backed exact NIN/BVN lookup, cross-MDA, exclusion, and no full scan.
 */
class DeterministicDuplicateFinderTest extends TestCase
{
    use RefreshDatabase;

    private DeterministicDuplicateFinder $finder;

    private Mda $mdaA;

    private Mda $mdaB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(MatchingConfigSeeder::class);
        $this->finder = app(DeterministicDuplicateFinder::class);
        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);
    }

    private function config()
    {
        return app(MatchingConfigService::class)->active();
    }

    public function test_finds_an_existing_record_by_normalized_nin_citing_the_key(): void
    {
        $existing = Beneficiary::factory()->withoutBvn()->create([
            'owner_mda_id' => $this->mdaA->id,
            'nin' => '22200000011',
        ]);

        // Differently-formatted NIN still resolves via normalization.
        $results = $this->finder->find(['nin' => '222-000-000-11'], $this->config());

        $this->assertCount(1, $results);
        $this->assertSame($existing->id, $results[0]->reference);
        $this->assertSame(MatchBand::Exact, $results[0]->band);
        $this->assertSame([['type' => 'deterministic', 'fields' => ['nin'], 'matched' => true]], $results[0]->score->explanation);
    }

    public function test_matches_on_bvn_across_mdas(): void
    {
        $existing = Beneficiary::factory()->withoutNin()->create([
            'owner_mda_id' => $this->mdaB->id,
            'bvn' => '33300000022',
        ]);

        $results = $this->finder->find(['bvn' => '33300000022'], $this->config());

        $this->assertCount(1, $results);
        $this->assertSame($existing->id, $results[0]->reference);
    }

    public function test_a_different_nin_does_not_match(): void
    {
        Beneficiary::factory()->withoutBvn()->create(['owner_mda_id' => $this->mdaA->id, 'nin' => '22200000011']);

        $this->assertSame([], $this->finder->find(['nin' => '99999999999'], $this->config()));
    }

    public function test_a_candidate_without_any_key_never_scans_and_returns_nothing(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);

        $this->assertNull($this->finder->buildQuery(['first_name' => 'Amina'], $this->config()));
        $this->assertSame([], $this->finder->find(['first_name' => 'Amina'], $this->config()));
    }

    public function test_can_exclude_a_record_by_id(): void
    {
        $existing = Beneficiary::factory()->withoutBvn()->create(['owner_mda_id' => $this->mdaA->id, 'nin' => '22200000011']);

        $this->assertSame([], $this->finder->find(['nin' => '22200000011'], $this->config(), $existing->id));
    }

    public function test_the_exact_lookup_uses_an_index_and_does_not_full_scan(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            $this->markTestSkipped('Index-plan assertion is written for the sqlite test database.');
        }

        Beneficiary::factory()->withoutBvn()->create(['owner_mda_id' => $this->mdaA->id, 'nin' => '22200000011']);

        $query = $this->finder->buildQuery(['nin' => '22200000011'], $this->config());
        $this->assertNotNull($query);

        $plan = DB::select('EXPLAIN QUERY PLAN '.$query->toSql(), $query->getBindings());
        $detail = collect($plan)->pluck('detail')->implode(' | ');

        $this->assertStringContainsString('USING INDEX', $detail, "Plan was: {$detail}");
        $this->assertStringNotContainsString('SCAN', $detail, "Full scan in plan: {$detail}");
    }
}
