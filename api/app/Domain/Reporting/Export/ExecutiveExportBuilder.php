<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Export;

use Illuminate\Support\Carbon;

/**
 * Turns the executive dashboard metric bundle into an AGGREGATE {@see ReportData} for
 * CSV/Excel/PDF export (PRD FR-RPT-03). Every row is a section/metric/value tuple built
 * from de-identified aggregates — there is NO beneficiary-level data and NO PII column,
 * so an executive export can never leak identities (SECURITY.md). The same builder feeds
 * all three formats, so they always agree.
 */
class ExecutiveExportBuilder
{
    /**
     * @param  array<string, mixed>  $m  the dashboard metric bundle (scoped + filtered)
     */
    public function build(array $m, string $scopeLabel, string $subtitle = 'Aggregate metrics · de-identified'): ReportData
    {
        $rows = [];
        $add = function (string $section, string $metric, string $value) use (&$rows): void {
            $rows[] = ['section' => $section, 'metric' => $metric, 'value' => $value];
        };

        $naira = fn ($kobo): string => '₦'.number_format((int) $kobo / 100, 2);
        $count = fn ($n): string => number_format((int) $n);
        $pct = fn ($rate): string => $rate === null ? '—' : round((float) $rate * 100).'%';

        $pop = $m['population'] ?? [];
        $add('Reach', 'Net-unique beneficiaries', $count($pop['net_unique_served'] ?? 0));
        $add('Reach', 'Total households', $count($pop['total_households'] ?? 0));
        $add('Reach', 'Total individuals', $count($pop['total_individuals'] ?? 0));
        $add('Reach', 'New registrations (period)', $count($pop['new_registrations_period'] ?? 0));
        $add('Coverage', 'LGAs covered', $count($pop['lgas_covered'] ?? 0));
        $add('Coverage', 'Wards covered', $count($pop['wards_covered'] ?? 0));

        $b = $m['benefits']['budget'] ?? [];
        $add('Budget', 'Allocated', $naira($b['allocated'] ?? 0));
        $add('Budget', 'Disbursed', $naira($b['utilized_value'] ?? 0));
        $add('Budget', 'Remaining', $naira($b['remaining'] ?? 0));
        $add('Budget', 'Utilisation', $pct($b['utilization_rate'] ?? null));

        $prog = $m['programmes'] ?? [];
        $add('Programmes', 'Active programmes', $count($prog['active'] ?? 0));
        $add('Programmes', 'Active activities', $count($prog['activities_active'] ?? 0));
        foreach ($m['programme_performance'] ?? [] as $p) {
            $name = $p['name'] ?? 'Programme';
            $add('Programme performance', "{$name} — reached / target", $count($p['reached'] ?? 0).' / '.$count($p['target'] ?? 0).' ('.$pct($p['completion_rate'] ?? null).')');
            $add('Programme performance', "{$name} — spent / allocated", $naira($p['budget']['spent'] ?? 0).' / '.$naira($p['budget']['allocated'] ?? 0));
        }

        $d = $m['demographics'] ?? [];
        $ab = $d['age_bands'] ?? [];
        $add('Demographics', 'Female share', $pct($d['female_pct'] ?? null));
        $add('Demographics', 'Children (0–17)', $count($ab['children'] ?? 0));
        $add('Demographics', 'Youth (18–34)', $count($ab['youth'] ?? 0));
        $add('Demographics', 'Adults (35–59)', $count($ab['adults'] ?? 0));
        $add('Demographics', 'Elderly (60+)', $count($ab['elderly'] ?? 0));

        $cb = $m['coverage_bands']['summary'] ?? [];
        $add('Coverage bands', 'High (green) areas', $count($cb['green'] ?? 0));
        $add('Coverage bands', 'Moderate (yellow) areas', $count($cb['yellow'] ?? 0));
        $add('Coverage bands', 'Low (red) areas', $count($cb['red'] ?? 0));

        $rq = $m['registry_quality'] ?? [];
        $add('Registry quality', 'Verified', $count($rq['verified'] ?? 0));
        $add('Registry quality', 'Pending', $count($rq['pending'] ?? 0));
        $add('Registry quality', 'Duplicates detected', $count($rq['duplicates_detected'] ?? 0));
        $add('Registry quality', 'NIN completeness', $pct($rq['nin_completeness'] ?? null));
        $add('Registry quality', 'Data completeness', $pct($rq['data_completeness'] ?? null));

        $c = $m['coordination'] ?? null;
        if (is_array($c)) {
            $add('Coordination', 'Active agencies', $count($c['active_mdas'] ?? 0));
            $add('Coordination', 'Joint programmes', $count($c['joint_programmes'] ?? 0));
            $add('Coordination', 'Cross-MDA beneficiaries', $count($c['cross_mda_beneficiaries'] ?? 0));
            $add('Coordination', 'Referral completion', $pct($c['referral_throughput']['completion_rate'] ?? null));
            $add('Coordination', 'Request-to-serve approval', $pct($c['request_to_serve']['approval_rate'] ?? null));
        }

        return new ReportData(
            reportKey: 'executive-dashboard',
            title: 'Executive dashboard',
            subtitle: $subtitle,
            scopeLabel: $scopeLabel,
            generatedAt: Carbon::now(),
            columns: [
                new ReportColumn('section', 'Section'),
                new ReportColumn('metric', 'Metric'),
                new ReportColumn('value', 'Value'),
            ],
            rows: $rows,
        );
    }
}
