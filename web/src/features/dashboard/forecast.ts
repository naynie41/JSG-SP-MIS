import type { DashboardMetrics, TrendPoint } from './types'

/**
 * Simple, transparent trend projections for the executive suite — SIMPLE/LINEAR maths,
 * NOT machine learning. Every projection is clearly labelled and states its assumption,
 * so nobody mistakes a straight-line extrapolation for a forecast model.
 *
 *  - budget runway  : exhaustion date from the recent monthly burn rate;
 *  - beneficiaries  : least-squares trend on cumulative reach, projected forward;
 *  - registrations  : least-squares trend on monthly new registrations.
 */

export const PROJECTION_LABEL = 'Projection · based on current trend'

/** Least-squares slope + intercept over evenly-spaced points (x = 0,1,2,…). */
export function linearFit(values: number[]): { slope: number; intercept: number } {
  const n = values.length
  if (n === 0) return { slope: 0, intercept: 0 }
  if (n === 1) return { slope: 0, intercept: values[0] }
  const meanX = (n - 1) / 2
  const meanY = values.reduce((s, v) => s + v, 0) / n
  let num = 0
  let den = 0
  for (let i = 0; i < n; i++) {
    num += (i - meanX) * (values[i] - meanY)
    den += (i - meanX) ** 2
  }
  const slope = den === 0 ? 0 : num / den
  return { slope, intercept: meanY - slope * meanX }
}

/** Next `ahead` 'YYYY-MM' labels after the given month. */
export function nextMonths(lastMonth: string, ahead: number): string[] {
  const [y, m] = lastMonth.split('-').map(Number)
  const out: string[] = []
  let year = y
  let month = m
  for (let i = 0; i < ahead; i++) {
    month += 1
    if (month > 12) {
      month = 1
      year += 1
    }
    out.push(`${year}-${String(month).padStart(2, '0')}`)
  }
  return out
}

export function monthLong(ym: string): string {
  const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
  const [y, m] = ym.split('-')
  return `${MONTHS[(Number(m) || 1) - 1] ?? ''} ${y}`.trim()
}

export interface SeriesProjection {
  monthlyRate: number // slope (per month)
  projected: TrendPoint[] // future points (extrapolated)
  endValue: number // projected value at the horizon
  horizonMonths: number
  assumption: string
}

/** Project a monthly series forward with a straight-line (least-squares) trend. */
export function projectSeries(history: TrendPoint[], horizonMonths: number): SeriesProjection | null {
  const points = history.filter((p) => Number.isFinite(p.value))
  if (points.length < 2) return null

  const { slope, intercept } = linearFit(points.map((p) => p.value))
  const n = points.length
  const labels = nextMonths(points[n - 1].month, horizonMonths)
  const projected = labels.map((month, k) => ({
    month,
    value: Math.max(0, Math.round(intercept + slope * (n + k))),
  }))

  return {
    monthlyRate: Math.round(slope),
    projected,
    endValue: projected.length > 0 ? projected[projected.length - 1].value : points[n - 1].value,
    horizonMonths,
    assumption: `Assumes the last ${n}-month trend (${slope >= 0 ? '+' : ''}${Math.round(slope).toLocaleString()}/month) continues.`,
  }
}

export type BudgetStatus = 'on-track' | 'exhausting' | 'over' | 'idle'

export interface BudgetProjection {
  monthlyBurn: number // kobo/month
  monthsRemaining: number | null // null when it can't be projected
  exhaustionMonth: string | null // 'YYYY-MM'
  status: BudgetStatus
  assumption: string
}

/** Budget-exhaustion date from the average monthly burn of the last (up to 3) active months. */
export function budgetRunway(allocated: number, spent: number, disbursement: TrendPoint[]): BudgetProjection {
  const active = disbursement.filter((p) => p.value > 0)
  const recent = active.slice(-3)
  const burn = recent.length > 0 ? Math.round(recent.reduce((s, p) => s + p.value, 0) / recent.length) : 0
  const remaining = allocated - spent

  if (remaining <= 0) {
    return { monthlyBurn: burn, monthsRemaining: 0, exhaustionMonth: null, status: 'over', assumption: 'The allocation is already fully committed.' }
  }
  if (burn <= 0) {
    return { monthlyBurn: 0, monthsRemaining: null, exhaustionMonth: null, status: 'idle', assumption: 'No recent disbursement, so no burn rate to project from.' }
  }

  const monthsRemaining = remaining / burn
  const last = disbursement.length > 0 ? disbursement[disbursement.length - 1].month : `${new Date().getFullYear()}-${String(new Date().getMonth() + 1).padStart(2, '0')}`
  const exhaustionMonth = nextMonths(last, Math.max(1, Math.ceil(monthsRemaining))).at(-1) ?? null

  return {
    monthlyBurn: burn,
    monthsRemaining: Math.round(monthsRemaining * 10) / 10,
    exhaustionMonth,
    status: monthsRemaining < 6 ? 'exhausting' : 'on-track',
    assumption: `Assumes disbursement continues at the recent average of ${recent.length} month${recent.length === 1 ? '' : 's'}.`,
  }
}

export interface ExecutiveForecast {
  beneficiaries: SeriesProjection | null
  registrations: SeriesProjection | null
  budget: BudgetProjection | null
}

/** Build all executive projections from the (filtered) metrics; null when data is thin. */
export function buildForecast(metrics: DashboardMetrics, horizonMonths = 6): ExecutiveForecast {
  const trends = metrics.trends
  const budget = metrics.benefits.budget
  return {
    beneficiaries: trends ? projectSeries(trends.beneficiaries_cumulative, horizonMonths) : null,
    registrations: trends ? projectSeries(trends.registrations, horizonMonths) : null,
    budget: trends && budget.allocated > 0 ? budgetRunway(budget.allocated, budget.utilized_value, trends.disbursement) : null,
  }
}
