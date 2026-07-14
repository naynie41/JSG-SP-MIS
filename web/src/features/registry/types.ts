export type Gender = 'male' | 'female' | 'other'
export type BeneficiaryStatus = 'active' | 'suspended' | 'flagged'
export type HouseholdRole = 'head' | 'spouse' | 'child' | 'dependent' | 'other'
export type ImportStatus = 'pending' | 'processing' | 'preview_ready' | 'committing' | 'completed' | 'failed'

export interface Beneficiary {
  id: string
  owner_mda_id: string
  first_name: string
  middle_name: string | null
  last_name: string
  full_name: string
  nin: string | null
  bvn: string | null
  phone: string | null
  date_of_birth: string | null
  gender: Gender | null
  address: string | null
  lga: string | null
  ward: string | null
  registration_source: string
  registration_date: string
  import_batch_id: string | null
  original_record_id: string | null
  status: BeneficiaryStatus
  current_household?: { household_id: string; role_in_household: HouseholdRole; joined_at: string } | null
  created_at: string | null
  updated_at: string | null
}

export interface BeneficiaryInput {
  first_name: string
  middle_name?: string
  last_name: string
  nin?: string
  bvn?: string
  phone?: string
  date_of_birth: string
  gender: Gender
  address?: string
  lga: string
  ward: string
}

/** Reveal-only projection returned by the cross-MDA lookup/serve path (FR-OWN-03). */
export interface RevealMatch {
  id: string
  full_name: string
  owner_mda: { id: string; name: string } | null
  registration_source: string
  registration_date: string
  lga: string | null
  ward: string | null
  status: BeneficiaryStatus
}

export type MatchBand = 'exact' | 'probable' | 'none'
export type MatchCandidateType = 'registry' | 'batch'

/**
 * The reveal payload for a match (FR-DUP-04): enough to recognise an existing
 * record, never the full profile. `id` is null for a within-batch peer (not yet
 * persisted); Phase 4 fills programmes/benefits.
 */
export interface MatchReveal {
  id: string | null
  row_number?: number
  full_name: string
  owner_mda: { id: string; name: string | null } | null
  registration_source: string
  registration_date: string | null
  lga: string | null
  ward: string | null
  status: string
  programmes: unknown[]
  benefits: { summary: string | null; items: unknown[] }
}

/** One candidate attached to a flagged import row (registry or within-batch peer). */
export interface MatchCandidate {
  type: MatchCandidateType
  band: Exclude<MatchBand, 'none'>
  score: number
  matched_fields: string[]
  reveal: MatchReveal | null
}

/** A ranked result from the standalone duplicate search (/beneficiaries/search). */
export interface SearchCandidate {
  band: Exclude<MatchBand, 'none'>
  score: number
  matched_fields: string[]
  beneficiary: MatchReveal
}

export type ImportRowResolution = 'new' | 'link' | 'skip'
export type ServiceRequestStatus = 'pending' | 'accepted' | 'declined'

/**
 * A Service Request against an existing beneficiary owned by another MDA
 * (§12, FR-OWN-06/07). The owner accepts (opening a read-access grant) or declines.
 */
export interface ServiceRequest {
  id: string
  beneficiary_id: string
  beneficiary_name?: string | null
  from_mda_id: string
  to_mda_id: string
  owner_mda?: { id: string; name: string } | null
  activity_id: string | null
  status: ServiceRequestStatus
  reason: string | null
  decided_at: string | null
  decision_reason: string | null
  created_at: string | null
}

export type MatchComparator = 'exact' | 'jaro_winkler' | 'levenshtein' | 'phonetic' | 'date_proximity'
export type ExactMatchBehaviour = 'auto_link' | 'confirm'

export interface FuzzyFieldRule {
  field: string
  comparator: MatchComparator
  weight: number
}

/** The versioned duplicate-matching configuration (FR-DUP-02/03). */
export interface MatchingConfig {
  id: string
  version: number
  is_active: boolean
  deterministic_rules: string[][]
  fuzzy_fields: FuzzyFieldRule[]
  review_threshold: number
  auto_accept_threshold: number | null
  exact_match_behaviour: ExactMatchBehaviour
  description: string | null
  created_by: string | null
  created_at: string | null
  updated_at: string | null
}

export interface MatchingConfigInput {
  deterministic_rules: string[][]
  fuzzy_fields: FuzzyFieldRule[]
  review_threshold: number
  auto_accept_threshold: number | null
  exact_match_behaviour: ExactMatchBehaviour
  description?: string | null
}

export interface HouseholdMembership {
  id: string
  household_id: string
  beneficiary_id: string
  beneficiary_name?: string
  role_in_household: HouseholdRole
  joined_at: string
  left_at: string | null
  is_open: boolean
}

export interface Household {
  id: string
  owner_mda_id: string
  head_beneficiary_id: string | null
  registration_source: string
  registration_date: string
  address: string | null
  lga: string | null
  ward: string | null
  members: HouseholdMembership[]
  history?: HouseholdMembership[]
  created_at: string | null
  updated_at: string | null
}

export interface HouseholdInput {
  lga: string
  ward: string
  address?: string
  members?: { beneficiary_id: string; role_in_household: HouseholdRole }[]
  head_beneficiary_id?: string
}

export interface ImportRow {
  row_number: number
  original_record_id: string | null
  is_valid: boolean
  errors: { field: string; message: string }[]
  beneficiary_id: string | null
  resolution: ImportRowResolution | null
  resolution_note: string | null
  resolved_beneficiary_id: string | null
  match: { band: MatchBand; candidates: MatchCandidate[] }
  preview: {
    first_name: string | null
    last_name: string | null
    nin: string | null
    bvn: string | null
    phone: string | null
    date_of_birth: string | null
    gender: string | null
    lga: string | null
    ward: string | null
  }
}

export interface ImportBatch {
  id: string
  owner_mda_id: string
  uploaded_by: string | null
  original_filename: string
  source: string
  activity_id: string | null
  /** Activity-wizard preview (§10): name of the activity created on confirm; null for a standalone batch. */
  draft_activity_name: string | null
  /** Activity-wizard target beneficiaries; null for a standalone batch. */
  draft_target_beneficiaries: number | null
  /** True when the uploaded row count differs from the target — a non-blocking warning. */
  target_mismatch: boolean
  status: ImportStatus
  summary: {
    total_rows: number
    valid_rows: number
    invalid_rows: number
    committed_rows: number
    served_rows: number
    skipped_rows: number
  }
  error: string | null
  rows?: ImportRow[]
  created_at: string | null
  updated_at: string | null
}

export interface BeneficiaryDocument {
  id: string
  beneficiary_id: string
  document_type: string
  original_filename: string
  mime_type: string
  size_bytes: number
  checksum_sha256: string
  uploaded_by: string | null
  created_at: string | null
}
