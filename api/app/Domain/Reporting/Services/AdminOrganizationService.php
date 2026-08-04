<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Enums\MdaStatus;
use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Models\Activity;
use Illuminate\Support\Facades\DB;

/**
 * ORGANIZATION roll-up for the administration console (FR-UAM-01, FR-PRG-05).
 *
 * A read-only aggregate over data that already exists: MDAs (Phase 1), the users
 * allocated to them (Phase 1), and the activities they own (Phase 4). It answers the
 * questions the console's Organization section asks — how many users and MDA
 * administrators each organization has, and how much programme delivery it runs —
 * WITHOUT duplicating any organization logic: creating, editing, activating and
 * deactivating an MDA all remain the existing `/mdas` endpoints and policies.
 *
 * Development Partners are users holding the Development Partner role; their footprint
 * is the activities they FUND (`activities.funding_partner_id`, Phase 6P).
 *
 * Counts only — no PII beyond the staff names/emails an administrator already manages.
 * Every query bypasses the MDA scope EXPLICITLY: the administrator's remit is platform-wide.
 */
class AdminOrganizationService
{
    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        $mdas = Mda::query()->orderBy('name')->get(['id', 'name', 'type', 'status']);

        $usersByMda = User::query()
            ->whereNotNull('mda_id')
            ->selectRaw('mda_id, count(*) as c')->groupBy('mda_id')->pluck('c', 'mda_id');

        $adminRoleId = DB::table('roles')->where('key', RoleKey::MdaAdmin->value)->value('id');
        $adminsByMda = $adminRoleId === null
            ? collect()
            : User::query()->where('role_id', $adminRoleId)->whereNotNull('mda_id')
                ->selectRaw('mda_id, count(*) as c')->groupBy('mda_id')->pluck('c', 'mda_id');

        $activityBase = Activity::query()->withoutGlobalScope(MdaScope::class);
        $activitiesByMda = (clone $activityBase)
            ->selectRaw('owner_mda_id, count(*) as c')->groupBy('owner_mda_id')->pluck('c', 'owner_mda_id');
        $activeByMda = (clone $activityBase)->where('status', ActivityStatus::Active->value)
            ->selectRaw('owner_mda_id, count(*) as c')->groupBy('owner_mda_id')->pluck('c', 'owner_mda_id');

        $rows = [];
        foreach ($mdas as $mda) {
            $rows[] = [
                'id' => $mda->id,
                'name' => $mda->name,
                'type' => $mda->type->value,
                'status' => $mda->status->value,
                'users_total' => (int) ($usersByMda[$mda->id] ?? 0),
                'mda_admins' => (int) ($adminsByMda[$mda->id] ?? 0),
                'activities_total' => (int) ($activitiesByMda[$mda->id] ?? 0),
                'activities_active' => (int) ($activeByMda[$mda->id] ?? 0),
            ];
        }

        return [
            'mdas' => $rows,
            'partners' => $this->partners(),
            'totals' => [
                'mdas' => $mdas->count(),
                'mdas_active' => $mdas->where('status', MdaStatus::Active)->count(),
                'users_allocated' => (int) $usersByMda->sum(),
                // Platform-level accounts (executives, partners, administrators) belong
                // to no MDA — surfaced so allocation always reconciles to the user total.
                'users_unallocated' => (int) User::query()->whereNull('mda_id')->count(),
            ],
        ];
    }

    /**
     * Development Partners with the delivery they fund (Phase 6P attribution).
     *
     * @return array<int, array<string, mixed>>
     */
    private function partners(): array
    {
        $roleId = DB::table('roles')->where('key', RoleKey::DevelopmentPartner->value)->value('id');
        if ($roleId === null) {
            return [];
        }

        $partners = User::query()->where('role_id', $roleId)
            ->orderBy('name')->get(['id', 'name', 'email', 'status']);
        if ($partners->isEmpty()) {
            return [];
        }

        $funded = Activity::query()->withoutGlobalScope(MdaScope::class)
            ->whereIn('funding_partner_id', $partners->pluck('id')->all())
            ->get(['funding_partner_id', 'programme_id', 'owner_mda_id'])
            ->groupBy('funding_partner_id');

        $out = [];
        foreach ($partners as $partner) {
            $activities = $funded[$partner->id] ?? collect();
            $out[] = [
                'id' => $partner->id,
                'name' => $partner->name,
                'email' => $partner->email,
                'status' => $partner->status->value,
                'is_active' => $partner->status === UserStatus::Active,
                'funded_activities' => $activities->count(),
                'funded_programmes' => $activities->pluck('programme_id')->unique()->count(),
                'implementing_mdas' => $activities->pluck('owner_mda_id')->filter()->unique()->count(),
            ];
        }

        return $out;
    }
}
