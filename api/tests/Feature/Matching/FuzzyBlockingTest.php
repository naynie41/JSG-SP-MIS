<?php

declare(strict_types=1);

namespace Tests\Feature\Matching;

use App\Domain\Access\Models\Mda;
use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Matching\Services\MatchingConfigService;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Services\CandidateGatherer;
use App\Domain\Registry\Services\FuzzyDuplicateFinder;
use Database\Seeders\MatchingConfigSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Candidate blocking keeps fuzzy matching bounded and fast (PRD FR-DUP-03,
 * NFR-PERF-01): on a large registry the gathered candidate set stays small, uses
 * the index, and a single lookup returns well within the 5s target.
 */
class FuzzyBlockingTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MatchingConfigSeeder::class);
        $this->mda = Mda::factory()->create();
    }

    private function config()
    {
        return app(MatchingConfigService::class)->active();
    }

    /**
     * @return array<string, mixed>
     */
    private function probe(): array
    {
        return [
            'last_name' => 'Sani',
            'first_name' => 'Muhammadu',
            'date_of_birth' => '1990-05-10',
            'phone' => '08031234567',
            'lga' => 'dutse',
            'ward' => 'Ward 3',
        ];
    }

    /**
     * @return list<string> ids of the seeded near-duplicates
     */
    private function seedRegistry(): array
    {
        // A large, unrelated registry.
        Beneficiary::factory()->count(200)->create(['owner_mda_id' => $this->mda->id]);

        // A handful of near-duplicates that share the candidate's block key.
        $nearDupes = collect([
            ['last_name' => 'Saani', 'first_name' => 'Mohammed'],
            ['last_name' => 'Sani', 'first_name' => 'Muhammad'],
            ['last_name' => 'Sanni', 'first_name' => 'Muhammadu'],
        ])->map(fn (array $attrs) => Beneficiary::factory()->withoutNin()->withoutBvn()->create([
            'owner_mda_id' => $this->mda->id,
            'date_of_birth' => '1990-05-10',
            'phone' => '08031234567',
            'lga' => 'dutse',
            'ward' => 'Ward 3',
            ...$attrs,
        ])->id);

        return $nearDupes->all();
    }

    public function test_blocking_bounds_the_candidate_set_on_a_large_registry(): void
    {
        $nearDupeIds = $this->seedRegistry();

        $candidates = app(CandidateGatherer::class)->gather($this->probe(), $this->config());
        $ids = array_column($candidates, 'id');

        // The registry has 203 rows, but blocking narrows to a tiny bounded set…
        $this->assertGreaterThanOrEqual(3, count($candidates));
        $this->assertLessThanOrEqual(20, count($candidates), 'Blocking should keep the candidate set small');
        $this->assertLessThanOrEqual(CandidateGatherer::MAX_CANDIDATES, count($candidates));

        // …that includes every near-duplicate.
        foreach ($nearDupeIds as $id) {
            $this->assertContains($id, $ids);
        }
    }

    public function test_the_block_query_uses_an_index_and_does_not_full_scan(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            $this->markTestSkipped('Index-plan assertion is written for the sqlite test database.');
        }

        $this->seedRegistry();

        $query = app(CandidateGatherer::class)->buildQuery($this->probe(), $this->config());
        $this->assertNotNull($query);

        $plan = DB::select('EXPLAIN QUERY PLAN '.$query->toSql(), $query->getBindings());
        $detail = collect($plan)->pluck('detail')->implode(' | ');

        $this->assertStringContainsString('USING INDEX', $detail, "Plan was: {$detail}");
        $this->assertStringNotContainsString('SCAN', $detail, "Full scan in plan: {$detail}");
    }

    public function test_a_single_lookup_returns_the_near_duplicates_within_the_target(): void
    {
        $nearDupeIds = $this->seedRegistry();

        $start = microtime(true);
        $results = app(FuzzyDuplicateFinder::class)->find($this->probe(), $this->config());
        $elapsed = microtime(true) - $start;

        // Well within the 5s NFR (bounded candidate set).
        $this->assertLessThan(3.0, $elapsed);

        $matchedIds = array_map(fn ($r) => $r->reference, $results);
        foreach ($nearDupeIds as $id) {
            $this->assertContains($id, $matchedIds);
        }
        foreach ($results as $result) {
            $this->assertContains($result->band, [MatchBand::Exact, MatchBand::Probable]);
        }
    }
}
