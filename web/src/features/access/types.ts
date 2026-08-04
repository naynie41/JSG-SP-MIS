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

export interface AccessGrant {
  id: string
  user: { id: string; name: string; email: string } | null
  mda: { id: string; name: string } | null
  granted_by: string | null
  reason: string | null
  expires_at: string | null
  active: boolean
  created_at: string | null
}

export interface CreateGrantInput {
  user_id: string
  mda_id: string
  reason?: string
  expires_at?: string
}
