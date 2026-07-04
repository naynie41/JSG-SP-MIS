<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Imports;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Enums\BenefitType;
use App\Domain\Benefit\Enums\VerificationMethod;
use App\Domain\Programme\Enums\EnrollmentStatus;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Services\EligibilityEvaluator;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Validates one row of a benefit-delivery list against an activity's programme
 * (PRD FR-BEN-02, §8.3). It resolves the EXISTING beneficiary (by id/NIN/BVN),
 * validates the benefit fields, and flags the row: unmatched or not-enrolled
 * beneficiaries are errors (not committed); ineligible ones are an advisory flag
 * unless the programme enforces eligibility, when they become an error.
 */
class BenefitDeliveryRowValidator
{
    public function __construct(private readonly EligibilityEvaluator $eligibility) {}

    /**
     * @param  array<string, string>  $values  canonical (lower_snake) header => value
     * @return array{is_valid: bool, errors: list<array{field: string, message: string}>, resolved_beneficiary_id: ?string, eligibility_flagged: bool, payload: array<string, mixed>}
     */
    public function validate(array $values, Programme $programme): array
    {
        $errors = [];

        $beneficiary = $this->resolveBeneficiary($values, $errors);
        $payload = $this->benefitFields($values, $errors);

        $flagged = false;
        if ($beneficiary !== null) {
            $enrolled = Enrollment::query()
                ->withoutGlobalScope(MdaScope::class)
                ->where('programme_id', $programme->id)
                ->where('beneficiary_id', $beneficiary->id)
                ->where('status', EnrollmentStatus::Enrolled)
                ->exists();

            if (! $enrolled) {
                $errors[] = ['field' => 'beneficiary', 'message' => 'The beneficiary is not enrolled in this programme.'];
            } else {
                $result = $this->eligibility->evaluate($programme->eligibility, $beneficiary);
                if (! $result['eligible']) {
                    if ($programme->enforce_eligibility) {
                        $errors[] = ['field' => 'eligibility', 'message' => 'Ineligible — unmet: '.implode(', ', $result['unmet']).'.'];
                    } else {
                        $flagged = true;
                    }
                }
            }
        }

        return [
            'is_valid' => $errors === [],
            'errors' => $errors,
            'resolved_beneficiary_id' => $beneficiary?->id,
            'eligibility_flagged' => $flagged,
            'payload' => $payload,
        ];
    }

    /**
     * @param  array<string, string>  $values
     * @param  list<array{field: string, message: string}>  $errors
     */
    private function resolveBeneficiary(array $values, array &$errors): ?Beneficiary
    {
        $id = trim($values['beneficiary_id'] ?? '');
        $nin = Beneficiary::normalizeDigits($values['nin'] ?? null);
        $bvn = Beneficiary::normalizeDigits($values['bvn'] ?? null);

        if ($id === '' && $nin === null && $bvn === null) {
            $errors[] = ['field' => 'beneficiary', 'message' => 'Provide a beneficiary_id, nin, or bvn.'];

            return null;
        }

        // Cross-MDA: a delivering MDA may serve a beneficiary it does not own.
        $base = Beneficiary::query()->withoutGlobalScope(MdaScope::class);

        $beneficiary = null;
        if ($id !== '' && Str::isUuid($id)) {
            $beneficiary = (clone $base)->find($id);
        }
        if ($beneficiary === null && $nin !== null) {
            $beneficiary = (clone $base)->where('nin', $nin)->first();
        }
        if ($beneficiary === null && $bvn !== null) {
            $beneficiary = (clone $base)->where('bvn', $bvn)->first();
        }

        if ($beneficiary === null) {
            $errors[] = ['field' => 'beneficiary', 'message' => 'No matching beneficiary found for the given identifier.'];
        }

        return $beneficiary;
    }

    /**
     * @param  array<string, string>  $values
     * @param  list<array{field: string, message: string}>  $errors
     * @return array<string, mixed>
     */
    private function benefitFields(array $values, array &$errors): array
    {
        $type = strtolower(trim($values['benefit_type'] ?? ''));
        if ($type === '') {
            $errors[] = ['field' => 'benefit_type', 'message' => 'A benefit type is required.'];
        } elseif (BenefitType::tryFrom($type) === null) {
            $errors[] = ['field' => 'benefit_type', 'message' => "Unknown benefit type '{$type}'."];
        }

        $deliveryDate = $this->parseDate(trim($values['delivery_date'] ?? ''), $errors);

        $money = trim($values['monetary_value'] ?? ($values['value'] ?? ''));
        if ($money !== '' && ! (ctype_digit($money) && (int) $money >= 0)) {
            $errors[] = ['field' => 'monetary_value', 'message' => 'Monetary value must be a whole number of kobo (minor units).'];
        }

        $quantity = trim($values['quantity'] ?? '');
        if ($quantity !== '' && (! is_numeric($quantity) || (float) $quantity < 0)) {
            $errors[] = ['field' => 'quantity', 'message' => 'Quantity must be a non-negative number.'];
        }

        $method = strtolower(trim($values['verification_method'] ?? '')) ?: VerificationMethod::None->value;
        $reference = trim($values['verification_reference'] ?? '') ?: null;
        $verification = VerificationMethod::tryFrom($method);
        if ($verification === null) {
            $errors[] = ['field' => 'verification_method', 'message' => "Unknown verification method '{$method}'."];
        } elseif (in_array($verification, [VerificationMethod::Otp, VerificationMethod::Biometric], true)) {
            $errors[] = ['field' => 'verification_method', 'message' => "Verification method '{$method}' is unavailable for bulk delivery."];
        } elseif ($verification === VerificationMethod::Signature && $reference === null) {
            $errors[] = ['field' => 'verification_reference', 'message' => 'A signature reference is required for signature verification.'];
        }

        return [
            'benefit_type' => $type !== '' ? $type : null,
            'quantity' => $quantity !== '' ? $quantity : null,
            'unit' => trim($values['unit'] ?? '') ?: null,
            'monetary_value' => $money !== '' ? (int) $money : null,
            'funding_source' => trim($values['funding_source'] ?? '') ?: null,
            'delivery_date' => $deliveryDate,
            'verification_method' => $method,
            'verification_reference' => $reference,
            'notes' => trim($values['notes'] ?? '') ?: null,
        ];
    }

    /**
     * @param  list<array{field: string, message: string}>  $errors
     */
    private function parseDate(string $value, array &$errors): ?string
    {
        if ($value === '') {
            $errors[] = ['field' => 'delivery_date', 'message' => 'A delivery date is required.'];

            return null;
        }
        try {
            $date = Carbon::parse($value);
        } catch (\Throwable) {
            $errors[] = ['field' => 'delivery_date', 'message' => "'{$value}' is not a valid date."];

            return null;
        }
        if ($date->isFuture()) {
            $errors[] = ['field' => 'delivery_date', 'message' => 'The delivery date cannot be in the future.'];
        }

        return $date->toDateString();
    }
}
