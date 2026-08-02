import { useState } from 'react'
import { Outlet, useLocation, useNavigate } from 'react-router-dom'
import { RefreshCw } from 'lucide-react'
import { Card } from '@/components/Card/Card'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { formatNaira } from '@/lib/utils/money'
import { ExportMenu } from './ExportMenu'
import { FilterBar } from './FilterBar'
import { EXECUTIVE_TAB_ROUTES, ExecutiveDashboardContext } from './executiveContext'
import type { ExecutiveDashboardContextValue } from './executiveContext'
import { useDashboard } from './hooks'
import { EMPTY_FILTER } from './types'
import type { DashboardFilterValue, DrillFn, ScopeTier } from './types'
import styles from './executiveShell.module.css'

const num = (n: number | null | undefined): string => (n ?? 0).toLocaleString()

const TIER_LABEL: Record<ScopeTier, string> = {
  statewide: 'State-wide oversight',
  operational: 'Operational scope',
  partner: 'Partner scope',
}

function asOf(iso: string): string {
  const mins = Math.round((Date.now() - new Date(iso).getTime()) / 60000)
  if (mins < 1) return 'moments ago'
  if (mins < 60) return `${mins} min ago`
  const hrs = Math.round(mins / 60)
  if (hrs < 24) return `${hrs}h ago`
  return new Date(iso).toLocaleDateString(undefined, { day: 'numeric', month: 'long', year: 'numeric' })
}

/**
 * Executive briefing suite shell (PRD FR-RPT-01, Phase 6E) — the Governor's read-only
 * view. A shared forest hero carrying the NET-UNIQUE headline (Overview page only), a
 * cross-cutting filter, and Export/Refresh wrap the five routed pages (Overview ·
 * Programmes · Registry · Coordination · Coverage Map), each reached from the side rail.
 * The dashboard is fetched once here and the filter is shared, so a filter or drill-down
 * applies across every page; drill-down is route navigation. Executive role only;
 * de-identified aggregates; no edit controls.
 */
export function ExecutiveLayout() {
  const { user, hasPermission } = useAuth()
  const isExecutive = user?.role?.key === 'executive'
  const [filter, setFilter] = useState<DashboardFilterValue>(EMPTY_FILTER)
  const navigate = useNavigate()
  const location = useLocation()
  // The headline hero belongs to the Overview page only; inner pages get the filter bar
  // + their own body (no repeated hero).
  const isOverview = location.pathname === '/executive' || location.pathname === '/executive/'
  const { data, isLoading, isFetching, refetch } = useDashboard(filter, isExecutive && hasPermission('dashboard.view'))

  // Drill-down: apply a (scoped) filter patch and navigate to the detail page.
  const drill: DrillFn = (tab, patch) => {
    if (patch) setFilter((f) => ({ ...f, ...patch }))
    navigate(EXECUTIVE_TAB_ROUTES[tab] ?? '/executive')
  }

  if (!isExecutive) {
    return (
      <Card>
        <p className={styles.forbidden}>The executive dashboard is available to Executive users only.</p>
      </Card>
    )
  }

  if (isLoading || !data) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={28} label="Loading briefing" />
      </div>
    )
  }

  const pop = data.metrics.population
  const budget = data.metrics.benefits.budget
  const ctx: ExecutiveDashboardContextValue = { data, filter, setFilter, drill }

  return (
    <div className={styles.shell}>
      {/* ---------- HERO (Overview page only) ---------- */}
      {isOverview && (
        <header className={`${styles.hero} ${styles.reveal}`}>
          <div className={styles.heroTop}>
            <span className={styles.heroEyebrow}>Executive briefing</span>
            <span className={styles.dateline}>
              {data.scope.tier ? `${TIER_LABEL[data.scope.tier]} · ` : ''}
              {data.scope.label} · {data.live ? 'filtered · ' : ''}updated {asOf(data.computed_at)}
            </span>
          </div>

          <h1 className={styles.heroTitle}>
            The state of social protection in <em>Jigawa</em>
          </h1>

          <div className={styles.heroFigures}>
            <div className={styles.marquee}>
              <span className={styles.marqueeValue}>{num(pop?.net_unique_served)}</span>
              <span className={styles.marqueeLabel}>Net-unique beneficiaries reached</span>
            </div>
            <div className={`${styles.marquee} ${styles.marqueeSub}`}>
              <span className={styles.marqueeValue}>{formatNaira(budget.utilized_value)}</span>
              <span className={styles.marqueeLabel}>Benefits disbursed</span>
            </div>
            <div className={styles.heroActions}>
              {hasPermission('reporting.export') && <ExportMenu filter={filter} />}
              <button type="button" className={styles.refresh} onClick={() => refetch()} disabled={isFetching}>
                <Icon icon={RefreshCw} size={14} className={isFetching ? styles.spin : undefined} />
                Refresh
              </button>
            </div>
          </div>
        </header>
      )}

      {data.filter_options && (
        <FilterBar value={filter} options={data.filter_options} onChange={setFilter} live={data.live} />
      )}

      <ExecutiveDashboardContext.Provider value={ctx}>
        <Outlet />
      </ExecutiveDashboardContext.Provider>

      <p className={styles.footnote}>
        Read-only oversight · de-identified aggregates from the reporting layer · {data.scope.label} · updated{' '}
        {asOf(data.computed_at)}
      </p>
    </div>
  )
}
