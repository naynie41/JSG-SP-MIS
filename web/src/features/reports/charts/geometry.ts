/*
 * Number formatting and SVG geometry for the MDA report charts. No React here, so the
 * chart components stay presentational and these stay testable on their own.
 */

const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
const MONTHS_LONG = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']

function oneDecimal(value: number): string {
  return Number(value.toFixed(1)).toLocaleString()
}

/** 1,284 · 12.9K · 4.2M · 1.1B — for axis ticks and tile values that must stay short. */
export function compactNumber(value: number): string {
  const abs = Math.abs(value)
  if (abs >= 1e9) return `${oneDecimal(value / 1e9)}B`
  if (abs >= 1e6) return `${oneDecimal(value / 1e6)}M`
  if (abs >= 1e4) return `${oneDecimal(value / 1e3)}K`
  return Math.round(value).toLocaleString()
}

/** Money arrives in kobo; a chart shows naira, compact. */
export function compactNaira(kobo: number): string {
  return `₦${compactNumber(kobo / 100)}`
}

/** 'YYYY-MM' → 'Sep'. */
export function monthShort(ym: string): string {
  return MONTHS[Number(ym.slice(5, 7)) - 1] ?? ym
}

/** 'YYYY-MM' → 'September 2026'. */
export function monthLong(ym: string): string {
  const name = MONTHS_LONG[Number(ym.slice(5, 7)) - 1]
  return name ? `${name} ${ym.slice(0, 4)}` : ym
}

/** Clean axis ticks from zero: 0 / 25 / 50 / 75 / 100, never 0 / 23.7 / 47.4. */
export function niceTicks(max: number, count = 4): number[] {
  if (max <= 0) return [0, 1]
  const raw = max / count
  const magnitude = 10 ** Math.floor(Math.log10(raw))
  const normalised = raw / magnitude
  const step = (normalised <= 1 ? 1 : normalised <= 2 ? 2 : normalised <= 2.5 ? 2.5 : normalised <= 5 ? 5 : 10) * magnitude
  const top = Math.ceil(max / step) * step
  const ticks: number[] = []
  for (let tick = 0; tick <= top + step / 2; tick += step) ticks.push(Math.round(tick * 1000) / 1000)
  return ticks
}

/** A column with a 4px rounded data end and a square foot on the baseline. */
export function columnPath(x: number, y: number, width: number, height: number, radius = 4): string {
  if (height <= 0 || width <= 0) return ''
  const r = Math.min(radius, width / 2, height)
  return `M${x},${y + height} V${y + r} Q${x},${y} ${x + r},${y} H${x + width - r} Q${x + width},${y} ${x + width},${y + r} V${y + height} Z`
}

function polar(cx: number, cy: number, r: number, degrees: number): [number, number] {
  const a = ((degrees - 90) * Math.PI) / 180
  return [cx + r * Math.cos(a), cy + r * Math.sin(a)]
}

/** One ring segment between two angles (degrees, clockwise from 12 o'clock). */
export function arcPath(cx: number, cy: number, outer: number, inner: number, start: number, end: number): string {
  // A full ring cannot be drawn as one arc — its start and end points coincide.
  if (end - start >= 359.99) {
    return `${arcPath(cx, cy, outer, inner, start, start + 180)} ${arcPath(cx, cy, outer, inner, start + 180, start + 360 - 0.01)}`
  }
  const [x1, y1] = polar(cx, cy, outer, start)
  const [x2, y2] = polar(cx, cy, outer, end)
  const [x3, y3] = polar(cx, cy, inner, end)
  const [x4, y4] = polar(cx, cy, inner, start)
  const large = end - start > 180 ? 1 : 0
  return `M${x1},${y1} A${outer},${outer} 0 ${large} 1 ${x2},${y2} L${x3},${y3} A${inner},${inner} 0 ${large} 0 ${x4},${y4} Z`
}
