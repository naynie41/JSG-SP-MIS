/**
 * Display formatting shared across the MDA console.
 *
 * `titleCase` and `when` were each defined three times across the console's pages —
 * `when` in two different formats and with two different null tokens, so the same
 * timestamp read differently depending on which page you were on. One definition each,
 * with the variation made explicit as an argument rather than left to drift.
 *
 * These deliberately do NOT reuse `titleCase` from `@/features/registry/constants`:
 * that one requires a string and capitalises every word ("Cash Transfer"), while the
 * console's own convention is sentence case with a placeholder for absent values
 * ("Cash transfer", "—"). Same name, different contract.
 */

/** Sentence-case an enum value, with a placeholder when there is nothing to show. */
export function titleCase(value: string | null | undefined, absent = '—'): string {
  return !value ? absent : value.replace(/[_-]/g, ' ').replace(/^./, (c) => c.toUpperCase())
}

interface WhenOptions {
  /** Include the year. Off for recent-activity feeds, on for audit-style timestamps. */
  year?: boolean
  /** What to render when there is no timestamp — "—" in a table, "never" in prose. */
  absent?: string
}

/** A timestamp in the reader's own locale, or the absent token. */
export function when(iso: string | null | undefined, { year = false, absent = '—' }: WhenOptions = {}): string {
  if (!iso) return absent

  const date = new Date(iso)
  if (Number.isNaN(date.getTime())) return absent

  return date.toLocaleString(undefined, {
    day: 'numeric',
    month: 'short',
    ...(year ? { year: 'numeric' as const } : {}),
    hour: '2-digit',
    minute: '2-digit',
  })
}
