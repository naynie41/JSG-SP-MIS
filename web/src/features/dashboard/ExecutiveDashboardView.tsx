import { ClipboardList, Coins, MapPin, RefreshCw, Users } from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { formatNaira } from '@/lib/utils/money'
import { titleCase } from '@/features/registry/constants'
import { BENEFIT_TYPE_LABELS } from '@/features/benefits/constants'
import type { BenefitTypeGroup, DashboardResponse } from './types'
import styles from './executive.module.css'

const pct = (rate: number | null | undefined) => Math.round((rate ?? 0) * 100)

function asOf(iso: string): string {
  const mins = Math.round((Date.now() - new Date(iso).getTime()) / 60000)
  if (mins < 1) return 'moments ago'
  if (mins < 60) return `${mins} min ago`
  const hrs = Math.round(mins / 60)
  if (hrs < 24) return `${hrs}h ago`
  return new Date(iso).toLocaleDateString(undefined, { day: 'numeric', month: 'long', year: 'numeric' })
}

/** A single-ratio donut (referral completion, on-SLA share). */
function Donut({ value, tone, size = 92 }: { value: number; tone?: 'info' | 'warning'; size?: number }) {
  const r = size / 2 - 7
  const circ = 2 * Math.PI * r
  const dash = circ * Math.min(1, Math.max(0, value / 100))
  return (
    <svg className={styles.donut} width={size} height={size} viewBox={`0 0 ${size} ${size}`} role="img" aria-label={`${value}%`}>
      <circle cx={size / 2} cy={size / 2} r={r} fill="none" strokeWidth="8" className={styles.donutTrack} />
      <circle
        cx={size / 2}
        cy={size / 2}
        r={r}
        fill="none"
        strokeWidth="8"
        className={styles.donutValue}
        data-tone={tone}
        strokeDasharray={`${dash} ${circ}`}
        transform={`rotate(-90 ${size / 2} ${size / 2})`}
      />
      <text x="50%" y="50%" textAnchor="middle" dominantBaseline="central" className={styles.donutText}>
        {value}%
      </text>
    </svg>
  )
}

interface BarItem {
  name: string
  value: number
}

/** Ranked horizontal bars: sorted magnitude, one hue, top-rank endpoint emphasis. */
function RankedBars({ items, format }: { items: BarItem[]; format: (n: number) => string }) {
  const max = Math.max(1, ...items.map((i) => i.value))
  if (items.length === 0) return <p className={styles.empty}>No data yet.</p>
  return (
    <div className={styles.bars}>
      {items.map((item, i) => (
        <div key={item.name} className={styles.barRow}>
          <span className={styles.barName} title={item.name}>{item.name}</span>
          <span className={styles.barTrack}>
            <span
              className={styles.barFill}
              data-lead={i === 0}
              style={{ width: `${Math.max(4, Math.round((item.value / max) * 100))}%` }}
              title={`${item.name}: ${format(item.value)}`}
            />
          </span>
          <span className={styles.barValue}>{format(item.value)}</span>
        </div>
      ))}
    </div>
  )
}

function Figure({ icon, label, value, hint }: { icon: LucideIcon; label: string; value: string; hint?: string }) {
  return (
    <div className={styles.figure}>
      <span className={styles.figureLabel}>
        <Icon icon={icon} size={14} />
        {label}
      </span>
      <span className={styles.figureValue}>{value}</span>
      {hint && <span className={styles.figureHint}>{hint}</span>}
    </div>
  )
}

export interface ExecutiveDashboardViewProps {
  data: DashboardResponse
  isFetching: boolean
  onRefresh: () => void
}

/**
 * The Executive briefing — a "state-of-the-state" report for the governor and
 * commissioners. Read-only, state-wide, editorial: a forest hero, a hairline
 * key-figures band, and dataviz-correct ranked bars. Deliberately distinct from the
 * Admin/MDA dashboards. All figures come from the reporting aggregation layer.
 */
export function ExecutiveDashboardView({ data, isFetching, onRefresh }: ExecutiveDashboardViewProps) {
  const m = data.metrics
  const budget = m.benefits.budget
  const budgetPct = pct(budget.utilization_rate)
  const overBudget = budget.remaining < 0

  const coverage = [...m.coverage]
    .sort((a, b) => b.beneficiary_count - a.beneficiary_count)
    .slice(0, 8)
    .map((c) => ({ name: titleCase(c.lga), value: c.beneficiary_count }))

  const typeLabel = (t: BenefitTypeGroup) => (t.key ? (BENEFIT_TYPE_LABELS[t.key] ?? titleCase(t.key)) : 'Unspecified')
  const byType = [...m.benefits.by_type]
    .sort((a, b) => b.total_value - a.total_value)
    .slice(0, 6)
    .map((t) => ({ name: typeLabel(t), value: t.total_value }))

  const grievanceOnSla =
    m.grievances && m.grievances.total > 0 ? Math.round((1 - m.grievances.sla_breaches / m.grievances.total) * 100) : 100

  return (
    <div className={styles.page}>
      {/* ---- HERO ---- */}
      <header className={`${styles.hero} ${styles.reveal}`}>
        <div className={styles.heroTop}>
          <span className={styles.heroEyebrow}>State-wide briefing</span>
          <span className={styles.dateline}>{data.scope.label} · as of {asOf(data.computed_at)}</span>
        </div>

        <h1 className={styles.heroTitle}>
          The state of social protection in <em>Jigawa</em>
        </h1>

        <div className={styles.heroFigures}>
          <div className={styles.marquee}>
            <span className={styles.marqueeValue}>{m.registry.beneficiaries.total.toLocaleString()}</span>
            <span className={styles.marqueeLabel}>Citizens reached</span>
          </div>
          <div className={`${styles.marquee} ${styles.marqueeSub}`}>
            <span className={styles.marqueeValue}>{formatNaira(m.benefits.disbursed.total_value)}</span>
            <span className={styles.marqueeLabel}>Benefits disbursed</span>
          </div>
          <button type="button" className={styles.refresh} onClick={onRefresh} disabled={isFetching} style={{ marginLeft: 'auto' }}>
            <Icon icon={RefreshCw} size={14} className={isFetching ? styles.spin : undefined} />
            Refresh
          </button>
        </div>
      </header>

      {/* ---- KEY FIGURES ---- */}
      <section className={`${styles.keyFigures} ${styles.reveal}`} style={{ animationDelay: '80ms' }}>
        <Figure icon={Users} label="Beneficiaries" value={m.registry.beneficiaries.total.toLocaleString()} hint={`${(m.registry.households?.total ?? 0).toLocaleString()} households`} />
        <Figure icon={Coins} label="Disbursed" value={formatNaira(m.benefits.disbursed.total_value)} hint={`${m.benefits.disbursed.benefit_count.toLocaleString()} deliveries`} />
        <Figure icon={ClipboardList} label="Programmes" value={m.programmes.active.toLocaleString()} hint={`of ${m.programmes.total} in the catalogue`} />
        <Figure icon={MapPin} label="LGAs reached" value={m.coverage.length.toLocaleString()} hint="local government areas" />
      </section>

      {/* ---- 01 COVERAGE ---- */}
      <section className={`${styles.section} ${styles.reveal}`} style={{ animationDelay: '160ms' }}>
        <div className={styles.sectionHead}>
          <span className={styles.sectionNo}>01</span>
          <h2 className={styles.sectionTitle}>Coverage across the State</h2>
        </div>
        <p className={styles.sectionSub}>Beneficiaries reached by local government area — where social protection is landing.</p>
        <div className={styles.panel}>
          <RankedBars items={coverage} format={(n) => n.toLocaleString()} />
        </div>
      </section>

      {/* ---- 02 DELIVERY ---- */}
      <section className={`${styles.section} ${styles.reveal}`} style={{ animationDelay: '240ms' }}>
        <div className={styles.sectionHead}>
          <span className={styles.sectionNo}>02</span>
          <h2 className={styles.sectionTitle}>Delivery &amp; budget</h2>
        </div>
        <div className={styles.split}>
          <div className={styles.panel}>
            <div className={styles.budget}>
              <div className={styles.budgetTopline}>
                <span className={styles.budgetPct} data-over={overBudget}>{budgetPct}%</span>
                <span className={styles.budgetCaption}>
                  of budget {overBudget ? 'exceeded' : 'utilised'}
                </span>
              </div>
              <div className={styles.ribbon}>
                <span className={styles.ribbonFill} data-over={overBudget} style={{ width: `${Math.min(100, budgetPct)}%` }} />
              </div>
              <div className={styles.budgetLegend}>
                <div className={styles.legendItem}>
                  <span className={styles.legendLabel}>Allocated</span>
                  <span className={styles.legendValue}>{formatNaira(budget.allocated)}</span>
                </div>
                <div className={styles.legendItem}>
                  <span className={styles.legendLabel}>Utilised</span>
                  <span className={styles.legendValue}>{formatNaira(budget.utilized_value)}</span>
                </div>
                <div className={styles.legendItem}>
                  <span className={styles.legendLabel}>Remaining</span>
                  <span className={styles.legendValue}>{formatNaira(budget.remaining)}</span>
                </div>
              </div>
            </div>
          </div>
          <div className={styles.panel}>
            <p className={styles.sectionSub} style={{ marginBottom: 'var(--space-4)' }}>Benefits by type</p>
            <RankedBars items={byType} format={formatNaira} />
          </div>
        </div>
      </section>

      {/* ---- 03 COORDINATION ---- */}
      {(m.referrals !== null || m.grievances !== null) && (
        <section className={`${styles.section} ${styles.reveal}`} style={{ animationDelay: '320ms' }}>
          <div className={styles.sectionHead}>
            <span className={styles.sectionNo}>03</span>
            <h2 className={styles.sectionTitle}>Coordination</h2>
          </div>
          <div className={styles.coordGrid}>
            {m.referrals !== null && (
              <div className={styles.panel}>
                <div className={styles.coordCard}>
                  <Donut value={pct(m.referrals.completion_rate)} tone="info" />
                  <div className={styles.coordMeta}>
                    <span className={styles.coordLabel}>Referrals</span>
                    <span className={styles.coordValue}>{m.referrals.total.toLocaleString()}</span>
                    <span className={styles.coordNote}>
                      {m.referrals.completed.toLocaleString()} completed
                      {m.referrals.overdue > 0 ? <> · <strong>{m.referrals.overdue.toLocaleString()} overdue</strong></> : null}
                    </span>
                  </div>
                </div>
              </div>
            )}
            {m.grievances !== null && (
              <div className={styles.panel}>
                <div className={styles.coordCard}>
                  <Donut value={grievanceOnSla} tone={m.grievances.sla_breaches > 0 ? 'warning' : undefined} />
                  <div className={styles.coordMeta}>
                    <span className={styles.coordLabel}>Grievances</span>
                    <span className={styles.coordValue}>{m.grievances.total.toLocaleString()}</span>
                    <span className={styles.coordNote}>
                      {m.grievances.sla_breaches > 0 ? <><strong>{m.grievances.sla_breaches} SLA breaches</strong> · </> : null}
                      resolved in {m.grievances.avg_resolution_days ?? '—'}d avg
                    </span>
                  </div>
                </div>
              </div>
            )}
          </div>
        </section>
      )}

      <p className={styles.footnote}>
        Read-only oversight · de-identified figures from the reporting layer · {data.scope.label}
      </p>
    </div>
  )
}
