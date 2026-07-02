<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Registry\Enums\HouseholdRole;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;

/**
 * Forms households from a source household-reference during ingestion (PRD
 * FR-REG-02, §9) — used by every source (Excel/CSV, Kobo/ODK, API, SOCU, …).
 *
 * Idempotent and single-open-safe: the household is found-or-created per
 * (owning MDA, source household key), and a beneficiary's membership is only
 * opened if they have no open membership (the DB partial-unique index is the
 * backstop). Provenance (source + import_batch + original_record_id) is stamped;
 * creation is audited via Auditable + an explicit member-added entry.
 */
class HouseholdIngestionService
{
    public function __construct(private readonly AuditLogger $audit) {}

    /**
     * Attach a beneficiary to the source-referenced household, forming the
     * household on first sight. Safe to call repeatedly (re-imports).
     */
    public function attach(
        string $ownerMdaId,
        RegistrationSource $source,
        ?string $importBatchId,
        string $householdRef,
        Beneficiary $beneficiary,
        ?string $role,
        bool $isHead,
    ): Household {
        $household = Household::query()
            ->withoutGlobalScope(MdaScope::class)
            ->firstOrCreate(
                ['owner_mda_id' => $ownerMdaId, 'original_record_id' => $householdRef],
                [
                    'registration_source' => $source,
                    'import_batch_id' => $importBatchId,
                    'lga' => $beneficiary->lga,
                    'ward' => $beneficiary->ward,
                ],
            );

        $roleEnum = $this->resolveRole($role, $isHead);
        $membership = $this->ensureMembership($household, $beneficiary, $roleEnum);

        // Only designate head when the beneficiary is actually an open member here.
        if ($isHead && $membership !== null && $membership->household_id === $household->id) {
            if ($household->head_beneficiary_id !== $beneficiary->id) {
                $household->update(['head_beneficiary_id' => $beneficiary->id]);
            }
            if ($membership->role_in_household !== HouseholdRole::Head) {
                $membership->update(['role_in_household' => HouseholdRole::Head]);
            }
        }

        return $household;
    }

    private function resolveRole(?string $role, bool $isHead): HouseholdRole
    {
        if ($isHead) {
            return HouseholdRole::Head;
        }
        if ($role === null) {
            return HouseholdRole::Other;
        }

        return HouseholdRole::tryFrom(strtolower(trim($role))) ?? HouseholdRole::Other;
    }

    /**
     * Open a membership unless the beneficiary already has an open one. If their
     * open membership is in a different household, it is left untouched (imports
     * never silently move a beneficiary between households).
     */
    private function ensureMembership(Household $household, Beneficiary $beneficiary, HouseholdRole $role): ?HouseholdMembership
    {
        $open = HouseholdMembership::query()
            ->where('beneficiary_id', $beneficiary->id)
            ->whereNull('left_at')
            ->first();

        if ($open !== null) {
            return $open->household_id === $household->id ? $open : null;
        }

        $membership = HouseholdMembership::create([
            'household_id' => $household->id,
            'beneficiary_id' => $beneficiary->id,
            'role_in_household' => $role,
        ]);

        $this->audit->record('household.member_added', $household, after: [
            'beneficiary_id' => $beneficiary->id,
            'membership_id' => $membership->id,
            'via' => 'import',
        ]);

        return $membership;
    }
}
