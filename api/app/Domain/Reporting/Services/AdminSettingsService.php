<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Models\Role;
use App\Domain\Access\Services\RolePermissionService;
use App\Domain\Notification\Contracts\NotificationChannel;
use App\Domain\Registry\Support\BeneficiaryRules;

/**
 * The EFFECTIVE configuration behind the administration console's Settings page.
 *
 * Everything here is **read-only and derived** — the live value of an existing config
 * key, a registered channel's own availability, or a locked domain constant. The
 * console deliberately has no settings store of its own: a value that is set by
 * environment/config is reported with the key that owns it, so an administrator can
 * see what is in force and where to change it, and the console can never drift from
 * the deployment's real configuration.
 *
 * The two things an administrator genuinely CHANGES from Settings are handled by their
 * existing owners: the permission matrix (`role_permission` via
 * {@see RolePermissionService}) and their own notification
 * preferences (`/notifications/preferences`).
 */
class AdminSettingsService
{
    /**
     * @param  iterable<NotificationChannel>  $channels
     */
    public function __construct(private readonly iterable $channels) {}

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return [
            'general' => $this->general(),
            'security' => $this->security(),
            'registry' => $this->registry(),
            'notifications' => $this->notifications(),
        ];
    }

    /**
     * @return list<array{label: string, value: string, source: string}>
     */
    private function general(): array
    {
        return [
            $this->row('Application name', (string) config('app.name'), 'APP_NAME'),
            $this->row('Environment', (string) config('app.env'), 'APP_ENV'),
            $this->row('Timezone', (string) config('app.timezone'), 'config/app.php'),
            $this->row('Locale', (string) config('app.locale'), 'APP_LOCALE'),
            $this->row('Debug mode', $this->bool((bool) config('app.debug')), 'APP_DEBUG'),
            $this->row('Audit retention (days)', (string) config('audit.retention_days', '—'), 'config/audit.php'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function security(): array
    {
        return [
            'policy' => [
                $this->row('MFA enforced', $this->bool((bool) config('security.mfa.enforce')), 'MFA_ENFORCE'),
                $this->row('MFA issuer', (string) config('security.mfa.issuer'), 'MFA_ISSUER'),
                $this->row('Recovery codes issued', (string) config('security.mfa.recovery_code_count'), 'MFA_RECOVERY_CODE_COUNT'),
                $this->row('Lockout after (failed attempts)', (string) config('security.lockout.max_attempts'), 'AUTH_LOCKOUT_MAX_ATTEMPTS'),
                $this->row('Lockout ceiling (minutes)', (string) config('security.lockout.max_minutes'), 'AUTH_LOCKOUT_MAX_MINUTES'),
                $this->row('Idle session timeout (minutes)', (string) config('security.session.idle_timeout_minutes'), 'SESSION_IDLE_TIMEOUT_MINUTES'),
                $this->row('Absolute session lifetime (minutes)', (string) config('security.session.absolute_lifetime_minutes'), 'SESSION_ABSOLUTE_LIFETIME_MINUTES'),
                $this->row('Export rate limit (per minute)', (string) config('security.rate_limits.exports_per_minute'), 'RATE_LIMIT_EXPORTS_PER_MINUTE'),
            ],
            // Which roles must carry MFA — a Phase 1 property of the role itself.
            'mfa_roles' => Role::query()->orderBy('name')->get()
                ->map(fn (Role $role): array => [
                    'key' => $role->key,
                    'name' => $role->name,
                    'requires_mfa' => (bool) $role->requires_mfa,
                ])->values()->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function registry(): array
    {
        return [
            // Locked decision (CLAUDE.md §9): identity-field validation is never
            // administrator-editable, so it is reported, never offered as a control.
            'identity_fields' => BeneficiaryRules::IDENTITY_FIELDS,
            'non_identity_fields' => BeneficiaryRules::NON_IDENTITY_FIELDS,
            'locked' => true,
            'privacy' => [
                $this->row('Processing requires consent', $this->bool((bool) config('privacy.consent.processing_requires_consent')), 'PRIVACY_PROCESSING_REQUIRES_CONSENT'),
                $this->row('Retention enforcement', $this->bool((bool) config('privacy.retention.enabled')), 'PRIVACY_RETENTION_ENABLED'),
                $this->row('Hard delete on retention', $this->bool((bool) config('privacy.retention.delete_hard')), 'PRIVACY_RETENTION_DELETE_HARD'),
            ],
            'consent_purposes' => array_map(
                fn (array $purpose, string $key): array => [
                    'key' => $key,
                    'label' => (string) $purpose['label'],
                    'gate' => (string) $purpose['gate'],
                ],
                $purposes = (array) config('privacy.consent.purposes', []),
                array_keys($purposes),
            ),
        ];
    }

    /**
     * Channel availability is asked of each registered channel, so a stubbed
     * SMS/WhatsApp integration reports itself as unavailable rather than the console
     * claiming a delivery path that does not exist.
     *
     * @return list<array{key: string, available: bool}>
     */
    private function notifications(): array
    {
        $out = [];
        foreach ($this->channels as $channel) {
            $out[] = ['key' => $channel->key(), 'available' => $channel->isAvailable()];
        }

        return $out;
    }

    /**
     * @return array{label: string, value: string, source: string}
     */
    private function row(string $label, string $value, string $source): array
    {
        return ['label' => $label, 'value' => $value === '' ? '—' : $value, 'source' => $source];
    }

    private function bool(bool $value): string
    {
        return $value ? 'Enabled' : 'Disabled';
    }
}
