<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Jobs;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Registry\Export\BeneficiaryListExport;
use App\Domain\Reporting\DuplicateReview\DuplicateReviewFilter;
use App\Domain\Reporting\DuplicateReview\DuplicateReviewReport;
use App\Domain\Reporting\Events\ReportReady;
use App\Domain\Reporting\Export\MonthlyProjectReportBuilder;
use App\Domain\Reporting\Export\RegisterProfileExportBuilder;
use App\Domain\Reporting\Export\ReportExporterRegistry;
use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Models\ReportRun;
use App\Domain\Reporting\Reports\AdHoc\AdHocReportBuilder;
use App\Domain\Reporting\Reports\ReportBuilder;
use App\Domain\Reporting\Segments\SegmentAccess;
use App\Domain\Reporting\Segments\SegmentDefinition;
use App\Domain\Reporting\Segments\SegmentDimensionRegistry;
use App\Domain\Reporting\Segments\SegmentReportService;
use App\Domain\Reporting\Services\MonthlyProjectService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Generates a report on the queue (PRD FR-RPT-03): build the tabular data from the
 * aggregation layer for the captured scope, render it to the requested format, store
 * the file, and notify the requester. Heavy work never runs in the request cycle.
 * The scope is re-applied from the run, so a queued job (no auth session) still
 * honours the requester's data scope.
 */
class GenerateReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly string $runId) {}

    public function handle(ReportBuilder $builder, AdHocReportBuilder $adHoc, ReportExporterRegistry $exporters, AuditLogger $audit, BeneficiaryListExport $beneficiaryExport, SegmentReportService $segments, SegmentDimensionRegistry $dimensions, DuplicateReviewReport $duplicateReview, RegisterProfileExportBuilder $registerProfile, MonthlyProjectReportBuilder $monthlyProject): void
    {
        $run = ReportRun::query()->find($this->runId);
        if ($run === null) {
            return;
        }

        $run->update(['status' => ReportRun::STATUS_PROCESSING]);

        try {
            $format = ReportFormat::from($run->format);
            $scope = $run->toScope();
            $definition = $run->adHocDefinition();
            $data = match (true) {
                $definition !== null => $adHoc->build($definition, $scope),
                $run->report_key === 'beneficiary_list' => $beneficiaryExport->fromRun($scope, $run->params),
                // The segment builder renders from the CAPTURED definition + entitlement,
                // never a re-resolution: the file is the population the requester was
                // entitled to when they asked, whatever their roles are by the time the
                // queue reaches it.
                $run->report_key === 'segment' => $segments->toReportData(
                    SegmentDefinition::fromArray((array) ($run->definition ?? []), $dimensions),
                    SegmentAccess::fromParams((array) ($run->params ?? []), $scope),
                    withSummary: (bool) (((array) ($run->params ?? []))['summary'] ?? false),
                ),
                $run->report_key === ReportRun::KEY_DUPLICATE_REVIEW => $duplicateReview->toReportData(
                    $scope,
                    DuplicateReviewFilter::fromArray((array) (((array) ($run->params ?? []))['filters'] ?? [])),
                ),
                // No definition to rebuild — this report always means the whole of the
                // captured scope. The entitlement is still restored from the run, so a
                // requester whose access has since widened does not get a wider file.
                $run->report_key === ReportRun::KEY_REGISTER_PROFILE => $registerProfile->build(
                    SegmentAccess::fromParams((array) ($run->params ?? []), $scope),
                ),
                // The month comes from the run when a person asked for one. A SCHEDULED
                // run carries none — it is created by the monthly sweep with no period
                // — so it resolves the last complete month as of today. Resolving it
                // here rather than at schedule time is what makes one schedule produce
                // a different month each time it fires.
                $run->report_key === ReportRun::KEY_MONTHLY_PROJECT => $monthlyProject->build(
                    $scope,
                    ...$this->reportMonth((array) ($run->params ?? [])),
                ),
                default => $builder->build($run->report_key, $scope),
            };
            $bytes = $exporters->for($format)->render($data);

            $path = "reports/{$run->id}.{$format->extension()}";
            $fileName = Str::slug($run->report_label).'-'.Carbon::now()->format('Ymd-His').'.'.$format->extension();
            Storage::disk('local')->put($path, $bytes);

            $run->update([
                'status' => ReportRun::STATUS_READY,
                'file_path' => $path,
                'file_name' => $fileName,
                'row_count' => $data->rowCount(),
                'completed_at' => Carbon::now(),
            ]);

            $audit->record('report.generated', $run, after: [
                'report_key' => $run->report_key,
                'format' => $run->format,
                'scope_kind' => $run->scope_kind,
                'row_count' => $data->rowCount(),
            ], actor: $this->requester($run));

            ReportReady::dispatch($run);
        } catch (Throwable $e) {
            $run->update(['status' => ReportRun::STATUS_FAILED, 'error' => mb_substr($e->getMessage(), 0, 1000)]);

            throw $e;
        }
    }

    /**
     * The [year, month] a monthly run covers: what was asked for, or the last complete
     * month when nothing was.
     *
     * @param  array<string, mixed>  $params
     * @return array{int, int}
     */
    private function reportMonth(array $params): array
    {
        $year = isset($params['year']) ? (int) $params['year'] : null;
        $month = isset($params['month']) ? (int) $params['month'] : null;

        if ($year === null || $month === null || $month < 1 || $month > 12) {
            return MonthlyProjectService::lastCompleteMonth();
        }

        return [$year, $month];
    }

    private function requester(ReportRun $run): ?User
    {
        if ($run->requested_by === null) {
            return null;
        }

        return User::query()->withoutGlobalScope(MdaScope::class)->find($run->requested_by);
    }
}
