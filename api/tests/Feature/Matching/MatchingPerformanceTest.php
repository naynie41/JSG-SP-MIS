<?php

declare(strict_types=1);

namespace Tests\Feature\Matching;

use App\Domain\Access\Models\Mda;
use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Services\MatchingConfigService;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Services\BatchDuplicateScreener;
use App\Domain\Registry\Services\CandidateGatherer;
use App\Domain\Registry\Services\FuzzyDuplicateFinder;
use Database\Seeders\MatchingConfigSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Matching PERFORMANCE on a large registry (PRD NFR-PERF-01). Candidate blocking
 * keeps both a single duplicate check and a whole batch bounded and fast — the
 * cost scales with the (tiny) blocked candidate set, not the registry size.
 */
class MatchingPerformanceTest extends TestCase
{
    use RefreshDatabase;

    private const REGISTRY_SIZE = 1200;

    private Mda $mdaA;

    private Mda $mdaB;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MatchingConfigSeeder::class);
        $this->mdaA = Mda::factory()->create();
        $this->mdaB = Mda::factory()->create();

        // A large, mostly-unrelated registry split across two MDAs.
        Beneficiary::factory()->count(self::REGISTRY_SIZE / 2)->create(['owner_mda_id' => $this->mdaA->id]);
        Beneficiary::factory()->count(self::REGISTRY_SIZE / 2)->create(['owner_mda_id' => $this->mdaB->id]);

        // One planted near-duplicate to find amid the noise.
        Beneficiary::factory()->withoutNin()->withoutBvn()->create([
            'owner_mda_id' => $this->mdaB->id,
            'first_name' => 'Mohammed', 'last_name' => 'Saani',
            'date_of_birth' => '1990-05-10', 'phone' => '08031234567', 'lga' => 'dutse', 'ward' => 'Ward 9',
        ]);
    }

    private function config(): MatchingConfig
    {
        return app(MatchingConfigService::class)->active();
    }

    /** @return array<string, mixed> */
    private function probe(): array
    {
        return ['first_name' => 'Muhammadu', 'last_name' => 'Sani', 'date_of_birth' => '1990-05-10', 'phone' => '08031234567', 'lga' => 'dutse', 'ward' => 'Ward 3'];
    }

    public function test_blocking_keeps_the_candidate_set_bounded_on_a_large_registry(): void
    {
        $candidates = app(CandidateGatherer::class)->gather($this->probe(), $this->config());

        // 1,200+ rows in the registry, but blocking narrows to a tiny bounded set.
        $this->assertLessThanOrEqual(25, count($candidates), 'Blocking should keep the candidate set small');
        $this->assertLessThanOrEqual(CandidateGatherer::MAX_CANDIDATES, count($candidates));
    }

    public function test_a_single_duplicate_check_returns_within_target(): void
    {
        $start = microtime(true);
        $results = app(FuzzyDuplicateFinder::class)->find($this->probe(), $this->config());
        $elapsed = microtime(true) - $start;

        // Comfortably within the 5s NFR-PERF-01 target thanks to blocking.
        $this->assertLessThan(3.0, $elapsed, "Single check took {$elapsed}s");
        $this->assertNotEmpty($results);
        $this->assertContains($results[0]->band, [MatchBand::Exact, MatchBand::Probable]);
    }

    public function test_batch_matching_stays_bounded_and_within_target(): void
    {
        // A 50-row batch screened against the large registry AND against earlier
        // rows in the same batch (within-batch blocking).
        $screener = app(BatchDuplicateScreener::class);
        $config = $this->config();

        $rows = [];
        for ($i = 1; $i <= 49; $i++) {
            $rows[] = ['first_name' => 'Unique'.$i, 'last_name' => 'Person'.$i, 'date_of_birth' => '19'.str_pad((string) (50 + $i % 40), 2, '0', STR_PAD_LEFT).'-01-01', 'phone' => '0808'.str_pad((string) $i, 7, '0', STR_PAD_LEFT)];
        }
        // Row 50 duplicates the planted registry near-duplicate.
        $rows[] = $this->probe();

        $start = microtime(true);
        $lastBand = null;
        foreach ($rows as $number => $row) {
            $outcome = $screener->screen($row, $number + 1, $config);
            $lastBand = $outcome['band'];
        }
        $elapsed = microtime(true) - $start;

        // A whole batch stays well within target (cost scales with the blocked set).
        $this->assertLessThan(5.0, $elapsed, "Batch of 50 took {$elapsed}s");
        // The planted duplicate row was still detected amid the noise.
        $this->assertContains($lastBand, ['exact', 'probable']);
    }
}
