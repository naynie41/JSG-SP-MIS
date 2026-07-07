<?php

declare(strict_types=1);

namespace App\Domain\Grievance\Exceptions;

use DomainException;

/**
 * Thrown when a grievance transition/assignment is not valid from its current
 * status (PRD FR-GRM-02 guards) — e.g. starting an unassigned grievance, or
 * assigning to a user outside the handling MDA. Mapped to 422 by the controller.
 */
class InvalidGrievanceTransitionException extends DomainException {}
