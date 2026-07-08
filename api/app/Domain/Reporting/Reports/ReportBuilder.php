<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Reports;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Services\LedgerAggregator;
use App\Domain\Programme\Models\Programme;
use App\Domain\Reporting\Export\ReportColumn;
use App\Domain\Reporting\Export\ReportData;
use App\Domain\Reporting\Services\DashboardMetricsService;
use App\Domain\Reporting\Support\DashboardScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Builds a standard report's {@see ReportData} from the aggregation layer for a
 * resolved {@see DashboardScope} (PRD FR-RPT-03). Because every figure comes from the
 * same layer the dashboards use, reports reconcile with dashboards by construction.
 * All output is de-identified aggregate data — no beneficiary PII.
 */
class ReportBuilder
{
    public function __construct(
        private readonly DashboardMetricsService $metrics,
        private readonly LedgerAggregator $ledger,
    ) {}

    public function build(string $reportKey, DashboardScope $scope): ReportData
    {
        return match ($reportKey) {
            'beneficiaries_by_lga' => $this->beneficiariesByLga($scope),
            'benefits_by_programme' => $this->benefitsByProgramme($scope),
            'benefits_by_mda' => $this->benefitsByMda($scope),
            'benefits_by_lga' => $this->benefitsByLga($scope),
            'budget_utilization' => $this->budgetUtilization($scope),
            'referral_completion' => $this->referralCompletion($scope),
            'grievance_sla' => $this->grievanceSla($scope),
            default => throw new \InvalidArgumentException("Unknown report: {$reportKey}"),
        };
    }

    private function beneficiariesByLga(DashboardScope $scope): ReportData
    {
        /** @var array<string, int> $byLga */
        $byLga = $this->metrics->compute($scope)['registry']['beneficiaries']['by_lga'];
        arsort($byLga);

        $rows = [];
        foreach ($byLga as $lga => $count) {
            $rows[] = ['lga' => $this->title($lga), 'beneficiaries' => $count];
        }

        return $this->data('beneficiaries_by_lga', 'Beneficiaries by LGA', $scope, [
            new ReportColumn('lga', 'LGA'),
            new ReportColumn('beneficiaries', 'Beneficiaries', numeric: true),
        ], $rows);
    }

    private function benefitsByProgramme(DashboardScope $scope): ReportData
    {
        $groups = $this->ledger->scopedGroup('programme', $scope->mdaIds, $scope->programmeIds);
        $names = Programme::query()->withoutGlobalScope(MdaScope::class)
            ->whereIn('id', array_column($groups, 'key'))->pluck('name', 'id')->all();

        return $this->benefitGroupReport('benefits_by_programme', 'Benefits by programme', 'Programme', $scope, $groups,
            fn (mixed $key): string => $names[$key] ?? 'Unknown');
    }

    private function benefitsByMda(DashboardScope $scope): ReportData
    {
        $groups = $this->ledger->scopedGroup('mda', $scope->mdaIds, $scope->programmeIds);
        $names = Mda::query()->whereIn('id', array_column($groups, 'key'))->pluck('name', 'id')->all();

        return $this->benefitGroupReport('benefits_by_mda', 'Benefits by MDA', 'MDA', $scope, $groups,
            fn (mixed $key): string => $names[$key] ?? 'Unknown');
    }

    private function benefitsByLga(DashboardScope $scope): ReportData
    {
        $groups = $this->ledger->scopedGroup('lga', $scope->mdaIds, $scope->programmeIds);

        return $this->benefitGroupReport('benefits_by_lga', 'Benefits by LGA', 'LGA', $scope, $groups,
            fn (mixed $key): string => $this->title(is_string($key) ? $key : null));
    }

    /**
     * @param  list<array<string, mixed>>  $groups
     * @param  callable(mixed): string  $labelFor
     */
    private function benefitGroupReport(string $key, string $title, string $dimensionLabel, DashboardScope $scope, array $groups, callable $labelFor): ReportData
    {
        usort($groups, fn (array $a, array $b): int => ((int) $b['total_value']) <=> ((int) $a['total_value']));

        $rows = [];
        foreach ($groups as $group) {
            $rows[] = [
                'dimension' => $labelFor($group['key']),
                'deliveries' => (int) $group['benefit_count'],
                'value' => $this->naira((int) $group['total_value']),
            ];
        }

        return $this->data($key, $title, $scope, [
            new ReportColumn('dimension', $dimensionLabel),
            new ReportColumn('deliveries', 'Deliveries', numeric: true),
            new ReportColumn('value', 'Value (₦)', numeric: true),
        ], $rows);
    }

    private function budgetUtilization(DashboardScope $scope): ReportData
    {
        $b = $this->ledger->scopedBudget($scope->mdaIds, $scope->programmeIds);
        $rate = $b['utilization_rate'];

        $rows = [[
            'allocated' => $this->naira((int) $b['allocated']),
            'utilised' => $this->naira((int) $b['utilized_value']),
            'remaining' => $this->naira((int) $b['remaining']),
            'rate' => $rate === null ? '—' : round($rate * 100).'%',
        ]];

        return $this->data('budget_utilization', 'Budget utilisation', $scope, [
            new ReportColumn('allocated', 'Allocated (₦)', numeric: true),
            new ReportColumn('utilised', 'Utilised (₦)', numeric: true),
            new ReportColumn('remaining', 'Remaining (₦)', numeric: true),
            new ReportColumn('rate', 'Utilisation'),
        ], $rows);
    }

    private function referralCompletion(DashboardScope $scope): ReportData
    {
        /** @var array<string, mixed>|null $r */
        $r = $this->metrics->compute($scope)['referrals'];
        $r ??= ['total' => 0, 'completed' => 0, 'completion_rate' => null, 'overdue' => 0, 'avg_completion_days' => null];

        $rows = [
            ['metric' => 'Total referrals', 'value' => (int) $r['total']],
            ['metric' => 'Completed', 'value' => (int) $r['completed']],
            ['metric' => 'Completion rate', 'value' => $r['completion_rate'] === null ? '—' : round($r['completion_rate'] * 100).'%'],
            ['metric' => 'Overdue', 'value' => (int) $r['overdue']],
            ['metric' => 'Avg completion (days)', 'value' => $r['avg_completion_days'] ?? '—'],
        ];

        return $this->data('referral_completion', 'Referral completion', $scope, [
            new ReportColumn('metric', 'Metric'),
            new ReportColumn('value', 'Value', numeric: true),
        ], $rows);
    }

    private function grievanceSla(DashboardScope $scope): ReportData
    {
        /** @var array<string, mixed>|null $g */
        $g = $this->metrics->compute($scope)['grievances'];
        $g ??= ['total' => 0, 'sla_breaches' => 0, 'avg_resolution_days' => null];

        $rows = [
            ['metric' => 'Total grievances', 'value' => (int) $g['total']],
            ['metric' => 'SLA breaches', 'value' => (int) $g['sla_breaches']],
            ['metric' => 'Avg resolution (days)', 'value' => $g['avg_resolution_days'] ?? '—'],
        ];

        return $this->data('grievance_sla', 'Grievance SLA', $scope, [
            new ReportColumn('metric', 'Metric'),
            new ReportColumn('value', 'Value', numeric: true),
        ], $rows);
    }

    /**
     * @param  list<ReportColumn>  $columns
     * @param  list<array<string, scalar|null>>  $rows
     */
    private function data(string $key, string $title, DashboardScope $scope, array $columns, array $rows): ReportData
    {
        return new ReportData($key, $title, 'Standard report', $scope->label, Carbon::now(), $columns, $rows);
    }

    private function naira(int $kobo): string
    {
        return '₦'.number_format($kobo / 100, 2);
    }

    private function title(?string $value): string
    {
        if ($value === null || $value === '') {
            return 'Unspecified';
        }

        return (string) Str::of($value)->replace('_', ' ')->title();
    }
}
