<?php

declare(strict_types=1);

namespace Tests\Feature\Matching;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Matching\Enums\ExactMatchBehaviour;
use App\Domain\Matching\Services\MatchingConfigService;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Services\FuzzyDuplicateFinder;
use Database\Seeders\MatchFixtureSeeder;
use Database\Seeders\MatchingConfigSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * End-to-end matching ACCURACY on the labelled, cross-MDA fixture set
 * (PRD FR-DUP-01/03/04). Runs each labelled probe through the same registry
 * duplicate check used by import screening and the standalone lookup, reports
 * precision/recall, asserts the exact/probable/none bands behave, proves the check
 * spans MDAs, and asserts nothing is silently auto-merged.
 */
class DuplicateAccuracyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MatchingConfigSeeder::class);
        $this->seed(MatchFixtureSeeder::class);
    }

    public function test_precision_recall_and_band_behaviour_on_the_fixture_set(): void
    {
        $config = app(MatchingConfigService::class)->active();
        $finder = app(FuzzyDuplicateFinder::class);

        $tp = $fp = $fn = $tn = 0;

        foreach (MatchFixtureSeeder::probes() as $label => $probe) {
            $results = $finder->find($probe['query'], $config);
            $top = $results[0] ?? null;
            $predictedBand = $top?->band->value ?? 'none';
            $predictedDuplicate = $top !== null;

            // The band must be exactly what the fixture is labelled with.
            $this->assertSame(
                $probe['band'],
                $predictedBand,
                "Fixture [{$label}] expected band {$probe['band']}, got {$predictedBand}",
            );

            if ($probe['duplicate'] && $predictedDuplicate) {
                $tp++;
            } elseif (! $probe['duplicate'] && $predictedDuplicate) {
                $fp++;
            } elseif ($probe['duplicate']) {
                $fn++;
            } else {
                $tn++;
            }

            // A matched probe must resolve to the record in the expected owner MDA
            // (register once, serve many — detection spans MDAs).
            if ($probe['owner'] !== null && $top !== null) {
                $matched = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->find($top->reference);
                $this->assertNotNull($matched);
                $expectedMda = Mda::query()->where('name', $probe['owner'] === 'A' ? MatchFixtureSeeder::MDA_A : MatchFixtureSeeder::MDA_B)->firstOrFail();
                $this->assertSame($expectedMda->id, $matched->owner_mda_id, "Fixture [{$label}] matched the wrong MDA's record");
            }
        }

        $precision = (float) $tp / max(1, $tp + $fp);
        $recall = (float) $tp / max(1, $tp + $fn);

        fwrite(STDERR, sprintf(
            "\n[duplicate fixtures] precision=%.2f recall=%.2f (tp=%d fp=%d fn=%d tn=%d)\n",
            $precision, $recall, $tp, $fp, $fn, $tn,
        ));

        $this->assertSame(1.0, $recall, 'Every labelled duplicate should be caught');
        $this->assertSame(1.0, $precision, 'No non-duplicate should be flagged');
    }

    public function test_detection_spans_both_mdas(): void
    {
        $config = app(MatchingConfigService::class)->active();
        $finder = app(FuzzyDuplicateFinder::class);

        $matchedOwners = [];
        foreach (MatchFixtureSeeder::probes() as $probe) {
            $top = $finder->find($probe['query'], $config)[0] ?? null;
            if ($top !== null) {
                $matched = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->find($top->reference);
                $matchedOwners[$matched?->owner_mda_id] = true;
            }
        }

        // Matches were found against records owned by BOTH fixture MDAs.
        $this->assertGreaterThanOrEqual(2, count($matchedOwners), 'Duplicate detection should span both MDAs');
    }

    public function test_the_check_is_read_only_and_never_silently_auto_merges(): void
    {
        $config = app(MatchingConfigService::class)->active();
        $finder = app(FuzzyDuplicateFinder::class);

        // Default behaviour surfaces exact matches for confirmation — never auto-links.
        $this->assertSame(ExactMatchBehaviour::Confirm, $config->exact_match_behaviour);

        $before = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->count();

        foreach (MatchFixtureSeeder::probes() as $probe) {
            $finder->find($probe['query'], $config);
        }

        // Running the duplicate check created/merged/deleted nothing.
        $after = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->count();
        $this->assertSame($before, $after, 'The duplicate check must not create or merge any record');
    }
}
