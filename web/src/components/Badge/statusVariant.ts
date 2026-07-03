import type { BadgeVariant } from './Badge'

/**
 * Domain status → badge variant map (DESIGN-SYSTEM.md §5.8). Use this everywhere
 * a status is shown so colors stay consistent; extend it as new statuses appear
 * rather than inventing per-screen colors.
 */
export const STATUS_VARIANTS: Record<string, BadgeVariant> = {
  // User / beneficiary account status
  active: 'success',
  suspended: 'warning',
  deactivated: 'neutral',
  flagged: 'danger',
  duplicate: 'danger',

  // Match types
  'match.exact': 'dark',
  'match.probable': 'warning',
  'match.ai': 'accent',
  'match.none': 'neutral',

  // Request-to-serve lifecycle (FR-DUP-05)
  'serve.pending': 'warning',
  'serve.accepted': 'success',
  'serve.declined': 'danger',

  // Import row resolution (FR-DUP-05)
  'resolution.new': 'success',
  'resolution.link': 'info',
  'resolution.skip': 'neutral',

  // Referral lifecycle
  'referral.created': 'neutral',
  'referral.accepted': 'info',
  'referral.in_progress': 'accent',
  'referral.completed': 'success',
  'referral.rejected': 'danger',

  // Import rows
  'import.valid': 'success',
  'import.error': 'danger',

  // Import batch lifecycle (FR-REG-02/06)
  'batch.pending': 'neutral',
  'batch.processing': 'accent',
  'batch.preview_ready': 'info',
  'batch.committing': 'accent',
  'batch.completed': 'success',
  'batch.failed': 'danger',

  // MDA status
  inactive: 'neutral',
}

/** Resolve a badge variant for a domain status, defaulting to neutral. */
export function statusVariant(status: string): BadgeVariant {
  return STATUS_VARIANTS[status] ?? 'neutral'
}
