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

  // Service Request lifecycle (§12, FR-OWN-06 · DESIGN-SYSTEM §5.8/§5.9)
  'service_request.pending': 'warning',
  'service_request.accepted': 'success',
  'service_request.declined': 'danger',

  // Import row resolution (FR-DUP-05)
  'resolution.new': 'success',
  'resolution.link': 'info',
  'resolution.skip': 'neutral',

  // Programme / activity lifecycle (FR-PRG-01/02)
  'programme.draft': 'neutral',
  'programme.active': 'success',
  'programme.closed': 'warning',
  'programme.archived': 'neutral',

  // Programme type
  'type.individual': 'info',
  'type.household': 'accent',

  // Enrollment lifecycle (FR-PRG-03)
  'enrollment.enrolled': 'success',
  'enrollment.suspended': 'warning',
  'enrollment.exited': 'neutral',
  'enrollment.graduated': 'info',

  // Benefit ledger status (FR-BEN-02)
  'benefit.recorded': 'info',
  'benefit.verified': 'success',
  'benefit.reversed': 'neutral',

  // Double-dipping flag (FR-BEN-05)
  'flag.open': 'warning',
  'flag.confirmed': 'danger',
  'flag.dismissed': 'neutral',

  // Referral lifecycle
  'referral.created': 'neutral',
  'referral.accepted': 'info',
  'referral.more_info_requested': 'warning',
  'referral.in_progress': 'accent',
  'referral.completed': 'success',
  'referral.closed': 'success',
  'referral.rejected': 'danger',

  // Grievance lifecycle (FR-GRM-02)
  'grievance.open': 'neutral',
  'grievance.assigned': 'info',
  'grievance.in_progress': 'accent',
  'grievance.resolved': 'success',
  'grievance.closed': 'neutral',

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
