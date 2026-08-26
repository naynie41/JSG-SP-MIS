/** MDA console types. */

/**
 * Live "what needs me today" counts (`GET /mda/action-required`). Counts only — sizing
 * these queues by fetching their rows would pull beneficiary records onto the Overview
 * just to render a number.
 */
export interface MdaActionRequired {
  /** Referrals this MDA received that are still open (created / more info requested). */
  pending_referrals: number
  /** Request-to-serve on a beneficiary this MDA owns, awaiting accept/decline. */
  pending_service_requests: number
  /** Grievances this MDA is handling that are not yet resolved — each with a running SLA. */
  open_grievances: number
  /** The subset already past their SLA window. */
  breached_grievances: number
  mda_id: string | null
}
