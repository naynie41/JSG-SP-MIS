<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Registry\Support\BeneficiaryRules;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules\Enum;

/**
 * The CANONICAL registry validation rules, exposed READ-ONLY for the administration
 * console (PRD FR-REG-04/05).
 *
 * These are deliberately NOT admin-editable: identity-field handling is a locked
 * decision (CLAUDE.md §9) — a present-but-malformed identity field rejects the whole
 * row, and an identity field is never partial-saved. Publishing them here is for
 * transparency only, and the payload is derived from {@see BeneficiaryRules} itself,
 * so the console can never drift from what the ingestion paths actually enforce.
 */
class RegistryRulesController extends Controller
{
    public function index(): JsonResponse
    {
        $rules = BeneficiaryRules::forRegistration();

        $fields = [];
        foreach ($rules as $field => $constraints) {
            $fields[] = [
                'field' => $field,
                'identity' => BeneficiaryRules::isIdentityField($field),
                'required' => in_array('required', $constraints, true),
                'constraints' => array_values(array_map(
                    fn ($rule): string => $this->describe($rule),
                    $constraints,
                )),
            ];
        }

        return ApiResponse::success([
            'editable' => false,
            'policy' => [
                'identity' => 'Present but malformed rejects the WHOLE row — an identity field is never partial-saved.',
                'non_identity' => 'A failure drops or flags just that field; the row still saves.',
            ],
            'identity_fields' => BeneficiaryRules::IDENTITY_FIELDS,
            'non_identity_fields' => BeneficiaryRules::NON_IDENTITY_FIELDS,
            'fields' => $fields,
        ]);
    }

    /** Render a Laravel rule (string or rule object) as a readable token. */
    private function describe(mixed $rule): string
    {
        if (is_string($rule)) {
            return $rule;
        }

        if ($rule instanceof Enum) {
            return 'enum';
        }

        return class_basename($rule::class);
    }
}
