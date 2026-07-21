import { ClipboardList, PackageCheck, RefreshCw, Users } from 'lucide-react'
import { Card } from '@/components/Card/Card'
import { Icon } from '@/components/Icon/Icon'
import { StatCard } from '@/components/StatCard/StatCard'
import { cn } from '@/lib/utils/cn'
import { formatNaira } from '@/lib/utils/money'
import { titleCase } from '@/features/registry/constants'
import { BENEFIT_TYPE_LABELS } from '@/features/benefits/constants'
import type { BenefitTypeGroup, DashboardResponse } from './types'
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

export interface PartnerDashboardViewProps {
  data: DashboardResponse
  isFetching: boolean
  onRefresh: () => void
}

/**
 * The Development-Partner (funder) dashboard (PRD FR-RPT-02) — a deliberately distinct
 * layout from the MDA/executive view. A funder reads money-first, so the page LEADS
 * with a forest "funding at work" hero carrying a linear budget-utilisation meter, then
 * a compact KPI trio, then two evidence panels: where the funding REACHED (ranked LGAs
 * by benefit value) and the delivery MIX (share of funding by benefit type). Every
 * figure is real aggregation data — no invented trends; coordination families are
 * omitted (they don't apply to a funding scope). Read-only.
 *
 * dataviz: the brand palette is a single forest/teal family, so identity is carried by
 * text labels, not categorical hues (single-hue magnitude bars); status colour is
 * reserved for the over-budget state; the scarce lime accents only the budget meter.
 */
export function PartnerDashboardView({ data, isFetching, onRefresh }: PartnerDashboardViewProps) {
  const m = data.metrics
  const budget = m.benefits.budget
  const overBudget = budget.remaining < 0
  const budgetPct = pct(budget.utilization_rate)

  // Where the funding reached: LGAs ranked by benefit value.
  const reach = [...m.coverage].sort((a, b) => b.benefit_value - a.benefit_value).slice(0, 8)
  const maxReach = Math.max(1, ...reach.map((r) => r.benefit_value))

  // Delivery mix: share of funding by benefit type (top 5 + folded "Other").
  const totalValue = m.benefits.disbursed.total_value
  const typeLabel = (t: BenefitTypeGroup) => (t.key ? (BENEFIT_TYPE_LABELS[t.key] ?? titleCase(t.key)) : 'Unspecified')
  const sorted = [...m.benefits.by_type].sort((a, b) => b.total_value - a.total_value)
  const otherValue = sorted.slice(5).reduce((s, t) => s + t.total_value, 0)
  const mix = [
    ...sorted.slice(0, 5).map((t) => ({ label: typeLabel(t), value: t.total_value })),
    ...(otherValue > 0 ? [{ label: 'Other', value: otherValue }] : []),
  ]

  return (
    <div className={styles.page}>
      <div className={styles.head}>
        <div className={styles.title}>
          <span className="eyebrow">06 · Reporting</span>
          <h1 className="t-h1">Partner dashboard</h1>
          <p className={styles.lead}>
            Coverage, budget utilisation and delivery performance for the programmes you fund. Scoped to your
            funded programmes only.
          </p>
        </div>
        <div className={styles.meta}>
          <span className={styles.updated}>{updatedLabel(data.computed_at)}</span>
          <button type="button" className={styles.refresh} onClick={onRefresh} disabled={isFetching}>
            <Icon icon={RefreshCw} size={14} className={isFetching ? styles.spin : undefined} />
            Refresh
          </button>
        </div>
      </div>

      {/* Money-first hero: funding delivered + a linear budget-utilisation meter. */}
      <div className={styles.fundHero}>
        <div className={styles.fundHeadline}>
          <span className={styles.fundEyebrow}>Funding at work</span>
          <span className={styles.fundValue}>{formatNaira(m.benefits.disbursed.total_value)}</span>
          <span className={styles.fundSub}>
            {m.benefits.disbursed.benefit_count.toLocaleString()} deliveries across your funded programmes · {data.scope.label}
          </span>
        </div>
        <div className={styles.meter}>
          <div className={styles.meterTop}>
            <span className={styles.meterLabel}>Budget utilised</span>
            <span className={styles.meterPct}>{budgetPct}%</span>
          </div>
          <span className={styles.meterTrack} aria-hidden="true">
            <span
              className={cn(styles.meterFill, overBudget && styles.meterFillOver)}
              style={{ width: `${Math.min(100, Math.max(2, budgetPct))}%` }}
            />
          </span>
          <span className={styles.meterCaption}>
            {formatNaira(budget.utilized_value)} of {formatNaira(budget.allocated)} ·{' '}
            {overBudget ? `over by ${formatNaira(Math.abs(budget.remaining))}` : `${formatNaira(budget.remaining)} remaining`}
          </span>
        </div>
      </div>

      {/* Funder KPI trio (3-up — distinct from the MDA's 4-up row). */}
      <div className={styles.kpiRow}>
        <StatCard icon={Users} label="Beneficiaries served" value={m.registry.beneficiaries.total.toLocaleString()} hint={data.scope.label} tone="forest" />
        <StatCard icon={ClipboardList} label="Funded programmes" value={m.programmes.active.toLocaleString()} hint={`${m.programmes.total} funded`} tone="info" />
        <StatCard icon={PackageCheck} label="Deliveries" value={m.benefits.disbursed.benefit_count.toLocaleString()} hint="records in ledger" tone="success" />
      </div>

      {/* Evidence: reach (ranked LGAs) + delivery mix (share by type). */}
      <div className={styles.partnerLower}>
        <Card eyebrow="Reach" title="Where your funding reached">
          {reach.length === 0 ? (
            <p className={styles.empty}>No coverage data yet.</p>
          ) : (
            <div className={styles.bars}>
              {reach.map((r) => (
                <div key={r.lga} className={styles.barRow} title={`${titleCase(r.lga)}: ${formatNaira(r.benefit_value)}`}>
                  <span className={styles.barLabel} title={titleCase(r.lga)}>{titleCase(r.lga)}</span>
                  <span className={styles.barTrack} aria-hidden="true">
                    <span className={styles.barFill} style={{ width: `${Math.max(2, Math.round((r.benefit_value / maxReach) * 100))}%` }} />
                  </span>
                  <span className={styles.barValue}>{formatNaira(r.benefit_value)}</span>
                </div>
              ))}
            </div>
          )}
        </Card>

        <Card eyebrow="Mix" title="Delivery by type">
          {mix.length === 0 ? (
            <p className={styles.empty}>No benefits delivered yet.</p>
          ) : (
            <div className={styles.bars}>
              {mix.map((s) => {
                const share = totalValue > 0 ? Math.round((s.value / totalValue) * 100) : 0
                return (
                  <div key={s.label} className={styles.barRow} title={`${s.label}: ${share}% · ${formatNaira(s.value)}`}>
                    <span className={styles.barLabel} title={s.label}>{s.label}</span>
                    <span className={styles.barTrack} aria-hidden="true">
                      <span className={styles.barFill} style={{ width: `${Math.max(2, share)}%` }} />
                    </span>
                    <span className={styles.barValue}>{share}% · {formatNaira(s.value)}</span>
                  </div>
                )
              })}
            </div>
          )}
        </Card>
      </div>
    </div>
  )
}
