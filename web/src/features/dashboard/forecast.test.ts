import { describe, expect, it } from 'vitest'
import { budgetRunway, buildForecast, linearFit, nextMonths, projectSeries } from './forecast'
import { makeExecutivePayload } from './executiveTestData'
import type { TrendPoint } from './types'

const series = (values: number[], start = 1): TrendPoint[] =>
  values.map((value, i) => ({ month: `2026-${String(start + i).padStart(2, '0')}`, value }))

describe('linearFit', () => {
  it('recovers the slope + intercept of a straight line', () => {
    const { slope, intercept } = linearFit([10, 20, 30, 40])
    expect(slope).toBeCloseTo(10)
    expect(intercept).toBeCloseTo(10)
  })

  it('is flat for a single point', () => {
    expect(linearFit([42])).toEqual({ slope: 0, intercept: 42 })
  })
})

describe('nextMonths', () => {
  it('rolls over the year boundary', () => {
    expect(nextMonths('2026-11', 3)).toEqual(['2026-12', '2027-01', '2027-02'])
  })
})

describe('projectSeries', () => {
  it('extends a rising series forward at the fitted rate', () => {
    const proj = projectSeries(series([100, 200, 300, 400]), 2)!
    expect(proj.monthlyRate).toBe(100)
    expect(proj.projected.map((p) => p.value)).toEqual([500, 600])
    expect(proj.projected.map((p) => p.month)).toEqual(['2026-05', '2026-06'])
    expect(proj.endValue).toBe(600)
    expect(proj.assumption).toMatch(/trend .* continues/i)
  })

  it('never projects below zero and needs at least two points', () => {
    expect(projectSeries(series([5]), 3)).toBeNull()
    const declining = projectSeries(series([100, 60, 20]), 3)!
    expect(declining.projected.every((p) => p.value >= 0)).toBe(true)
  })
})

describe('budgetRunway', () => {
  it('projects an exhaustion month from the recent burn rate', () => {
    const b = budgetRunway(1_000_000, 700_000, series([100_000, 100_000, 100_000]))
    expect(b.status).toBe('exhausting') // 300k left ÷ 100k/mo = 3 months (< 6)
    expect(b.monthsRemaining).toBe(3)
    expect(b.exhaustionMonth).toBeTruthy()
    expect(b.assumption).toMatch(/recent average/i)
  })

  it('is on-track when the runway is long', () => {
    const b = budgetRunway(1_000_000, 100_000, series([100_000]))
    expect(b.status).toBe('on-track') // 900k ÷ 100k = 9 months
  })

  it('flags an over-committed budget', () => {
    const b = budgetRunway(1_000_000, 1_200_000, series([100_000]))
    expect(b.status).toBe('over')
    expect(b.monthsRemaining).toBe(0)
  })

  it('is idle when there is no recent burn', () => {
    const b = budgetRunway(1_000_000, 0, series([0, 0, 0]))
    expect(b.status).toBe('idle')
    expect(b.monthsRemaining).toBeNull()
  })
})

describe('buildForecast', () => {
  it('produces labelled projections from the metrics', () => {
    const f = buildForecast(makeExecutivePayload().metrics)
    expect(f.beneficiaries?.projected.length).toBe(6)
    expect(f.registrations?.projected.length).toBe(6)
    expect(f.budget).not.toBeNull()
    expect(f.budget?.assumption).toBeTruthy()
  })

  it('degrades to nulls when trends are absent', () => {
    const payload = makeExecutivePayload()
    payload.metrics.trends = undefined
    const f = buildForecast(payload.metrics)
    expect(f.beneficiaries).toBeNull()
    expect(f.budget).toBeNull()
  })
})
