import { createContext, useContext } from 'react'
import type { DashboardFilterValue, DashboardResponse, DrillFn } from './types'

/**
 * Shared state for the Development-Partner suite. `PartnerLayout` fetches the dashboard
 * once and owns the cross-cutting filter; the five routed pages read it here, so they
 * share one data fetch + one filter and drill-down is route navigation.
 */
export interface PartnerDashboardContextValue {
  data: DashboardResponse
  filter: DashboardFilterValue
  setFilter: (next: DashboardFilterValue) => void
  /** Jump to a detail page (drill-down), optionally applying a scoped filter patch. */
  drill: DrillFn
  /** Whether the activity-level drill-down is shown (activity.view). */
  canDrill: boolean
}

export const PartnerDashboardContext = createContext<PartnerDashboardContextValue | null>(null)

/** Read the partner dashboard context (throws if used outside PartnerLayout). */
export function usePartnerDashboard(): PartnerDashboardContextValue {
  const ctx = useContext(PartnerDashboardContext)
  if (!ctx) {
    throw new Error('usePartnerDashboard must be used within PartnerLayout')
  }
  return ctx
}

/** Tab id → route for drill-down navigation. */
export const PARTNER_TAB_ROUTES: Record<string, string> = {
  overview: '/partner',
  programmes: '/partner/programmes',
  registry: '/partner/registry',
  coordination: '/partner/coordination',
  investment: '/partner/investment',
}
