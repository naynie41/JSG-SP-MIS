<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Reporting\Services\DashboardService;
use Database\Seeders\ExecutiveDemoSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

/**
 * The Phase 6E demo seed must produce enough cross-module data that EVERY executive
 * panel — KPIs, demographics, households, programmes, coverage bands, coordination,
 * duplicates AND the trend history the projections need — renders meaningfully.
 */
class ExecutiveDemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seed_populates_every_executive_panel(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(ExecutiveDemoSeeder::class);

        $exec = User::factory()->create([
            'role_id' => Role::where('key', 'executive')->firstOrFail()->id,
            'mda_id' => null,
        ]);
        $m = app(DashboardService::class)->forUser($exec)['metrics'];

        // Headline reach + programmes + demographics + households.
        $this->assertGreaterThan(0, $m['population']['net_unique_served']);
        $this->assertGreaterThan(0, $m['programmes']['active']);
        $this->assertGreaterThan(0, $m['demographics']['total']);
        $this->assertGreaterThan(0, $m['household_size']['total_households']);

        // Net-unique is distinct from (and never exceeds) the gross delivery count.
        $this->assertLessThanOrEqual($m['benefits']['disbursed']['benefit_count'], $m['population']['net_unique_served']);

        // Coverage bands + programme performance render.
        $this->assertNotEmpty($m['coverage_bands']['areas']);
        $this->assertNotEmpty($m['programme_performance']);

        // Registry quality: an import-matched duplicate was surfaced.
        $this->assertGreaterThan(0, $m['registry_quality']['duplicates_detected']);

        // Coordination / data sharing: sync connectors + runs exist.
        $this->assertGreaterThan(0, $m['coordination']['sync_health']['total_runs']);
        $this->assertGreaterThan(0, $m['coordination']['sync_health']['connectors']);

        // Trend HISTORY for the charts + projections: ≥ 2 months of registrations + disbursement.
        $nonZero = fn (Collection $series): int => $series->filter(fn (array $p): bool => $p['value'] > 0)->count();
        $this->assertGreaterThanOrEqual(2, $nonZero(collect($m['trends']['registrations'])));
        $this->assertGreaterThanOrEqual(2, $nonZero(collect($m['trends']['disbursement'])));
    }
}
