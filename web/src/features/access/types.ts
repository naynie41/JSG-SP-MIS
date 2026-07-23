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
export interface PermissionMatrix {
  permissions: string[]
  roles: { key: string; name: string; permissions: string[] }[]
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
