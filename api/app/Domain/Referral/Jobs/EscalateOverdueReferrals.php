<?php

declare(strict_types=1);

namespace App\Domain\Referral\Jobs;

use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Referral\Enums\ReferralStatus;
use App\Domain\Referral\Events\ReferralSlaBreached;
use App\Domain\Referral\Models\Referral;
use App\Domain\Referral\Models\ReferralSlaPolicy;
use App\Domain\Referral\Scopes\ReferralScope;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

/**
 * Scheduled SLA sweep (PRD FR-REF-04/05). Flags overdue referrals and escalates
 * them one tier per elapsed window (capped at the configured chain length),
 * dispatching {@see ReferralSlaBreached} whenever the level increases. It NEVER
 * changes a referral's status — no auto-close; it only escalates + notifies.
 */
class EscalateOverdueReferrals implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** Non-terminal statuses that can breach an SLA. */
    private const ACTIVE = [
        ReferralStatus::Created,
        ReferralStatus::MoreInfoRequested,
        ReferralStatus::Accepted,
        ReferralStatus::InProgress,
    ];

    public function handle(AuditLogger $audit): void
    {
        /** @var array<string, int> $windows */
        $windows = ReferralSlaPolicy::query()->pluck('hours', 'status')->all();
        $chainSize = max(1, count((array) config('sla.referral.escalation_chain', [])));

        Referral::query()
            ->withoutGlobalScope(ReferralScope::class)
            ->whereIn('status', array_map(static fn (ReferralStatus $s): string => $s->value, self::ACTIVE))
            ->orderBy('id')
            ->chunkById(200, function ($referrals) use ($windows, $chainSize, $audit): void {
                foreach ($referrals as $referral) {
                    /** @var Referral $referral */
                    $hours = $windows[$referral->status->value] ?? null;
                    $enteredAt = $this->enteredAt($referral);
                    if ($hours === null || $hours <= 0 || $enteredAt === null) {
                        continue;
                    }

                    $overdueHours = intdiv(Carbon::now()->getTimestamp() - $enteredAt->getTimestamp(), 3600) - $hours;
                    if ($overdueHours < 0) {
                        continue; // still within the window
                    }

                    $targetLevel = min(1 + intdiv($overdueHours, $hours), $chainSize);
                    if ($targetLevel <= $referral->escalation_level) {
                        continue; // already escalated to (at least) this tier
                    }

                    $referral->update([
                        'escalation_level' => $targetLevel,
                        'sla_breached_at' => $referral->sla_breached_at ?? Carbon::now(),
                    ]);

                    $audit->record('referral.sla_breached', $referral, after: [
                        'status' => $referral->status->value,
                        'escalation_level' => $targetLevel,
                    ]);

                    ReferralSlaBreached::dispatch($referral, $targetLevel);
                }
            });
    }

    /** When the referral entered its current status (the SLA clock start). */
    private function enteredAt(Referral $referral): ?Carbon
    {
        return match ($referral->status) {
            ReferralStatus::Created => $referral->info_responded_at ?? $referral->created_at,
            ReferralStatus::MoreInfoRequested => $referral->info_requested_at,
            ReferralStatus::Accepted => $referral->accepted_at,
            ReferralStatus::InProgress => $referral->started_at,
            default => null,
        };
    }
}
