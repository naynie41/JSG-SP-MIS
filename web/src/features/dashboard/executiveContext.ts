import { createContext, useContext } from 'react'
import type { DashboardFilterValue, DashboardResponse, DrillFn } from './types'

/**
 * Shared state for the Executive briefing suite. `ExecutiveLayout` fetches the dashboard
 * once and owns the cross-cutting filter; the five routed pages read it here, so they
 * share one data fetch + one filter and drill-down is route navigation.
 */
export interface ExecutiveDashboardContextValue {
  data: DashboardResponse
  filter: DashboardFilterValue
  setFilter: (next: DashboardFilterValue) => void
  /** Jump to a detail page (drill-down), optionally applying a scoped filter patch. */
  drill: DrillFn
}

export const ExecutiveDashboardContext = createContext<ExecutiveDashboardContextValue | null>(null)

/** Read the executive dashboard context (throws if used outside ExecutiveLayout). */
export function useExecutiveDashboard(): ExecutiveDashboardContextValue {
  const ctx = useContext(ExecutiveDashboardContext)
  if (!ctx) {
    throw new Error('useExecutiveDashboard must be used within ExecutiveLayout')
  }
  return ctx
}

/** Tab id → route for drill-down navigation. */
export const EXECUTIVE_TAB_ROUTES: Record<string, string> = {
  overview: '/executive',
  programmes: '/executive/programmes',
  registry: '/executive/registry',
  coordination: '/executive/coordination',
  coverage: '/executive/coverage',
}
