import { AlertTriangle, CheckCircle2, Coins, Info, Lightbulb, MapPin, CalendarPlus, TrendingUp } from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { formatNaira } from '@/lib/utils/money'
import { buildAlerts, buildInsights } from './executiveInsights'
import type { InsightTone } from './executiveInsights'
import { Figure, TrendCard } from './executiveWidgets'
import { num, pct } from './executiveFormat'
import type { DashboardResponse, DrillFn } from './types'
import styles from './executiveOverview.module.css'

const INSIGHT_ICON: Record<InsightTone, LucideIcon> = {
  positive: CheckCircle2,
  neutral: Lightbulb,
  attention: AlertTriangle,
}

export interface ExecutiveOverviewTabProps {
  data: DashboardResponse
  /** Drill-down: jump to a detailed page with a scoped filter. */
  onDrill?: DrillFn
}

/**
 * Executive Overview — the Governor's first screen.
 *
 * It answers one question: *is social protection working across the state, and
 * where is it not?* PRODUCT.md describes this audience as highest-stakes and
 * lowest-frequency — they arrive infrequently and must comprehend without
 * training. Fifty co-equal figures is a page that has to be studied, and an
 * executive who must study a dashboard asks for the PDF instead, which is the
 * manual-report problem this system exists to replace.
 *
 * So this page carries the headline (net-unique reached — the one figure that
 * exists only because of deduplication, and therefore the product's own proof of
 * value; rendered in the shell hero), three figures that qualify it, the
 * interpretive prose, and a single trend. Delivery, programme distribution,
 * projections and demographics moved to the sections that own those questions —
 * nothing was deleted.
 */
export function ExecutiveOverviewTab({ data }: ExecutiveOverviewTabProps) {
  const m = data.metrics
  const pop = m.population
  const budget = m.benefits.budget
  const trends = m.trends

  const insights = buildInsights(m)
  const alerts = buildAlerts(m)

  return (
    <div className={styles.page}>
      {/* Three figures that qualify the headline: is the money moving, does it
          reach the whole state, is it still growing. */}
      <section className={styles.reveal} aria-label="Key indicators">
        <div className={styles.figureGrid} data-cols="3">
          <Figure
            icon={Coins}
            label="Disbursed"
            value={formatNaira(budget.utilized_value)}
            hint={`${pct(budget.utilization_rate)}% of allocated budget`}
          />
          <Figure
            icon={MapPin}
            label="LGAs covered"
            value={num(pop?.lgas_covered)}
            hint="local government areas reached"
          />
          <Figure
            icon={CalendarPlus}
            label="New this period"
            value={num(pop?.new_registrations_period)}
            hint={`last ${num(pop?.period_days)} days`}
          />
        </div>
      </section>

      {/* The interpretive layer. Prose, not figures — and the only part of this
          page an executive can actually act on. */}
      <section className={`${styles.insightGrid} ${styles.reveal}`} style={{ animationDelay: '160ms' }}>
        <div className={styles.panel}>
          <div className={styles.panelHead}>
            <Icon icon={Lightbulb} size={16} />
            <h2 className={styles.panelTitle}>Executive insights</h2>
          </div>
          {insights.length === 0 ? (
            <p className={styles.empty}>Not enough data to summarise yet.</p>
          ) : (
            <ul className={styles.insights}>
              {insights.map((i) => (
                <li key={i.id} className={styles.insight} data-tone={i.tone}>
                  <Icon icon={INSIGHT_ICON[i.tone]} size={16} className={styles.insightIcon} />
                  <span>{i.text}</span>
                </li>
              ))}
            </ul>
          )}
        </div>

        <div className={styles.panel}>
          <div className={styles.panelHead}>
            <Icon icon={AlertTriangle} size={16} />
            <h2 className={styles.panelTitle}>Alerts</h2>
          </div>
          {alerts.length === 0 ? (
            <p className={styles.allClear}>
              <Icon icon={CheckCircle2} size={16} /> No alerts — all indicators within range.
            </p>
          ) : (
            <ul className={styles.alerts}>
              {alerts.map((a) => (
                <li key={a.id} className={styles.alert} data-severity={a.severity}>
                  <Icon icon={a.severity === 'info' ? Info : AlertTriangle} size={16} className={styles.alertIcon} />
                  <span className={styles.alertBody}>
                    <span className={styles.alertTitle}>{a.title}</span>
                    <span className={styles.alertDetail}>{a.detail}</span>
                  </span>
                  <span className={styles.alertBadge} data-severity={a.severity}>
                    {a.severity}
                  </span>
                </li>
              ))}
            </ul>
          )}
        </div>
      </section>

      {/* One trend: the headline's trajectory. The disbursement, registration and
          programme-growth series live with their own sections. */}
      {trends && (
        <section className={styles.reveal} style={{ animationDelay: '240ms' }} aria-label="Trend">
          <div className={styles.sectionHead}>
            <Icon icon={TrendingUp} size={16} />
            <h2 className={styles.sectionTitle}>
              Beneficiaries reached over the last {trends.months.length} months
            </h2>
          </div>
          <div className={styles.trendGrid} data-cols="1">
            <TrendCard title="Beneficiaries reached (cumulative)" points={trends.beneficiaries_cumulative} format={num} />
          </div>
        </section>
      )}
    </div>
  )
}
