<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\BeneficiaryDocument;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\UploadDocumentRequest;
use App\Http\Resources\BeneficiaryDocumentResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Supporting documents for a beneficiary (PRD FR-REG-07, SECURITY.md §5). Files
 * are stored on a private disk (outside the web root) and only ever streamed
 * through the authorized download action — never served statically. List/download
 * follow beneficiary read access; upload/delete are owner-MDA only. Uploads and
 * downloads are audited (document access is sensitive-PII access).
 */
class BeneficiaryDocumentController extends Controller
{
    public function __construct(private readonly AuditLogger $audit) {}

    /** List a beneficiary's documents (metadata only). */
    public function index(string $beneficiary): JsonResponse
    {
        $model = Beneficiary::query()->findOrFail($beneficiary);
        $this->authorize('view', $model);

        $documents = BeneficiaryDocument::query()
            ->where('beneficiary_id', $model->id)
            ->latest('created_at')
            ->get();

        return ApiResponse::success(
            ['documents' => BeneficiaryDocumentResource::collection($documents)->resolve()],
        );
    }

    /** Attach a validated document to a beneficiary — owner MDA only. */
    public function store(UploadDocumentRequest $request, string $beneficiary): JsonResponse
    {
        // Resolve without the owner scope so a non-owner gets 403 (not 404).
        $model = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->findOrFail($beneficiary);
        $this->authorize('update', $model);

        $file = $request->file('file');
        $checksum = hash_file('sha256', $file->getRealPath());

        // Random, extension-only stored name under a per-beneficiary folder — no
        // user-controlled path segments, so nothing is executable or traversable.
        $storedPath = $file->store('documents/'.$model->id, 'local');

        $document = BeneficiaryDocument::create([
            'beneficiary_id' => $model->id,
            'owner_mda_id' => $model->owner_mda_id,
            'uploaded_by' => $request->user()->id,
            'document_type' => $request->string('document_type')->value(),
            'original_filename' => $file->getClientOriginalName(),
            'stored_path' => $storedPath,
            'mime_type' => $file->getMimeType() ?? $file->getClientMimeType(),
            'size_bytes' => $file->getSize(),
            'checksum_sha256' => $checksum,
        ]);

        return ApiResponse::success((new BeneficiaryDocumentResource($document))->resolve(), status: 201);
    }

    /** Stream a document to a permitted, in-scope user; the access is audited. */
    public function download(string $beneficiary, string $document): StreamedResponse
    {
        $model = BeneficiaryDocument::query()
            ->where('beneficiary_id', $beneficiary)
            ->findOrFail($document);

        $this->authorize('view', $model);

        $this->audit->record('beneficiary_document.downloaded', $model);

        return Storage::disk('local')->download(
            $model->stored_path,
            $model->original_filename,
            ['Content-Type' => $model->mime_type],
        );
    }

    /** Soft-delete a document — owner MDA only. */
    public function destroy(string $beneficiary, string $document): JsonResponse
    {
        $model = BeneficiaryDocument::query()
            ->withoutGlobalScope(MdaScope::class)
            ->where('beneficiary_id', $beneficiary)
            ->findOrFail($document);

        $this->authorize('delete', $model);

        $model->delete();

        return ApiResponse::success(['message' => 'Document deleted.']);
    }
}
