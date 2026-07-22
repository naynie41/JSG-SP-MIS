<?php

declare(strict_types=1);

namespace App\Domain\Sync\Enums;

/** What kicked off a sync run (FR-DSH-02, FR-REG-08). */
enum SyncTrigger: string
{
    case Scheduled = 'scheduled';
    case Manual = 'manual';
    case OfflineBatch = 'offline_batch';
}
