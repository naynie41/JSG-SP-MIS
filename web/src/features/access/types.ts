/** RBAC administration (roles, permission catalogue, cross-MDA grants). */

export interface AccessRole {
  id: string
  key: string
  name: string
  description: string | null
  requires_mfa: boolean
  permissions: string[]
}

export interface PermissionEntry {
  key: string
  action: string
  description: string | null
}

/** Permission catalogue grouped by module (from GET /permissions). */
export type PermissionModules = Record<string, PermissionEntry[]>

/** Role × permission matrix (from GET /access/matrix). */
/** One cell-column of the matrix, carrying the server's policy for that permission. */
export interface MatrixPermission {
  key: string
  module: string
  action: string
  action_label: string
  description: string | null
  /** False for permissions that may never be bundled into a role (export.reveal_pii). */
  role_grantable: boolean
  /** Grants carrying a DPO/sign-off obligation — flagged, not blocked. */
  sensitive: boolean
}

export interface MatrixRole {
  id: string
  key: string
  name: string
  /** False for the System Administrator role, which holds everything implicitly. */
  editable: boolean
  permissions: string[]
}

export interface PermissionMatrix {
  permissions: MatrixPermission[]
  roles: MatrixRole[]
}

/**
 * A cross-MDA access grant (FR-UAM-03).
 *
 * Revoking is a SOFT revoke: the row is retained so the audit trail still shows that the
 * access existed and when it ended (NFR-PRV-01). A listing therefore contains ended
 * grants as well as live ones — read `active`, never the presence of the row, and never
 * `expires_at` alone, since a revoked grant may have no expiry at all.
 */
export interface AccessGrant {
  id: string
  user: { id: string; name: string; email: string } | null
  mda: { id: string; name: string } | null
  granted_by: string | null
  granted_at: string | null
  reason: string | null
  expires_at: string | null
  /** False once revoked OR expired — the only correct "can they read it now" signal. */
  active: boolean
  revoked_at: string | null
  revoked_by: string | null
  revocation_reason: string | null
  created_at: string | null
}

export interface CreateGrantInput {
  user_id: string
  mda_id: string
  reason?: string
  expires_at?: string
}
