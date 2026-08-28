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
      text: `${num(pop.net_unique_served)} net-unique beneficiaries have been reached across ${num(
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
        text: `${num(bands.summary.red)} of ${num(total)} LGAs are below the target coverage band (under ${num(
          bands.thresholds.yellow_min,
        )} beneficiaries).`,
      })
    } else if (total > 0) {
      out.push({
        id: 'coverage-ok',
        tone: 'positive',
        text: `All ${num(total)} covered LGAs are at or above the minimum coverage band.`,
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
        detail: `${pct(p.budget.utilization_rate)}% utilised, ${formatNaira(p.budget.remaining)} remaining.`,
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
      title: `${num(rq.duplicates_detected)} potential duplicates surfaced`,
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

  const rank: Record<AlertSeverity, number> = { critical: 0, warning: 1, info: 2 }
  return out.sort((a, b) => rank[a.severity] - rank[b.severity])
}
