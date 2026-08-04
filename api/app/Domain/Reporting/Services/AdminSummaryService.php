<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Enums\MdaStatus;
use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Benefit\Services\LedgerAggregator;
use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * GOVERNANCE aggregates for the System Administrator console (FR-UAM-01, FR-AUD-01).
 *
 * This is the administration read-model: who is provisioned, what is configured, and
 * what has recently been changed — deliberately NOT system health. Backup age, queue
 * depth, snapshot freshness, CPU/memory and other infrastructure telemetry stay out of
 * this console (they are an ops/CLI concern served by `/health/metrics`).
 *
 * Every figure is a COUNT over data the administrator may already see; no beneficiary
 * PII is read or returned, and audit `before`/`after` payloads are never exposed —
 * recent activity carries only actor, action, entity type and timestamp (SECURITY.md
 * §6: audit payloads may contain sensitive values).
 */
class AdminSummaryService
{
    /** Months of user-adoption history to return. */
    private const TREND_MONTHS = 12;

    /** How many recent administrative events to surface. */
    private const RECENT_LIMIT = 10;

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        $registry = $this->registrySnapshot();
        $kpis = $this->kpis();

        return [
            'generated_at' => Carbon::now()->toIso8601String(),
            'kpis' => $kpis,
            'adoption_trend' => $this->adoptionTrend(),
            'registry' => $registry,
            'alerts' => $this->alerts($kpis, $registry),
            'recent_activity' => $this->recentActivity(),
        ];
    }

    /**
     * Provisioning + catalog counts. Global by design: the System Administrator's remit
     * is the whole platform, so the MDA scope is bypassed EXPLICITLY (never implicitly).
     *
     * @return array<string, int>
     */
    private function kpis(): array
    {
        $usersByStatus = User::query()
            ->selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');

        $partnerRoleId = DB::table('roles')->where('key', RoleKey::DevelopmentPartner->value)->value('id');

        return [
            'users_total' => (int) $usersByStatus->sum(),
            'users_active' => (int) ($usersByStatus[UserStatus::Active->value] ?? 0),
            'users_suspended' => (int) ($usersByStatus[UserStatus::Suspended->value] ?? 0),
            'users_deactivated' => (int) ($usersByStatus[UserStatus::Deactivated->value] ?? 0),
            'users_without_mfa' => (int) User::query()
                ->where('status', UserStatus::Active->value)->where('mfa_enabled', false)->count(),
            'mdas_registered' => (int) Mda::query()->count(),
            'mdas_active' => (int) Mda::query()->where('status', MdaStatus::Active->value)->count(),
            'development_partners' => $partnerRoleId === null
                ? 0
                : (int) User::query()->where('role_id', $partnerRoleId)->count(),
            'programmes_catalog' => (int) Programme::query()->count(),
            'activities_active' => (int) Activity::query()->withoutGlobalScope(MdaScope::class)
                ->where('status', ActivityStatus::Active->value)->count(),
            'beneficiaries_registered' => (int) Beneficiary::query()->withoutGlobalScope(MdaScope::class)->count(),
            'households_registered' => (int) Household::query()->withoutGlobalScope(MdaScope::class)->count(),
        ];
    }

    /**
     * USER ADOPTION over the last N months: accounts created per month plus the running
     * total, so the console can show uptake rather than a raw headcount.
     *
     * @return array<int, array{month: string, new_users: int, total_users: int}>
     */
    private function adoptionTrend(): array
    {
        $expr = LedgerAggregator::monthKeyExpr('created_at');
        $labels = $this->monthLabels(self::TREND_MONTHS);
        $since = Carbon::createFromFormat('Y-m', $labels[0])->startOfMonth();

        $perMonth = User::query()
            ->where('created_at', '>=', $since)
            ->selectRaw("{$expr} as m, count(*) as c")
            ->groupByRaw($expr)
            ->pluck('c', 'm');

        // Everything created before the window forms the opening balance.
        $running = (int) User::query()->where('created_at', '<', $since)->count();

        $out = [];
        foreach ($labels as $month) {
            $new = (int) ($perMonth[$month] ?? 0);
            $running += $new;
            $out[] = ['month' => $month, 'new_users' => $new, 'total_users' => $running];
        }

        return $out;
    }

    /**
     * REGISTRY & DATA QUALITY snapshot: import throughput, row-level validation, and
     * duplicate resolution. Counts only — no row payloads, no PII.
     *
     * @return array<string, mixed>
     */
    private function registrySnapshot(): array
    {
        $batches = DB::table('import_batches')
            ->selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');

        $rows = DB::table('import_rows')
            ->selectRaw('count(*) as total')
            ->selectRaw('sum(case when is_valid then 1 else 0 end) as valid')
            ->first();

        $rowsTotal = (int) ($rows->total ?? 0);
        $rowsValid = (int) ($rows->valid ?? 0);

        $surfaced = (int) DB::table('import_rows')->whereIn('match_band', ['exact', 'probable'])->count();
        $resolved = (int) DB::table('import_rows')
            ->whereIn('match_band', ['exact', 'probable'])->whereNotNull('resolution')->count();

        return [
            'imports_total' => (int) $batches->sum(),
            'imports_completed' => (int) ($batches['completed'] ?? 0),
            'imports_failed' => (int) ($batches['failed'] ?? 0),
            'imports_in_progress' => (int) ($batches['pending'] ?? 0) + (int) ($batches['processing'] ?? 0)
                + (int) ($batches['preview_ready'] ?? 0) + (int) ($batches['committing'] ?? 0),
            'rows_total' => $rowsTotal,
            'rows_valid' => $rowsValid,
            'rows_invalid' => max(0, $rowsTotal - $rowsValid),
            'validation_rate' => $rowsTotal > 0 ? round($rowsValid / $rowsTotal, 4) : null,
            'duplicates_surfaced' => $surfaced,
            'duplicates_resolved' => $resolved,
            'duplicates_pending' => max(0, $surfaced - $resolved),
            // Share of processed rows that raised a potential match (lower is better).
            'duplicate_rate' => $rowsTotal > 0 ? round($surfaced / $rowsTotal, 4) : null,
        ];
    }

    /**
     * ADMINISTRATIVE alerts — governance conditions an administrator must act on
     * (provisioning, configuration, data quality). Never infrastructure warnings.
     *
     * @param  array<string, int>  $kpis
     * @param  array<string, mixed>  $registry
     * @return array<int, array<string, string>>
     */
    private function alerts(array $kpis, array $registry): array
    {
        $out = [];

        if ($kpis['users_without_mfa'] > 0) {
            $out[] = [
                'id' => 'mfa',
                'severity' => 'warning',
                'title' => $kpis['users_without_mfa'].' active '.($kpis['users_without_mfa'] === 1 ? 'account has' : 'accounts have').' no MFA',
                'detail' => 'Two-factor authentication is not enrolled. Review these accounts under User & Access.',
            ];
        }

        if ($kpis['users_suspended'] > 0) {
            $out[] = [
                'id' => 'suspended',
                'severity' => 'info',
                'title' => $kpis['users_suspended'].' suspended '.($kpis['users_suspended'] === 1 ? 'account' : 'accounts'),
                'detail' => 'Suspended accounts retain their role. Reactivate or deactivate them.',
            ];
        }

        $inactiveMdas = $kpis['mdas_registered'] - $kpis['mdas_active'];
        if ($inactiveMdas > 0) {
            $out[] = [
                'id' => 'mdas',
                'severity' => 'info',
                'title' => $inactiveMdas.' inactive '.($inactiveMdas === 1 ? 'MDA' : 'MDAs'),
                'detail' => 'Inactive MDAs cannot register or serve beneficiaries.',
            ];
        }

        if ($registry['imports_failed'] > 0) {
            $out[] = [
                'id' => 'imports',
                'severity' => 'warning',
                'title' => $registry['imports_failed'].' failed '.($registry['imports_failed'] === 1 ? 'import' : 'imports'),
                'detail' => 'Review the error report and reprocess under Registry & Data Quality.',
            ];
        }

        if ($registry['duplicates_pending'] > 0) {
            $out[] = [
                'id' => 'duplicates',
                'severity' => 'warning',
                'title' => $registry['duplicates_pending'].' unresolved duplicate '.($registry['duplicates_pending'] === 1 ? 'match' : 'matches'),
                'detail' => 'Potential duplicates are awaiting an adjudication decision.',
            ];
        }

        if ($kpis['programmes_catalog'] === 0) {
            $out[] = [
                'id' => 'catalog',
                'severity' => 'warning',
                'title' => 'Programme catalog is empty',
                'detail' => 'MDAs cannot create activities until the catalog has at least one programme.',
            ];
        }

        return $out;
    }

    /**
     * RECENT ADMINISTRATIVE ACTIVITY from the append-only audit log. Only the envelope
     * is exposed — actor, action, entity type and time. The `before`/`after` payloads
     * are deliberately NOT returned: they may carry sensitive values (SECURITY.md §6).
     *
     * @return array<int, array<string, string|null>>
     */
    private function recentActivity(): array
    {
        $entries = AuditLog::query()
            ->orderByDesc('created_at')
            ->limit(self::RECENT_LIMIT)
            ->get(['id', 'actor_id', 'actor_mda_id', 'action', 'entity_type', 'created_at']);

        $actors = User::query()
            ->whereIn('id', $entries->pluck('actor_id')->filter()->unique()->all())
            ->pluck('name', 'id');
        $mdas = Mda::query()
            ->whereIn('id', $entries->pluck('actor_mda_id')->filter()->unique()->all())
            ->pluck('name', 'id');

        $out = [];
        foreach ($entries as $entry) {
            $out[] = [
                'id' => $entry->id,
                'action' => $entry->action,
                'entity_type' => $entry->entity_type === null ? null : class_basename($entry->entity_type),
                'actor' => $entry->actor_id === null ? 'System' : ($actors[$entry->actor_id] ?? 'Unknown user'),
                'actor_mda' => $entry->actor_mda_id === null ? null : ($mdas[$entry->actor_mda_id] ?? null),
                'at' => $entry->created_at?->toIso8601String(),
            ];
        }

        return $out;
    }

    /**
     * @return list<string> the last N 'YYYY-MM' month labels, oldest first
     */
    private function monthLabels(int $months): array
    {
        $labels = [];
        $cursor = Carbon::now()->startOfMonth()->subMonths(max(0, $months - 1));
        for ($i = 0; $i < max(1, $months); $i++) {
            $labels[] = $cursor->format('Y-m');
            $cursor->addMonth();
        }

        return $labels;
    }
}
