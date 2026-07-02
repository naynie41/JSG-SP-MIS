<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;

/**
 * Single choke-point for persisting a beneficiary with correct provenance
 * (PRD §6.1, FR-OWN-01). Every inbound channel — manual, bulk import, Kobo/ODK,
 * the REST intake — funnels through here, so ownership and origin (source +
 * import_batch + original_record_id) are stamped consistently and the record is
 * always traceable. Creation is audited via the model's Auditable trait.
 */
class BeneficiaryRegistrar
{
    /**
     * @param  array<string, mixed>  $attributes  validated canonical fields
     */
    public function register(
        array $attributes,
        string $ownerMdaId,
        RegistrationSource $source,
        ?string $originalRecordId = null,
        ?string $importBatchId = null,
    ): Beneficiary {
        $beneficiary = new Beneficiary;
        $beneficiary->fill($attributes);
        $beneficiary->owner_mda_id = $ownerMdaId;
        $beneficiary->registration_source = $source;
        $beneficiary->original_record_id = $originalRecordId;
        $beneficiary->import_batch_id = $importBatchId;
        $beneficiary->save();

        return $beneficiary;
    }
}
