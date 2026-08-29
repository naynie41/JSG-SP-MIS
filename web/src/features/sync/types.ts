/** Phase 7 synchronization (mirrors /sync/*). */

/**
 * The state of a connector's STANDING column-mapping approval (CLAUDE.md §11).
 *
 * `stale` is deliberately separate from `never_configured`: the remedies differ — a
 * first mapping versus a review of one that used to be right — and only `stale` means a
 * working feed has stopped.
 */
export type ConnectorMappingStatus = 'never_configured' | 'stale' | 'confirmed'

export interface ConnectorMappingState {
  status: ConnectorMappingStatus
  confirmed_at: string | null
  confirmed_by?: string | null
  stale_at: string | null
  stale_reason: string | null
  /** False while the mapping is unconfirmed or stale — the server refuses to enable. */
  can_enable: boolean
}

export interface SyncConnector {
  id: string
  name: string
  source: string
  owner_mda_id: string | null
  owner_mda?: { id: string; name: string } | null
  conflict_policy: string
  enabled: boolean
  schedule: string | null
  last_run_at: string | null
  mapping: ConnectorMappingState
  /**
   * The activity synced rows bind to (activity-first). The second standing decision a
   * connector cannot run without, alongside the mapping — `blocker` is non-null when it
   * is the one holding the feed, and carries the same sentence the held run records.
   */
  activity: ConnectorActivityBinding
}

export interface ConnectorActivityBinding {
  id: string | null
  name?: string | null
  /** Why this connector cannot sync yet, or null when it can. */
  blocker: string | null
}

/** Advisory proposal for one canonical field — never applied on its own. */
export interface ConnectorMappingSuggestion {
  header: string | null
  confidence: 'high' | 'low' | 'none'
  reason: string
}

/**
 * The connector mapping screen. Mirrors the file-import proposal deliberately: this is
 * the SAME Data Import & Mapping layer applied at configuration time, not a second
 * mapping engine.
 */
export interface ConnectorMappingProposal {
  detected_fields: string[]
  suggestions: Record<string, ConnectorMappingSuggestion>
  column_map: Record<string, string | null>
  samples: Record<string, string[]>
  normalized_preview: { field: string; header: string; original: string; normalized: string | null }[]
  identity_fields: string[]
  unconfirmed_identity_fields: string[]
  source_signature: string | null
  confirmed_signature: string | null
  /** The source's fields have moved since the mapping was approved. */
  signature_changed: boolean
  mapping_confirmed_at: string | null
}

export interface SyncRunSummary {
  fetched: number
  created: number
  updated: number
  skipped: number
  flagged: number
  rejected: number
  errors: number
}

export interface SyncRun {
  id: string
  connector_id: string | null
  trigger: string
  source: string | null
  owner_mda_id: string | null
  conflict_policy: string
  status: string
  summary: SyncRunSummary
  error: string | null
  started_at: string | null
  finished_at: string | null
  created_at: string | null
}
