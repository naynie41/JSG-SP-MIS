/**
 * A count is withheld when the server published a minimum group size and this group is
 * under it. `minimum` is null on the operational tier, where an MDA already holds the
 * records it is counting. Zero is never withheld: "nobody" discloses nothing.
 */
export function isHeld(count: number, minimum: number | null): boolean {
  return minimum !== null && minimum > 0 && count > 0 && count < minimum
}

export function formatCount(count: number, minimum: number | null = null): string {
  return isHeld(count, minimum) ? `< ${minimum}` : count.toLocaleString()
}

/**
 * One labelled count in a breakdown — the row shape every count chart and count list
 * takes. It lives beside the suppression helpers because whether a row may be printed
 * at all is decided by those, not by the chart drawing it.
 */
export interface CountRow {
  key: string
  label: string
  count: number
}

/**
 * Turn a `{ key: count }` map into labelled rows, largest first — the shape every
 * count chart takes. Zero rows are kept: "none recorded" is a finding, and dropping
 * the row would silently turn it into "not a category we track".
 */
export function rowsFrom(
  map: Record<string, number> | undefined,
  label: (key: string) => string,
): CountRow[] {
  return Object.entries(map ?? {})
    .map(([key, count]) => ({ key, label: label(key), count: Number(count) || 0 }))
    .sort((a, b) => b.count - a.count)
}
