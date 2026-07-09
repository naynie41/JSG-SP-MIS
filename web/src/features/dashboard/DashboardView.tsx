import { RefreshCw } from 'lucide-react'
import { Card, KpiPanel } from '@/components/Card/Card'
import { Icon } from '@/components/Icon/Icon'
import { cn } from '@/lib/utils/cn'
import { formatNaira } from '@/lib/utils/money'
import { titleCase } from '@/features/registry/constants'
import { BENEFIT_TYPE_LABELS } from '@/features/benefits/constants'
import { DashboardQuickActions } from './DashboardQuickActions'
import type { BenefitTypeGroup, CoverageRow, DashboardResponse } from './types'
import styles from './dashboard.module.css'

const pct = (rate: number | null | undefined) => Math.round((rate ?? 0) * 100)

function updatedLabel(iso: string): string {
  const mins = Math.round((Date.now() - new Date(iso).getTime()) / 60000)
  if (mins < 1) return 'Updated just now'
  if (mins < 60) return `Updated ${mins} min ago`
  const hrs = Math.round(mins / 60)
  if (hrs < 24) return `Updated ${hrs}h ago`
  return `Updated ${new Date(iso).toLocaleDateString(undefined, { month: 'short', day: 'numeric' })}`
}

/** A horizontal bar row scaled to `max` (read-only chart primitive). */
function Bar({ label, value, ratio, sub }: { label: string; value: string; ratio: number; sub?: string }) {
  return (
    <div className={styles.barRow}>
      <span className={styles.barLabel} title={label}>{label}</span>
      <span className={styles.barTrack} aria-hidden="true">
        <span className={styles.barFill} style={{ width: `${Math.max(2, Math.round(ratio * 100))}%` }} />
      </span>
      <span className={styles.barValue}>
        {value}
        {sub && <><br /><span className={styles.barSub}>{sub}</span></>}
      </span>
    </div>
  )
}

export interface DashboardViewProps {
  eyebrow: string
  title: string
  lead: string
  /** Label for the beneficiaries KPI (e.g. "Beneficiaries served" for partners). */
  beneficiariesLabel?: string
  /** Show the permission-aware quick-action bar (MDA scope only; not exec/partner). */
  showQuickActions?: boolean
  data: DashboardResponse
  isFetching: boolean
  onRefresh: () => void
}

/**
 * Shared, read-only presentation for every scoped dashboard (PRD FR-RPT-01/02). It
 * renders whatever the aggregation layer returned for the caller's scope — headline
 * forest KPIs, a budget-utilisation bar, benefit + coverage breakdowns, and (when the
 * scope includes them) referral/grievance headlines. There are no edit controls.
 */
export function DashboardView({ eyebrow, title, lead, beneficiariesLabel = 'Beneficiaries', showQuickActions = false, data, isFetching, onRefresh }: DashboardViewProps) {
  const m = data.metrics
  const budget = m.benefits.budget
  const overBudget = budget.remaining < 0

  const byType = [...m.benefits.by_type].sort((a, b) => b.total_value - a.total_value).slice(0, 6)
  const maxType = Math.max(1, ...byType.map((t) => t.total_value))

  const coverage = [...m.coverage].sort((a, b) => b.beneficiary_count - a.beneficiary_count).slice(0, 8)
  const maxCoverage = Math.max(1, ...coverage.map((c) => c.beneficiary_count))

  const typeLabel = (t: BenefitTypeGroup) => (t.key ? (BENEFIT_TYPE_LABELS[t.key] ?? titleCase(t.key)) : 'Unspecified')

  return (
    <div className={styles.page}>
      <div className={styles.head}>
        <div className={styles.title}>
          <span className="eyebrow">{eyebrow}</span>
          <h1 className="t-h1">{title}</h1>
          <p className={styles.lead}>{lead}</p>
        </div>
        <div className={styles.meta}>
          <span className={styles.updated}>{updatedLabel(data.computed_at)}</span>
          <button type="button" className={styles.refresh} onClick={onRefresh} disabled={isFetching}>
            <Icon icon={RefreshCw} size={14} className={isFetching ? styles.spin : undefined} />
            Refresh
          </button>
        </div>
      </div>

      {showQuickActions && <DashboardQuickActions />}

      {/* Headline forest KPI panels */}
      <div className={styles.kpiGrid}>
        <KpiPanel label={beneficiariesLabel} value={m.registry.beneficiaries.total.toLocaleString()} hint={data.scope.label} />
        <KpiPanel label="Active programmes" value={m.programmes.active.toLocaleString()} hint={`${m.programmes.total} total`} />
        <KpiPanel label="Benefits disbursed" value={formatNaira(m.benefits.disbursed.total_value)} hint={`${m.benefits.disbursed.benefit_count.toLocaleString()} deliveries`} />
        <KpiPanel label="Budget utilisation" value={`${pct(budget.utilization_rate)}%`} hint={`${formatNaira(budget.utilized_value)} of ${formatNaira(budget.allocated)}`} />
      </div>

      {/* Budget utilisation bar */}
      <Card eyebrow="Benefits" title="Budget utilisation">
        <div className={styles.track} aria-hidden="true">
          <span className={cn(styles.fill, overBudget && styles.fillOver)} style={{ width: `${Math.min(100, pct(budget.utilization_rate))}%` }} />
        </div>
        <div className={styles.budgetMeta}>
          <span>Allocated {formatNaira(budget.allocated)}</span>
          <span>Utilised {formatNaira(budget.utilized_value)}</span>
          <span>{overBudget ? 'Over by ' : 'Remaining '}{formatNaira(Math.abs(budget.remaining))}</span>
        </div>
      </Card>

      <div className={styles.sections}>
        {/* Benefits by type */}
        <Card eyebrow="Breakdown" title="Benefits by type">
          {byType.length === 0 ? (
            <p className={styles.empty}>No benefits delivered yet.</p>
          ) : (
            <div className={styles.bars}>
              {byType.map((t) => (
                <Bar key={t.key ?? 'unspecified'} label={typeLabel(t)} value={formatNaira(t.total_value)} ratio={t.total_value / maxType} />
              ))}
            </div>
          )}
        </Card>

        {/* Coverage by LGA (map arrives in a later step) */}
        <Card eyebrow="Coverage" title="Beneficiaries by LGA">
          {coverage.length === 0 ? (
            <p className={styles.empty}>No coverage data yet.</p>
          ) : (
            <div className={styles.bars}>
              {coverage.map((c: CoverageRow) => (
                <Bar
                  key={c.lga}
                  label={titleCase(c.lga)}
                  value={c.beneficiary_count.toLocaleString()}
                  ratio={c.beneficiary_count / maxCoverage}
                  sub={formatNaira(c.benefit_value)}
                />
              ))}
            </div>
          )}
        </Card>

        {/* Referral headline — only when the scope includes coordination. */}
        {m.referrals !== null && (
          <Card eyebrow="Coordination" title="Referrals">
            <div className={styles.statRow}>
              <div className={styles.stat}>
                <span className={styles.statValue}>{m.referrals.total.toLocaleString()}</span>
                <span className={styles.statLabel}>Total</span>
              </div>
              <div className={styles.stat}>
                <span className={styles.statValue}>{pct(m.referrals.completion_rate)}%</span>
                <span className={styles.statLabel}>Completed</span>
              </div>
              <div className={styles.stat}>
                <span className={cn(styles.statValue, m.referrals.overdue > 0 && styles.statDanger)}>{m.referrals.overdue.toLocaleString()}</span>
                <span className={styles.statLabel}>Overdue</span>
              </div>
            </div>
          </Card>
        )}

        {/* Grievance headline — only when the scope includes coordination. */}
        {m.grievances !== null && (
          <Card eyebrow="Coordination" title="Grievances">
            <div className={styles.statRow}>
              <div className={styles.stat}>
                <span className={styles.statValue}>{m.grievances.total.toLocaleString()}</span>
                <span className={styles.statLabel}>Total</span>
              </div>
              <div className={styles.stat}>
                <span className={cn(styles.statValue, m.grievances.sla_breaches > 0 && styles.statDanger)}>{m.grievances.sla_breaches.toLocaleString()}</span>
                <span className={styles.statLabel}>SLA breaches</span>
              </div>
              <div className={styles.stat}>
                <span className={styles.statValue}>{m.grievances.avg_resolution_days ?? '—'}</span>
                <span className={styles.statLabel}>Avg days</span>
              </div>
            </div>
          </Card>
        )}
      </div>
    </div>
  )
}
