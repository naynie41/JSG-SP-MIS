<?php

declare(strict_types=1);

namespace App\Http\Requests\Matching;

use App\Domain\Matching\Enums\ExactMatchBehaviour;
use App\Domain\Matching\Enums\MatchComparator;
use App\Domain\Matching\Enums\MatchField;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Publish a new version of the matching configuration (PRD FR-DUP-02/03). Fields
 * must reference known beneficiary fields; comparators/behaviour must be known
 * enums; the optional auto-accept threshold must be ≥ the review threshold.
 */
class UpdateMatchingConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $fields = MatchField::values();
        $comparators = array_map(static fn (MatchComparator $c) => $c->value, MatchComparator::cases());

        return [
            'deterministic_rules' => ['present', 'array'],
            'deterministic_rules.*' => ['array', 'min:1'],
            'deterministic_rules.*.*' => ['string', Rule::in($fields)],

            'fuzzy_fields' => ['required', 'array', 'min:1'],
            'fuzzy_fields.*.field' => ['required', 'string', Rule::in($fields)],
            'fuzzy_fields.*.comparator' => ['required', 'string', Rule::in($comparators)],
            'fuzzy_fields.*.weight' => ['required', 'numeric', 'gt:0'],

            'review_threshold' => ['required', 'numeric', 'between:0,1'],
            'auto_accept_threshold' => ['nullable', 'numeric', 'between:0,1', 'gte:review_threshold'],
            'exact_match_behaviour' => ['required', Rule::enum(ExactMatchBehaviour::class)],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
