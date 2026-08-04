/** System Administrator console — governance summary (mirrors GET /admin/summary).
 *
 * These are GOVERNANCE figures: who is provisioned, what is configured, what changed.
 * Infrastructure telemetry (backups, queue depth, snapshot freshness) is deliberately
 * absent — that is an ops concern, not this console's.
 */

export interface AdminKpis {
  users_total: number
  users_active: number
  users_suspended: number
  users_deactivated: number
  users_without_mfa: number
  mdas_registered: number
  mdas_active: number
  development_partners: number
  programmes_catalog: number
  activities_active: number
  beneficiaries_registered: number
  households_registered: number
}

/** One month of user adoption: new accounts + the running total. */
export interface AdoptionPoint {
  month: string // 'YYYY-MM'
  new_users: number
  total_users: number
}

/** Registry & data-quality snapshot — import throughput, validation, duplicates. */
export interface AdminRegistrySnapshot {
  imports_total: number
  imports_completed: number
  imports_failed: number
  imports_in_progress: number
  rows_total: number
  rows_valid: number
  rows_invalid: number
  validation_rate: number | null // 0..1
  duplicates_surfaced: number
  duplicates_resolved: number
  duplicates_pending: number
  /** Surfaced matches ÷ rows processed (0..1); null when nothing has been processed. */
  duplicate_rate: number | null
}

export interface AdminAlert {
  id: string
  severity: 'warning' | 'info'
  title: string
  detail: string
}

/** An audit-log envelope: actor/action/entity/time only — never before/after payloads. */
export interface AdminActivity {
  id: string
  action: string
  entity_type: string | null
  actor: string
  actor_mda: string | null
  at: string | null
}

export interface AdminSummary {
  generated_at: string
  kpis: AdminKpis
  adoption_trend: AdoptionPoint[]
  registry: AdminRegistrySnapshot
  alerts: AdminAlert[]
  recent_activity: AdminActivity[]
}

/* ---------------------------------------------------------------- login activity */

/** An authentication event, projected from the audit log (never a second login log). */
export interface LoginActivityEntry {
  id: string
  action: string
  outcome: 'success' | 'failure' | 'security'
  actor: string
  actor_email: string | null
  actor_mda: string | null
  ip_address: string | null
  at: string | null
}

export interface LoginActivity {
  window_days: number
  summary: { logins: number; failed_logins: number; lockouts: number; mfa_resets: number }
  entries: LoginActivityEntry[]
}

/* ------------------------------------------------------------------ organizations */

/** One organization (MDA) with its allocation + delivery footprint. */
export interface OrganizationRow {
  id: string
  name: string
  type: string
  status: string
  users_total: number
  mda_admins: number
  activities_total: number
  activities_active: number
}

/** A Development Partner and the delivery it funds (Phase 6P attribution). */
export interface PartnerOrganization {
  id: string
  name: string
  email: string
  status: string
  is_active: boolean
  funded_activities: number
  funded_programmes: number
  implementing_mdas: number
}

export interface AdminOrganizations {
  mdas: OrganizationRow[]
  partners: PartnerOrganization[]
  totals: { mdas: number; mdas_active: number; users_allocated: number; users_unallocated: number }
}

/* ------------------------------------------------------- registry validation rules */

/** One field's canonical validation constraints (read-only; sourced from the server). */
export interface RegistryRuleField {
  field: string
  identity: boolean
  required: boolean
  constraints: string[]
}

/** The enforced registry validation ruleset — never admin-editable (CLAUDE.md §9). */
export interface RegistryRules {
  editable: false
  policy: { identity: string; non_identity: string }
  identity_fields: string[]
  non_identity_fields: string[]
  fields: RegistryRuleField[]
}

/* ----------------------------------------------------------------- audit log */

/** One audit entry: the ENVELOPE plus changed field NAMES — never recorded values. */
export interface AuditEntry {
  id: string
  action: string
  category: string
  entity_type: string | null
  entity_id: string | null
  actor: string
  actor_id: string | null
  actor_mda: string | null
  ip_address: string | null
  correlation_id: string | null
  chain_position: number | null
  changed_fields: string[]
  at: string | null
}

export interface AuditFilters {
  category?: string
  action?: string
  actor_id?: string
  entity_type?: string
  from?: string
  to?: string
  q?: string
  page?: number
}

/* ------------------------------------------------------------------- settings */

/** One effective configuration value and the key that owns it. */
export interface SettingRow {
  label: string
  value: string
  source: string
}

export interface AdminSettings {
  general: SettingRow[]
  security: {
    policy: SettingRow[]
    mfa_roles: { key: string; name: string; requires_mfa: boolean }[]
  }
  registry: {
    identity_fields: string[]
    non_identity_fields: string[]
    locked: boolean
    privacy: SettingRow[]
    consent_purposes: { key: string; label: string; gate: string }[]
  }
  notifications: { key: string; available: boolean }[]
}

export interface BroadcastInput {
  subject: string
  body?: string
  role_key?: string
  mda_id?: string
}
