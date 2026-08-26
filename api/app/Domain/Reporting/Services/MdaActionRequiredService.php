<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Models\User;
use App\Domain\Grievance\Enums\GrievanceStatus;
use App\Domain\Grievance\Models\Grievance;
use App\Domain\Referral\Enums\ReferralStatus;
use App\Domain\Referral\Models\Referral;
use App\Domain\Referral\Scopes\ReferralScope;
use App\Domain\Registry\Enums\ServiceRequestStatus;
use App\Domain\Registry\Models\ServiceRequest;

/**
 * The MDA console's action-required counters: work waiting on THIS MDA right now.
 *
 * These are deliberately NOT part of the Phase 6 dashboard metrics, for two reasons:
 *
 *  1. **They must be live.** An unfiltered `/dashboard` serves a snapshot refreshed on
 *     a 15-minute cycle ({@see DashboardService}). A work queue that reports two
 *     approvals outstanding after the officer already cleared them is worse than no
 *     counter at all, so these are computed per request.
 *  2. **They are DIRECTIONAL.** The dashboard's referral block counts everything the
 *     MDA is party to, in either direction. "Awaiting me" is specifically the INBOUND
 *     side — a referral this MDA received, or a request-to-serve on a beneficiary this
 *     MDA owns. That distinction cannot be derived from `referrals.by_status`.
 *
 * Both are COUNTS only. Reading the underlying lists to size them would pull
 * beneficiary records into a page that just renders a number (SECURITY.md — minimise
 * PII on screen).
 */
class MdaActionRequiredService
{
    /**
     * Referral states that still need the receiving MDA to act. `more_info_requested`
     * counts because the ball is back with the receiver.
     *
     * @var list<string>
     */
    private const REFERRAL_OPEN = [
        ReferralStatus::Created->value,
        ReferralStatus::MoreInfoRequested->value,
    ];

    /**
     * Grievance states still on this MDA's desk. Resolved and Closed are done.
     *
     * @var list<string>
     */
    private const GRIEVANCE_OPEN = [
        GrievanceStatus::Open->value,
        GrievanceStatus::Assigned->value,
        GrievanceStatus::InProgress->value,
    ];

    /**
     * @return array{pending_referrals: int, pending_service_requests: int, open_grievances: int, breached_grievances: int, mda_id: string|null}
     */
    public function forUser(User $user): array
    {
        $mdaId = $user->mda_id;

        // A user with no MDA (oversight roles) has no inbound queue of their own.
        if ($mdaId === null) {
            return [
                'pending_referrals' => 0,
                'pending_service_requests' => 0,
                'open_grievances' => 0,
                'breached_grievances' => 0,
                'mda_id' => null,
            ];
        }

        return [
            // Inbound referrals this MDA has been asked to act on. The global
            // ReferralScope already limits visibility to the two parties; the explicit
            // to_mda_id is what makes this "awaiting ME" rather than "either side".
            'pending_referrals' => Referral::query()
                ->withoutGlobalScope(ReferralScope::class)
                ->where('to_mda_id', $mdaId)
                ->whereIn('status', self::REFERRAL_OPEN)
                ->count(),

            // Request-to-serve raised against a beneficiary THIS MDA owns, awaiting an
            // accept/decline. Only a holder of beneficiary.approve can action these
            // (SECURITY.md / §9) — the count is visible to the whole MDA, the decision
            // is not.
            'pending_service_requests' => ServiceRequest::query()
                ->where('to_mda_id', $mdaId)
                ->where('status', ServiceRequestStatus::Pending->value)
                ->count(),

            // Grievances this MDA is handling that are not yet resolved. They carry a
            // running SLA (FR-GRM-03) but sat two clicks deep behind a tab that opens on
            // Benefits — a queue with a clock on it that nothing surfaced.
            'open_grievances' => Grievance::query()
                ->where('handling_mda_id', $mdaId)
                ->whereIn('status', self::GRIEVANCE_OPEN)
                ->count(),

            // The subset already past their window, so the tile can say what is urgent
            // rather than only how much there is.
            'breached_grievances' => Grievance::query()
                ->where('handling_mda_id', $mdaId)
                ->whereIn('status', self::GRIEVANCE_OPEN)
                ->whereNotNull('sla_breached_at')
                ->count(),

            'mda_id' => $mdaId,
        ];
    }
}
