<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Reports\AdHoc;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Activity;
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

            case 'activities':
                // The creating MDA owns the activity (§10 — the programme is a shared
                // catalogue with no owner, so the activity carries the MDA).
                if ($scope->programmeIds !== null) {
                    $query->whereIn('programme_id', $scope->programmeIds);
                } elseif ($scope->mdaIds !== null) {
                    $query->whereIn('owner_mda_id', $scope->mdaIds);
                }
                break;

            case 'duplicates':
                // An administrative dataset that an MDA may report on for its OWN rows
                // (AdHocDatasetRegistry::isMdaScopable). ImportRow carries no MDA column
                // of its own, so the constraint goes through the owning batch. A
                // governance scope has mdaIds === null and stays platform-wide, exactly
                // as before this exception existed.
                if ($scope->mdaIds !== null) {
                    $ids = $scope->mdaIds;
                    $query->whereHas('batch', function (Builder $q) use ($ids): void {
                        $q->withoutGlobalScope(MdaScope::class)->whereIn('owner_mda_id', $ids);
                    });
                }
                break;

            default:
                // The remaining administrative datasets (users, organizations, programme
                // catalogue, audit, imports) are platform-wide by definition: they are
                // reachable only from a governance scope, which is always state-wide, so
                // there is no narrower MDA/programme constraint to apply. Entitlement is
                // enforced in validate() via AdHocDatasetRegistry::availableTo().
                //
                // Any dataset flagged `mda_scopable` MUST have its own case above — this
                // branch would silently serve it platform-wide.
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
                'declared_area' => $this->applyDeclaredArea($query, (string) ($cfg['area'] ?? ''), (string) $value),
                default => null,
            };
        }
    }

    /**
     * Narrow to the activities that DECLARED an area.
     *
     * An activity covers a SET of areas (`activity_locations`) — the single
     * `activities.lga`/`.ward` pair was dropped — so this is a whereHas, never a column
     * comparison. A ward also matches an activity that declared the WHOLE LGA the ward
     * sits in: declaring a whole LGA is a claim to cover every ward in it.
     *
     * Only Activity carries a location set, so any other dataset is REFUSED rather than
     * silently left unfiltered: a filter that quietly does nothing produces a report that
     * looks narrowed and is not.
     *
     * @param  Builder<Model>  $query
     */
    private function applyDeclaredArea(Builder $query, string $area, string $value): void
    {
        if (! $query->getModel() instanceof Activity) {
            throw new InvalidReportDefinitionException("The {$area} filter applies only to activities.");
        }

        /** @var Builder<Activity> $query */
        $query->declaredIn($area === 'lga' ? $value : null, $area === 'ward' ? $value : null);
    }

    /**
     * Resolve id→name maps for programme/MDA/role dimensions in one query each.
     *
     * @param  array<string, array<string, mixed>>  $dimensions
     * @param  Collection<int, Model>  $records
     * @return array{programme: array<string, string>, mda: array<string, string>, role: array<string, string>}
     */
    private function nameMaps(AdHocDefinition $definition, array $dimensions, $records): array
    {
        $ids = ['programme' => [], 'mda' => [], 'role' => []];

        foreach ($definition->groupBy as $i => $dimKey) {
            $render = (string) $dimensions[$dimKey]['render'];
            if (! isset($ids[$render])) {
                continue;
            }
            foreach ($records as $record) {
                $value = $record->getAttribute("d{$i}");
                if ($value !== null) {
                    $ids[$render][] = (string) $value;
                }
            }
        }

        return [
            // withArchived: label lookup for rows that already exist — an archived
            // programme must still resolve to its name in historical output.
            'programme' => $ids['programme'] === [] ? [] : Programme::query()->withArchived()->withoutGlobalScope(MdaScope::class)
                ->whereIn('id', array_unique($ids['programme']))->pluck('name', 'id')->all(),
            'mda' => $ids['mda'] === [] ? [] : Mda::query()->withoutGlobalScopes()
                ->whereIn('id', array_unique($ids['mda']))->pluck('name', 'id')->all(),
            'role' => $ids['role'] === [] ? [] : Role::query()
                ->whereIn('id', array_unique($ids['role']))->pluck('name', 'id')->all(),
        ];
    }

    /**
     * @param  array{programme: array<string, string>, mda: array<string, string>, role: array<string, string>}  $names
     */
    private function renderDimension(string $render, mixed $raw, array $names): string
    {
        if ($render === 'bool') {
            // A false is a real answer here, not a blank. Postgres hands back 'f'/'t'
            // through a raw select, and 'f' is truthy in PHP — so test explicitly.
            return in_array($raw, [false, 0, '0', 'f', 'false', null], true) ? 'No' : 'Yes';
        }

        if ($raw === null || $raw === '') {
            return in_array($render, ['programme', 'mda', 'role'], true) ? 'Unknown' : 'Unspecified';
        }

        return match ($render) {
            'programme' => $names['programme'][(string) $raw] ?? 'Unknown',
            'mda' => $names['mda'][(string) $raw] ?? 'Unknown',
            'role' => $names['role'][(string) $raw] ?? 'Unknown',
            // `App\Domain\Registry\Models\Beneficiary` → `Beneficiary`
            'class' => (string) Str::of((string) $raw)->afterLast('\\')->headline(),
            // `matching_config.created` → `Matching config · created`
            'action' => (string) Str::of((string) $raw)->replace('_', ' ')->replace('.', ' · ')->ucfirst(),
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
