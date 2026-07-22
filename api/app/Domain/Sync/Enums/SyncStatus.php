<?php

declare(strict_types=1);

namespace App\Domain\Sync\Enums;

/** Lifecycle of a sync run. */
enum SyncStatus: string
{
    case Pending = 'pending';
    case Running = 'running';
    case Completed = 'completed';
    case Failed = 'failed';
}
