<?php

declare(strict_types=1);

namespace Tests\Unit\Matching;

use App\Domain\Matching\Engine\DeterministicMatcher;
use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Scoring\FieldNormalizer;
use PHPUnit\Framework\TestCase;

class DeterministicMatcherTest extends TestCase
{
    private DeterministicMatcher $matcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->matcher = new DeterministicMatcher(new FieldNormalizer);
    }

    private function config(): MatchingConfig
    {
        return new MatchingConfig([
            'deterministic_rules' => [['nin'], ['bvn']],
            'fuzzy_fields' => [],
            'review_threshold' => 0.75,
            'auto_accept_threshold' => 0.92,
            'exact_match_behaviour' => 'confirm',
        ]);
    }

    public function test_shared_normalized_nin_is_an_exact_match_citing_the_key(): void
    {
        $results = $this->matcher->match(
            ['nin' => '222-000-000-11'],
            [['id' => 'a', 'nin' => '22200000011']],
            $this->config(),
        );

        $this->assertCount(1, $results);
        $this->assertSame('a', $results[0]->reference);
        $this->assertSame(MatchBand::Exact, $results[0]->band);
        $this->assertSame([['type' => 'deterministic', 'fields' => ['nin'], 'matched' => true]], $results[0]->score->explanation);
    }

    public function test_shared_bvn_matches_on_the_bvn_key(): void
    {
        $results = $this->matcher->match(
            ['bvn' => '333 000 000 22'],
            [['id' => 'b', 'bvn' => '33300000022']],
            $this->config(),
        );

        $this->assertCount(1, $results);
        $this->assertSame(['bvn'], $results[0]->score->explanation[0]['fields']);
    }

    public function test_matching_on_both_keys_cites_both(): void
    {
        $results = $this->matcher->match(
            ['nin' => '22200000011', 'bvn' => '33300000022'],
            [['id' => 'c', 'nin' => '22200000011', 'bvn' => '33300000022']],
            $this->config(),
        );

        $fired = array_map(fn (array $e) => $e['fields'], $results[0]->score->explanation);
        $this->assertSame([['nin'], ['bvn']], $fired);
    }

    public function test_differing_keys_do_not_match(): void
    {
        $results = $this->matcher->match(
            ['nin' => '11111111111'],
            [['id' => 'a', 'nin' => '22200000011']],
            $this->config(),
        );

        $this->assertSame([], $results);
    }

    public function test_null_candidate_key_never_matches(): void
    {
        $results = $this->matcher->match(
            ['nin' => null, 'bvn' => ''],
            [['id' => 'a', 'nin' => '22200000011', 'bvn' => '33300000022']],
            $this->config(),
        );

        $this->assertSame([], $results);
    }

    public function test_null_existing_key_never_matches(): void
    {
        $results = $this->matcher->match(
            ['nin' => '22200000011'],
            [['id' => 'a', 'nin' => null]],
            $this->config(),
        );

        $this->assertSame([], $results);
    }

    public function test_operates_within_a_batch_returning_only_the_shared_record(): void
    {
        $results = $this->matcher->match(
            ['nin' => '22200000011'],
            [
                ['id' => 'row-1', 'nin' => '22200000011'],
                ['id' => 'row-2', 'nin' => '99999999999'],
                ['id' => 'row-3', 'nin' => null],
            ],
            $this->config(),
        );

        $this->assertCount(1, $results);
        $this->assertSame('row-1', $results[0]->reference);
    }
}
