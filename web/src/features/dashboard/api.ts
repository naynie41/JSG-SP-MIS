import { apiRequest } from '@/lib/api/client'
import type { DashboardResponse, OpsMetricsResponse } from './types'

export const dashboardApi = {
  /** The caller's scoped dashboard snapshot (served from the summary layer). */
  get(): Promise<DashboardResponse> {
    return apiRequest<DashboardResponse>({ method: 'GET', url: '/dashboard' })
  },
  /** Operational health for the admin dashboard (backup age, snapshot freshness). */
  opsMetrics(): Promise<OpsMetricsResponse> {
    return apiRequest<OpsMetricsResponse>({ method: 'GET', url: '/health/metrics' })
  },
}
