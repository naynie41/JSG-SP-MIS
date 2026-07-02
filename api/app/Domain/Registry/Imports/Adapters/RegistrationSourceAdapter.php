<?php

declare(strict_types=1);

namespace App\Domain\Registry\Imports\Adapters;

use App\Domain\Registry\Enums\RegistrationSource;

/**
 * A registration SOURCE adapter (PRD FR-REG-02, §6.1). Each inbound channel —
 * Excel/CSV, Kobo Collect, ODK, an existing government system, or a future
 * source — maps its own record shape onto the canonical beneficiary field set,
 * and declares the provenance to stamp.
 *
 * This is the ONLY seam a new source needs to implement: the parse → validate →
 * commit pipeline and the shared registration rules never change. Register the
 * adapter in {@see SourceAdapterRegistry} (see app/Domain/Registry/README.md).
 */
interface RegistrationSourceAdapter
{
    /** The provenance stamped on records ingested through this adapter. */
    public function source(): RegistrationSource;

    /**
     * Map one raw source record to canonical beneficiary fields plus
     * `original_record_id` (the caller's/source's own id, for traceability).
     * Unknown source fields are ignored; missing fields are simply absent.
     *
     * Canonical keys: first_name, middle_name, last_name, nin, bvn, phone,
     * date_of_birth, gender, address, lga, ward, original_record_id.
     *
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    public function map(array $raw): array;
}
