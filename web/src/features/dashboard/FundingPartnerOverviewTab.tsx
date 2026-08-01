import {
  AlertTriangle,
  ArrowRight,
  Baby,
  Building2,
  CheckCircle2,
  ClipboardList,
  Coins,
  GitBranch,
  HandCoins,
  House,
  Info,
  Layers,
  Lock,
  Map,
  MapPin,
  PackageCheck,
  Percent,
  Target,
  UserRound,
  Users,
  Wallet,
} from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { formatNaira } from '@/lib/utils/money'
import type { DashboardResponse, DrillFn, PartnerFunding } from './types'
import styles from './fundingPartner.module.css'

/* ------------------------------------------------------------------ helpers */

const pct = (rate: number | null | undefined): number => Math.round((rate ?? 0) * 100)
const num = (n: number | null | undefined): string => (n ?? 0).toLocaleString()

/* --------------------------------------------------------------- alert rules */

type Severity = 'warning' | 'info'
interface PartnerAlert {
  id: string
  severity: Severity
  title: string
  detail: string
}

/** Funded-scope, rule-based alerts (labelled on DELIVERY value, not expenditure). */
function buildAlerts(pf: PartnerFunding): PartnerAlert[] {
  const out: PartnerAlert[] = []

  if (pf.utilization_rate !== null && pf.utilization_rate >= 0.9) {
    out.push({
      id: 'budget',
      severity: 'warning',
      title: 'Committed funding nearly delivered',
      detail: `${pct(pf.utilization_rate)}% of the allocation delivered · ${formatNaira(pf.remaining)} remaining.`,
    })
  }
  if (pf.reach_vs_target !== null && pf.target > 0 && pf.reach_vs_target < 0.5) {
    out.push({
      id: 'reach',
      severity: 'warning',
      title: 'Reach below target',
      detail: `${num(pf.net_unique_reached)} of ${num(pf.target)} beneficiaries (${pct(pf.reach_vs_target)}% of target).`,
    })
  }
  const red = pf.coverage_bands.summary.red
  if (red > 0) {
    out.push({
      id: 'coverage',
      severity: 'info',
      title: `${num(red)} low-coverage ${red === 1 ? 'LGA' : 'LGAs'}`,
      detail: `Below the target coverage band (under ${num(pf.coverage_bands.thresholds.yellow_min)} beneficiaries).`,
    })
  }
  return out
}

/* --------------------------------------------------------------- components */

function KpiPanel({ icon, label, value, hint, headline, onClick }: { icon: LucideIcon; label: string; value: string; hint?: string; headline?: boolean; onClick?: () => void }) {
  const className = headline ? `${styles.kpi} ${styles.kpiHeadline}` : styles.kpi
  const body = (
    <>
      <span className={styles.kpiLabel}>
        <Icon icon={icon} size={14} />
        {label}
      </span>
      <span className={styles.kpiValue}>{value}</span>
      {hint && <span className={styles.kpiHint}>{hint}</span>}
    </>
  )
  if (onClick) {
    return (
      <button type="button" className={`${className} ${styles.drillable}`} onClick={onClick}>
        {body}
      </button>
    )
  }
  return <div className={className}>{body}</div>
}

function Figure({ icon, label, value, hint, onClick }: { icon: LucideIcon; label: string; value: string; hint?: string; onClick?: () => void }) {
  const body = (
    <>
      <span className={styles.figureLabel}>
        <Icon icon={icon} size={14} />
        {label}
      </span>
      <span className={styles.figureValue}>{value}</span>
      {hint && <span className={styles.figureHint}>{hint}</span>}
    </>
  )
  if (onClick) {
    return (
      <button type="button" className={`${styles.figure} ${styles.drillable}`} onClick={onClick}>
        {body}
      </button>
    )
  }
  return <div className={styles.figure}>{body}</div>
}

function ReachStat({ icon, label, value, hint }: { icon: LucideIcon; label: string; value: string; hint?: string }) {
  return (
    <div className={styles.reachStat}>
      <Icon icon={icon} size={18} />
      <span className={styles.reachValue}>{value}</span>
      <span className={styles.reachLabel}>{label}</span>
      {hint && <span className={styles.reachHint}>{hint}</span>}
    </div>
  )
}

export interface FundingPartnerOverviewTabProps {
  data: DashboardResponse
  /** Jump to a detail tab (drill-down); the shell applies any scoped filter patch. */
  onDrill?: DrillFn
}

/**
 * Funding Partner Overview (Phase 6P, tab 1) — pure tab content under the partner shell
 * (which owns the hero + Refresh). A funder reads money-first: the lifecycle leads with
 * DELIVERED VALUE against committed funding — deliberately labelled "delivery value",
 * never treasury expenditure (SP-MIS records value as data; it never moves money).
 * Everything is the partner's FUNDED scope only, read-only, aggregate — no raw PII.
 * Only captured demographics show; outcomes/impact are an external slot.
 */
export function FundingPartnerOverviewTab({ data, onDrill }: FundingPartnerOverviewTabProps) {
  const pf = data.metrics.partner_funding

  if (!pf) {
    return (
      <p className={styles.empty}>No funded activities are attributed to you yet. Once an MDA attributes an activity to your funding, its budget, delivery and reach appear here.</p>
    )
  }

  const alerts = buildAlerts(pf)
  const reachPctOfBudget = pf.allocated > 0 ? Math.min(100, Math.round((pf.delivered_value / pf.allocated) * 100)) : 0
  const reachPctOfTarget = pf.target > 0 ? Math.min(100, Math.round((pf.net_unique_reached / pf.target) * 100)) : 0

  return (
    <div className={styles.tabBody}>
      {/* ---------- KPI BAND ---------- */}
      <section className={styles.reveal} aria-label="Funded-scope indicators">
        <span className={styles.groupLabel}>Funding</span>
        <div className={styles.kpiBand}>
          <KpiPanel headline icon={PackageCheck} label="Value delivered" value={formatNaira(pf.delivered_value)} hint="benefits delivered under funded activities" />
          <KpiPanel icon={Wallet} label="Allocated" value={formatNaira(pf.allocated)} hint="committed funding" />
          <KpiPanel icon={HandCoins} label="Remaining" value={formatNaira(pf.remaining)} />
          <KpiPanel icon={Users} label="Net-unique reached" value={num(pf.net_unique_reached)} hint="distinct persons served" onClick={onDrill ? () => onDrill('registry') : undefined} />
        </div>

        <span className={styles.groupLabel}>Portfolio</span>
        <div className={styles.figureGrid}>
          <Figure icon={Percent} label="Utilisation" value={`${pct(pf.utilization_rate)}%`} hint="delivered ÷ allocated" />
          <Figure icon={ClipboardList} label="Funded programmes" value={num(pf.funded_programmes)} onClick={onDrill ? () => onDrill('programmes') : undefined} />
          <Figure icon={Building2} label="Implementing MDAs" value={num(pf.implementing_mdas)} />
          <Figure icon={Layers} label="Active activities" value={num(pf.active_activities)} hint={`of ${num(pf.funded_activities)} funded`} />
          <Figure icon={Target} label="Target beneficiaries" value={num(pf.target)} />
          <Figure icon={Coins} label="Value / beneficiary" value={pf.cost_per_beneficiary === null ? '—' : formatNaira(pf.cost_per_beneficiary)} />
          <Figure icon={MapPin} label="LGAs covered" value={num(pf.lgas_covered)} />
          <Figure icon={Map} label="Wards covered" value={num(pf.wards_covered)} />
        </div>
      </section>

      {/* ---------- FUNDING LIFECYCLE ---------- */}
      <section className={`${styles.section} ${styles.reveal}`} aria-label="Funding lifecycle">
        <div className={styles.sectionHead}>
          <Icon icon={GitBranch} size={16} />
          <h2 className={styles.sectionTitle}>Funding lifecycle</h2>
        </div>
        <div className={styles.panel}>
          <div className={styles.lifecycle}>
            <div className={styles.stage}>
              <span className={styles.stageLabel}>Allocated</span>
              <span className={styles.stageValue}>{formatNaira(pf.allocated)}</span>
              <span className={styles.stageNote}>committed funding</span>
            </div>
            <Icon icon={ArrowRight} size={20} className={styles.stageArrow} />
            <div className={styles.stage} data-emphasis="true">
              <span className={styles.stageLabel}>Delivered</span>
              <span className={styles.stageValue}>{formatNaira(pf.delivered_value)}</span>
              <span className={styles.stageNote}>value of benefits delivered</span>
            </div>
            <Icon icon={ArrowRight} size={20} className={styles.stageArrow} />
            <div className={styles.stage}>
              <span className={styles.stageLabel}>Remaining</span>
              <span className={styles.stageValue}>{formatNaira(pf.remaining)}</span>
              <span className={styles.stageNote}>not yet delivered</span>
            </div>
          </div>
          <div className={styles.lifecycleBar}>
            <span className={styles.lifecycleDelivered} style={{ width: `${reachPctOfBudget}%` }} />
          </div>
          <p className={styles.deliveryNote}>
            <strong>Delivery value</strong>, not treasury expenditure — the recorded value of benefits delivered under funded activities (programme data). SP-MIS records value; it does not move money.
          </p>
        </div>
      </section>

      {/* ---------- RESULTS & REACH ---------- */}
      <section className={`${styles.section} ${styles.reveal}`} aria-label="Results and reach">
        <div className={styles.sectionHead}>
          <Icon icon={Users} size={16} />
          <h2 className={styles.sectionTitle}>Results &amp; reach</h2>
        </div>
        <div className={styles.reachGrid}>
          <div className={styles.panel}>
            <div className={styles.reachTop}>
              <span className={styles.reachHeadValue}>
                {num(pf.net_unique_reached)} <span className={styles.reachOf}>of {num(pf.target)}</span>
              </span>
              <span className={styles.reachHeadLabel}>Beneficiaries reached vs target</span>
            </div>
            <div className={styles.progressTrack}>
              <span className={styles.progressFill} style={{ width: `${Math.max(2, reachPctOfTarget)}%` }} />
            </div>
            <span className={styles.progressPct}>{pct(pf.reach_vs_target)}% of target · programme completion</span>
          </div>
          <div className={styles.panel}>
            <div className={styles.reachStats}>
              <ReachStat icon={House} label="Households reached" value={num(pf.reach.households_reached)} />
              <ReachStat icon={UserRound} label="Women reached" value={num(pf.reach.women_reached)} hint="recorded female" />
              <ReachStat icon={Baby} label="Children reached" value={num(pf.reach.children_reached)} hint="0–17" />
            </div>
          </div>
        </div>
      </section>

      {/* ---------- RESULTS FRAMEWORK SNAPSHOT ---------- */}
      <section className={`${styles.section} ${styles.reveal}`} aria-label="Results framework">
        <div className={styles.sectionHead}>
          <Icon icon={GitBranch} size={16} />
          <h2 className={styles.sectionTitle}>Results framework</h2>
          <span className={styles.sectionSub}>Full detail in Programmes &amp; Results</span>
        </div>
        <div className={styles.framework}>
          <div className={styles.frameNode}>
            <span className={styles.frameLabel}>Funding</span>
            <span className={styles.frameValue}>{formatNaira(pf.allocated)}</span>
            <span className={styles.frameNote}>allocated</span>
          </div>
          <Icon icon={ArrowRight} size={18} className={styles.frameArrow} />
          <div className={styles.frameNode}>
            <span className={styles.frameLabel}>Activities</span>
            <span className={styles.frameValue}>{num(pf.active_activities)}</span>
            <span className={styles.frameNote}>active of {num(pf.funded_activities)}</span>
          </div>
          <Icon icon={ArrowRight} size={18} className={styles.frameArrow} />
          <div className={styles.frameNode}>
            <span className={styles.frameLabel}>Outputs</span>
            <span className={styles.frameValue}>{num(pf.net_unique_reached)}</span>
            <span className={styles.frameNote}>reached · {formatNaira(pf.delivered_value)} delivered</span>
          </div>
          <Icon icon={ArrowRight} size={18} className={styles.frameArrow} />
          <div className={`${styles.frameNode} ${styles.frameExternal}`} aria-label="Outcomes and impact — tracked externally">
            <span className={styles.frameLabel}>
              <Icon icon={Lock} size={12} /> Outcomes → Impact
            </span>
            <span className={styles.frameExternalNote}>Tracked externally · not computed here</span>
          </div>
        </div>
      </section>

      {/* ---------- ALERTS ---------- */}
      <section className={`${styles.section} ${styles.reveal}`} aria-label="Alerts">
        <div className={styles.sectionHead}>
          <Icon icon={AlertTriangle} size={16} />
          <h2 className={styles.sectionTitle}>Alerts</h2>
        </div>
        <div className={styles.panel}>
          {alerts.length === 0 ? (
            <p className={styles.allClear}>
              <Icon icon={CheckCircle2} size={16} /> No alerts — funding delivery and reach are within range.
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
                </li>
              ))}
            </ul>
          )}
        </div>
      </section>
    </div>
  )
}
