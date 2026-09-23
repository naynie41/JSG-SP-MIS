import { useAuth } from '@/lib/auth/AuthProvider'
import type { AuthUser } from '@/types/auth'

/**
 * What to CALL the delivery workspace and the person standing in it.
 *
 * The workspace is one set of screens, but two kinds of organisation now work in it: a
 * government MDA, and a development partner that implements its own programmes. Telling
 * Save the Children that it is an "MDA", that its role is "MDA Admin" and that its data
 * is "scoped to your MDA" is simply false, and it invites the exact confusion the two
 * identities were separated to avoid.
 *
 * Only the WORDS change. The role key stays `mda_admin`, the permissions are untouched
 * and the server scopes both kinds of organisation identically — renaming the role
 * itself would be a permissions migration, and would relabel every government admin too.
 *
 * Government wording is unchanged on purpose: an MDA user sees exactly what they saw.
 */
export interface WorkspaceIdentity {
  /** The eyebrow above every workspace page title. */
  workspace: string
  /** The signed-in user's role, named for the kind of organisation they work in. */
  roleName: string
  /** The bare noun for the user's OWN organisation, for prose: "scoped to your …". */
  org: string
  /** The same noun capitalised, for labels and the start of a sentence. */
  orgLabel: string
  /** Possessive, because "your MDA's data" and "your organisation's data" differ. */
  orgPossessive: string
  /** Whether this user's organisation is a development partner rather than an MDA. */
  isPartner: boolean
}

/**
 * Role names are held in the database and shared across every holder of the role, so a
 * partner's label cannot come from there. Keyed on the role KEY rather than munging the
 * stored name, so a role renamed in the admin console does not silently change meaning.
 */
const PARTNER_ROLE_NAMES: Record<string, string> = {
  mda_admin: 'Partner Admin',
}

export function isPartnerOrg(user: AuthUser | null | undefined): boolean {
  return user?.mda?.type === 'partner'
}

export function workspaceIdentity(user: AuthUser | null | undefined): WorkspaceIdentity {
  const isPartner = isPartnerOrg(user)
  const roleKey = user?.role?.key ?? ''
  const storedName = user?.role?.name ?? '—'

  return {
    workspace: isPartner ? 'Partner workspace' : 'MDA workspace',
    roleName: isPartner ? (PARTNER_ROLE_NAMES[roleKey] ?? storedName) : storedName,
    org: isPartner ? 'organisation' : 'MDA',
    orgLabel: isPartner ? 'Organisation' : 'MDA',
    orgPossessive: isPartner ? "organisation's" : "MDA's",
    isPartner,
  }
}

export function useWorkspaceIdentity(): WorkspaceIdentity {
  const { user } = useAuth()

  return workspaceIdentity(user)
}
