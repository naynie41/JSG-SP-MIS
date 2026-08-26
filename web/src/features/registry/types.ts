export type Gender = 'male' | 'female' | 'other'
export type BeneficiaryStatus = 'active' | 'suspended' | 'flagged'
export type HouseholdRole = 'head' | 'spouse' | 'child' | 'dependent' | 'other'
export type ImportStatus = 'mapping_required' | 'pending' | 'processing' | 'preview_ready' | 'committing' | 'completed' | 'failed'

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
  /**
   * What this person has already received (BeneficiaryRevealPresenter).
   *
   * `summary` is an OBJECT, not a string — it was typed as `string | null` and every
   * fixture used null, so nothing caught it until a real reveal rendered the object as a
   * React child and blanked the adjudication page.
   *
   * `total_value` is null when the viewing MDA may not see monetary value, which is not
   * the same as zero — a non-owner sees that deliveries happened without seeing what
   * they were worth.
   */
  benefits: {
    summary: {
      count: number
      total_value: number | null
      last_delivery_date: string | null
      types: string[]
    } | null
    items: unknown[]
  }
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
  /**
   * Whether this matched record already belongs to the MDA doing the import. It
   * decides which resolution is even offered — "already in your registry" versus a
   * cross-MDA request-to-serve — so the server states it rather than leaving the
   * client to compare MDA ids. Absent on batches previewed before this shipped.
   */
  owned_by_you?: boolean
  reveal: MatchReveal | null
}

/** A ranked result from the standalone duplicate search (/beneficiaries/search). */
export interface SearchCandidate {
  band: Exclude<MatchBand, 'none'>
  score: number
  matched_fields: string[]
  beneficiary: MatchReveal
}

/* ------------------------------------------------- Data Import & Mapping (§11) */

export type MappingConfidence = 'high' | 'low' | 'none'

/** An advisory proposal for one canonical field — never applied on its own. */
export interface MappingSuggestion {
  header: string | null
  confidence: MappingConfidence
  reason: string
}

/** What the current mapping would do to a real value, shown before anything commits. */
export interface NormalizedPreviewRow {
  field: string
  header: string
  original: string
  normalized: string | null
}

/**
 * The mapping screen's payload (CLAUDE.md §11).
 *
 * `column_map` records ANSWERS: a canonical field pointing at a header, or explicitly at
 * null meaning "this source does not carry it". A field absent from the map is
 * unanswered — which is why `unconfirmed_identity_fields` is computed server-side rather
 * than inferred from null values here.
 */
export interface ImportMappingProposal {
  detected_headers: string[]
  suggestions: Record<string, MappingSuggestion>
  column_map: Record<string, string | null>
  samples: Record<string, string[]>
  normalized_preview: NormalizedPreviewRow[]
  template: { id: string; name: string } | null
  /**
   * Where a pre-filled mapping came from, so the reviewer is not asked to confirm
   * choices that appeared from nowhere. `template` is a deliberately saved artefact;
   * `previous_import` is "we recognised this layout from the last file you mapped".
   * Null when this file shape has not been seen before.
   */
  prefilled_from:
    | { type: 'template'; name: string }
    | { type: 'previous_import'; name: string; confirmed_by: string | null; confirmed_at: string | null }
    | null
  /** The batch's provenance (PRD §6.1) — what this upload claims its data came from. */
  source: string
  /**
   * True for a SOCU-mined batch: each row must carry its SOCU record id, mapped onto
   * `original_record_id`. Source is not ownership — the record is still owned by the
   * first MDA to import it (FR-OWN-01).
   */
  requires_source_record_id: boolean
  identity_fields: string[]
  unconfirmed_identity_fields: string[]
  unknown_headers: string[]
  mapping_confirmed_at: string | null
}

export type ImportRowResolution = 'new' | 'link' | 'own' | 'skip'
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
  /**
   * The catalog programme this batch registers people under — the bound activity's when
   * there is one, else the batch's own. A programme-only import is a complete intake.
   */
  programme_id: string | null
  /** Activity-wizard preview (§10): name of the activity created on confirm; null for a standalone batch. */
  draft_activity_name: string | null
  /** Activity-wizard target beneficiaries; null for a standalone batch. */
  draft_target_beneficiaries: number | null
  /** True when the uploaded row count differs from the target — a non-blocking warning. */
  target_mismatch: boolean
  status: ImportStatus
  /**
   * The mapping this batch was read with (CLAUDE.md §11) — part of its permanent
   * history. `confirmed_by` and `template` are only present when the relations were
   * loaded (the batch detail, not the list).
   */
  mapping?: {
    confirmed_at: string | null
    confirmed_by?: string | null
    column_map: Record<string, string | null> | null
    source_signature: string | null
    template?: { id: string; name: string } | null
  }
  summary: {
    total_rows: number
    valid_rows: number
    invalid_rows: number
    committed_rows: number
    served_rows: number
    /**
     * Rows matching a beneficiary this MDA already owns. Absent on batches committed
     * before the own-match outcome shipped, where those rows were counted as skipped.
     */
    own_rows?: number
    skipped_rows: number
  }
  error: string | null
  /** Seconds this batch has been waiting on the queue; null when it is not waiting. */
  processing_for_seconds: number | null
  /**
   * Waiting on the queue longer than parsing could plausibly take — nearly always a
   * queue worker that is not consuming. Server-computed; there is no error to show
   * because nothing failed, the job was just never picked up.
   */
  processing_stalled: boolean
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

/* --------------------------------------------- the duplicate queue (FR-DUP-01/05) */

/**
 * A flagged row as the QUEUE serves it: the row itself, plus just enough of its batch
 * to decide it and to say which file it came from.
 *
 * The queue spans files, so context the batch page gets for free — which import am I
 * looking at — has to travel with the row.
 */
export interface DuplicateQueueRow extends ImportRow {
  batch: {
    id: string
    original_filename: string | null
    status: string | null
    matching_thresholds: { review: number; auto_accept: number | null } | null
  }
}

/** Outstanding vs total flagged rows per band, across everything in scope. */
export interface DuplicateQueueCounts {
  exact: { awaiting: number; total: number }
  probable: { awaiting: number; total: number }
}

export interface DuplicateQueuePage {
  items: DuplicateQueueRow[]
  counts: DuplicateQueueCounts
  pagination: { page: number; per_page: number; total: number; total_pages: number }
}

export interface DuplicateQueueQuery {
  band?: 'exact' | 'probable'
  /** Omit for both — the server defaults to `awaiting`, the working queue. */
  state?: 'awaiting' | 'decided' | 'all'
  page?: number
  per_page?: number
}