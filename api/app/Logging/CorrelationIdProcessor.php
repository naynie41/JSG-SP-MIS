<?php

declare(strict_types=1);

namespace App\Logging;

use App\Http\Middleware\AssignCorrelationId;
use Illuminate\Support\Facades\Auth;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

/**
 * Enriches every log record with the request correlation id (set by
 * {@see AssignCorrelationId}) and the acting user/MDA, so
 * structured logs can be traced end-to-end across nodes and correlated with the
 * audit trail (NFR-AVAIL-01, SECURITY.md §8). Never logs PII — only opaque ids.
 */
class CorrelationIdProcessor implements ProcessorInterface
{
    public function __invoke(LogRecord $record): LogRecord
    {
        $extra = $record->extra;

        if (app()->bound('request')) {
            $correlationId = request()->attributes->get('correlation_id');
            if ($correlationId !== null) {
                $extra['correlation_id'] = $correlationId;
            }
        }

        if (Auth::hasUser()) {
            $user = Auth::user();
            $extra['actor_id'] = $user?->getAuthIdentifier();
            $extra['actor_mda_id'] = $user->mda_id ?? null;
        }

        return $record->with(extra: $extra);
    }
}
