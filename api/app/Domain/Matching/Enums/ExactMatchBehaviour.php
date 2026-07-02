<?php

declare(strict_types=1);

namespace App\Domain\Matching\Enums;

/**
 * What the system should do when a deterministic (exact) key set matches
 * (PRD FR-DUP-03/05). Stored on the config; acted on by the caller downstream.
 *
 * - AutoLink — treat as the same person automatically (block the duplicate /
 *              route to serve) without a confirmation step.
 * - Confirm  — surface the exact match for a human to confirm before acting.
 */
enum ExactMatchBehaviour: string
{
    case AutoLink = 'auto_link';
    case Confirm = 'confirm';
}
