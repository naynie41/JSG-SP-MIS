<?php

declare(strict_types=1);

namespace App\Domain\Registry\Export;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Export\ReportColumn;
use App\Domain\Reporting\Export\ReportData;
use App\Domain\Reporting\Support\DashboardScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Turns the beneficiary registry list into a {@see ReportData} for the shared Phase 6
 * exporters (PRD FR-REG-04 + FR-RPT-03). One place owns the filter logic and the
 * column spec so the export is byte-for-byte the same view the list endpoint returns —
 * same scope, same filters/search, same masking. NIN/BVN are marked sensitive (masked
 * by {@see ReportData::cell()}) UNLESS the caller may reveal them.
 *
 * Two entry points share everything below:
 *  - the controller streams a small export in-request (rows already MDA-scoped by the
 *    global {@see MdaScope});
 *  - {@see self::fromRun()} rebuilds the same query on the queue from a captured
 *    {@see DashboardScope}, so a large export renders exactly what the requester saw.
 */
class BeneficiaryListExport
{
    /** Rows above this count are generated on the queue instead of streamed. */
    private const DEFAULT_SYNC_MAX = 2000;

    /** Hard ceiling to keep any single export bounded. */
    private const MAX_ROWS = 100_000;

    public const REVEAL_PERMISSION = 'beneficiary-reveal.view';

    public function syncMax(): int
    {
        return (int) config('registry.export_sync_max', self::DEFAULT_SYNC_MAX);
    }

    /**
     * The list filters, read with the SAME param names the list endpoint uses
     * (`search`, `filter[...]`), plus the source/batch axes.
     *
     * @return array{search: string, lga: ?string, ward: ?string, status: ?string, source: ?string, batch: ?string}
     */
    public function filtersFromRequest(Request $request): array
    {
        return [
            'search' => trim((string) $request->input('search', '')),
            'lga' => $this->stringOrNull($request->input('filter.lga')),
            'ward' => $this->stringOrNull($request->input('filter.ward')),
            'status' => $this->stringOrNull($request->input('filter.status')),
            'source' => $this->stringOrNull($request->input('filter.source')),
            'batch' => $this->stringOrNull($request->input('filter.batch')),
        ];
    }

    /**
     * Apply the list filters to a query. This is the SINGLE filter implementation
     * the list endpoint and the export both call, so they can never diverge.
     *
     * @param  Builder<Beneficiary>  $query
     * @param  array<string, mixed>  $filters
     * @return Builder<Beneficiary>
     */
    public function applyFilters(Builder $query, array $filters): Builder
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $lga = $this->stringOrNull($filters['lga'] ?? null);
        $ward = $this->stringOrNull($filters['ward'] ?? null);
        $status = $this->stringOrNull($filters['status'] ?? null);
        $source = $this->stringOrNull($filters['source'] ?? null);
        $batch = $this->stringOrNull($filters['batch'] ?? null);

        return $query
            ->when($search !== '', function (Builder $q) use ($search): void {
                $digits = Beneficiary::normalizeDigits($search);
                $q->where(function (Builder $inner) use ($search, $digits): void {
                    $inner->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                    if ($digits !== null) {
                        $inner->orWhere('nin', $digits)->orWhere('bvn', $digits);
                    }
                });
            })
            ->when($lga !== null, fn (Builder $q) => $q->where('lga', $lga))
            ->when($ward !== null, fn (Builder $q) => $q->where('ward', $ward))
            ->when($status !== null, fn (Builder $q) => $q->where('status', $status))
            ->when($source !== null, fn (Builder $q) => $q->where('registration_source', $source))
            ->when($batch !== null, fn (Builder $q) => $q->where('import_batch_id', $batch));
    }

    /**
     * The same order the list uses (newest registration first), so the exported
     * file matches the on-screen sequence.
     *
     * @param  Builder<Beneficiary>  $query
     * @return Builder<Beneficiary>
     */
    public function ordered(Builder $query): Builder
    {
        return $query->latest('registration_date')->latest('id');
    }

    /**
     * The export column spec, mirroring the visible list. NIN/BVN are sensitive
     * (masked to last-4 by the exporter) unless the caller may reveal them.
     *
     * @return list<ReportColumn>
     */
    public function columns(bool $reveal): array
    {
        return [
            new ReportColumn('full_name', 'Full name'),
            new ReportColumn('nin', 'NIN', sensitive: ! $reveal),
            new ReportColumn('bvn', 'BVN', sensitive: ! $reveal),
            new ReportColumn('phone', 'Phone'),
            new ReportColumn('date_of_birth', 'Date of birth'),
            new ReportColumn('gender', 'Gender'),
            new ReportColumn('address', 'Address'),
            new ReportColumn('lga', 'LGA'),
            new ReportColumn('ward', 'Ward'),
            new ReportColumn('registration_source', 'Registration source'),
            new ReportColumn('registration_date', 'Registration date'),
            new ReportColumn('status', 'Status'),
        ];
    }

    /**
     * Build the {@see ReportData} from an already scoped + filtered query.
     *
     * @param  Builder<Beneficiary>  $query
     */
    public function toReportData(Builder $query, bool $reveal, string $scopeLabel): ReportData
    {
        $rows = [];
        foreach ($this->ordered($query)->limit(self::MAX_ROWS)->cursor() as $beneficiary) {
            $rows[] = $this->row($beneficiary);
        }

        return new ReportData(
            'beneficiary_list',
            'Beneficiary registry export',
            'Registry list',
            $scopeLabel,
            Carbon::now(),
            $this->columns($reveal),
            $rows,
        );
    }

    /**
     * Rebuild the export on the queue from a captured scope + params (see
     * ReportService::queueBeneficiaryExport). Applies the SAME scope and filters
     * the request resolved, so a deferred large export honours exactly the
     * requester's entitlement — out-of-scope rows never appear.
     *
     * @param  array<string, mixed>|null  $params
     */
    public function fromRun(DashboardScope $scope, ?array $params): ReportData
    {
        $params ??= [];
        /** @var array<string, mixed> $filters */
        $filters = is_array($params['filters'] ?? null) ? $params['filters'] : [];
        $reveal = (bool) ($params['reveal'] ?? false);

        $query = $this->applyFilters($this->scopedQuery($scope), $filters);

        return $this->toReportData($query, $reveal, $scope->label);
    }

    /**
     * The beneficiary query constrained to a resolved scope, WITHOUT the request
     * MdaScope (the queue has no auth session). Mirrors MdaScope exactly: state-wide
     * sees all; an MDA scope is limited to its owner MDAs; anything else sees nothing.
     *
     * @return Builder<Beneficiary>
     */
    public function scopedQuery(DashboardScope $scope): Builder
    {
        $query = Beneficiary::query()->withoutGlobalScope(MdaScope::class);

        return match ($scope->kind) {
            DashboardScope::KIND_STATE_WIDE => $query,
            DashboardScope::KIND_MDA => $query->whereIn('owner_mda_id', $scope->mdaIds ?? []),
            default => $query->whereRaw('1 = 0'),
        };
    }

    /**
     * One beneficiary → a row keyed by column key. Raw NIN/BVN are passed through;
     * masking happens at render via the sensitive column flag (never here), so a
     * reveal-less export can never leak an identifier.
     *
     * @return array<string, scalar|null>
     */
    private function row(Beneficiary $beneficiary): array
    {
        return [
            'full_name' => $beneficiary->fullName(),
            'nin' => $beneficiary->nin,
            'bvn' => $beneficiary->bvn,
            'phone' => $beneficiary->phone,
            'date_of_birth' => $beneficiary->date_of_birth?->toDateString(),
            'gender' => $beneficiary->gender?->value,
            'address' => $beneficiary->address,
            'lga' => $beneficiary->lga,
            'ward' => $beneficiary->ward,
            'registration_source' => $beneficiary->registration_source->value,
            'registration_date' => $beneficiary->registration_date->toDateString(),
            'status' => $beneficiary->status->value,
        ];
    }

    private function stringOrNull(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }
        $value = trim($value);

        return $value === '' ? null : $value;
    }
}
