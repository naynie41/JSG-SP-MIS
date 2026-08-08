/**
 * Formatting helpers and the chart palette shared by the Executive briefing
 * suite. Kept out of executiveWidgets.tsx so that file exports components only
 * (a module mixing both breaks React Fast Refresh).
 */

export const pct = (rate: number | null | undefined): number => Math.round((rate ?? 0) * 100)
export const num = (n: number | null | undefined): string => (n ?? 0).toLocaleString()

const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

export function monthLong(ym: string): string {
  const [yyyy, mm] = ym.split('-')
  return `${MONTHS[(Number(mm) || 1) - 1] ?? ''} ${yyyy}`.trim()
}

/**
 * Validated categorical palette for the ONE categorical chart (programme share).
 * A forest-leaning ordering of the dataviz reference hues — it clears every hard
 * gate in light mode (the app is light-only); the CVD warning band is covered by
 * the secondary encoding this chart carries (direct % labels + 2px surface gaps +
 * a legend). Every other chart here is single-hue forest (magnitude) so needs none.
 */
export const PROGRAMME_COLORS = [
  'var(--chart-1)',
  'var(--chart-2)',
  'var(--chart-3)',
  'var(--chart-4)',
  'var(--chart-5)',
  'var(--chart-6)',
]
export const OTHER_COLOR = 'var(--chart-other)'
