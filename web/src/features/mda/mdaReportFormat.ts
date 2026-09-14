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
