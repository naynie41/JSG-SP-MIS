<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Ad-hoc duplicate/serve search (PRD FR-DUP-04, FR-OWN-03): partial identity
 * details fed to the SAME matching engine used by import screening. At least one
 * blocking-capable field (NIN/BVN/phone, or last name for the name+DOB block) is
 * required so the search never degrades into a full-table scan.
 */
class BeneficiaryMatchSearchRequest extends FormRequest
{
    /** Canonical fields the engine understands; only these are forwarded. */
    private const QUERY_FIELDS = [
        'nin', 'bvn', 'phone', 'first_name', 'middle_name', 'last_name',
        'date_of_birth', 'gender', 'lga', 'ward',
    ];

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // One of these must be present to yield a blocking key.
            'nin' => ['required_without_all:bvn,phone,last_name', 'nullable', 'string', 'max:20'],
            'bvn' => ['required_without_all:nin,phone,last_name', 'nullable', 'string', 'max:20'],
            'phone' => ['required_without_all:nin,bvn,last_name', 'nullable', 'string', 'max:20'],
            'last_name' => ['required_without_all:nin,bvn,phone', 'nullable', 'string', 'max:100'],
            'first_name' => ['nullable', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:20'],
            'lga' => ['nullable', 'string', 'max:100'],
            'ward' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * The provided (non-empty) query fields in the engine's canonical shape.
     *
     * @return array<string, string>
     */
    public function canonicalQuery(): array
    {
        $query = [];
        foreach (self::QUERY_FIELDS as $field) {
            $value = $this->input($field);
            if (is_string($value) && trim($value) !== '') {
                $query[$field] = $value;
            }
        }

        return $query;
    }
}
