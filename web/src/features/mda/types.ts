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
  mda_id: string | null
}
