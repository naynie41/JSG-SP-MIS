<?php

declare(strict_types=1);

namespace App\Http\Requests\Library;

use App\Domain\Library\Enums\LibraryItemStatus;
use App\Http\Requests\Library\Concerns\ValidatesLibraryPayload;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Add a resource to the public library (FR-RES-02).
 *
 * Authorization is the route's `permission:library.create`; this only shapes the
 * payload. `status` is accepted so the administrator can save a draft or publish in
 * one step — but never `archived`, which is a withdrawal of something already
 * published and therefore only reachable through an update.
 */
class StoreLibraryItemRequest extends FormRequest
{
    use ValidatesLibraryPayload;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return array_merge($this->libraryMetadataRules(required: true), [
            'status' => ['sometimes', Rule::in([
                LibraryItemStatus::Draft->value,
                LibraryItemStatus::Published->value,
            ])],
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return $this->libraryMessages();
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(fn (Validator $v) => $this->validateKindShape($v));
    }
}
