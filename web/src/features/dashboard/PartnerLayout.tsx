import { useState } from 'react'
import { Outlet, useNavigate } from 'react-router-dom'
import { RefreshCw } from 'lucide-react'
import { Card } from '@/components/Card/Card'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { formatNaira } from '@/lib/utils/money'
import { ExportMenu } from './ExportMenu'
import { FilterBar } from './FilterBar'
import { PARTNER_TAB_ROUTES, PartnerDashboardContext } from './partnerContext'
import type { PartnerDashboardContextValue } from './partnerContext'
import { useDashboard } from './hooks'
import { EMPTY_FILTER } from './types'
import type { DashboardFilterValue, DrillFn } from './types'
import styles from './fundingPartner.module.css'

const pct = (rate: number | null | undefined): number => Math.round((rate ?? 0) * 100)

function asOf(iso: string): string {
  const mins = Math.round((Date.now() - new Date(iso).getTime()) / 60000)
  if (mins < 1) return 'moments ago'
  if (mins < 60) return `${mins} min ago`
  const hrs = Math.round(mins / 60)
  if (hrs < 24) return `${hrs}h ago`
  return new Date(iso).toLocaleDateString(undefined, { day: 'numeric', month: 'long', year: 'numeric' })
}

/**
 * Development-Partner suite shell (PRD FR-RPT-02, Phase 6P). A shared forest hero
 * (delivered value vs committed funding + utilisation meter), a cross-cutting filter,
 * and Export/Refresh wrap the five routed pages (Overview · Programmes & Results ·
 * Registry · Coordination · Investment Map), each reached from the side rail. The
 * dashboard is fetched once here and the filter is shared, so a filter or drill-down
 * applies across every page; drill-down is route navigation. Money is DELIVERY VALUE,
 * never treasury expenditure; the server enforces the funded scope; read-only, no PII.
 */
export function PartnerLayout() {
  const { user, hasPermission } = useAuth()
  const isPartner = user?.role?.key === 'development_partner'
  const [filter, setFilter] = useState<DashboardFilterValue>(EMPTY_FILTER)
  const navigate = useNavigate()
  const { data, isLoading, isFetching, refetch } = useDashboard(filter, isPartner && hasPermission('dashboard.view'))

  const drill: DrillFn = (tab, patch) => {
    if (patch) setFilter((f) => ({ ...f, ...patch }))
    navigate(PARTNER_TAB_ROUTES[tab] ?? '/partner')
  }

  if (!isPartner) {
    return (
      <Card>
        <p className={styles.forbidden}>The partner dashboard is available to Development Partner users only.</p>
      </Card>
    )
  }

  if (isLoading || !data) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={28} label="Loading dashboard" />
      </div>
    )
  }

  const pf = data.metrics.partner_funding
  const deliveredPct = pf && pf.allocated > 0 ? Math.min(100, Math.round((pf.delivered_value / pf.allocated) * 100)) : 0
  const ctx: PartnerDashboardContextValue = { data, filter, setFilter, drill, canDrill: hasPermission('activity.view') }

  return (
    <div className={styles.page}>
      {/* ---------- SHARED HERO ---------- */}
      <header className={`${styles.hero} ${styles.reveal}`}>
        <div className={styles.heroTop}>
          <span className={styles.heroEyebrow}>Funding partner · funded scope</span>
          <span className={styles.dateline}>
            {data.scope.label} · {data.live ? 'filtered · ' : ''}updated {asOf(data.computed_at)}
          </span>
        </div>

        <h1 className={styles.heroTitle}>
          Your funding <em>at work</em> in Jigawa
        </h1>

        <div className={styles.heroFigures}>
          <div className={styles.marquee}>
            <span className={styles.marqueeValue}>{formatNaira(pf?.delivered_value ?? 0)}</span>
            <span className={styles.marqueeLabel}>Value delivered to beneficiaries</span>
          </div>
          <div className={`${styles.marquee} ${styles.marqueeSub}`}>
            <span className={styles.marqueeValue}>{formatNaira(pf?.allocated ?? 0)}</span>
            <span className={styles.marqueeLabel}>Committed funding</span>
          </div>
          <div className={styles.heroActions}>
            {hasPermission('reporting.export') && <ExportMenu filter={filter} />}
            <button type="button" className={styles.refresh} onClick={() => refetch()} disabled={isFetching}>
              <Icon icon={RefreshCw} size={14} className={isFetching ? styles.spin : undefined} />
              Refresh
            </button>
          </div>
        </div>

        {pf && (
          <div className={styles.heroMeter}>
            <span className={styles.heroMeterFill} style={{ width: `${deliveredPct}%` }} />
            <span className={styles.heroMeterLabel}>{pct(pf.utilization_rate)}% of committed funding delivered</span>
          </div>
        )}
      </header>

      {data.filter_options && <FilterBar value={filter} options={data.filter_options} onChange={setFilter} live={data.live} />}

      {pf ? (
        <PartnerDashboardContext.Provider value={ctx}>
          <Outlet />
        </PartnerDashboardContext.Provider>
      ) : (
        <p className={styles.empty}>
          No funded activities are attributed to you yet. Once an MDA attributes an activity to your funding, its budget,
          delivery and reach appear here.
        </p>
      )}

      <p className={styles.footnote}>
        Read-only · funded scope · de-identified aggregates · figures are DELIVERY VALUE (benefits delivered under your
        funded activities), not treasury expenditure · {data.scope.label}
      </p>
    </div>
  )
}
