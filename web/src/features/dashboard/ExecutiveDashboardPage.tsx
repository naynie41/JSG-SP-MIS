import { useState } from 'react'
import { RefreshCw } from 'lucide-react'
import { Card } from '@/components/Card/Card'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import { Tabs } from '@/components/Tabs/Tabs'
import { useAuth } from '@/lib/auth/AuthProvider'
import { formatNaira } from '@/lib/utils/money'
import { CoordinationTab } from './CoordinationTab'
import { CoverageMapTab } from './CoverageMapTab'
import { ExecutiveOverviewTab } from './ExecutiveOverviewTab'
import { ExportMenu } from './ExportMenu'
import { FilterBar } from './FilterBar'
import { ProgrammesTab } from './ProgrammesTab'
import { RegistryTab } from './RegistryTab'
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
 * Executive dashboard (PRD FR-RPT-01) — the Governor's read-only suite. A shared
 * forest hero carrying the NET-UNIQUE headline + a persistent Refresh, over a tabbed
 * body (Overview, Programmes, …), all served from the reporting aggregation layer.
 * Executive role only; de-identified aggregates; no edit controls.
 */
export function ExecutiveDashboardPage() {
  const { user, hasPermission } = useAuth()
  const isExecutive = user?.role?.key === 'executive'
  const [filter, setFilter] = useState<DashboardFilterValue>(EMPTY_FILTER)
  const [tab, setTab] = useState('overview')
  const { data, isLoading, isFetching, refetch } = useDashboard(filter, isExecutive && hasPermission('dashboard.view'))

  // Drill-down: apply a (scoped) filter patch and jump to the detailed tab.
  const drill: DrillFn = (nextTab, patch) => {
    if (patch) setFilter((f) => ({ ...f, ...patch }))
    setTab(nextTab)
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

  return (
    <div className={styles.shell}>
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

      {data.filter_options && (
        <FilterBar value={filter} options={data.filter_options} onChange={setFilter} live={data.live} />
      )}

      <Tabs
        activeId={tab}
        onChange={setTab}
        items={[
          { id: 'overview', label: 'Overview', content: <ExecutiveOverviewTab data={data} onDrill={drill} /> },
          { id: 'programmes', label: 'Programmes', content: <ProgrammesTab data={data} onDrill={drill} /> },
          { id: 'registry', label: 'Registry', content: <RegistryTab data={data} /> },
          { id: 'coordination', label: 'Coordination', content: <CoordinationTab data={data} /> },
          { id: 'coverage', label: 'Coverage Map', content: <CoverageMapTab filter={filter} /> },
        ]}
      />

      <p className={styles.footnote}>
        Read-only oversight · de-identified aggregates from the reporting layer · {data.scope.label} · updated{' '}
        {asOf(data.computed_at)}
      </p>
    </div>
  )
}
