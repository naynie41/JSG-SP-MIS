<?php

declare(strict_types=1);

namespace Tests\Feature\Matching;

use App\Domain\Matching\Engine\DuplicateCascade;
use App\Domain\Matching\Engine\MatchResult;
use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Services\MatchingConfigService;
use Database\Seeders\MatchingConfigSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The ordered matching cascade on the SEEDED DEFAULT config (PRD §9): exact NIN →
 * exact BVN → fuzzy name/phone, stopping at the first exact stage, and skipping a
 * stage when its identifier is absent. Pure fixtures (in-memory records) so the
 * ordering, stop-on-exact and fall-through behaviour is proven directly.
 */
class MatchingCascadeTest extends TestCase
{
    use RefreshDatabase;

    private DuplicateCascade $cascade;

    private MatchingConfig $config;

    protected function setUp(): void
    {
        parent::setUp();

        // Prove behaviour against the actual shipped DEFAULT config.
        $this->seed(MatchingConfigSeeder::class);
        $this->cascade = app(DuplicateCascade::class);
        $this->config = app(MatchingConfigService::class)->active();
    }

    /**
     * @param  array<string, mixed>  $candidate
     * @param  list<array<string, mixed>>  $existing
     * @return array{band: MatchBand, stage: list<string>|null, results: list<MatchResult>}
     */
    private function evaluate(array $candidate, array $existing): array
    {
        return $this->cascade->evaluate($candidate, $existing, $this->config);
    }

    /** @param array{results: list<MatchResult>} $outcome */
    private function references(array $outcome): array
    {
        return array_map(static fn ($r) => $r->reference, $outcome['results']);
    }

    public function test_default_config_encodes_the_ordered_cascade(): void
    {
        $this->assertSame([['nin'], ['bvn']], $this->config->deterministicKeySets());
        $fuzzy = array_column($this->config->fuzzyFields(), 'field');
        $this->assertContains('last_name', $fuzzy);
        $this->assertContains('phone', $fuzzy);
    }

    public function test_nin_is_evaluated_before_bvn_and_stops(): void
    {
        // A matches on NIN; B matches on BVN. Both identifiers are on the candidate.
        $a = ['id' => 'A', 'nin' => '22200000011', 'last_name' => 'Ade', 'first_name' => 'Bola', 'date_of_birth' => '1980-01-01'];
        $b = ['id' => 'B', 'bvn' => '33300000022', 'last_name' => 'Zango', 'first_name' => 'Umar', 'date_of_birth' => '1975-05-05'];

        $outcome = $this->evaluate(['nin' => '22200000011', 'bvn' => '33300000022'], [$a, $b]);

        // The NIN stage fires first and STOPS — the BVN match (B) is never surfaced.
        $this->assertSame(MatchBand::Exact, $outcome['band']);
        $this->assertSame(['nin'], $outcome['stage']);
        $this->assertSame(['A'], $this->references($outcome));
    }

    public function test_an_exact_match_stops_before_fuzzy_and_suppresses_it(): void
    {
        // A is an exact NIN match; C is a strong fuzzy match (name+dob+phone) that
        // would otherwise be probable.
        $exact = ['id' => 'A', 'nin' => '22200000011', 'last_name' => 'Bello', 'first_name' => 'Musa', 'date_of_birth' => '1990-01-01', 'phone' => '08030000001'];
        $fuzzy = ['id' => 'C', 'last_name' => 'Bello', 'first_name' => 'Musa', 'date_of_birth' => '1990-01-01', 'phone' => '08030000001'];

        $candidate = ['nin' => '22200000011', 'last_name' => 'Bello', 'first_name' => 'Musa', 'date_of_birth' => '1990-01-01', 'phone' => '08030000001'];
        $outcome = $this->evaluate($candidate, [$exact, $fuzzy]);

        $this->assertSame(MatchBand::Exact, $outcome['band']);
        $this->assertSame(['nin'], $outcome['stage']);
        // Only the exact match — the probable fuzzy candidate C is not reported.
        $this->assertSame(['A'], $this->references($outcome));
    }

    public function test_missing_nin_falls_through_to_the_bvn_stage(): void
    {
        $b = ['id' => 'B', 'bvn' => '33300000022'];

        // No NIN on the candidate → the NIN stage is skipped, BVN fires.
        $outcome = $this->evaluate(['bvn' => '33300000022'], [$b]);

        $this->assertSame(MatchBand::Exact, $outcome['band']);
        $this->assertSame(['bvn'], $outcome['stage']);
        $this->assertSame(['B'], $this->references($outcome));
    }

    public function test_present_nin_with_no_match_falls_through_to_bvn(): void
    {
        // Candidate HAS a NIN but nothing matches it; its BVN matches B.
        $b = ['id' => 'B', 'nin' => '11100000001', 'bvn' => '33300000022'];

        $outcome = $this->evaluate(['nin' => '99900000099', 'bvn' => '33300000022'], [$b]);

        $this->assertSame(MatchBand::Exact, $outcome['band']);
        $this->assertSame(['bvn'], $outcome['stage']);
        $this->assertSame(['B'], $this->references($outcome));
    }

    public function test_missing_both_identifiers_falls_through_to_fuzzy(): void
    {
        $d = ['id' => 'D', 'last_name' => 'Bello', 'first_name' => 'Musa', 'date_of_birth' => '1990-01-01', 'phone' => '08030000001'];

        // Neither NIN nor BVN → straight to the fuzzy pass (name+dob+phone → probable).
        $candidate = ['last_name' => 'Bello', 'first_name' => 'Musa', 'date_of_birth' => '1990-01-01', 'phone' => '08030000001'];
        $outcome = $this->evaluate($candidate, [$d]);

        $this->assertSame(MatchBand::Probable, $outcome['band']);
        $this->assertNull($outcome['stage']);
        $this->assertSame(['D'], $this->references($outcome));
    }

    public function test_no_identifier_and_no_fuzzy_similarity_is_none(): void
    {
        $x = ['id' => 'X', 'last_name' => 'Zango', 'first_name' => 'Umar', 'date_of_birth' => '1975-05-05', 'phone' => '08099999999'];

        $candidate = ['last_name' => 'Bello', 'first_name' => 'Musa', 'date_of_birth' => '1990-01-01', 'phone' => '08030000001'];
        $outcome = $this->evaluate($candidate, [$x]);

        $this->assertSame(MatchBand::None, $outcome['band']);
        $this->assertSame([], $outcome['results']);
    }
}
