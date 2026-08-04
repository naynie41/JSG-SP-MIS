/** Phase 7 synchronization (mirrors /sync/*). */

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
