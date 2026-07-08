import { apiRequest } from '@/lib/api/client'
import type { DashboardResponse } from './types'

export const dashboardApi = {
  /** The caller's scoped dashboard snapshot (served from the summary layer). */
  get(): Promise<DashboardResponse> {
    return apiRequest<DashboardResponse>({ method: 'GET', url: '/dashboard' })
  },
}
