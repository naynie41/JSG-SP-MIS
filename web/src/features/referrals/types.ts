export type ReferralStatus =
  | 'created'
  | 'accepted'
  | 'rejected'
  | 'more_info_requested'
  | 'in_progress'
  | 'completed'
  | 'closed'

export type ReferralDirection = 'incoming' | 'outgoing'

export interface ReferralTimeline {
  created_at: string | null
  accepted_at: string | null
  rejected_at: string | null
  info_requested_at: string | null
  info_responded_at: string | null
  started_at: string | null
  completed_at: string | null
  closed_at: string | null
}

export interface Referral {
  id: string
  beneficiary_id: string
  from_mda_id: string
  to_mda_id: string
  need: string
  notes: string | null
  status: ReferralStatus
  outcome: string | null
  reason: string | null
  info_request: string | null
  info_response: string | null
  escalation_level: number
  sla_breached_at: string | null
  timeline: ReferralTimeline
}

/** Ledger reconciliation for a completed referral (FR-REF-03). */
export interface ReferralLedger {
  benefit_count: number
  benefit_value_total: number // kobo
  benefit_ids: string[]
}

export interface ReferralWithLedger extends Referral {
  ledger?: ReferralLedger
}

export interface ReferralInput {
  beneficiary_id: string
  to_mda_id: string
  need: string
  notes?: string
}

/** Receiving-MDA + originating-MDA lifecycle transitions (server-guarded). */
export type ReferralAction = 'accept' | 'reject' | 'request-info' | 'respond-info' | 'start' | 'complete' | 'close'
