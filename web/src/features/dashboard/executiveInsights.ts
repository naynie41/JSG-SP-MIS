import { formatNaira } from '@/lib/utils/money'
import { titleCase } from '@/features/registry/constants'
import type { DashboardMetrics, ProgrammePerformance } from './types'

/**
 * Rule-based (NOT AI) executive insights + alerts for the Overview tab. These are
 * pure functions over the de-identified aggregation metrics: given the same
 * snapshot they always produce the same statements, in a stable, testable order.
 * No raw PII ever enters here — only counts, rates and money totals.
 */

export type InsightTone = 'positive' | 'neutral' | 'attention'

export interface Insight {
  id: string
  tone: InsightTone
  text: string
}

export type AlertSeverity = 'critical' | 'warning' | 'info'

export interface Alert {
  id: string
  severity: AlertSeverity
  title: string
  detail: string
  /**
   * The executive section that explains this alert, for the drill-through.
   *
   * An alert an executive cannot act on is decoration. Naming the owning tab is what
   * turns "5 referrals are overdue" from a fact into a next step, and it is why these
   * rules live here rather than in the panel: the rule knows which page answers it.
   */
  tab?: 'programmes' | 'registry' | 'coordination' | 'coverage'
}

const pct = (rate: number | null | undefined): number => Math.round((rate ?? 0) * 100)
const num = (n: number): string => n.toLocaleString()
const programmeName = (p: ProgrammePerformance): string => (p.name ? titleCase(p.name) : 'A programme')

/** How exhausted a budget must be (utilisation) before we flag it. */
const BUDGET_WARN = 0.9
/** Only surface a "top programme" insight when it has actually cleared this bar. */
const STRONG_COMPLETION = 0.5

/**
 * Auto-generated briefing statements from the aggregates. Ordered headline-first so
 * the governor reads reach, then delivery, then the standout programme, then equity
 * and coverage. Each rule guards its own inputs and is skipped when data is absent.
 */
export function buildInsights(m: DashboardMetrics): Insight[] {
  const out: Insight[] = []
  const pop = m.population
  const perf = m.programme_performance ?? []
  const bands = m.coverage_bands
  const demo = m.demographics
  const budget = m.benefits.budget

  // 1 — Headline reach (net-unique is the figure, never gross deliveries).
  if (pop) {
    out.push({
      id: 'reach',
      tone: 'neutral',
      text: `${num(pop.net_unique_served)} people have been reached across ${num(
        m.programmes.active,
      )} active programmes and ${num(pop.lgas_covered)} LGAs.`,
    })
  }

  // 2 — Budget disbursement.
  if (budget.allocated > 0) {
    out.push({
      id: 'budget',
      tone: budget.remaining < 0 ? 'attention' : 'neutral',
      text: `${pct(budget.utilization_rate)}% of the allocated budget has been disbursed (${formatNaira(
        budget.utilized_value,
      )} of ${formatNaira(budget.allocated)}).`,
    })
  }

  // 3 — Standout programme (highest completion among rated programmes).
  const rated = perf.filter((p) => p.completion_rate !== null)
  const top = rated.slice().sort((a, b) => (b.completion_rate ?? 0) - (a.completion_rate ?? 0))[0]
  if (top && (top.completion_rate ?? 0) >= STRONG_COMPLETION) {
    out.push({
      id: 'top-programme',
      tone: top.traffic_light === 'green' ? 'positive' : 'neutral',
      text: `${programmeName(top)} reached ${pct(top.completion_rate)}% of its target (${num(top.reached)} of ${num(
        top.target,
      )}).`,
    })
  }

  // 4 — Female participation (equity), over recorded genders only.
  if (demo && demo.female_pct !== null) {
    out.push({
      id: 'female',
      tone: 'neutral',
      text: `Female participation stands at ${pct(demo.female_pct)}% of beneficiaries with a recorded gender.`,
    })
  }

  // 5 — Coverage gaps by absolute band (never a % of population).
  if (bands) {
    const total = bands.summary.green + bands.summary.yellow + bands.summary.red
    if (bands.summary.red > 0) {
      out.push({
        id: 'coverage-gap',
        tone: 'attention',
        text: `${num(bands.summary.red)} of ${num(total)} LGAs are below the target level of coverage (fewer than ${num(
          bands.thresholds.yellow_min,
        )} people reached).`,
      })
    } else if (total > 0) {
      out.push({
        id: 'coverage-ok',
        tone: 'positive',
        text: `All ${num(total)} covered LGAs are at or above the minimum level of coverage.`,
      })
    }
  }

  // 6 — Recent registration momentum.
  if (pop && pop.new_registrations_period > 0) {
    out.push({
      id: 'registrations',
      tone: 'neutral',
      text: `${num(pop.new_registrations_period)} new beneficiaries were registered in the last ${num(
        pop.period_days,
      )} days.`,
    })
  }

  return out
}

/**
 * Operational alerts, ordered by severity. Each is a de-identified, rule-triggered
 * flag: low-performing programmes, near-exhausted budgets, under-covered LGAs,
 * surfaced duplicates and records pending review.
 */
export function buildAlerts(m: DashboardMetrics): Alert[] {
  const out: Alert[] = []
  const perf = m.programme_performance ?? []
  const bands = m.coverage_bands
  const rq = m.registry_quality
  const budget = m.benefits.budget

  // Low-performing programmes (red traffic-light) — one per programme, capped.
  perf
    .filter((p) => p.traffic_light === 'red')
    .slice(0, 4)
    .forEach((p) => {
      const critical = (p.completion_rate ?? 0) < 0.25
      out.push({
        id: `low-${p.programme_id}`,
        severity: critical ? 'critical' : 'warning',
        title: `Low delivery: ${programmeName(p)}`,
        detail: `Reached ${pct(p.completion_rate)}% of target (${num(p.reached)} of ${num(p.target)}).`,
      })
    })

  // Budgets nearly exhausted / overspent.
  if (budget.allocated > 0 && budget.remaining < 0) {
    out.push({
      id: 'budget-over',
      severity: 'critical',
      title: 'Budget exceeded',
      detail: `Disbursement has passed the allocation by ${formatNaira(Math.abs(budget.remaining))}.`,
    })
  }
  perf
    .filter((p) => p.budget.allocated > 0 && (p.budget.utilization_rate ?? 0) >= BUDGET_WARN && p.budget.remaining >= 0)
    .slice(0, 3)
    .forEach((p) => {
      out.push({
        id: `budget-${p.programme_id}`,
        severity: 'warning',
        title: `Budget nearly exhausted: ${programmeName(p)}`,
        detail: `${pct(p.budget.utilization_rate)}% of budget used, ${formatNaira(p.budget.remaining)} remaining.`,
      })
    })

  // LGAs below the target coverage band.
  if (bands && bands.summary.red > 0) {
    out.push({
      id: 'coverage-red',
      severity: 'warning',
      title: `${num(bands.summary.red)} LGAs below target coverage`,
      detail: `Fewer than ${num(bands.thresholds.yellow_min)} beneficiaries reached in each.`,
    })
  }

  // Potential duplicates surfaced during import.
  if (rq && rq.duplicates_detected > 0) {
    out.push({
      id: 'duplicates',
      severity: 'info',
      title: `${num(rq.duplicates_detected)} possible duplicates found`,
      detail: 'Flagged during import matching and awaiting resolution.',
    })
  }

  // Registry records pending verification.
  if (rq && rq.pending > 0) {
    out.push({
      id: 'pending',
      severity: 'warning',
      title: `${num(rq.pending)} records pending verification`,
      detail: 'Beneficiaries flagged for review before they can be served.',
    })
  }

  /*
   * Everything below was visible only to someone who opened the tab that owned it.
   * On a screen built for a reader with sixty seconds, a problem nobody scrolls to is
   * a problem nobody knows about — these are the facts an executive would most want
   * to be told without asking.
   */

  // Referrals past their SLA. A referral is one agency asking another to serve a
  // person: an overdue one is a specific person waiting, not a slipped metric.
  const ref = m.referrals
  if (ref && ref.overdue > 0) {
    const share = ref.total > 0 ? ref.overdue / ref.total : 0
    out.push({
      id: 'referrals-overdue',
      severity: share >= 0.5 ? 'critical' : 'warning',
      title: `${num(ref.overdue)} referral${ref.overdue === 1 ? '' : 's'} past the response time`,
      detail: `Of ${num(ref.total)} raised. Each one is a person an agency accepted and has not yet served.`,
      tab: 'coordination',
    })
  }

  // Grievances past their SLA — the complaint route the public is told to use.
  const gr = m.grievances
  if (gr && gr.sla_breaches > 0) {
    out.push({
      id: 'grievance-sla',
      severity: 'warning',
      title: `${num(gr.sla_breaches)} grievance${gr.sla_breaches === 1 ? '' : 's'} past the response time`,
      detail: `Of ${num(gr.total)} raised, averaging ${gr.avg_resolution_days ?? 0} days to resolve.`,
      tab: 'coordination',
    })
  }

  // Coverage: how much of the state is under-served. Reported as a share rather than
  // a count, because "27 areas" means nothing without knowing there are 27.
  if (bands?.summary) {
    const { green = 0, yellow = 0, red = 0 } = bands.summary
    const total = green + yellow + red
    if (total > 0 && red === total) {
      out.push({
        id: 'coverage-all-red',
        severity: 'critical',
        title: `Every local government area is below the coverage threshold`,
        detail: `All ${num(total)} areas fall in the lowest band. None has reached the level the threshold is set at.`,
        tab: 'coverage',
      })
    } else if (total > 0 && red / total >= 0.5) {
      out.push({
        id: 'coverage-red',
        severity: 'warning',
        title: `${num(red)} of ${num(total)} areas are below the coverage threshold`,
        detail: 'Delivery is concentrated in a minority of the state.',
        tab: 'coverage',
      })
    }
  }

  // A budget barely touched is as much a finding as one overspent — it usually means
  // the money was committed but the delivery never started.
  if (budget.allocated > 0 && (budget.utilization_rate ?? 0) < 0.25) {
    out.push({
      id: 'budget-idle',
      severity: 'warning',
      title: `Only ${pct(budget.utilization_rate)}% of the allocated budget has been delivered`,
      detail: `${formatNaira(budget.remaining)} of ${formatNaira(budget.allocated)} is still unspent.`,
      tab: 'programmes',
    })
  }

  // Programmes catalogued but never turned into activity. A programme with no
  // activity delivers to nobody, however good it looks in the catalogue.
  const prog = m.programmes
  if (prog && prog.active > 0 && prog.activities_active === 0) {
    out.push({
      id: 'no-activities',
      severity: 'critical',
      title: 'No activity is currently running',
      detail: `${num(prog.active)} programmes are active in the catalogue, but none has a running activity.`,
      tab: 'programmes',
    })
  }

  const rank: Record<AlertSeverity, number> = { critical: 0, warning: 1, info: 2 }
  return out.sort((a, b) => rank[a.severity] - rank[b.severity])
}
