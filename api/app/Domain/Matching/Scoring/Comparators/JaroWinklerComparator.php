<?php

declare(strict_types=1);

namespace App\Domain\Matching\Scoring\Comparators;

/**
 * Jaro-Winkler string similarity (`[0, 1]`) — the default for names, tolerant of
 * typos and transpositions while rewarding a shared prefix. Pure PHP so it runs
 * identically on PostgreSQL and sqlite (no pg_trgm dependency).
 */
class JaroWinklerComparator implements Comparator
{
    private const PREFIX_SCALE = 0.1;

    private const MAX_PREFIX = 4;

    public function compare(?string $a, ?string $b): float
    {
        if ($a === null || $b === null || $a === '' || $b === '') {
            return 0.0;
        }
        if ($a === $b) {
            return 1.0;
        }

        $jaro = $this->jaro($a, $b);
        if ($jaro === 0.0) {
            return 0.0;
        }

        $prefix = $this->commonPrefix($a, $b);

        return $jaro + $prefix * self::PREFIX_SCALE * (1.0 - $jaro);
    }

    private function jaro(string $a, string $b): float
    {
        /** @var list<string> $s1 */
        $s1 = mb_str_split($a);
        /** @var list<string> $s2 */
        $s2 = mb_str_split($b);
        $len1 = count($s1);
        $len2 = count($s2);

        $window = (int) floor(max($len1, $len2) / 2) - 1;
        $window = max($window, 0);

        $s1Matches = array_fill(0, $len1, false);
        $s2Matches = array_fill(0, $len2, false);

        $matches = 0;
        for ($i = 0; $i < $len1; $i++) {
            $start = max(0, $i - $window);
            $end = min($i + $window + 1, $len2);
            for ($j = $start; $j < $end; $j++) {
                if ($s2Matches[$j] || $s1[$i] !== $s2[$j]) {
                    continue;
                }
                $s1Matches[$i] = true;
                $s2Matches[$j] = true;
                $matches++;
                break;
            }
        }

        if ($matches === 0) {
            return 0.0;
        }

        // Count transpositions among the matched characters (in order).
        $transpositions = 0;
        $k = 0;
        for ($i = 0; $i < $len1; $i++) {
            if (! $s1Matches[$i]) {
                continue;
            }
            while (! $s2Matches[$k]) {
                $k++;
            }
            if ($s1[$i] !== $s2[$k]) {
                $transpositions++;
            }
            $k++;
        }
        $transpositions = (int) ($transpositions / 2);

        return (
            $matches / $len1
            + $matches / $len2
            + ($matches - $transpositions) / $matches
        ) / 3.0;
    }

    private function commonPrefix(string $a, string $b): int
    {
        /** @var list<string> $s1 */
        $s1 = mb_str_split($a);
        /** @var list<string> $s2 */
        $s2 = mb_str_split($b);
        $limit = min(self::MAX_PREFIX, count($s1), count($s2));

        $prefix = 0;
        for ($i = 0; $i < $limit; $i++) {
            if ($s1[$i] !== $s2[$i]) {
                break;
            }
            $prefix++;
        }

        return $prefix;
    }
}
