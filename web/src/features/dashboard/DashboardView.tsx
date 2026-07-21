import { ClipboardList, Coins, PackageCheck, RefreshCw, Users } from 'lucide-react'
import { Card } from '@/components/Card/Card'
import { Icon } from '@/components/Icon/Icon'
import { StatCard } from '@/components/StatCard/StatCard'
import { Gauge } from '@/components/Gauge/Gauge'
import { AccentPanel } from '@/components/AccentPanel/AccentPanel'
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

/** Catmull-Rom → Bézier smoothing for a soft area/line. */
function smoothPath(points: [number, number][]): string {
  if (points.length === 0) return ''
  if (points.length === 1) return `M ${points[0][0]} ${points[0][1]}`
  let d = `M ${points[0][0]} ${points[0][1]}`
  for (let i = 0; i < points.length - 1; i++) {
    const p0 = points[i - 1] ?? points[i]
    const p1 = points[i]
    const p2 = points[i + 1]
    const p3 = points[i + 2] ?? p2
    const c1x = p1[0] + (p2[0] - p0[0]) / 6
    const c1y = p1[1] + (p2[1] - p0[1]) / 6
    const c2x = p2[0] - (p3[0] - p1[0]) / 6
    const c2y = p2[1] - (p3[1] - p1[1]) / 6
    d += ` C ${c1x} ${c1y} ${c2x} ${c2y} ${p2[0]} ${p2[1]}`
  }
  return d
}

/** Smooth area chart of beneficiaries across LGAs (real distribution, not a trend). */
function CoverageArea({ rows }: { rows: CoverageRow[] }) {
  const W = 640
  const H = 210
  const pad = { t: 14, r: 14, b: 30, l: 40 }
  const n = rows.length
  const maxY = Math.max(1, ...rows.map((r) => r.beneficiary_count))
  const xAt = (i: number) => pad.l + (n <= 1 ? (W - pad.l - pad.r) / 2 : (i * (W - pad.l - pad.r)) / (n - 1))
  const yAt = (v: number) => pad.t + (1 - v / maxY) * (H - pad.t - pad.b)
  const pts = rows.map((r, i) => [xAt(i), yAt(r.beneficiary_count)] as [number, number])
  const line = smoothPath(pts)
  const area = pts.length > 0 ? `${line} L ${xAt(n - 1)} ${H - pad.b} L ${xAt(0)} ${H - pad.b} Z` : ''
  const gridVals = [0, Math.round(maxY / 2), maxY]

  const total = rows.reduce((s, r) => s + r.beneficiary_count, 0)

  return (
    <svg className={styles.area} viewBox={`0 0 ${W} ${H}`} role="img" aria-label={`Beneficiaries across ${n} local government areas, ${total.toLocaleString()} total`}>
      <defs>
        <linearGradient id="coverageFill" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%" stopColor="var(--forest-2)" stopOpacity="0.28" />
          <stop offset="100%" stopColor="var(--forest-2)" stopOpacity="0" />
        </linearGradient>
      </defs>
      {gridVals.map((v) => (
        <g key={v}>
          <line x1={pad.l} x2={W - pad.r} y1={yAt(v)} y2={yAt(v)} className={styles.areaGrid} />
          <text x={pad.l - 8} y={yAt(v) + 4} className={styles.areaAxis} textAnchor="end">{v.toLocaleString()}</text>
        </g>
      ))}
      {area && <path d={area} fill="url(#coverageFill)" />}
      {line && <path d={line} fill="none" className={styles.areaLine} />}
      {pts.map(([x, y], i) => (
        <g key={rows[i].lga}>
          {/* Native hover tooltip + a larger transparent hit area for the point. */}
          <title>{`${titleCase(rows[i].lga)}: ${rows[i].beneficiary_count.toLocaleString()} beneficiaries`}</title>
          <circle cx={x} cy={y} r="12" fill="transparent" />
          <circle cx={x} cy={y} r="3.5" className={styles.areaDot} />
        </g>
      ))}
      {rows.map((r, i) => (
        <text key={r.lga} x={xAt(i)} y={H - 10} className={styles.areaAxis} textAnchor="middle">{titleCase(r.lga)}</text>
      ))}
    </svg>
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
 * Shared, read-only presentation for every scoped dashboard (PRD FR-RPT-01/02):
 * headline KPI cards, a coverage-distribution hero, a forest accent summary, budget +
 * referral gauges, and benefit/coordination breakdowns. All figures come from the
 * scope's aggregation payload — no invented trends. There are no edit controls.
 */
export function DashboardView({ eyebrow, title, lead, beneficiariesLabel = 'Beneficiaries', showQuickActions = false, data, isFetching, onRefresh }: DashboardViewProps) {
  const m = data.metrics
  const budget = m.benefits.budget
  const overBudget = budget.remaining < 0
  const budgetPct = pct(budget.utilization_rate)

  const byType = [...m.benefits.by_type].sort((a, b) => b.total_value - a.total_value).slice(0, 6)
  const maxType = Math.max(1, ...byType.map((t) => t.total_value))

  const coverage = [...m.coverage].sort((a, b) => a.lga.localeCompare(b.lga)).slice(0, 8)

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

      {/* Headline KPI stat cards */}
      <div className={styles.kpiRow}>
        <StatCard icon={Users} label={beneficiariesLabel} value={m.registry.beneficiaries.total.toLocaleString()} hint={data.scope.label} tone="forest" />
        <StatCard icon={ClipboardList} label="Active programmes" value={m.programmes.active.toLocaleString()} hint={`${m.programmes.total} in catalogue`} tone="info" />
        <StatCard icon={Coins} label="Benefits disbursed" value={formatNaira(m.benefits.disbursed.total_value)} hint={`${m.benefits.disbursed.benefit_count.toLocaleString()} deliveries`} tone="mint" />
        <StatCard icon={PackageCheck} label="Deliveries" value={m.benefits.disbursed.benefit_count.toLocaleString()} hint="records in ledger" tone="success" />
      </div>

      {/* Hero: coverage distribution + accent summary */}
      <div className={styles.hero}>
        <Card eyebrow="Coverage" title="Coverage by LGA">
          {coverage.length === 0 ? (
            <p className={styles.empty}>No coverage data yet.</p>
          ) : (
            <>
              <p className={styles.heroSub}>Beneficiaries reached across local government areas in this scope.</p>
              <CoverageArea rows={coverage} />
            </>
          )}
        </Card>

        <AccentPanel
          label="Benefits disbursed"
          value={formatNaira(m.benefits.disbursed.total_value)}
          sub={`${m.benefits.disbursed.benefit_count.toLocaleString()} deliveries · ${data.scope.label}`}
        />
      </div>

      {/* Gauges: budget utilisation + referral completion (coordination scope only) */}
      <div className={styles.gauges}>
        <Gauge
          label="Budget utilisation"
          value={budgetPct}
          caption={`${formatNaira(budget.utilized_value)} of ${formatNaira(budget.allocated)}${overBudget ? ' · over budget' : ''}`}
          tone={overBudget ? 'danger' : 'forest'}
        />
        {m.referrals !== null && (
          <Gauge
            label="Referral completion"
            value={pct(m.referrals.completion_rate)}
            caption={`${m.referrals.total.toLocaleString()} referrals · ${m.referrals.overdue.toLocaleString()} overdue`}
            tone="info"
          />
        )}
      </div>

      {/* Breakdowns + coordination */}
      <div className={styles.lower}>
        <Card eyebrow="Breakdown" title="Benefits by type">
          {byType.length === 0 ? (
            <p className={styles.empty}>No benefits delivered yet.</p>
          ) : (
            <div className={styles.bars}>
              {byType.map((t) => (
                <div key={t.key ?? 'unspecified'} className={styles.barRow} title={`${typeLabel(t)}: ${formatNaira(t.total_value)}`}>
                  <span className={styles.barLabel} title={typeLabel(t)}>{typeLabel(t)}</span>
                  <span className={styles.barTrack} aria-hidden="true">
                    <span className={styles.barFill} style={{ width: `${Math.max(2, Math.round((t.total_value / maxType) * 100))}%` }} />
                  </span>
                  <span className={styles.barValue}>{formatNaira(t.total_value)}</span>
                </div>
              ))}
            </div>
          )}
        </Card>

        {m.referrals !== null && (
          <Card eyebrow="Coordination" title="Referrals">
            <div className={styles.statRow}>
              <div className={styles.stat}>
                <span className={styles.statValue}>{m.referrals.total.toLocaleString()}</span>
                <span className={styles.statLabel}>Total</span>
              </div>
              <div className={styles.stat}>
                <span className={styles.statValue}>{m.referrals.completed.toLocaleString()}</span>
                <span className={styles.statLabel}>Completed</span>
              </div>
              <div className={styles.stat}>
                <span className={cn(styles.statValue, m.referrals.overdue > 0 && styles.statDanger)}>{m.referrals.overdue.toLocaleString()}</span>
                <span className={styles.statLabel}>Overdue</span>
              </div>
            </div>
          </Card>
        )}

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
