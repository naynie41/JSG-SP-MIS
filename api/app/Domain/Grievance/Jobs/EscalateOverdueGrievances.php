<?php

declare(strict_types=1);

namespace App\Domain\Grievance\Jobs;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Grievance\Enums\GrievanceStatus;
use App\Domain\Grievance\Events\GrievanceSlaBreached;
use App\Domain\Grievance\Models\Grievance;
use App\Domain\Grievance\Models\GrievanceSlaPolicy;
use App\Domain\Referral\Jobs\EscalateOverdueReferrals;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

/**
 * Scheduled SLA sweep (PRD FR-GRM-03), mirroring {@see EscalateOverdueReferrals}.
 * Flags unresolved grievances that have breached their CATEGORY window (measured from
 * when they were logged) and escalates one tier per elapsed window, capped at the
 * configured chain length, dispatching {@see GrievanceSlaBreached} whenever the level
 * increases. It NEVER changes a grievance's status — no auto-close; only escalate + notify.
 */
class EscalateOverdueGrievances implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** Non-terminal statuses that can breach an SLA (still unresolved). */
    private const ACTIVE = [
        GrievanceStatus::Open,
        GrievanceStatus::Assigned,
        GrievanceStatus::InProgress,
    ];

    public function handle(AuditLogger $audit): void
    {
        /** @var array<string, int> $windows */
        $windows = GrievanceSlaPolicy::query()->pluck('hours', 'category')->all();
        $chainSize = max(1, count((array) config('sla.grievance.escalation_chain', [])));

        Grievance::query()
            ->withoutGlobalScope(MdaScope::class)
            ->whereIn('status', array_map(static fn (GrievanceStatus $s): string => $s->value, self::ACTIVE))
            ->orderBy('id')
            ->chunkById(200, function ($grievances) use ($windows, $chainSize, $audit): void {
                foreach ($grievances as $grievance) {
                    /** @var Grievance $grievance */
                    $hours = $windows[$grievance->category->value] ?? null;
                    if ($hours === null || $hours <= 0 || $grievance->created_at === null) {
                        continue;
                    }

                    $overdueHours = intdiv(Carbon::now()->getTimestamp() - $grievance->created_at->getTimestamp(), 3600) - $hours;
                    if ($overdueHours < 0) {
                        continue; // still within the window
                    }

                    $targetLevel = min(1 + intdiv($overdueHours, $hours), $chainSize);
                    if ($targetLevel <= $grievance->escalation_level) {
                        continue; // already escalated to (at least) this tier
                    }

                    $grievance->update([
                        'escalation_level' => $targetLevel,
                        'sla_breached_at' => $grievance->sla_breached_at ?? Carbon::now(),
                    ]);

                    $audit->record('grievance.sla_breached', $grievance, after: [
                        'status' => $grievance->status->value,
                        'escalation_level' => $targetLevel,
                    ]);

                    GrievanceSlaBreached::dispatch($grievance, $targetLevel);
                }
            });
    }
}
