<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Registry\Enums\ConsentStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Sharing\DataSharingGuard;

/**
 * The processing-consent gate (NFR-PRV-01). Separate from the cross-MDA SHARING
 * gate ({@see DataSharingGuard}); this one governs whether a NEW
 * processing of the subject's data (recording an intervention) may proceed.
 *
 * Whether processing legally requires consent is a DPO decision — config
 * `privacy.consent.processing_requires_consent`, OFF by default so enabling it is a
 * deliberate choice that never silently blocks existing operations.
 */
class ConsentGate
{
    public function __construct(private readonly ConsentService $consents) {}

    /** Is the processing-consent gate switched on (DPO/NDPA policy — config)? */
    public function processingRequired(): bool
    {
        return (bool) config('privacy.consent.processing_requires_consent', false);
    }

    /**
     * May the subject's data be processed now? True when the gate is off, or when
     * processing consent is currently granted for this beneficiary.
     */
    public function mayProcess(Beneficiary $beneficiary): bool
    {
        if (! $this->processingRequired()) {
            return true;
        }

        return $this->consents->currentStatus($beneficiary, ConsentService::PURPOSE_PROCESSING) === ConsentStatus::Granted;
    }
}
