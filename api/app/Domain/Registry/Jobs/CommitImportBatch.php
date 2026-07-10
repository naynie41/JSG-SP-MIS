<?php

declare(strict_types=1);

namespace App\Domain\Registry\Jobs;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Services\ImportCommitter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

/**
 * Async commit of a confirmed preview for the standalone Import Center (PRD FR-REG-02).
 * A thin wrapper over {@see ImportCommitter} — the same engine the activity-creation
 * wizard's atomic confirm uses — so there is no duplicated commit logic. The payload
 * carries only IDs (batch id + confirming user id).
 */
class CommitImportBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly string $batchId,
        public readonly ?string $actorId = null,
    ) {}

    public function handle(ImportCommitter $committer): void
    {
        $batch = ImportBatch::query()->withoutGlobalScope(MdaScope::class)->find($this->batchId);
        if ($batch === null) {
            return;
        }

        // Attribute the audit trail to the confirming user (the queue worker has no
        // authenticated user of its own).
        $actor = $this->actorId !== null ? User::query()->find($this->actorId) : null;
        if ($actor !== null) {
            Auth::setUser($actor);
        }

        $committer->commit($batch, $actor);
    }

    public function failed(Throwable $e): void
    {
        ImportBatch::query()->withoutGlobalScope(MdaScope::class)->where('id', $this->batchId)->update([
            'status' => ImportStatus::Failed->value,
            'error' => Str::limit($e->getMessage(), 500),
        ]);
    }
}
