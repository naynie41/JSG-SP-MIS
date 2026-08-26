import type { DashboardResponse } from './types'

/**
 * The headline figures shared by the Overview summary and the full reporting dashboard
 * (PRD FR-DSH-01, FR-RPT-03).
 *
 * Derivation lives here, in ONE place, reading the `DashboardResponse` the full page
 * already renders. That is what stops the two from drifting: a summary that recomputed
 * its own totals would eventually disagree with the page it summarises, and the version
 * a person quotes in a meeting would be whichever one they happened to open.
 *
 * Net-unique beneficiaries is the headline, never gross registrations (CLAUDE.md §11).
 */
export interface SummaryTile {
  key: string
  label: string
  /** null when the guard withheld it — render the reason, never a zero. */
  value: number | null
  suppressed: boolean
  /** Which tab of the full dashboard this figure belongs to. */
  hint: string
}

export interface ReportingSummary {
  tiles: SummaryTile[]
  computedAt: string
  scopeLabel: string
  tier: string
  /** The threshold applied, or null on the operational tier where it does not apply. */
  minCellSize: number | null
  suppressedCount: number
}

/**
 * A count is withheld when the caller is on an aggregate tier and the group is smaller
 * than the configured minimum. `min_cell_size` arrives null for an MDA, which already
 * holds the records its dashboard counts — so the guard is off by construction rather
 * than by a role check repeated on the client.
 *
 * Zero is never suppressed: "nobody" discloses nothing, and hiding it would read as a
 * withheld population rather than an empty one.
 */
function guard(value: number, minimum: number | null): { value: number | null; suppressed: boolean } {
  const withheld = minimum !== null && minimum > 0 && value > 0 && value < minimum
  return { value: withheld ? null : value, suppressed: withheld }
}

export function summariseReporting(data: DashboardResponse): ReportingSummary {
  const m = data.metrics
  const minimum = data.min_cell_size ?? null

  const raw: { key: string; label: string; value: number; hint: string }[] = [
    {
      key: 'beneficiaries',
      label: 'Net-unique beneficiaries',
      value: m.registry.beneficiaries.total,
      hint: 'Registry',
    },
    {
      key: 'households',
      label: 'Households',
      value: m.registry.households?.total ?? 0,
      hint: 'Registry',
    },
    {
      key: 'programmes',
      label: 'Active programmes',
      value: m.programmes.active,
      hint: 'Programmes',
    },
    {
      key: 'activities',
      label: 'Active activities',
      value: m.programmes.activities_active ?? 0,
      hint: 'Programmes',
    },
    {
      key: 'deliveries',
      label: 'Benefit deliveries',
      value: m.benefits.disbursed.benefit_count,
      hint: 'Benefits',
    },
    {
      key: 'duplicates',
      label: 'Duplicates surfaced',
      value: m.duplicates?.matches_surfaced ?? 0,
      hint: 'Duplicates',
    },
  ]

  const tiles = raw.map(({ key, label, value, hint }) => ({
    key,
    label,
    hint,
    ...guard(value, minimum),
  }))

  return {
    tiles,
    computedAt: data.computed_at,
    scopeLabel: data.scope.label,
    tier: data.scope.tier ?? 'operational',
    minCellSize: minimum,
    suppressedCount: tiles.filter((t) => t.suppressed).length,
  }
}

/** Where the summary's "expand" affordance goes, per console. */
export function reportsPathFor(roleKey: string | null | undefined, isMda: boolean): string | null {
  if (roleKey === 'system_administrator') return '/admin/reports'
  if (isMda) return '/mda/reports'

  // Executive and Development Partner consoles have no Reports section: their own tabs
  // ARE the full dashboard. Returning null renders the summary without a dead link
  // rather than inventing a route that would 404.
  return null
}
