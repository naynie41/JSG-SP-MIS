import { keepPreviousData, useQuery } from '@tanstack/react-query'
import { dashboardApi } from './api'
import type { DashboardFilterValue } from './types'

/**
 * The scoped dashboard. Unfiltered it's the fast precomputed snapshot; with an active
 * filter the backend recomputes live. The filter is part of the query key, so each
 * filter combination caches independently. Refetched on an interval + on focus so the
 * "last updated" figure stays current.
 */
export function useDashboard(filter?: DashboardFilterValue, enabled = true) {
  return useQuery({
    queryKey: ['dashboard', filter ?? null],
    queryFn: () => dashboardApi.get(filter),
    enabled,
    // Keep the current view on screen while a filter change recomputes, so the page
    // doesn't flash a full-screen spinner on every filter tweak.
    placeholderData: keepPreviousData,
    refetchInterval: 60_000,
    refetchOnWindowFocus: true,
  })
}

/** Operational health (backup age, snapshot freshness) — admin dashboard only. */
export function useOpsMetrics(enabled = true) {
  return useQuery({
    queryKey: ['ops-metrics'],
    queryFn: () => dashboardApi.opsMetrics(),
    enabled,
    refetchInterval: 120_000,
  })
}
