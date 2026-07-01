import type { AuthUser } from '@/types/auth'

/** A user as returned by the admin /users endpoints (same shape as /auth/me). */
export type ManagedUser = AuthUser

export interface RoleOption {
  id: string
  key: string
  name: string
  requires_mfa: boolean
}

export interface CreateUserInput {
  name: string
  email: string
  password: string
  password_confirmation: string
  role_id: string
  mda_id?: string
}

export interface UpdateUserInput {
  name?: string
  email?: string
  role_id?: string
  mda_id?: string | null
}
