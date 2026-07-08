import { useQuery } from '@tanstack/react-query'
import { dashboardApi } from './api'

/**
 * The scoped dashboard snapshot. Near-real-time: refetched on an interval and on
 * window focus so the "last updated" figure stays current without a manual reload.
 */
export function useDashboard(enabled = true) {
  return useQuery({
    queryKey: ['dashboard'],
    queryFn: () => dashboardApi.get(),
    enabled,
    refetchInterval: 60_000,
    refetchOnWindowFocus: true,
  })
}
