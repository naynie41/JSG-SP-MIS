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

/**
 * A GLOBAL catalog programme (§10) — type-level attributes only; not owned by any
 * MDA. Budget, funding and period live on the Activity.
 */
export interface Programme {
  id: string
  name: string
  objective: string | null
  type: ProgrammeType
  benefit_category: string | null
  eligibility: EligibilityCriterion[]
  enforce_eligibility: boolean
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
  benefit_category?: string | null
  eligibility?: EligibilityCriterion[]
  enforce_eligibility?: boolean
  status?: ProgrammeStatus
}

/** An MDA-owned activity that runs a catalog programme (§10); carries budget + funding. */
export interface Activity {
  id: string
  programme_id: string
  owner_mda_id: string
  involves_beneficiaries: boolean
  name: string
  description: string | null
  target_beneficiaries: number | null
  lga: string | null
  ward: string | null
  location_description: string | null
  schedule: Record<string, unknown> | null
  starts_on: string | null
  ends_on: string | null
  budget_amount: number | null
  funding_source: string | null
  status: ActivityStatus
  created_by: string | null
  created_at: string | null
  updated_at: string | null
}

/** A beneficiary/intervention recorded under an activity (identifiers masked). */
export interface ActivityBeneficiary {
  enrollment_id: string
  beneficiary_id: string
  full_name: string | null
  nin: string | null
  bvn: string | null
  lga: string | null
  ward: string | null
  beneficiary_status: string | null
  enrollment_status: EnrollmentStatus
  enrolled_on: string
}

/** The import/validation summary aggregated across an activity's bound batch(es). */
export interface ActivityImportSummary {
  batches: number
  total_rows: number
  valid_rows: number
  invalid_rows: number
  rejected_rows: number
  dropped_field_rows: number
  committed_rows: number
  served_rows: number
  skipped_rows: number
}

/** The full "View Activity" payload (GET /activities/{id}). */
export interface ActivityDetail extends Activity {
  programme: Programme | null
  counts: { target: number | null; actual: number; pending_service_requests: number }
  beneficiaries: ActivityBeneficiary[]
  import_summary: ActivityImportSummary | null
  service_requests: import('@/features/registry/types').ServiceRequest[]
}

export interface ActivityInput {
  programme_id?: string
  involves_beneficiaries?: boolean
  name: string
  description?: string | null
  target_beneficiaries?: number | null
  lga?: string | null
  ward?: string | null
  location_description?: string | null
  starts_on?: string | null
  ends_on?: string | null
  budget_amount?: number | null
  funding_source?: string | null
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
