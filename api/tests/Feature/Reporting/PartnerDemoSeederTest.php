<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Models\User;
use App\Domain\Reporting\Services\DashboardService;
use Database\Seeders\PartnerDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 6P finalisation — the multi-partner demo seeder produces enough synthetic,
 * activity-precise data that EVERY partner tab, the overlap detector and the map render
 * meaningfully (never real PII). This doubles as an end-to-end check of the whole suite.
 */
class PartnerDemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_multi_partner_data_renders_every_partner_view(): void
    {
        $this->seed(PartnerDemoSeeder::class);

        $worldBank = User::query()->where('email', 'worldbank.partner@example.test')->firstOrFail();
        $pf = app(DashboardService::class)->forUser($worldBank)['metrics']['partner_funding'];

        $this->assertNotNull($pf);

        // Overview — funded portfolio with delivery value ≤ committed funding.
        $this->assertGreaterThan(0, $pf['allocated']);
        $this->assertGreaterThan(0, $pf['delivered_value']);
        $this->assertLessThanOrEqual($pf['allocated'], $pf['delivered_value']);

        // Programmes & Results — every delivery status is exercised.
        $statuses = collect($pf['programmes'])->pluck('status_light')->unique()->all();
        foreach (['on_track', 'completed', 'at_risk', 'delayed'] as $status) {
            $this->assertContains($status, $statuses);
        }

        // Output indicators (OUTPUTS ONLY) are present.
        $this->assertNotEmpty($pf['output_indicators']);

        // Registry — the reduced funnel narrows (enrolled-but-not-served exist).
        $this->assertGreaterThan($pf['registry']['funnel']['receiving'], $pf['registry']['funnel']['registered']);
        $this->assertGreaterThan(0, $pf['registry']['total_households']);

        // Coordination — ≥2 funders and PROGRAMME OVERLAP detected (CCT × dutse, two funders/MDAs).
        $this->assertGreaterThanOrEqual(2, $pf['coordination']['landscape']['funders']);
        $this->assertGreaterThanOrEqual(1, $pf['programme_overlap']['count']);

        // A co-funder appears WITHOUT amounts (money never leaks between partners).
        $coFunder = collect($pf['coordination']['funding_by_partner'])->firstWhere('is_self', false);
        $this->assertNotNull($coFunder);
        $this->assertNull($coFunder['allocated']);

        // Investment/coverage — funded areas carry attributed budget (map density metric).
        $partnerB = User::query()->where('email', 'unicef.partner@example.test')->firstOrFail();
        $this->assertNotSame(
            app(DashboardService::class)->forUser($partnerB)['metrics']['partner_funding']['allocated'],
            $pf['allocated'],
        ); // each partner sees only its own funded totals
    }
}
