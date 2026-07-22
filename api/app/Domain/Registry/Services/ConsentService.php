<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Access\Models\User;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Registry\Enums\ConsentStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\BeneficiaryConsent;
use App\Domain\Sharing\DataSharingGuard;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Captures and enforces beneficiary consent per PURPOSE (NFR-PRV-01, FR-DSH-01). A
 * change appends an immutable history row and audits the change — so consent has a
 * full, auditable lifecycle (capture, update, withdraw). For the cross-MDA sharing
 * purpose the current status is also denormalised onto the beneficiary so the
 * sharing gate ({@see DataSharingGuard}) stays a cheap read.
 * Other purposes (e.g. processing) resolve their current status from the history.
 */
class ConsentService
{
    public const PURPOSE_CROSS_MDA = 'cross_mda_sharing';

    public const PURPOSE_PROCESSING = 'data_processing';

    public function __construct(private readonly AuditLogger $audit) {}

    public function record(
        Beneficiary $beneficiary,
        ConsentStatus $status,
        ?User $actor = null,
        ?string $basis = null,
        ?string $source = null,
        ?string $note = null,
        string $purpose = self::PURPOSE_CROSS_MDA,
    ): BeneficiaryConsent {
        $before = $this->currentStatus($beneficiary, $purpose)->value;

        $entry = DB::transaction(function () use ($beneficiary, $status, $actor, $basis, $source, $note, $purpose) {
            // Only the cross-MDA sharing purpose is denormalised onto the record
            // (the sharing gate reads it directly). Other purposes live in history.
            if ($purpose === self::PURPOSE_CROSS_MDA) {
                $beneficiary->forceFill([
                    'sharing_consent' => $status,
                    'sharing_consent_at' => Carbon::now(),
                ])->save();
            }

            return BeneficiaryConsent::create([
                'beneficiary_id' => $beneficiary->id,
                'purpose' => $purpose,
                'status' => $status,
                'basis' => $basis,
                'source' => $source,
                'note' => $note,
                'recorded_by' => $actor?->id,
            ]);
        });

        $action = $status === ConsentStatus::Withdrawn ? 'consent.withdrawn' : 'consent.recorded';
        $this->audit->record($action, $beneficiary, before: ['sharing_consent' => $before], after: [
            'sharing_consent' => $status->value,
            'purpose' => $purpose,
            'basis' => $basis,
        ], actor: $actor);

        return $entry;
    }

    /**
     * The beneficiary's current consent status for a purpose. The cross-MDA purpose
     * reads the denormalised column; any other purpose reads the latest history row
     * (defaulting to Unknown — no consent is ever assumed, NFR-PRV-01).
     */
    public function currentStatus(Beneficiary $beneficiary, string $purpose = self::PURPOSE_CROSS_MDA): ConsentStatus
    {
        if ($purpose === self::PURPOSE_CROSS_MDA) {
            return $beneficiary->sharing_consent ?? ConsentStatus::Unknown;
        }

        $latest = BeneficiaryConsent::query()
            ->where('beneficiary_id', $beneficiary->id)
            ->where('purpose', $purpose)
            ->latest('created_at')
            ->first();

        return $latest?->status ?? ConsentStatus::Unknown;
    }
}
