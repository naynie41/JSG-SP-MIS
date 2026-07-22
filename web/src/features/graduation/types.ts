/** Graduation management (FR-GRD-01, FR-GRD-02). */

export type CriteriaLogic = 'all' | 'any'

export type GraduationCriterionType = 'benefits_received' | 'months_enrolled' | 'total_benefit_value' | 'manual'

/** A single configured criterion as stored on the criteria set (config, not hard-coded). */
export interface GraduationRule {
  type: GraduationCriterionType | null
  label: string | null
  threshold: number | null
  automatic: boolean
}

/** An MDA-owned, admin-editable criteria set for a programme. */
export interface GraduationCriteria {
  id: string
  programme_id: string
  owner_mda_id: string
  name: string
  logic: CriteriaLogic
  is_active: boolean
  rules: GraduationRule[]
  created_at: string | null
  updated_at: string | null
}

/** One rule's live progress toward the threshold. */
export interface GraduationProgressRule {
  type: GraduationCriterionType
  label: string
  current: number
  threshold: number
  met: boolean
  progress: number
}

/** A beneficiary/household's progress toward graduation for an enrolment. */
export interface GraduationProgress {
  criteria_id: string | null
  logic: CriteriaLogic | null
  eligible: boolean
  progress: number
  rules: GraduationProgressRule[]
  already_graduated: boolean
  message?: string
}

/** The permanent record of a graduation. */
export interface GraduationEvent {
  id: string
  enrollment_id: string
  beneficiary_id: string | null
  household_id: string | null
  programme_id: string
  activity_id: string | null
  mda_id: string
  criteria_id: string | null
  reason: string | null
  decided_by: string | null
  graduated_at: string | null
}

export interface EnrollmentGraduation {
  enrollment_id: string
  status: string
  progress: GraduationProgress
  history: GraduationEvent[]
}

/** Payload for defining/updating a criteria set. */
export interface GraduationCriteriaInput {
  name: string
  logic: CriteriaLogic
  is_active?: boolean
  rules: Array<{ type: GraduationCriterionType; threshold?: number | null }>
}
