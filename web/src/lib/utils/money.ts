/**
 * Money helpers. The API stores monetary amounts as integer minor units (kobo),
 * currency NGN — this records delivered/allocated value as data; SP-MIS never
 * moves money. The UI collects Naira and converts at the edge.
 */

/** Format kobo (minor units) as Naira, e.g. 150000 → "₦1,500.00". */
export function formatNaira(kobo: number | null | undefined): string {
  if (kobo == null) return '—'
  return `₦${(kobo / 100).toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
}

/** Convert a Naira amount (from a form field) to integer kobo, or undefined if blank. */
export function nairaToKobo(naira: string | number | null | undefined): number | undefined {
  if (naira === '' || naira == null) return undefined
  const value = typeof naira === 'string' ? Number(naira) : naira
  if (!Number.isFinite(value) || value < 0) return undefined
  return Math.round(value * 100)
}

/** Convert integer kobo back to a Naira string for editing a form field. */
export function koboToNaira(kobo: number | null | undefined): string {
  if (kobo == null) return ''
  return String(kobo / 100)
}
