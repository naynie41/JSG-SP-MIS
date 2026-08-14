<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Registry\Services\ImportMappingService;
use App\Domain\Registry\Support\CanonicalSchema;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * Confirm which source column holds each canonical field (CLAUDE.md §11).
 *
 * The payload shape carries the distinction the guard depends on: a canonical field
 * mapped to a header string, or mapped to NULL meaning "this source does not carry it".
 * A field simply left out is UNANSWERED.
 *
 * That is why completeness is not checked here. Laravel's `required` cannot tell an
 * explicit null from an absent key, and those two mean opposite things to this feature —
 * so the identity guard lives in {@see ImportMappingService}
 * where `array_key_exists` can make the distinction.
 */
class ConfirmMappingRequest extends FormRequest
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
            'save_template_as' => ['nullable', 'string', 'max:120'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            // A typo'd key would otherwise look like an answered field while mapping
            // nothing — and for an identity field that would defeat the guard.
            foreach (array_keys((array) $this->input('column_map', [])) as $field) {
                if (! in_array((string) $field, CanonicalSchema::allFields(), true)) {
                    $v->errors()->add('column_map', "“{$field}” is not a field in the canonical schema.");
                }
            }
        });
    }

    /**
     * The confirmed map with its keys preserved exactly — including those set to null,
     * which is how "this source does not carry it" is recorded.
     *
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

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return ['column_map.required' => 'Confirm the column mapping before continuing.'];
    }
}
