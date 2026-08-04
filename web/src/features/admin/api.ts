import { apiRequest, apiRequestList } from '@/lib/api/client'
import type { Paginated } from '@/lib/api/client'
import { downloadFile } from '@/lib/api/exportList'
import type {
  AdminOrganizations,
  AdminSettings,
  AdminSummary,
  AuditEntry,
  AuditFilters,
  BroadcastInput,
  LoginActivity,
  RegistryRules,
} from './types'

/** Strip empty filter values so the request only carries active filters. */
function auditParams(filters: AuditFilters): Record<string, string | number> {
  const out: Record<string, string | number> = {}
  for (const [key, value] of Object.entries(filters)) {
    if (value !== undefined && value !== null && value !== '') out[key] = value as string | number
  }
  return out
}

export const adminApi = {
  /** The governance summary for the console. Server-gated to System Administrators. */
  summary(): Promise<AdminSummary> {
    return apiRequest<AdminSummary>({ method: 'GET', url: '/admin/summary' })
  },
  /** Authentication activity, projected from the audit log. */
  loginActivity(): Promise<LoginActivity> {
    return apiRequest<LoginActivity>({ method: 'GET', url: '/admin/login-activity' })
  },
  /** Per-organization allocation + delivery roll-up (read-only). */
  organizations(): Promise<AdminOrganizations> {
    return apiRequest<AdminOrganizations>({ method: 'GET', url: '/admin/organizations' })
  },
  /** The enforced registry validation ruleset (read-only, canonical). */
  registryRules(): Promise<RegistryRules> {
    return apiRequest<RegistryRules>({ method: 'GET', url: '/admin/registry-rules' })
  },
  /** Filtered page of the immutable audit log (envelope + changed field names). */
  auditLogs(filters: AuditFilters = {}): Promise<Paginated<AuditEntry>> {
    return apiRequestList<AuditEntry>({ method: 'GET', url: '/admin/audit-logs', params: auditParams(filters) })
  },
  /** Export the CURRENT filtered audit view through the Phase 6 exporters. */
  exportAuditLogs(format: 'csv' | 'xlsx' | 'pdf', filters: AuditFilters = {}): Promise<void> {
    return downloadFile('/admin/audit-logs/export', { ...auditParams(filters), format }, `audit-log.${format}`)
  },
  /** The EFFECTIVE configuration in force — read-only; the console has no settings store. */
  settings(): Promise<AdminSettings> {
    return apiRequest<AdminSettings>({ method: 'GET', url: '/admin/settings' })
  },
  /** How many active users an audience filter reaches, before sending. */
  broadcastAudience(params: { role_key?: string; mda_id?: string }): Promise<{ recipient_count: number }> {
    return apiRequest<{ recipient_count: number }>({
      method: 'GET',
      url: '/notifications/broadcast/audience',
      params: auditParams(params as AuditFilters),
    })
  },
  /** Send a system-wide announcement through the Phase 5 notifier. */
  broadcast(input: BroadcastInput): Promise<{ recipient_count: number; message: string }> {
    return apiRequest<{ recipient_count: number; message: string }>({
      method: 'POST',
      url: '/notifications/broadcast',
      data: input,
    })
  },
}
