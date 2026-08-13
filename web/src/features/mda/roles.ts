/**
 * The role that operates an MDA workspace.
 *
 * There is exactly ONE (FR-UAM-01): MDA Officer was merged into MDA Admin, which
 * already held a superset of its permissions. Kept as a list rather than a bare string
 * because the rail, the landing redirect and the layout guard all ask the same
 * question — "is this an MDA user?" — and should keep asking it in one place.
 *
 * The rail is still built once and gates each item on PERMISSION, not on role: that was
 * true when there were two roles and remains the right shape, since permissions can be
 * re-granted per role without touching the navigation.
 */
export const MDA_ROLES = ['mda_admin'] as const

export type MdaRole = (typeof MDA_ROLES)[number]

/** Whether a role key belongs to the MDA workspace. */
export function isMdaRole(roleKey: string | null | undefined): boolean {
  return (MDA_ROLES as readonly string[]).includes(roleKey ?? '')
}
