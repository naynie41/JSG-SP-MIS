import { describe, expect, it } from 'vitest'
import { classifyQuadrant, densityBand, median } from './investment'

describe('densityBand (funding-density banding)', () => {
  it('is grey when there is no value or no max', () => {
    expect(densityBand(0, 100)).toBe('grey')
    expect(densityBand(-5, 100)).toBe('grey')
    expect(densityBand(50, 0)).toBe('grey')
  })

  it('bands by tertiles of the max (green ≥ 2/3, yellow ≥ 1/3, else red)', () => {
    expect(densityBand(100, 100)).toBe('green')
    expect(densityBand(70, 100)).toBe('green')
    expect(densityBand(66, 100)).toBe('yellow') // just under 2/3
    expect(densityBand(50, 100)).toBe('yellow')
    expect(densityBand(34, 100)).toBe('yellow')
    expect(densityBand(30, 100)).toBe('red')
    expect(densityBand(1, 100)).toBe('red')
  })
})

describe('median (funding high/low split)', () => {
  it('ignores non-positive values', () => {
    expect(median([0, 0, 0])).toBe(0)
    expect(median([0, 10, 0, 20, 30])).toBe(20) // positives [10,20,30]
  })

  it('handles odd and even counts', () => {
    expect(median([10])).toBe(10)
    expect(median([10, 30])).toBe(20)
    expect(median([10, 20, 30])).toBe(20)
    expect(median([50_000, 100_000, 4_000_000, 5_000_000])).toBe(2_050_000)
  })
})

describe('classifyQuadrant (coverage vs funding)', () => {
  const mid = 1000 // funding midpoint
  const cov = 250 // absolute coverage threshold

  it('high funding + high coverage → strong', () => {
    expect(classifyQuadrant(2000, 500, mid, cov)).toBe('strong')
  })

  it('high funding + low coverage → review (possible implementation problem)', () => {
    expect(classifyQuadrant(2000, 100, mid, cov)).toBe('review')
  })

  it('low funding + high coverage → efficient', () => {
    expect(classifyQuadrant(500, 500, mid, cov)).toBe('efficient')
  })

  it('low funding + low coverage → emerging', () => {
    expect(classifyQuadrant(100, 50, mid, cov)).toBe('emerging')
  })

  it('treats zero funding/coverage as low even when the midpoint is 0', () => {
    expect(classifyQuadrant(0, 500, 0, cov)).toBe('efficient') // funding 0 is never "high"
    expect(classifyQuadrant(0, 0, 0, cov)).toBe('emerging')
    expect(classifyQuadrant(500, 0, 0, cov)).toBe('review') // funded, nobody reached
  })
})
