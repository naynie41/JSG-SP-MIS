<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Models\ProgrammeFunder;
use App\Domain\Registry\Enums\Lga;
use App\Domain\Reporting\Gis\BoundaryLoader;
use App\Domain\Reporting\Gis\GeoBoundary;
use App\Domain\Reporting\Services\DashboardSnapshotService;
use Illuminate\Database\Seeder;

/**
 * Phase 6 sample data (PRD FR-RPT/FR-GIS) so every dashboard, report and the map render
 * meaningfully out of the box: a Development Partner + a funded programme (partner
 * dashboard), the real Jigawa LGA boundaries (GIS choropleth), and warmed dashboard
 * snapshots. LOCAL/STAGING ONLY — never real PII, never production. Idempotent.
 */
class ReportingSampleSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $this->seedFundedPartner();
        $this->seedLgaBoundaries();

        // Warm the dashboard snapshots so dashboards show data on first load.
        app(DashboardSnapshotService::class)->refreshAll();
    }

    /** A Development Partner user funding a couple of active programmes. */
    private function seedFundedPartner(): void
    {
        $role = Role::where('key', RoleKey::DevelopmentPartner->value)->first();
        if ($role === null) {
            return;
        }

        $partner = User::updateOrCreate(
            ['email' => 'partner@spmis.local'],
            [
                'name' => 'Development Partner',
                'password' => (string) env('SEED_SAMPLE_PASSWORD', 'ChangeMe!Sample12345'),
                'role_id' => $role->id,
                'mda_id' => null,
                'status' => UserStatus::Active,
                'email_verified_at' => now(),
            ],
        );

        foreach (Programme::query()->where('status', 'active')->take(2)->get() as $programme) {
            ProgrammeFunder::query()->firstOrCreate(['programme_id' => $programme->id, 'user_id' => $partner->id]);

            // Phase 6P: attribute the programme's activities to the funding partner, so the
            // partner's funded scope (activities.funding_partner_id) is queryable + non-empty.
            Activity::query()->withoutGlobalScope(MdaScope::class)
                ->where('programme_id', $programme->id)
                ->whereNull('funding_partner_id')
                ->update(['funding_partner_id' => $partner->id]);
        }
    }

    /**
     * Jigawa's 27 LGA boundaries for the map.
     *
     * These are REAL traced boundaries, shipped with the repo
     * (`database/data/jigawa-lga-boundaries.geojson`) rather than fetched or invented, so
     * a fresh environment renders the state and not a stand-in. This used to plant 27
     * identical squares on a 6-column grid: harmless in principle, because the loader
     * command was documented as the way to replace them — and permanent in practice,
     * because nothing failed while they were wrong. A map of made-up shapes is worse than
     * no map: it invites people to read coverage off geography that does not exist.
     *
     * Re-runnable: the loader upserts on (level, code), so this also REPLACES the old
     * placeholder squares in an environment that still has them.
     */
    private function seedLgaBoundaries(): void
    {
        $file = database_path('data/jigawa-lga-boundaries.geojson');
        if (! is_file($file)) {
            return;
        }

        $decoded = json_decode((string) file_get_contents($file), true);
        if (! is_array($decoded) || ($decoded['type'] ?? null) !== 'FeatureCollection') {
            return;
        }

        app(BoundaryLoader::class)->load(GeoBoundary::LEVEL_LGA, $decoded);
    }
}
