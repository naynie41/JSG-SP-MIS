<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
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
 * dashboard), synthetic LGA boundaries (GIS choropleth), and warmed dashboard
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
        }
    }

    /**
     * Synthetic LGA boundaries for the map (real Jigawa boundaries are loaded from a
     * GeoJSON file via `php artisan gis:load-boundaries` — see the GIS README). Placeholder
     * squares laid over Jigawa; `code` slugs match the registry LGA values so coverage joins.
     */
    private function seedLgaBoundaries(): void
    {
        if (GeoBoundary::query()->where('level', GeoBoundary::LEVEL_LGA)->exists()) {
            return;
        }

        $features = [];
        foreach (Lga::cases() as $index => $lga) {
            $column = $index % 6;
            $row = intdiv($index, 6);
            $lng = 8.4 + $column * 0.4;
            $lat = 12.9 - $row * 0.4;

            $features[] = [
                'type' => 'Feature',
                'properties' => ['name' => $lga->label()],
                'geometry' => [
                    'type' => 'Polygon',
                    'coordinates' => [[
                        [$lng, $lat], [$lng + 0.35, $lat], [$lng + 0.35, $lat - 0.35], [$lng, $lat - 0.35], [$lng, $lat],
                    ]],
                ],
            ];
        }

        app(BoundaryLoader::class)->load(GeoBoundary::LEVEL_LGA, ['type' => 'FeatureCollection', 'features' => $features]);
    }
}
