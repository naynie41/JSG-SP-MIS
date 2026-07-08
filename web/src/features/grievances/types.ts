export type GrievanceStatus = 'open' | 'assigned' | 'in_progress' | 'resolved' | 'closed'
export type GrievanceCategory = 'payment' | 'eligibility' | 'data_correction' | 'service_quality' | 'complaint' | 'other'
export type GrievanceChannel = 'walk_in' | 'phone' | 'email' | 'sms' | 'online' | 'other'

export interface GrievanceTimeline {
  created_at: string | null
  assigned_at: string | null
  started_at: string | null
  resolved_at: string | null
  closed_at: string | null
}

export interface Grievance {
  id: string
  handling_mda_id: string
  beneficiary_id: string | null
  category: GrievanceCategory
  channel: GrievanceChannel
  description: string
  status: GrievanceStatus
  assignee_user_id: string | null
  resolution_notes: string | null
  submitted_by: string | null
  escalation_level: number
  sla_breached_at: string | null
  timeline: GrievanceTimeline
}

export interface GrievanceInput {
  category: GrievanceCategory
  channel: GrievanceChannel
  beneficiary_id?: string
  description: string
}

/** Guarded lifecycle transitions (server-enforced). */
export type GrievanceAction = 'start' | 'resolve' | 'close'
