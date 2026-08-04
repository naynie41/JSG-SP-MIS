/** Phase 6 reporting engine (mirrors /reports/*, /report-definitions, /report-schedules). */

export type ReportFormat = 'csv' | 'xlsx' | 'pdf'

export interface DatasetOption {
  key: string
  label: string
}

/**
 * A whitelisted ad-hoc dataset. `admin` marks the administrative/governance datasets
 * (users, organizations, catalogue, duplicates, audit, imports) — the server only ever
 * returns those to a System Administrator, so the flag is a label, not the gate.
 */
export interface AdHocDataset {
  key: string
  label: string
  admin: boolean
  dimensions: DatasetOption[]
  measures: DatasetOption[]
  filters: string[]
}

export interface AdHocPreview {
  title: string
  scope: { kind: string; label: string }
  columns: { label: string; numeric: boolean }[]
  rows: string[][]
  row_count: number
  truncated: boolean
}

export interface AdHocDefinitionInput {
  dataset: string
  group_by: string[]
  measures: string[]
  filters?: Record<string, string>
  /** Display label for the resulting run; falls back to a generic "Ad-hoc report". */
  name?: string
}

export interface ReportRun {
  id: string
  report_key: string | null
  report_label: string | null
  format: string
  status: string
  scope: { kind: string; label: string }
  row_count: number | null
  file_name: string | null
  error: string | null
  download_ready: boolean
  created_at: string | null
  completed_at: string | null
}

export interface ReportSchedule {
  id: string
  name: string
  report_key: string | null
  report_definition_id: string | null
  format: string
  frequency: string
  delivery: string
  status: string
  scope: { kind: string; label: string }
  recipient_user_ids: string[] | null
  last_run_on: string | null
  created_at: string | null
  updated_at: string | null
}

export interface ReportDefinition {
  id: string
  name: string
  dataset: string
  group_by: string[]
  measures: string[]
  filters: Record<string, string> | null
  created_at: string | null
}

export interface CatalogueReport {
  key: string
  label: string
  coordination?: boolean
}
