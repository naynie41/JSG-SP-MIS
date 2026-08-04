<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Audit;

use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Audit\Services\AuditQueryService;
use App\Domain\Reporting\Export\ReportColumn;
use App\Domain\Reporting\Export\ReportData;
use App\Domain\Reporting\Export\ReportExporterRegistry;
use App\Domain\Reporting\Export\ReportFormat;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * READ + EXPORT over the immutable audit log for the administration console
 * (FR-AUD-01, FR-RPT-03). Both endpoints are SELECT-only — writing remains with the
 * Auditable trait and {@see AuditLogger}, so no second logging path exists.
 *
 * Gated to the System Administrator role; export additionally requires the aggregate
 * `reporting.export` permission and is itself audited (`audit_log.exported`), through
 * the same audit path everything else uses.
 *
 * The payload carries the audit ENVELOPE plus changed FIELD NAMES only — recorded
 * before/after values never leave the server (SECURITY.md §6).
 */
class AuditLogController extends Controller
{
    public function __construct(
        private readonly AuditQueryService $audit,
        private readonly ReportExporterRegistry $exporters,
        private readonly AuditLogger $logger,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $this->filters($request);
        $perPage = min(max($request->integer('per_page', 25), 1), 100);

        $page = $this->audit->paginate($filters, $perPage);

        return ApiResponse::paginated(
            $this->audit->present($page->items()),
            $page,
            [
                'filters' => $filters,
                'categories' => array_merge(array_keys(AuditQueryService::CATEGORIES), [AuditQueryService::DEFAULT_CATEGORY]),
                'actions' => $this->audit->knownActions(),
            ],
        );
    }

    /** Export the CURRENT filtered view through the Phase 6 exporters. */
    public function export(Request $request): StreamedResponse
    {
        $format = ReportFormat::tryFrom((string) $request->query('format', 'csv')) ?? ReportFormat::Csv;
        $filters = $this->filters($request);

        $rows = $this->audit->present($this->audit->forExport($filters));
        $data = new ReportData(
            reportKey: 'audit_log',
            title: 'Audit & security log',
            subtitle: 'Immutable audit trail — envelope and changed fields only',
            scopeLabel: 'Platform-wide',
            generatedAt: Carbon::now(),
            columns: [
                new ReportColumn('at', 'When'),
                new ReportColumn('action', 'Action'),
                new ReportColumn('category', 'Category'),
                new ReportColumn('actor', 'Actor'),
                new ReportColumn('actor_mda', 'Actor MDA'),
                new ReportColumn('entity_type', 'Entity'),
                new ReportColumn('ip_address', 'IP address'),
                new ReportColumn('changed_fields', 'Changed fields'),
                new ReportColumn('chain_position', 'Chain #', numeric: true),
            ],
            rows: array_map(static fn (array $row): array => [
                'at' => $row['at'],
                'action' => $row['action'],
                'category' => $row['category'],
                'actor' => $row['actor'],
                'actor_mda' => $row['actor_mda'],
                'entity_type' => $row['entity_type'],
                'ip_address' => $row['ip_address'],
                // Names only — the export can never carry a recorded value.
                'changed_fields' => implode(', ', $row['changed_fields']),
                'chain_position' => $row['chain_position'],
            ], $rows),
        );

        $bytes = $this->exporters->for($format)->render($data);

        $this->logger->record('audit_log.exported', after: [
            'format' => $format->value,
            'rows' => $data->rowCount(),
            'filters' => $filters,
        ]);

        $filename = 'audit-log-'.now()->format('Ymd-His').'.'.$format->extension();

        return response()->streamDownload(static function () use ($bytes): void {
            echo $bytes;
        }, $filename, ['Content-Type' => $format->mimeType()]);
    }

    /**
     * @return array<string, mixed>
     */
    private function filters(Request $request): array
    {
        $validated = $request->validate([
            'category' => ['nullable', 'string', 'max:40'],
            'action' => ['nullable', 'string', 'max:120'],
            'actor_id' => ['nullable', 'uuid'],
            'entity_type' => ['nullable', 'string', 'max:120'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'q' => ['nullable', 'string', 'max:120'],
        ]);

        return array_filter($validated, static fn ($value): bool => $value !== null && $value !== '');
    }
}
