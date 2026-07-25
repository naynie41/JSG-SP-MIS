import { describe, expect, it } from 'vitest'
import { buildAlerts, buildInsights } from './executiveInsights'
import { makeExecutivePayload } from './executiveTestData'
import type { DashboardMetrics } from './types'

const metrics = (): DashboardMetrics => makeExecutivePayload().metrics

/** A minimal metrics object with only the always-present (non-executive) keys. */
function bareMetrics(): DashboardMetrics {
  return {
    registry: { beneficiaries: { total: 0, by_status: {}, by_source: {}, by_lga: {} }, households: null },
    programmes: { total: 0, active: 0 },
    duplicates: null,
    benefits: {
      disbursed: { benefit_count: 0, total_value: 0, total_quantity: '0' },
      budget: { allocated: 0, utilized_value: 0, utilized_quantity: '0', benefit_count: 0, remaining: 0, utilization_rate: null },
      by_type: [],
    },
    referrals: null,
    grievances: null,
    coverage: [],
  }
}

describe('buildInsights', () => {
  it('leads with the NET-UNIQUE headline (not the gross delivery count)', () => {
    const out = buildInsights(metrics())
    const reach = out.find((i) => i.id === 'reach')
    expect(reach).toBeDefined()
    expect(reach?.text).toContain('8,420') // net-unique
    expect(reach?.text).not.toContain('11,900') // gross deliveries never surfaced
    expect(reach?.text).toContain('21 LGAs')
  })

  it('names the standout programme by completion, with its percentage', () => {
    const top = buildInsights(metrics()).find((i) => i.id === 'top-programme')
    expect(top?.text).toContain('Cash Transfer')
    expect(top?.text).toContain('90%')
    expect(top?.tone).toBe('positive') // green traffic-light
  })

  it('does not surface a standout programme when none clears the completion bar', () => {
    const m = metrics()
    m.programme_performance = m.programme_performance!.map((p) => ({ ...p, completion_rate: 0.2, traffic_light: 'red' }))
    expect(buildInsights(m).some((i) => i.id === 'top-programme')).toBe(false)
  })

  it('reports female participation over recorded genders', () => {
    const female = buildInsights(metrics()).find((i) => i.id === 'female')
    expect(female?.text).toContain('51%')
  })

  it('flags coverage gaps when LGAs fall in the red band, and clears when none do', () => {
    const gap = buildInsights(metrics()).find((i) => i.id === 'coverage-gap')
    expect(gap?.tone).toBe('attention')
    expect(gap?.text).toContain('4 of 21 LGAs')

    const m = metrics()
    m.coverage_bands = { ...m.coverage_bands!, summary: { green: 20, yellow: 1, red: 0 } }
    const insights = buildInsights(m)
    expect(insights.some((i) => i.id === 'coverage-gap')).toBe(false)
    expect(insights.find((i) => i.id === 'coverage-ok')?.tone).toBe('positive')
  })

  it('never throws on a bare payload (executive keys absent)', () => {
    expect(() => buildInsights(bareMetrics())).not.toThrow()
    expect(buildInsights(bareMetrics())).toEqual([])
  })
})

describe('buildAlerts', () => {
  it('flags a low-performing (red) programme as a warning', () => {
    const low = buildAlerts(metrics()).find((a) => a.id === 'low-p-b')
    expect(low?.severity).toBe('warning')
    expect(low?.title).toContain('School Feeding')
    expect(low?.detail).toContain('40%')
  })

  it('escalates a severely under-delivering programme to critical', () => {
    const m = metrics()
    m.programme_performance = [{ ...m.programme_performance![1], completion_rate: 0.1, reached: 300 }]
    expect(buildAlerts(m).find((a) => a.id === 'low-p-b')?.severity).toBe('critical')
  })

  it('warns when a programme budget is nearly exhausted', () => {
    const budget = buildAlerts(metrics()).find((a) => a.id === 'budget-p-a')
    expect(budget?.severity).toBe('warning')
    expect(budget?.title).toContain('Cash Transfer')
  })

  it('raises a critical alert when the overall budget is exceeded', () => {
    const m = metrics()
    m.benefits.budget = { ...m.benefits.budget, remaining: -5_000_000 }
    const over = buildAlerts(m).find((a) => a.id === 'budget-over')
    expect(over?.severity).toBe('critical')
  })

  it('surfaces duplicates (info) and pending verification (warning)', () => {
    const alerts = buildAlerts(metrics())
    expect(alerts.find((a) => a.id === 'duplicates')?.severity).toBe('info')
    expect(alerts.find((a) => a.id === 'pending')?.severity).toBe('warning')
  })

  it('orders alerts by severity (critical → warning → info)', () => {
    const m = metrics()
    m.benefits.budget = { ...m.benefits.budget, remaining: -1 }
    const severities = buildAlerts(m).map((a) => a.severity)
    const rank = { critical: 0, warning: 1, info: 2 }
    for (let i = 1; i < severities.length; i++) {
      expect(rank[severities[i]]).toBeGreaterThanOrEqual(rank[severities[i - 1]])
    }
  })

  it('never throws on a bare payload and returns no alerts', () => {
    expect(buildAlerts(bareMetrics())).toEqual([])
  })
})
