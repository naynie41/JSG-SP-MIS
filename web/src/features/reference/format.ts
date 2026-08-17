import type { ActivityLocationGroup } from '@/features/programmes/types'

/**
 * A one-line summary of a declared location set, for table cells.
 *
 * Names the first LGA and counts the rest ("Dutse +2") rather than listing everything:
 * a cell that wraps to four lines makes the table unreadable, and the full breakdown is
 * one click away on the activity detail page.
 */
export function summariseLocations(locations: ActivityLocationGroup[] | undefined): string {
  if (!locations || locations.length === 0) return '—'

  const [first, ...rest] = locations
  const name = first.lga_name ?? '—'
  const head = first.whole_lga || first.wards.length === 0
    ? name
    : `${name} (${first.wards.length} ward${first.wards.length === 1 ? '' : 's'})`

  return rest.length === 0 ? head : `${head} +${rest.length}`
}
