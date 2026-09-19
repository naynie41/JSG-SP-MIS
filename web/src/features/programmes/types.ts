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

/** Whether a programme has been cleared for use (§10, revised). */
export type ProgrammeApproval = 'pending' | 'approved' | 'rejected'

/**
 * A catalog programme (§10) — type-level attributes only; budget, funding and
 * period live on the Activity.
 *
 * Two kinds. A CENTRAL entry has no `owner_mda` and every MDA reads it. One an MDA
 * created for itself names its owner, is invisible to every other MDA, and carries
 * no activities until a System Administrator approves it.
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
  /** Ownership (§10, revised). Null owner = the central catalog every MDA reads. */
  owner_mda_id?: string | null
  owner_mda?: { id: string | null; name: string | null }
  is_central?: boolean
  /** The decision, separate from `status`, which is the delivery lifecycle. */
  approval_status: ProgrammeApproval
  approval_label?: string
  submitted_at?: string | null
  approved_at?: string | null
  /** Why it was sent back — what the owning MDA has to address. */
  decision_note?: string | null
  /** Archive provenance (§10). Archiving is the "delete" for a catalog entry: it is
   *  hidden from selectable lists and blocks new activities, but never destroyed. */
  is_archived?: boolean
  archived_at?: string | null
  archive_reason?: string | null
  activities_count?: number
  /** Catalog USAGE: distinct MDAs running an activity for this programme (§10). */
  mdas_count?: number
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

/**
 * One LGA of an activity's declared coverage, with the wards chosen inside it.
 * `whole_lga` means the activity declared the entire LGA (stored as a null-ward row).
 */
export interface ActivityLocationGroup {
  lga_id: string
  lga_code: string | null
  lga_name: string | null
  whole_lga: boolean
  wards: Array<{ ward_id: string; ward_code: string | null; ward_name: string | null }>
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
  /** The declared location set, grouped LGA → wards. Descriptive only. */
  locations: ActivityLocationGroup[]
  location_description: string | null
  schedule: Record<string, unknown> | null
  starts_on: string | null
  ends_on: string | null
  budget_amount: number | null
  /** Free text from before the funding type existed. */
  funding_source: string | null
  funding_type?: FundingType | null
  funding_partner_id?: string | null
  /** The linked partner's name only. */
  funding_partner?: FundingPartnerOption | null
  co_funded_by_government?: boolean
  status: ActivityStatus
  created_by: string | null
  created_at: string | null
  updated_at: string | null
}

export type FundingType = 'government' | 'partner' | 'individual'

/** A social protection partner an activity can be linked to (an active partner account). */
export interface FundingPartnerOption {
  id: string
  name: string
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
  /**
   * The declared location set. Sending it REPLACES the whole set; omitting it leaves
   * the existing one untouched, so a partial edit cannot wipe an activity's coverage.
   */
  locations?: Array<{ lga_id: string; ward_ids?: string[]; whole_lga?: boolean }>
  location_description?: string | null
  starts_on?: string | null
  ends_on?: string | null
  budget_amount?: number | null
  funding_source?: string | null
  /** Sent together: a partner only with type `partner`, co-funding only with a partner. */
  funding_type?: FundingType | null
  funding_partner_id?: string | null
  co_funded_by_government?: boolean
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
