/**
 * How long something has been waiting, rounded to something a person would say.
 *
 * Exact seconds ("waiting 1,847 seconds") reads like a stack trace rather than a status.
 * Clamps at zero so clock skew between server and client can never render "-3 seconds".
 */
export function formatWaitedFor(seconds: number | null | undefined): string {
  const total = Math.max(0, Math.floor(seconds ?? 0))
  if (total < 120) return `${total} seconds`

  const minutes = Math.round(total / 60)
  if (minutes < 60) return `${minutes} minutes`

  const hours = Math.floor(minutes / 60)
  const rest = minutes % 60
  const hourPart = `${hours} hour${hours === 1 ? '' : 's'}`

  return rest === 0 ? hourPart : `${hourPart} ${rest} min`
}
