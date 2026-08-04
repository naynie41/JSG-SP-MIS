<?php

declare(strict_types=1);

namespace App\Domain\Access\Services;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use Illuminate\Support\Carbon;

/**
 * LOGIN ACTIVITY read model (FR-UAM-04/06, FR-AUD-01) — a narrow, read-only projection
 * of the append-only audit log over authentication events, for the administration
 * console's User & Access section.
 *
 * It reuses the audit trail that Phase 1 already writes (`auth.*`, `mfa.*`, the
 * administrative `user.mfa_reset` / `user.password_reset_forced`) — no separate login
 * table and no duplicated logging. Only the audit ENVELOPE is projected: actor, action,
 * IP and time. The `before`/`after` payloads are never read or returned (SECURITY.md §6).
 */
class LoginActivityService
{
    /** Audit actions that constitute authentication / account-security activity. */
    public const ACTIONS = [
        'auth.login',
        'auth.login_failed',
        'auth.logout',
        'auth.account_locked',
        'mfa.enrolled',
        'mfa.disabled',
        'mfa.challenge_failed',
        'user.mfa_reset',
        'user.password_reset_forced',
    ];

    /** Actions that represent a failed or security-relevant attempt. */
    private const FAILURE_ACTIONS = ['auth.login_failed', 'mfa.challenge_failed'];

    private const SECURITY_ACTIONS = ['auth.account_locked', 'user.mfa_reset', 'user.password_reset_forced', 'mfa.disabled'];

    /**
     * Recent authentication activity, newest first, optionally narrowed to one user.
     *
     * @return array<string, mixed>
     */
    public function recent(?string $userId = null, int $limit = 50, int $windowDays = 30): array
    {
        $since = Carbon::now()->subDays($windowDays);

        $base = AuditLog::query()
            ->whereIn('action', self::ACTIONS)
            ->where('created_at', '>=', $since);

        if ($userId !== null) {
            $base->where('actor_id', $userId);
        }

        $entries = (clone $base)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get(['id', 'actor_id', 'actor_mda_id', 'action', 'ip_address', 'created_at']);

        $actors = User::query()
            ->whereIn('id', $entries->pluck('actor_id')->filter()->unique()->all())
            ->get(['id', 'name', 'email'])->keyBy('id');
        $mdas = Mda::query()
            ->whereIn('id', $entries->pluck('actor_mda_id')->filter()->unique()->all())
            ->pluck('name', 'id');

        $rows = [];
        foreach ($entries as $entry) {
            $actor = $entry->actor_id === null ? null : $actors->get($entry->actor_id);
            $rows[] = [
                'id' => $entry->id,
                'action' => $entry->action,
                'outcome' => $this->outcome($entry->action),
                'actor' => $actor->name ?? ($entry->actor_id === null ? 'Unknown' : 'Deleted user'),
                'actor_email' => $actor?->email,
                'actor_mda' => $entry->actor_mda_id === null ? null : ($mdas[$entry->actor_mda_id] ?? null),
                'ip_address' => $entry->ip_address,
                'at' => $entry->created_at?->toIso8601String(),
            ];
        }

        // Window totals are counted over the whole window, not just the returned page.
        $counts = (clone $base)->selectRaw('action, count(*) as c')->groupBy('action')->pluck('c', 'action');

        return [
            'window_days' => $windowDays,
            'summary' => [
                'logins' => (int) ($counts['auth.login'] ?? 0),
                'failed_logins' => (int) ($counts['auth.login_failed'] ?? 0),
                'lockouts' => (int) ($counts['auth.account_locked'] ?? 0),
                'mfa_resets' => (int) ($counts['user.mfa_reset'] ?? 0),
            ],
            'entries' => $rows,
        ];
    }

    private function outcome(string $action): string
    {
        if (in_array($action, self::FAILURE_ACTIONS, true)) {
            return 'failure';
        }
        if (in_array($action, self::SECURITY_ACTIONS, true)) {
            return 'security';
        }

        return 'success';
    }
}
