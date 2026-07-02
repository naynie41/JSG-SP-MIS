<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Registry\Models\BeneficiaryDocument;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Metadata for a supporting document (PRD FR-REG-07). Never exposes the internal
 * stored path; the file is retrieved only via the authorized download endpoint.
 *
 * @mixin BeneficiaryDocument
 */
class BeneficiaryDocumentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'beneficiary_id' => $this->beneficiary_id,
            'document_type' => $this->document_type->value,
            'original_filename' => $this->original_filename,
            'mime_type' => $this->mime_type,
            'size_bytes' => $this->size_bytes,
            'checksum_sha256' => $this->checksum_sha256,
            'uploaded_by' => $this->uploaded_by,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
