<?php

declare(strict_types=1);

namespace App\Domain\Matching\Engine;

use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Matching\Scoring\MatchScore;

/**
 * The engine's verdict for one candidate-vs-existing comparison: the outcome band
 * plus the score and its per-rule explanation (PRD FR-DUP-03/06). `reference` is
 * the existing record's id when supplied.
 */
final readonly class MatchResult
{
    public function __construct(
        public ?string $reference,
        public MatchBand $band,
        public MatchScore $score,
    ) {}

    /**
     * @return array{reference: string|null, band: string, score: float, deterministic: bool, explanation: list<array<string, mixed>>}
     */
    public function toArray(): array
    {
        return [
            'reference' => $this->reference,
            'band' => $this->band->value,
            'score' => round($this->score->composite, 4),
            'deterministic' => $this->score->deterministic,
            'explanation' => $this->score->explanation,
        ];
    }
}
