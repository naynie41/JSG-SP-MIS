<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Gis;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Benefit\Services\LedgerAggregator;
use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Models\Activity;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Reporting\Support\DashboardFilter;
use App\Domain\Reporting\Support\DashboardScope;
use Illuminate\Support\Str;

/**
 * Coverage aggregates for the GIS map by admin level (PRD FR-GIS-01), reusing the same
 * scoped ledger aggregation the dashboards use. Rows are keyed by a slug that matches
 * {@see GeoBoundary::$code}, so the controller can join them to boundary shapes for the
 * choropleth (or serve them as the ranked-table fallback when no boundaries are loaded).
 * De-identified aggregate data only.
 */
class GisCoverageService
{
    public function __construct(private readonly LedgerAggregator $ledger) {}

    /**
     * Scoped coverage by admin area with the map's click-through detail. Each row carries
     * registered individuals + households, net-unique served beneficiaries, budget spent
     * (delivered value), active programmes/activities located in the area, the implementing
     * MDAs, and an ABSOLUTE traffic-light band (config thresholds; 0 → grey). Deliberately
     * omits population/poverty/vulnerability/coverage-% — no denominator is held.
     *
     * @return list<array<string, mixed>>
     */
    public function coverage(DashboardScope $scope, string $level, ?DashboardFilter $filter = null): array
    {
        $filter ??= DashboardFilter::none();
        $lf = $filter->ledgerFilters();
        $column = $level === GeoBoundary::LEVEL_WARD ? 'ward' : 'lga';

        $rows = [];
        $ensure = function (string $raw) use (&$rows): string {
            $key = $this->slug($raw);
            $rows[$key] ??= [
                'key' => $key, 'name' => $this->title($raw),
                'beneficiary_count' => 0, 'benefit_count' => 0, 'benefit_value' => 0,
                'households' => 0, 'served' => 0, 'active_programmes' => 0, 'active_activities' => 0,
                'mdas' => [], 'band' => 'grey',
            ];

            return $key;
        };

        // Registered individuals by area.
        foreach ($this->beneficiaryCounts($scope, $column, $filter) as $raw => $count) {
            $rows[$ensure((string) $raw)]['beneficiary_count'] = $count;
        }
        // Budget spent (delivered value) + delivery count by area.
        foreach ($this->ledger->scopedGroup($column, $scope->mdaIds, $scope->programmeIds, $lf) as $group) {
            $key = $ensure($group['key'] === null ? '' : (string) $group['key']);
            $rows[$key]['benefit_count'] = (int) $group['benefit_count'];
            $rows[$key]['benefit_value'] = (int) $group['total_value'];
        }
        // Registered households by area (owner-scoped; partners own none).
        foreach ($this->householdCounts($scope, $column, $filter) as $raw => $count) {
            $rows[$ensure((string) $raw)]['households'] = $count;
        }
        // Net-unique beneficiaries served by area.
        foreach ($this->ledger->scopedDistinctByArea($column, $scope->mdaIds, $scope->programmeIds, $lf) as $raw => $count) {
            $rows[$ensure((string) $raw)]['served'] = $count;
        }
        // Active programmes/activities + implementing MDAs by area.
        foreach ($this->activityBreakdown($scope, $column, $filter) as $raw => $detail) {
            $key = $ensure((string) $raw);
            $rows[$key]['active_activities'] = $detail['activities'];
            $rows[$key]['active_programmes'] = $detail['programmes'];
            $rows[$key]['mdas'] = $detail['mdas'];
        }

        // Absolute traffic-light band (NOT a population %); no coverage → grey.
        $green = (int) config('reporting.coverage_bands.green_min', 1000);
        $yellow = (int) config('reporting.coverage_bands.yellow_min', 250);
        foreach ($rows as &$row) {
            $c = $row['beneficiary_count'];
            $row['band'] = $c <= 0 ? 'grey' : ($c >= $green ? 'green' : ($c >= $yellow ? 'yellow' : 'red'));
        }
        unset($row);

        return array_values($rows);
    }

    /**
     * Registered households by admin area, scoped to the owning MDA. Partners hold no
     * households, so this is empty for them.
     *
     * @return array<string, int>
     */
    private function householdCounts(DashboardScope $scope, string $column, DashboardFilter $filter): array
    {
        if ($scope->isPartner()) {
            return [];
        }

        $query = Household::query()->withoutGlobalScope(MdaScope::class);
        if ($scope->mdaIds !== null) {
            $query->whereIn('owner_mda_id', $scope->mdaIds);
        }
        if ($filter->mdaId !== null) {
            $query->where('owner_mda_id', $filter->mdaId);
        }
        if ($filter->lga !== null) {
            $query->where('lga', $filter->lga);
        }
        if ($filter->ward !== null) {
            $query->where('ward', $filter->ward);
        }
        [$from, $to] = $filter->dateRange();
        if ($from !== null) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to !== null) {
            $query->whereDate('created_at', '<=', $to);
        }

        $out = [];
        foreach ($query->selectRaw("{$column} as k, count(*) as c")->groupBy($column)->get() as $row) {
            $out[(string) ($row->getAttribute('k') ?? '')] = (int) $row->getAttribute('c');
        }

        return $out;
    }

    /**
     * Active activities located in each area, with distinct programme count + implementing
     * MDA names. Scoped: a partner sees its funded programmes' activities, an MDA its own.
     *
     * @return array<string, array{activities: int, programmes: int, mdas: list<string>}>
     */
    private function activityBreakdown(DashboardScope $scope, string $column, DashboardFilter $filter): array
    {
        $query = Activity::query()->withoutGlobalScope(MdaScope::class)->where('status', ActivityStatus::Active->value);
        if ($scope->isPartner()) {
            $query->whereIn('programme_id', $scope->programmeIds ?? []);
        } elseif ($scope->mdaIds !== null) {
            $query->whereIn('owner_mda_id', $scope->mdaIds);
        }
        if ($filter->programmeId !== null) {
            $query->where('programme_id', $filter->programmeId);
        }
        if ($filter->mdaId !== null) {
            $query->where('owner_mda_id', $filter->mdaId);
        }
        if ($filter->lga !== null) {
            $query->where('lga', $filter->lga);
        }
        if ($filter->ward !== null) {
            $query->where('ward', $filter->ward);
        }

        $activities = $query->get(['owner_mda_id', 'programme_id', $column]);
        $names = Mda::query()->whereIn('id', $activities->pluck('owner_mda_id')->filter()->unique()->all())->pluck('name', 'id');

        $out = [];
        foreach ($activities->groupBy(fn (Activity $a) => (string) ($a->getAttribute($column) ?? '')) as $raw => $items) {
            $out[(string) $raw] = [
                'activities' => $items->count(),
                'programmes' => $items->pluck('programme_id')->unique()->count(),
                'mdas' => $items->pluck('owner_mda_id')->unique()->filter()
                    ->map(fn ($id) => $names[$id] ?? null)->filter()->values()->all(),
            ];
        }

        return $out;
    }

    /**
     * Beneficiary counts by the admin column, scoped like the dashboard registry
     * metric (owner MDA; or, for a partner, the beneficiaries served by funded programmes).
     *
     * @return array<string, int>
     */
    private function beneficiaryCounts(DashboardScope $scope, string $column, DashboardFilter $filter): array
    {
        $query = Beneficiary::query()->withoutGlobalScope(MdaScope::class);

        if ($scope->isPartner()) {
            $served = Benefit::query()->withoutGlobalScope(MdaScope::class)
                ->whereIn('programme_id', $scope->programmeIds ?? [])
                ->distinct()->pluck('beneficiary_id')->all();
            $query->whereIn('id', $served);
        } elseif ($scope->mdaIds !== null) {
            $query->whereIn('owner_mda_id', $scope->mdaIds);
        }

        if ($filter->mdaId !== null) {
            $query->where('owner_mda_id', $filter->mdaId);
        }
        if ($filter->lga !== null) {
            $query->where('lga', $filter->lga);
        }
        if ($filter->ward !== null) {
            $query->where('ward', $filter->ward);
        }
        [$from, $to] = $filter->dateRange();
        if ($from !== null) {
            $query->whereDate('registration_date', '>=', $from);
        }
        if ($to !== null) {
            $query->whereDate('registration_date', '<=', $to);
        }
        if ($filter->programmeId !== null) {
            $query->whereIn('id', Benefit::query()->withoutGlobalScope(MdaScope::class)
                ->where('programme_id', $filter->programmeId)->select('beneficiary_id'));
        }

        $out = [];
        foreach ($query->selectRaw("{$column} as k, count(*) as c")->groupBy($column)->get() as $row) {
            $out[(string) ($row->getAttribute('k') ?? '')] = (int) $row->getAttribute('c');
        }

        return $out;
    }

    private function slug(string $value): string
    {
        $slug = (string) Str::of($value)->lower()->replaceMatches('/[^a-z0-9]+/', '_')->trim('_');

        return $slug === '' ? 'unspecified' : $slug;
    }

    private function title(string $value): string
    {
        if ($value === '') {
            return 'Unspecified';
        }

        return (string) Str::of($value)->replace('_', ' ')->title();
    }
}
