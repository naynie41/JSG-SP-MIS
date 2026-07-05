<?php

declare(strict_types=1);

namespace App\Domain\Registry\Imports;

use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Support\BeneficiaryRules;
use Illuminate\Support\Facades\Validator;

/**
 * Normalises and validates a single import row using the SAME rules as manual
 * registration (BeneficiaryRules), then classifies each failure per the PRD §9
 * locked decision:
 *   - A PRESENT-but-malformed IDENTITY field (name/phone/NIN/BVN) rejects the whole
 *     row — it is never partial-saved (FR-REG-05). Absent optional NIN/BVN is valid.
 *   - A NON-IDENTITY field failure drops/flags just that field (nulled in the
 *     returned payload); the row still saves (FR-REG-09).
 *   - A NIN/BVN uniqueness hit is a DUPLICATE signal, not a malformed-field reject;
 *     it is surfaced separately so the duplicate/serve flow (not the error report)
 *     handles it.
 * The three buckets feed the preview + batch error report (FR-REG-06).
 */
class ImportRowValidator
{
    private const FIELDS = [
        'first_name', 'middle_name', 'last_name', 'nin', 'bvn', 'phone',
        'date_of_birth', 'gender', 'address', 'lga', 'ward',
    ];

    /**
     * @param  array<string, string>  $values  header-keyed source values
     * @return array{
     *     payload: array<string, mixed>,
     *     identity_errors: list<array{field: string, message: string}>,
     *     dropped_fields: list<array{field: string, message: string}>,
     *     duplicate_errors: list<array{field: string, message: string}>,
     * }
     */
    public function validate(array $values): array
    {
        $payload = $this->normalise($values);

        $validator = Validator::make($payload, BeneficiaryRules::forRegistration(), BeneficiaryRules::messages());

        $messages = $validator->errors();      // triggers validation
        $failedRules = $validator->failed();   // field => [RuleName => params]

        $identityErrors = [];
        $droppedFields = [];
        $duplicateErrors = [];

        foreach ($failedRules as $field => $rules) {
            $field = (string) $field;
            /** @var list<string> $fieldMessages */
            $fieldMessages = $messages->get($field);

            // Non-identity failure: drop the offending value and keep the row.
            if (! BeneficiaryRules::isIdentityField($field)) {
                foreach ($fieldMessages as $message) {
                    $droppedFields[] = ['field' => $field, 'message' => (string) $message];
                }
                $payload[$field] = null;

                continue;
            }

            // Identity field. A pure uniqueness hit is a duplicate, not malformed.
            $ruleNames = array_keys($rules);
            $isUniqueOnly = $ruleNames === ['Unique'];
            $bucket = $isUniqueOnly ? 'duplicate' : 'identity';

            foreach ($fieldMessages as $message) {
                $entry = ['field' => $field, 'message' => (string) $message];
                if ($bucket === 'duplicate') {
                    $duplicateErrors[] = $entry;
                } else {
                    $identityErrors[] = $entry;
                }
            }
        }

        return [
            'payload' => $payload,
            'identity_errors' => $identityErrors,
            'dropped_fields' => $droppedFields,
            'duplicate_errors' => $duplicateErrors,
        ];
    }

    /**
     * @param  array<string, string>  $values
     * @return array<string, mixed>
     */
    private function normalise(array $values): array
    {
        $payload = [];
        foreach (self::FIELDS as $field) {
            $value = $values[$field] ?? '';
            $payload[$field] = $value === '' ? null : $value;
        }

        // Identifiers: same digit normalisation as the model/manual path.
        $payload['nin'] = Beneficiary::normalizeDigits($payload['nin']);
        $payload['bvn'] = Beneficiary::normalizeDigits($payload['bvn']);
        $payload['phone'] = Beneficiary::normalizeDigits($payload['phone']);

        // Enum-ish fields: fold to the canonical lower snake_case the enums use.
        if ($payload['gender'] !== null) {
            $payload['gender'] = strtolower(trim($payload['gender']));
        }
        if ($payload['lga'] !== null) {
            $payload['lga'] = (string) preg_replace('/[\s\-]+/', '_', strtolower(trim($payload['lga'])));
        }

        return $payload;
    }
}
