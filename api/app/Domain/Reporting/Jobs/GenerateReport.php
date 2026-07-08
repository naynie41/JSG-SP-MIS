<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Jobs;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Reporting\Events\ReportReady;
use App\Domain\Reporting\Export\ReportExporterRegistry;
use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Models\ReportRun;
use App\Domain\Reporting\Reports\ReportBuilder;
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

    public function handle(ReportBuilder $builder, ReportExporterRegistry $exporters, AuditLogger $audit): void
    {
        $run = ReportRun::query()->find($this->runId);
        if ($run === null) {
            return;
        }

        $run->update(['status' => ReportRun::STATUS_PROCESSING]);

        try {
            $format = ReportFormat::from($run->format);
            $data = $builder->build($run->report_key, $run->toScope());
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

    private function requester(ReportRun $run): ?User
    {
        if ($run->requested_by === null) {
            return null;
        }

        return User::query()->withoutGlobalScope(MdaScope::class)->find($run->requested_by);
    }
}
