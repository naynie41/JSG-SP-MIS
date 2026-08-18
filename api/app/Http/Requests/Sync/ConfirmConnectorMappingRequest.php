<?php

declare(strict_types=1);

namespace App\Http\Requests\Sync;

use App\Domain\Registry\Support\CanonicalSchema;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * Approve a sync connector's column mapping (CLAUDE.md §11).
 *
 * Same shape as the import mapping request: a canonical field pointing at a source
 * field, or explicitly at NULL for "this source does not carry it". A field left out is
 * unanswered, and the completeness check lives in the service where an explicit null can
 * be told apart from an absent key.
 */
class ConfirmConnectorMappingRequest extends FormRequest
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
        return [
            'column_map' => ['required', 'array'],
            'column_map.*' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            foreach (array_keys((array) $this->input('column_map', [])) as $field) {
                // `mappableFields()`, not `allFields()`: a source field may be mapped onto
                // a derived field such as `full_name`, which is split into first/last name
                // rather than stored under its own name.
                if (! in_array((string) $field, CanonicalSchema::mappableFields(), true)) {
                    $v->errors()->add('column_map', "“{$field}” is not a field in the canonical schema.");
                }
            }
        });
    }

    /**
     * @return array<string, string|null>
     */
    public function columnMap(): array
    {
        /** @var array<string, string|null> $map */
        $map = (array) $this->input('column_map', []);

        return array_map(
            static fn ($value): ?string => $value === null || $value === '' ? null : (string) $value,
            $map,
        );
    }
}
