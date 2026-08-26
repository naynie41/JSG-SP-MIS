<?php

declare(strict_types=1);

namespace App\Domain\Registry\Support;

/**
 * A rule object that can state the shape it enforces as a readable token.
 *
 * The administration console publishes the registry rules READ-ONLY so an admin can see
 * what ingestion actually enforces (FR-REG-04/05). A rule object rendered by class name
 * — `NationalIdentifier` — satisfies that page structurally while telling the reader
 * nothing: the whole point is the SHAPE, and "11 digits" is the shape. A rule that
 * carries its own token keeps the console honest when a string rule is replaced by an
 * object, which is exactly when the description would otherwise silently degrade.
 */
interface DescribesConstraint
{
    /** e.g. `digits:11`, `nigerian_phone:11`. */
    public function constraintToken(): string;
}
