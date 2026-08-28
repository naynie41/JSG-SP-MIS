import type { CoverageBand, CoverageRow } from './types'

/**
 * Investment/coverage-map logic (Phase 6P). Pure, framework-free helpers: the toggleable
 * data LAYERS we HAVE, the relative funding-DENSITY banding, and the coverage-vs-funding
 * QUADRANT classification (funding = budget; coverage = ABSOLUTE beneficiaries — never a
 * population %). Kept separate from React so it can be unit-tested directly.
 */

/** A selectable base-layer metric — the "layers we have" for the choropleth/table. */
export interface InvestmentMetric {
  id: string
  label: string
  /** Value accessor on a coverage row. */
  value: (row: Pick<CoverageRow, 'funding_allocated' | 'beneficiary_count' | 'served' | 'active_programmes'>) => number
  /** How to format the value (money vs a plain count). */
  kind: 'money' | 'count'
  hint: string
}

/** The four layers we hold data for. Registered as the map's toggleable base layers;
 * poverty / vulnerability layers are intentionally absent (no data) — inert slots only. */
export const INVESTMENT_METRICS: InvestmentMetric[] = [
  { id: 'funding', label: 'Funding distribution', value: (r) => r.funding_allocated, kind: 'money', hint: 'attributed activity budget' },
  { id: 'beneficiaries', label: 'Beneficiary concentration', value: (r) => r.beneficiary_count, kind: 'count', hint: 'registered individuals (absolute)' },
  { id: 'coverage', label: 'Programme coverage', value: (r) => r.served, kind: 'count', hint: 'net-unique served (absolute)' },
  { id: 'programmes', label: 'Funded programmes', value: (r) => r.active_programmes, kind: 'count', hint: 'active funded programmes' },
]

/** Layers we deliberately DON'T offer (no data) — rendered as disabled slots. */
export const OMITTED_LAYERS: { id: string; label: string; reason: string }[] = [
  { id: 'poverty', label: 'Poverty rate', reason: 'no poverty register / denominator' },
  { id: 'vulnerability', label: 'Vulnerability', reason: 'no vulnerability field captured' },
]

/**
 * Relative density band for a value against the areas' max: grey = none, then red/yellow/
 * green tertiles. Density is comparative across the funded areas (high/moderate/low), not
 * a percentage of any external denominator.
 */
export function densityBand(value: number, max: number): CoverageBand {
  if (value <= 0 || max <= 0) return 'grey'
  const ratio = value / max
  if (ratio >= 2 / 3) return 'green'
  if (ratio >= 1 / 3) return 'yellow'
  return 'red'
}

export type Quadrant = 'strong' | 'review' | 'efficient' | 'emerging'

export interface QuadrantMeta {
  id: Quadrant
  label: string
  detail: string
  tone: 'good' | 'warn' | 'info' | 'muted'
}

/** The four coverage-vs-funding quadrants (high value: exposes implementation gaps). */
export const QUADRANTS: Record<Quadrant, QuadrantMeta> = {
  strong: { id: 'strong', label: 'High funding · High coverage', detail: 'Strong. Investment is reaching people.', tone: 'good' },
  review: { id: 'review', label: 'High funding · Low coverage', detail: 'Review. Funded but few reached, which may point to an implementation problem.', tone: 'warn' },
  efficient: { id: 'efficient', label: 'Low funding · High coverage', detail: 'Efficient. High reach on modest funding.', tone: 'info' },
  emerging: { id: 'emerging', label: 'Low funding · Low coverage', detail: 'Emerging. Low investment and low reach.', tone: 'muted' },
}

export const QUADRANT_ORDER: Quadrant[] = ['strong', 'review', 'efficient', 'emerging']

/**
 * Classify an area by FUNDING (budget) and COVERAGE (absolute served), each split
 * high/low. Coverage uses an ABSOLUTE threshold (the configured yellow band min), never
 * a population %. Funding uses the caller-supplied midpoint (median of funded areas).
 */
export function classifyQuadrant(funding: number, served: number, fundingMidpoint: number, coverageThreshold: number): Quadrant {
  const highFunding = funding > 0 && funding >= fundingMidpoint
  const highCoverage = served > 0 && served >= coverageThreshold
  if (highFunding && highCoverage) return 'strong'
  if (highFunding) return 'review'
  if (highCoverage) return 'efficient'
  return 'emerging'
}

/** Median of the POSITIVE values — the high/low funding split point (0 if none). */
export function median(values: number[]): number {
  const positive = values.filter((v) => v > 0).sort((a, b) => a - b)
  if (positive.length === 0) return 0
  const mid = Math.floor(positive.length / 2)
  return positive.length % 2 === 0 ? (positive[mid - 1]! + positive[mid]!) / 2 : positive[mid]!
}
