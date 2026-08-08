import { useCallback, useMemo } from 'react'
import { Outlet, useLocation, useNavigate, useSearchParams } from 'react-router-dom'
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

/** Numeric filter keys, so a query string round-trips to the right type. */
const NUMERIC_KEYS = ['year', 'quarter', 'month'] as const

/**
 * Read the filter out of the query string.
 *
 * The filter lived in component state, so browser Back did not undo a filter
 * change and a filtered view could not be bookmarked or sent to a colleague —
 * which for a Commissioner sharing a finding is the whole point.
 */
function filterFromParams(params: URLSearchParams): DashboardFilterValue {
  const next = { ...EMPTY_FILTER }
  const bag = next as unknown as Record<string, string | number | null>
  for (const key of Object.keys(EMPTY_FILTER)) {
    const raw = params.get(key)
    if (raw === null || raw === '') continue
    if ((NUMERIC_KEYS as readonly string[]).includes(key)) {
      const parsed = Number(raw)
      if (Number.isNaN(parsed)) continue
      bag[key] = parsed
    } else {
      bag[key] = raw
    }
  }
  return next
}

function paramsFromFilter(value: DashboardFilterValue): URLSearchParams {
  const params = new URLSearchParams()
  for (const [key, v] of Object.entries(value)) {
    if (v === null || v === undefined || v === '') continue
    params.set(key, String(v))
  }
  return params
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
  const navigate = useNavigate()
  const location = useLocation()
  const [searchParams, setSearchParams] = useSearchParams()

  // The filter IS the query string: Back undoes a filter change, and a filtered
  // view can be bookmarked or shared.
  const filter = useMemo(() => filterFromParams(searchParams), [searchParams])
  const setFilter = useCallback(
    (next: DashboardFilterValue | ((prev: DashboardFilterValue) => DashboardFilterValue)) => {
      setSearchParams(
        (prev) => {
          const current = filterFromParams(prev)
          const resolved = typeof next === 'function' ? next(current) : next
          return paramsFromFilter(resolved)
        },
        { replace: false },
      )
    },
    [setSearchParams],
  )
  // The headline hero belongs to the Overview page only; inner pages get the filter bar
  // + their own body (no repeated hero).
  const isOverview = location.pathname === '/executive' || location.pathname === '/executive/'
  const { data, isLoading, isFetching, refetch } = useDashboard(filter, isExecutive && hasPermission('dashboard.view'))

  // Drill-down: apply a (scoped) filter patch and navigate to the detail page.
  // Path and query must move together — navigating separately would replace the
  // location and discard the filter patch that was just written to it.
  const drill: DrillFn = (tab, patch) => {
    const next = paramsFromFilter({ ...filter, ...(patch ?? {}) })
    const path = EXECUTIVE_TAB_ROUTES[tab] ?? '/executive'
    const query = next.toString()
    navigate(query ? `${path}?${query}` : path)
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
