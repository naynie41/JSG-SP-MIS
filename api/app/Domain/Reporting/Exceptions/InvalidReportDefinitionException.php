<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Exceptions;

use RuntimeException;

/**
 * Thrown when an ad-hoc report definition is invalid or would exceed the caller's
 * scope (PRD FR-RPT-03). Controllers translate it to a 422 — a definition may never
 * be built if it references a dataset/column/filter outside the whitelist or a scope
 * the caller isn't entitled to.
 */
class InvalidReportDefinitionException extends RuntimeException {}
