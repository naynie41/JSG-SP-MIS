export type ProgrammeType = 'household' | 'individual'
export type ProgrammeStatus = 'draft' | 'active' | 'closed' | 'archived'
export type ActivityStatus = 'draft' | 'active' | 'completed' | 'archived'
export type EnrollmentStatus = 'enrolled' | 'suspended' | 'exited' | 'graduated'

/** A structured, configurable eligibility criterion (FR-PRG-01 / FR-OWN-04). */
export interface EligibilityCriterion {
  attribute?: string
  value?: string
  label?: string
}

export interface Programme {
  id: string
  owner_mda_id: string
  owner_mda?: { id: string; name: string }
  name: string
  objective: string | null
  type: ProgrammeType
  eligibility: EligibilityCriterion[]
  enforce_eligibility: boolean
  funding_source: string | null
  budget_amount: number | null // minor units (kobo)
  starts_on: string | null
  ends_on: string | null
  status: ProgrammeStatus
  activities_count?: number
  created_by: string | null
  created_at: string | null
  updated_at: string | null
}

export interface ProgrammeInput {
  name: string
  objective?: string | null
  type: ProgrammeType
  eligibility?: EligibilityCriterion[]
  enforce_eligibility?: boolean
  funding_source?: string | null
  budget_amount?: number | null
  starts_on?: string | null
  ends_on?: string | null
  status?: ProgrammeStatus
}

export interface Activity {
  id: string
  programme_id: string
  owner_mda_id: string
  name: string
  description: string | null
  target_count: number | null
  lga: string | null
  ward: string | null
  location_description: string | null
  schedule: Record<string, unknown> | null
  starts_on: string | null
  ends_on: string | null
  budget_amount: number | null
  status: ActivityStatus
  created_by: string | null
  created_at: string | null
  updated_at: string | null
}

export interface ActivityInput {
  programme_id?: string
  name: string
  description?: string | null
  target_count?: number | null
  lga?: string | null
  ward?: string | null
  location_description?: string | null
  starts_on?: string | null
  ends_on?: string | null
  budget_amount?: number | null
  status?: ActivityStatus
}

/** Allocated vs utilised (FR-PRG-04). */
export interface Budget {
  allocated: number | null
  utilized_value: number
  utilized_quantity: string
  benefit_count: number
  remaining: number | null
  utilization_rate: number | null
}

export interface Enrollment {
  id: string
  programme_id: string
  activity_id: string | null
  mda_id: string
  beneficiary_id: string | null
  household_id: string | null
  status: EnrollmentStatus
  enrolled_on: string
  exited_on: string | null
  exit_reason: string | null
  eligibility_flagged: boolean
  eligibility_notes: string[] | null
  enrolled_by: string | null
  created_at: string | null
}

export interface BulkEnrollResult {
  programme_id: string
  subject_type: 'beneficiary' | 'household'
  requested: number
  enrolled: number
  skipped: number
  rejected: number
  results: {
    target_id: string
    status: 'enrolled' | 'skipped' | 'rejected'
    reason: string | null
    enrollment_id: string | null
    eligibility_flagged: boolean
  }[]
}
