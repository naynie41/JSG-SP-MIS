export type Gender = 'male' | 'female' | 'other'
export type BeneficiaryStatus = 'active' | 'suspended' | 'flagged'
export type HouseholdRole = 'head' | 'spouse' | 'child' | 'dependent' | 'other'
export type ImportStatus = 'pending' | 'processing' | 'preview_ready' | 'committing' | 'completed' | 'failed'

/** Mirrors the server's `ConsentStatus` — no status is ever inferred from absence. */
export type ConsentStatus = 'unknown' | 'granted' | 'withdrawn'

/** A consent decision the owner MDA records against a registered purpose. */
export interface ConsentInput {
  status: Extract<ConsentStatus, 'granted' | 'withdrawn'>
  /** A key from `privacy.consent.purposes`; omitted means cross-MDA sharing. */
  purpose?: string
  /** The lawful basis / how consent was obtained — recorded in the audit trail. */
  basis?: string
  note?: string
}

/**
 * A cross-MDA READ grant opened when the owner MDA accepted a Service Request
 * (FR-OWN-07). Revoked grants are returned too — who *could* read this record is part
 * of the owner's accountability, not just who can today.
 */
export interface ServiceGrant {
  id: string
  /** The MDA the access was given to. Null only if that MDA was deleted. */
  mda: { id: string; name: string } | null
  service_request_id: string
  granted_at: string | null
  active: boolean
  revoked_at: string | null
  /** Name of whoever withdrew it. */
  revoked_by: string | null
  revocation_reason: string | null
}

export interface Beneficiary {
  id: string
  owner_mda_id: string
  /** Owning agency by name (eager-loaded on show/list). */
  owner_mda?: { id: string; name: string } | null
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
  /**
   * Cross-MDA data-sharing consent (NFR-PRV-01, FR-DSH-01). Nothing is ever assumed:
   * `unknown` means no decision has been recorded, and is NOT consent.
   */
  sharing_consent: ConsentStatus
  sharing_consent_at: string | null
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

/**
 * A per-field verdict for the adjudication screen (FR-DUP-09).
 *
 * The existing record's values are deliberately absent: MatchReveal withholds
 * NIN/BVN/phone/DOB because the record belongs to another MDA (FR-DUP-04). The
 * server compares and returns only the outcome, so the officer can see WHICH
 * fields agreed without any value crossing the boundary.
 */
export type ComparisonVerdict =
  | 'exact'
  | 'near'
  | 'differs'
  | 'absent_incoming'
  | 'absent_existing'
  | 'absent_both'

export interface FieldComparison {
  field: string
  verdict: ComparisonVerdict
  similarity: number | null
  weight: number | null
  participated: boolean
  /** Matched a deterministic key set — definitive, not a fuzzy score. */
  deterministic: boolean
}

/** One candidate attached to a flagged import row (registry or within-batch peer). */
export interface MatchCandidate {
  type: MatchCandidateType
  band: Exclude<MatchBand, 'none'>
  score: number
  matched_fields: string[]
  /** Empty on batches screened before per-field verdicts shipped. */
  comparison?: FieldComparison[]
  stage?: 'deterministic' | 'fuzzy' | null
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
  /** The requesting agency, by name — the owner decides against this, not a UUID. */
  from_mda?: { id: string; name: string } | null
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
  // Both are `whenLoaded` on HouseholdResource: present only when the endpoint
  // eager-loaded the relation. Reading `.length` off either unguarded crashes the
  // render, so they are optional here and every call site must cope.
  members?: HouseholdMembership[]
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
  /** When the decision was taken; null while the row is still awaiting one. */
  resolved_at: string | null
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
  /** Thresholds the engine used, for the band shown in place of a raw score. */
  matching_thresholds?: { review: number; auto_accept: number | null } | null
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
