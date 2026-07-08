<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Reports\AdHoc;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Programme;
use App\Domain\Referral\Scopes\ReferralScope;
use App\Domain\Reporting\Exceptions\InvalidReportDefinitionException;
use App\Domain\Reporting\Export\ReportColumn;
use App\Domain\Reporting\Export\ReportData;
use App\Domain\Reporting\Support\DashboardScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Builds an ad-hoc report's {@see ReportData} from a whitelisted {@see AdHocDefinition}
 * for a resolved {@see DashboardScope} (PRD FR-RPT-03). Every dataset/dimension/
 * measure/filter is validated against {@see AdHocDatasetRegistry}, so a definition can
 * never reference a raw/PII column or an unlisted filter. The scope is applied at the
 * QUERY level BEFORE the user's filters, so a filter can only narrow within scope —
 * it can never widen it. Output is always aggregate (group-by counts/sums).
 */
class AdHocReportBuilder
{
    /** Assert a definition is well-formed and within the caller's scope. */
    public function validate(AdHocDefinition $definition, DashboardScope $scope): void
    {
        $config = AdHocDatasetRegistry::get($definition->dataset);
        if ($config === null) {
            throw new InvalidReportDefinitionException("Unknown dataset: {$definition->dataset}.");
        }
        if (! AdHocDatasetRegistry::availableTo($definition->dataset, $scope)) {
            throw new InvalidReportDefinitionException('This dataset is not available for your scope.');
        }

        foreach ($definition->groupBy as $key) {
            if (! isset($config['dimensions'][$key])) {
                throw new InvalidReportDefinitionException("Unknown column: {$key}.");
            }
        }
        if ($definition->measures === []) {
            throw new InvalidReportDefinitionException('Select at least one measure.');
        }
        foreach ($definition->measures as $key) {
            if (! isset($config['measures'][$key])) {
                throw new InvalidReportDefinitionException("Unknown measure: {$key}.");
            }
        }
        foreach (array_keys($definition->filters) as $key) {
            if (! isset($config['filters'][$key])) {
                throw new InvalidReportDefinitionException("Unknown filter: {$key}.");
            }
        }
    }

    public function build(AdHocDefinition $definition, DashboardScope $scope): ReportData
    {
        $this->validate($definition, $scope);

        /** @var array<string, mixed> $config */
        $config = AdHocDatasetRegistry::get($definition->dataset);

        $query = $this->baseQuery($definition->dataset, $config, $scope);
        $this->applyFilters($config, $query, $definition->filters);

        /** @var array<string, array<string, mixed>> $dimensions */
        $dimensions = $config['dimensions'];
        /** @var array<string, array<string, mixed>> $measures */
        $measures = $config['measures'];

        $selects = [];
        $groupColumns = [];
        foreach ($definition->groupBy as $i => $dimKey) {
            $column = (string) $dimensions[$dimKey]['column'];
            $selects[] = "{$column} as d{$i}";
            $groupColumns[] = $column;
        }
        foreach ($definition->measures as $j => $measureKey) {
            $selects[] = ((string) $measures[$measureKey]['sql'])." as m{$j}";
        }

        $query->selectRaw(implode(', ', $selects));
        if ($groupColumns !== []) {
            $query->groupBy($groupColumns);
        }
        $records = $query->get();

        $names = $this->nameMaps($definition, $dimensions, $records);

        $columns = [];
        foreach ($definition->groupBy as $i => $dimKey) {
            $columns[] = new ReportColumn("d{$i}", (string) $dimensions[$dimKey]['label']);
        }
        foreach ($definition->measures as $j => $measureKey) {
            $columns[] = new ReportColumn("m{$j}", (string) $measures[$measureKey]['label'], numeric: true);
        }

        $rows = [];
        foreach ($records as $record) {
            $row = [];
            foreach ($definition->groupBy as $i => $dimKey) {
                $row["d{$i}"] = $this->renderDimension((string) $dimensions[$dimKey]['render'], $record->getAttribute("d{$i}"), $names);
            }
            foreach ($definition->measures as $j => $measureKey) {
                $row["m{$j}"] = $this->renderMeasure((string) $measures[$measureKey]['render'], $record->getAttribute("m{$j}"));
            }
            $rows[] = $row;
        }

        return new ReportData(
            'adhoc',
            $definition->label(),
            'Ad-hoc report — '.((string) $config['label']),
            $scope->label,
            Carbon::now(),
            $columns,
            $rows,
        );
    }

    /**
     * @param  array<string, mixed>  $config
     * @return Builder<Model>
     */
    private function baseQuery(string $dataset, array $config, DashboardScope $scope): Builder
    {
        /** @var class-string<Model> $model */
        $model = $config['model'];
        $query = $dataset === 'referrals'
            ? $model::query()->withoutGlobalScope(ReferralScope::class)
            : $model::query()->withoutGlobalScope(MdaScope::class);

        if (! empty($config['exclude_reversed'])) {
            $query->where('status', '!=', 'reversed');
        }

        $this->applyScope($dataset, $query, $scope);

        return $query;
    }

    /**
     * @param  Builder<Model>  $query
     */
    private function applyScope(string $dataset, Builder $query, DashboardScope $scope): void
    {
        switch ($dataset) {
            case 'benefits':
                if ($scope->programmeIds !== null) {
                    $query->whereIn('programme_id', $scope->programmeIds);
                } elseif ($scope->mdaIds !== null) {
                    $query->whereIn('mda_id', $scope->mdaIds);
                }
                break;

            case 'beneficiaries':
                if ($scope->isPartner()) {
                    $served = Benefit::query()->withoutGlobalScope(MdaScope::class)
                        ->whereIn('programme_id', $scope->programmeIds ?? [])
                        ->distinct()->pluck('beneficiary_id')->all();
                    $query->whereIn('id', $served);
                } elseif ($scope->mdaIds !== null) {
                    $query->whereIn('owner_mda_id', $scope->mdaIds);
                }
                break;

            case 'referrals':
                if ($scope->mdaIds !== null) {
                    $ids = $scope->mdaIds;
                    $query->where(fn (Builder $q) => $q->whereIn('from_mda_id', $ids)->orWhereIn('to_mda_id', $ids));
                }
                break;

            case 'grievances':
                if ($scope->mdaIds !== null) {
                    $query->whereIn('handling_mda_id', $scope->mdaIds);
                }
                break;
        }
    }

    /**
     * Apply the user's narrowing filters — only ever narrower than the scope above.
     *
     * @param  array<string, mixed>  $config
     * @param  Builder<Model>  $query
     * @param  array<string, string>  $filters
     */
    private function applyFilters(array $config, Builder $query, array $filters): void
    {
        /** @var array<string, array<string, mixed>> $filterConfig */
        $filterConfig = $config['filters'];

        foreach ($filters as $key => $value) {
            $cfg = $filterConfig[$key] ?? null;
            if ($cfg === null || $value === '') {
                continue;
            }

            $column = (string) ($cfg['column'] ?? '');
            match ($cfg['kind']) {
                'equals' => $query->where($column, $value),
                'date_from' => $query->whereDate($column, '>=', $value),
                'date_to' => $query->whereDate($column, '<=', $value),
                'mda_two_party' => $query->where(fn (Builder $q) => $q->where('from_mda_id', $value)->orWhere('to_mda_id', $value)),
                default => null,
            };
        }
    }

    /**
     * Resolve id→name maps for programme/MDA dimensions in one query each.
     *
     * @param  array<string, array<string, mixed>>  $dimensions
     * @param  Collection<int, Model>  $records
     * @return array{programme: array<string, string>, mda: array<string, string>}
     */
    private function nameMaps(AdHocDefinition $definition, array $dimensions, $records): array
    {
        $programmeIds = [];
        $mdaIds = [];
        foreach ($definition->groupBy as $i => $dimKey) {
            $render = (string) $dimensions[$dimKey]['render'];
            if ($render !== 'programme' && $render !== 'mda') {
                continue;
            }
            foreach ($records as $record) {
                $value = $record->getAttribute("d{$i}");
                if ($value === null) {
                    continue;
                }
                if ($render === 'programme') {
                    $programmeIds[] = (string) $value;
                } else {
                    $mdaIds[] = (string) $value;
                }
            }
        }

        return [
            'programme' => $programmeIds === [] ? [] : Programme::query()->withoutGlobalScope(MdaScope::class)
                ->whereIn('id', array_unique($programmeIds))->pluck('name', 'id')->all(),
            'mda' => $mdaIds === [] ? [] : Mda::query()
                ->whereIn('id', array_unique($mdaIds))->pluck('name', 'id')->all(),
        ];
    }

    /**
     * @param  array{programme: array<string, string>, mda: array<string, string>}  $names
     */
    private function renderDimension(string $render, mixed $raw, array $names): string
    {
        if ($raw === null || $raw === '') {
            return $render === 'programme' || $render === 'mda' ? 'Unknown' : 'Unspecified';
        }

        return match ($render) {
            'programme' => $names['programme'][(string) $raw] ?? 'Unknown',
            'mda' => $names['mda'][(string) $raw] ?? 'Unknown',
            default => (string) Str::of((string) $raw)->replace('_', ' ')->title(),
        };
    }

    private function renderMeasure(string $render, mixed $raw): string
    {
        return match ($render) {
            'naira' => '₦'.number_format(((int) $raw) / 100, 2),
            'int' => (string) (int) $raw,
            default => (string) $raw,
        };
    }
}
