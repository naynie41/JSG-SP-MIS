<?php

declare(strict_types=1);

namespace App\Http\Requests\Sync;

use App\Domain\Registry\Imports\Adapters\SourceAdapterRegistry;
use App\Domain\Sync\Enums\ConflictPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * An offline-capture batch flushed to the server (FR-REG-08). Each record is a raw
 * source object mapped by the source's adapter; it should carry the client's own id
 * (idempotency key) so re-flushing the same batch never double-inserts.
 */
class OfflineBatchRequest extends FormRequest
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
        $sources = app(SourceAdapterRegistry::class)->importableSources();

        return [
            'source' => ['required', Rule::in($sources)],
            'conflict_policy' => ['sometimes', Rule::enum(ConflictPolicy::class)],
            'records' => ['required', 'array', 'min:1', 'max:1000'],
            'records.*' => ['required', 'array'],
        ];
    }
}
