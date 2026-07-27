import { apiRequest } from '@/lib/api/client'
import { downloadFile } from '@/lib/api/exportList'
import type { DashboardFilterValue, DashboardResponse, OpsMetricsResponse } from './types'

/** Export formats for the executive dashboard (aggregate, de-identified). */
export type DashboardExportFormat = 'csv' | 'xlsx' | 'pdf'

/** Strip null/empty values so an empty filter sends no params (fast snapshot path). */
export function filterParams(filter?: DashboardFilterValue): Record<string, string | number> {
  const out: Record<string, string | number> = {}
  if (!filter) return out
  for (const [key, value] of Object.entries(filter)) {
    if (value !== null && value !== undefined && value !== '') out[key] = value as string | number
  }
  return out
}

export const dashboardApi = {
  /** The caller's scoped dashboard — snapshot when unfiltered, live when a filter is set. */
  get(filter?: DashboardFilterValue): Promise<DashboardResponse> {
    return apiRequest<DashboardResponse>({ method: 'GET', url: '/dashboard', params: filterParams(filter) })
  },
  /** Operational health for the admin dashboard (backup age, snapshot freshness). */
  opsMetrics(): Promise<OpsMetricsResponse> {
    return apiRequest<OpsMetricsResponse>({ method: 'GET', url: '/health/metrics' })
  },
  /** Download the CURRENT (scoped + filtered) dashboard as an aggregate CSV/Excel/PDF. */
  export(format: DashboardExportFormat, filter?: DashboardFilterValue): Promise<void> {
    return downloadFile('/dashboard/export', { ...filterParams(filter), format }, `executive-dashboard.${format}`)
  },
}
