<?php

declare(strict_types=1);

namespace App\Domain\Registry\Imports;

use App\Domain\Registry\Support\BeneficiaryRules;
use App\Domain\Registry\Support\CanonicalSchema;
use App\Domain\Registry\Support\NormalizationService;
use App\Domain\Registry\Support\OriginalInput;
use App\Domain\Registry\Support\UniqueIdentifier;
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
    public function __construct(private readonly NormalizationService $normalizer = new NormalizationService) {}

    /**
     * The canonical field set — declared once in {@see CanonicalSchema}.
     *
     * @return list<string>
     */
    private function fields(): array
    {
        return CanonicalSchema::fields();
    }

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

        /*
         * Validate the NORMALIZED row, but carry the untouched values alongside so a
         * failure can quote what the file actually said (FR-REG-16).
         *
         * Without this the report tells an officer that `8031234567` is the wrong
         * length while their cell reads `+234 (0) 803 123 456` — a string they cannot
         * find. The key is stripped from the payload before it is returned, so nothing
         * downstream ever sees it.
         */
        $originals = [];
        foreach ($this->fields() as $field) {
            $originals[$field] = $values[$field] ?? null;
        }

        $validator = Validator::make(
            [...$payload, OriginalInput::KEY => $originals],
            BeneficiaryRules::forRegistration(),
            BeneficiaryRules::messages(),
        );

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
            // (NIN/BVN uniqueness is the UniqueIdentifier rule — keyed on the
            // encrypted identifiers' hash columns; failed() keys it by FQCN.)
            $ruleNames = array_keys($rules);
            $isUniqueOnly = $ruleNames === ['Unique'] || $ruleNames === [UniqueIdentifier::class];
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
        foreach ($this->fields() as $field) {
            $value = $values[$field] ?? '';
            $payload[$field] = $value === '' ? null : $value;
        }

        // NIN/BVN reduce to digits because `digits:11` is the validated shape and the
        // punctuation carries nothing. PHONE is left EXACTLY as the source wrote it —
        // its written form is preserved on the record, and the comparable form is
        // derived separately (CLAUDE.md §11). Normalising it here would store the
        // normalized value and lose the original.
        $payload['nin'] = $this->normalizer->identifier($payload['nin']);
        $payload['bvn'] = $this->normalizer->identifier($payload['bvn']);

        /*
         * The date is parsed to ISO here rather than left to the validator, because
         * `date_of_birth` is a DATE column — there is no "original written form" it can
         * hold, so the only question is WHICH date gets stored. Left alone, PHP reads
         * `12/03/1995` as 3 December; sources here are written day-first, and a birth
         * date nine months out also shifts `block_name_dob`, the key that decides which
         * candidates the fuzzy matcher ever sees. An unparseable value is left as-is so
         * the validator reports it rather than this silently nulling it.
         */
        $payload['date_of_birth'] = $this->normalizer->date($payload['date_of_birth']) ?? $payload['date_of_birth'];

        // Enum-ish fields: fold to the canonical lower snake_case the enums use.
        $payload['gender'] = $this->normalizer->enumKey($payload['gender']);
        $payload['lga'] = $this->normalizer->enumKey($payload['lga']);

        return $payload;
    }
}
