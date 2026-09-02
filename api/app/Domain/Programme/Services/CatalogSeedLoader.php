<?php

declare(strict_types=1);

namespace App\Domain\Programme\Services;

use App\Domain\Access\Enums\MdaType;
use App\Domain\Matching\Scoring\Comparators\JaroWinklerComparator;
use RuntimeException;

/**
 * Reads the MDA programme inventory and prepares it for seeding (PRD §10).
 *
 * Deliberately does no writing. Parsing, type inference and duplicate detection are
 * all decisions worth testing on their own, and the seeders that consume this stay
 * thin enough to read.
 */
class CatalogSeedLoader
{
    /**
     * Name-similarity threshold for FLAGGING a possible duplicate.
     *
     * 0.85 was chosen against this actual inventory, not in the abstract: it surfaces
     * all five exact collisions (Goat revolving scheme, Cluster farming system and
     * Agric inputs support all appear under both Ministry of Agric and JARDA) plus
     * the real near-misses — "Cash transfer to poor and vulnerable groups" vs "Cash
     * assistance to poor and vulnerable" at 0.909, "Scholarship" vs "Scholarship to
     * students" at 0.896 — in 24 pairs across 112 programmes. Raising it to 0.90
     * drops to 8 pairs and loses genuine duplicates; lowering it to 0.80 adds noise.
     */
    public const SIMILARITY_THRESHOLD = 0.85;

    public function __construct(private readonly JaroWinklerComparator $similarity) {}

    /**
     * @return array{mdas: list<array<string, mixed>>, programmes: list<array<string, mixed>>}
     */
    public function load(?string $path = null): array
    {
        $path ??= database_path('data/mda-programme-seed.json');

        if (! is_file($path)) {
            throw new RuntimeException("Catalog seed file not found at {$path}.");
        }

        // Deliberately not annotated with the shape we HOPE for: the checks below are
        // the only thing that establishes it, and a docblock claiming the structure
        // would make them look redundant while the file could still be anything.
        $decoded = json_decode((string) file_get_contents($path), true);

        if (! is_array($decoded) || ! isset($decoded['mdas']) || ! is_array($decoded['mdas'])) {
            throw new RuntimeException("Catalog seed file at {$path} is not the expected shape.");
        }

        /** @var list<array<string, mixed>> $entries */
        $entries = $decoded['mdas'];

        $mdas = [];
        $programmes = [];

        foreach ($entries as $mda) {
            $name = trim((string) ($mda['name'] ?? ''));

            if ($name === '') {
                continue;
            }

            $inferred = $this->inferType($name);

            $mdas[] = [
                'name' => $name,
                'type' => $inferred['type'],
                'type_confidence' => $inferred['confidence'],
            ];

            foreach ($mda['programmes'] ?? [] as $programme) {
                $programmeName = trim((string) ($programme['name'] ?? ''));

                if ($programmeName === '') {
                    continue;
                }

                $programmes[] = [
                    'name' => $programmeName,
                    'mda' => $name,
                    // Stored verbatim: see the migration for why this is not
                    // normalised into a controlled vocabulary here.
                    'target_group' => trim((string) ($programme['target'] ?? '')) ?: null,
                    'is_automated' => (bool) ($programme['automated'] ?? false),
                ];
            }
        }

        return ['mdas' => $mdas, 'programmes' => $programmes];
    }

    /**
     * Infer an MdaType from the name.
     *
     * `mdas.type` is NOT NULL but the inventory does not carry it, so it has to come
     * from somewhere. Keyword inference is honest for "Ministry of Health" and
     * "…Development Agency"; it is a guess for acronyms like SEMA or JICHMA. Every
     * result therefore carries a confidence, and the low-confidence ones are printed
     * for correction rather than quietly accepted.
     *
     * @return array{type: MdaType, confidence: string}
     */
    public function inferType(string $name): array
    {
        $haystack = mb_strtolower($name);

        if (str_contains($haystack, 'ministry')) {
            return ['type' => MdaType::Ministry, 'confidence' => 'high'];
        }

        if (str_contains($haystack, 'directorate') || str_contains($haystack, 'department')) {
            return ['type' => MdaType::Department, 'confidence' => 'high'];
        }

        foreach (['agency', 'board', 'authority', 'commission'] as $marker) {
            if (str_contains($haystack, $marker)) {
                return ['type' => MdaType::Agency, 'confidence' => 'high'];
            }
        }

        // Acronyms and bare names (SEMA, JICHMA, FADAMA, SIP, NOMADIC, Contributory
        // Pension). Agency is the right default for a Jigawa parastatal, but nothing
        // in the name says so — hence low.
        return ['type' => MdaType::Agency, 'confidence' => 'low'];
    }

    /**
     * Pairs of programme names similar enough to be the SAME catalog entry run by
     * different MDAs — which is exactly what a shared catalog is for.
     *
     * Reported, never merged. "N-power programme (N-Teach)" and "N-power programme
     * (J-health)" score 0.952 and are plausibly distinct; "Old age social protection
     * scheme" and "Old age non-contributory health scheme" score 0.872 and clearly
     * are. No threshold separates those, so a person decides.
     *
     * @param  list<array<string, mixed>>  $programmes
     * @return list<array<string, mixed>>
     */
    public function findLikelyDuplicates(array $programmes): array
    {
        $pairs = [];
        $count = count($programmes);

        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $score = $this->similarity->compare(
                    $this->normalise((string) $programmes[$i]['name']),
                    $this->normalise((string) $programmes[$j]['name']),
                );

                if ($score < self::SIMILARITY_THRESHOLD) {
                    continue;
                }

                $pairs[] = [
                    'similarity' => round($score, 3),
                    'exact' => $score >= 1.0,
                    'a' => ['name' => $programmes[$i]['name'], 'mda' => $programmes[$i]['mda']],
                    'b' => ['name' => $programmes[$j]['name'], 'mda' => $programmes[$j]['mda']],
                ];
            }
        }

        usort($pairs, fn (array $x, array $y) => $y['similarity'] <=> $x['similarity']);

        return $pairs;
    }

    /**
     * Comparison key for a programme name: case, punctuation and spacing carry no
     * meaning here ("Livelihood programme (J - CARES)" vs "Livelihood Programme
     * (J - CARES)" are the same thing typed twice).
     */
    public function normalise(string $name): string
    {
        $lowered = mb_strtolower(trim($name));
        $stripped = preg_replace('/[^a-z0-9 ]+/', ' ', $lowered) ?? $lowered;
        $collapsed = preg_replace('/\s+/', ' ', $stripped) ?? $stripped;

        return trim($collapsed);
    }
}
