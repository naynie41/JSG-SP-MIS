<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Access\Models\User;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Registry\Enums\ConsentStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\BeneficiaryConsent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Captures and enforces cross-MDA data-sharing consent (NFR-PRV-01, FR-DSH-01). A
 * change denormalises the current status onto the beneficiary AND appends an
 * immutable history row, then audits the change — so consent has a full, auditable
 * lifecycle. Granting/withdrawing is the only way to move the consent gate.
 */
class ConsentService
{
    public const PURPOSE_CROSS_MDA = 'cross_mda_sharing';

    public function __construct(private readonly AuditLogger $audit) {}

    public function record(
        Beneficiary $beneficiary,
        ConsentStatus $status,
        ?User $actor = null,
        ?string $basis = null,
        ?string $source = null,
        ?string $note = null,
    ): BeneficiaryConsent {
        $before = $beneficiary->sharing_consent?->value;

        $entry = DB::transaction(function () use ($beneficiary, $status, $actor, $basis, $source, $note) {
            $beneficiary->forceFill([
                'sharing_consent' => $status,
                'sharing_consent_at' => Carbon::now(),
            ])->save();

            return BeneficiaryConsent::create([
                'beneficiary_id' => $beneficiary->id,
                'purpose' => self::PURPOSE_CROSS_MDA,
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
            'purpose' => self::PURPOSE_CROSS_MDA,
            'basis' => $basis,
        ], actor: $actor);

        return $entry;
    }
}
