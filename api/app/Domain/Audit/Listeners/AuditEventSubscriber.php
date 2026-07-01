<?php

declare(strict_types=1);

namespace App\Domain\Audit\Listeners;

use App\Domain\Access\Events\AccountLocked;
use App\Domain\Access\Events\CrossMdaAccessGranted;
use App\Domain\Access\Events\CrossMdaAccessRevoked;
use App\Domain\Access\Events\MfaChallengeFailed;
use App\Domain\Access\Events\MfaDisabled;
use App\Domain\Access\Events\MfaEnrolled;
use App\Domain\Audit\Services\AuditLogger;
use Illuminate\Events\Dispatcher;

/**
 * Retro-wires existing domain events into the audit log (FR-AUD-01): lockouts,
 * MFA enrolment/disable/failures, and cross-MDA grant changes. New modules can
 * either emit events handled here or use the Auditable trait.
 */
class AuditEventSubscriber
{
    public function __construct(private readonly AuditLogger $audit) {}

    public function handleAccountLocked(AccountLocked $event): void
    {
        $this->audit->record('auth.account_locked', $event->user, actor: $event->user);
    }

    public function handleMfaEnrolled(MfaEnrolled $event): void
    {
        $this->audit->record('mfa.enrolled', $event->user, actor: $event->user);
    }

    public function handleMfaDisabled(MfaDisabled $event): void
    {
        $this->audit->record('mfa.disabled', $event->user, actor: $event->user);
    }

    public function handleMfaChallengeFailed(MfaChallengeFailed $event): void
    {
        $this->audit->record('mfa.challenge_failed', $event->user, actor: $event->user);
    }

    public function handleCrossMdaAccessGranted(CrossMdaAccessGranted $event): void
    {
        $this->audit->record(
            'cross_mda.granted',
            $event->grant,
            after: [
                'user_id' => $event->grant->user_id,
                'mda_id' => $event->grant->mda_id,
                'expires_at' => $event->grant->expires_at?->toIso8601String(),
            ],
            actor: $event->actor,
        );
    }

    public function handleCrossMdaAccessRevoked(CrossMdaAccessRevoked $event): void
    {
        $this->audit->record(
            'cross_mda.revoked',
            $event->grant,
            before: [
                'user_id' => $event->grant->user_id,
                'mda_id' => $event->grant->mda_id,
            ],
            actor: $event->actor,
        );
    }

    /**
     * @return array<class-string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            AccountLocked::class => 'handleAccountLocked',
            MfaEnrolled::class => 'handleMfaEnrolled',
            MfaDisabled::class => 'handleMfaDisabled',
            MfaChallengeFailed::class => 'handleMfaChallengeFailed',
            CrossMdaAccessGranted::class => 'handleCrossMdaAccessGranted',
            CrossMdaAccessRevoked::class => 'handleCrossMdaAccessRevoked',
        ];
    }
}
