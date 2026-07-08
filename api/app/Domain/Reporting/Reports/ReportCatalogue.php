<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Reports;

use App\Domain\Reporting\Support\DashboardScope;

/**
 * The catalogue of standard reports (PRD FR-RPT-03), each generated from the
 * aggregation layer and scope-aware. Coordination reports (referrals/grievances)
 * don't apply to a partner's funded-programme scope and are hidden for it.
 */
final class ReportCatalogue
{
    /**
     * @var list<array{key: string, label: string, coordination: bool}>
     */
    public const REPORTS = [
        ['key' => 'beneficiaries_by_lga', 'label' => 'Beneficiaries by LGA', 'coordination' => false],
        ['key' => 'benefits_by_programme', 'label' => 'Benefits by programme', 'coordination' => false],
        ['key' => 'benefits_by_mda', 'label' => 'Benefits by MDA', 'coordination' => false],
        ['key' => 'benefits_by_lga', 'label' => 'Benefits by LGA', 'coordination' => false],
        ['key' => 'budget_utilization', 'label' => 'Budget utilisation', 'coordination' => false],
        ['key' => 'referral_completion', 'label' => 'Referral completion', 'coordination' => true],
        ['key' => 'grievance_sla', 'label' => 'Grievance SLA', 'coordination' => true],
    ];

    public static function has(string $key): bool
    {
        return self::find($key) !== null;
    }

    public static function label(string $key): ?string
    {
        return self::find($key)['label'] ?? null;
    }

    public static function isCoordination(string $key): bool
    {
        return self::find($key)['coordination'] ?? false;
    }

    /** Whether a report is available to a given scope (partners get no coordination reports). */
    public static function availableTo(string $key, DashboardScope $scope): bool
    {
        return self::has($key) && ! (self::isCoordination($key) && ! $scope->includesCoordinationMetrics());
    }

    /**
     * The reports available to a scope.
     *
     * @return list<array{key: string, label: string, coordination: bool}>
     */
    public static function availableFor(DashboardScope $scope): array
    {
        return array_values(array_filter(
            self::REPORTS,
            fn (array $report): bool => ! ($report['coordination'] && ! $scope->includesCoordinationMetrics()),
        ));
    }

    /**
     * @return array{key: string, label: string, coordination: bool}|null
     */
    private static function find(string $key): ?array
    {
        foreach (self::REPORTS as $report) {
            if ($report['key'] === $key) {
                return $report;
            }
        }

        return null;
    }
}
