<?php

declare(strict_types=1);

namespace App\Domain\Registry\Imports;

use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Support\BeneficiaryRules;
use Illuminate\Support\Facades\Validator;

/**
 * Normalises and validates a single import row using the SAME rules as manual
 * registration (BeneficiaryRules), so a file row is accepted iff the equivalent
 * manual registration would be (PRD FR-REG-05). Returns the cleaned payload plus
 * a structured, row-level error list for the preview (FR-REG-06).
 */
class ImportRowValidator
{
    private const FIELDS = [
        'first_name', 'middle_name', 'last_name', 'nin', 'bvn', 'phone',
        'date_of_birth', 'gender', 'address', 'lga', 'ward',
    ];

    /**
     * @param  array<string, string>  $values  header-keyed source values
     * @return array{payload: array<string, mixed>, valid: bool, errors: list<array{field: string, message: string}>}
     */
    public function validate(array $values): array
    {
        $payload = $this->normalise($values);

        $validator = Validator::make($payload, BeneficiaryRules::forRegistration(), BeneficiaryRules::messages());

        $errors = [];
        foreach ($validator->errors()->messages() as $field => $messages) {
            foreach ($messages as $message) {
                $errors[] = ['field' => (string) $field, 'message' => (string) $message];
            }
        }

        return ['payload' => $payload, 'valid' => $errors === [], 'errors' => $errors];
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
