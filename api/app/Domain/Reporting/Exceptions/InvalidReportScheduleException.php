<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Exceptions;

use RuntimeException;

/**
 * Thrown when a report schedule is invalid — an unavailable report for the owner's
 * scope, or a recipient whose scope does not cover the report (PRD FR-RPT-04).
 * Controllers translate it to a 422; a schedule may never deliver out-of-scope data.
 */
class InvalidReportScheduleException extends RuntimeException {}
