<?php

declare(strict_types=1);

namespace App\Domain\Privacy\Services;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\BeneficiaryServiceGrant;
use Illuminate\Support\Carbon;

/**
 * Assembles a Data Subject Access Request package (NFR-PRV-01, right of access): the
 * subject's FULL core record plus their complete benefit history, enrollments,
 * consent history, attached-document metadata, and the cross-MDA grants over their
 * record. Identifiers are UNMASKED here by design — this is the subject's own data
 * returned on an authorized request — so the endpoint is tightly permission-gated
 * and audited. Reads across all MDAs (the record belongs to the subject).
 */
class SubjectAccessAssembler
{
    /**
     * @return array<string, mixed>
     */
    public function assemble(Beneficiary $beneficiary): array
    {
        $beneficiary->loadMissing([
            'ownerMda' => fn ($q) => $q->withoutGlobalScope(MdaScope::class)->select('id', 'name'),
            'benefits',
            'enrollments',
            'consents',
            'documents',
        ]);

        return [
            'generated_at' => Carbon::now()->toIso8601String(),
            'subject' => $this->coreRecord($beneficiary),
            'consents' => $beneficiary->consents->map(fn ($c): array => [
                'purpose' => $c->purpose,
                'status' => $c->status->value,
                'basis' => $c->basis,
                'source' => $c->source,
                'recorded_at' => $c->created_at?->toIso8601String(),
            ])->all(),
            'enrollments' => $beneficiary->enrollments->map(fn ($e): array => [
                'programme_id' => $e->programme_id,
                'activity_id' => $e->activity_id,
                'mda_id' => $e->mda_id,
                'status' => $e->status->value,
                'enrolled_on' => $e->enrolled_on?->toDateString(),
                'exited_on' => $e->exited_on?->toDateString(),
            ])->all(),
            'benefits' => $beneficiary->benefits->map(fn ($b): array => [
                'programme_id' => $b->programme_id,
                'activity_id' => $b->activity_id,
                'mda_id' => $b->mda_id,
                'benefit_type' => $b->benefit_type->value,
                'quantity' => $b->quantity,
                'unit' => $b->unit,
                'monetary_value' => $b->monetary_value,
                'delivery_date' => $b->delivery_date?->toDateString(),
                'status' => $b->status->value,
            ])->all(),
            'documents' => $beneficiary->documents->map(fn ($d): array => [
                'document_type' => $d->document_type->value,
                'original_filename' => $d->original_filename,
                'mime_type' => $d->mime_type,
                'size_bytes' => $d->size_bytes,
                'checksum_sha256' => $d->checksum_sha256,
                'uploaded_at' => $d->created_at?->toIso8601String(),
            ])->all(),
            'access_grants' => $this->grants($beneficiary),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function coreRecord(Beneficiary $beneficiary): array
    {
        return [
            'id' => $beneficiary->id,
            'owner_mda' => $beneficiary->ownerMda?->name,
            'registration_source' => $beneficiary->registration_source->value,
            'registration_date' => $beneficiary->registration_date?->toDateString(),
            // Unmasked — the subject's own identifiers (right of access).
            'nin' => $beneficiary->nin,
            'bvn' => $beneficiary->bvn,
            'phone' => $beneficiary->phone,
            'first_name' => $beneficiary->first_name,
            'middle_name' => $beneficiary->middle_name,
            'last_name' => $beneficiary->last_name,
            'date_of_birth' => $beneficiary->date_of_birth?->toDateString(),
            'gender' => $beneficiary->gender?->value,
            'address' => $beneficiary->address,
            'lga' => $beneficiary->lga,
            'ward' => $beneficiary->ward,
            'status' => $beneficiary->status->value,
            'sharing_consent' => $beneficiary->sharing_consent?->value,
            'anonymized_at' => $beneficiary->anonymized_at?->toIso8601String(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function grants(Beneficiary $beneficiary): array
    {
        return BeneficiaryServiceGrant::query()
            ->withoutGlobalScope(MdaScope::class)
            ->with(['mda' => fn ($q) => $q->withoutGlobalScope(MdaScope::class)->select('id', 'name')])
            ->where('beneficiary_id', $beneficiary->id)
            ->get()
            ->map(fn (BeneficiaryServiceGrant $g): array => [
                'granted_mda' => $g->mda?->name,
                'granted_at' => $g->granted_at?->toIso8601String(),
                'revoked_at' => $g->revoked_at?->toIso8601String(),
                'active' => $g->revoked_at === null,
            ])->all();
    }
}
