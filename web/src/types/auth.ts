/** Auth/user types mirroring the /auth endpoints (snake_case). */

export interface AuthUserMda {
  id: string
  name: string
  type: string
}

export interface AuthUserRole {
  key: string
  name: string
}

export interface AuthUser {
  id: string
  name: string
  email: string
  status: string
  /** Runtime lockout (FR-UAM-06) — distinct from `status`: an account can be
   *  `active` yet temporarily locked after failed attempts. */
  is_locked?: boolean
  locked_until?: string | null
  mfa_enabled: boolean
  /** Whether the user's ROLE mandates MFA (the enforcement policy, FR-UAM-04). */
  mfa_required?: boolean
  /** An administrator issued a temporary password; the account holder must choose
   *  their own before doing anything else (FR-UAM-06). The API enforces this
   *  independently — this only drives where the SPA sends them. */
  must_change_password?: boolean
  last_login_at: string | null
  mda: AuthUserMda | null
  role: AuthUserRole | null
  permissions: string[]
}

/** Successful full login / MFA challenge completion. */
export interface AuthTokenResponse {
  token: string
  token_type: 'Bearer'
  user: AuthUser
}

/** Login when MFA is enabled: a challenge token must be exchanged. */
export interface MfaRequiredResponse {
  mfa_required: true
  token_type: 'Bearer'
  mfa_token: string
}

/** Login when the role requires MFA but the user has not enrolled. */
export interface MfaSetupRequiredResponse {
  mfa_setup_required: true
  token_type: 'Bearer'
  mfa_token: string
}

export type LoginResponse = AuthTokenResponse | MfaRequiredResponse | MfaSetupRequiredResponse

export function isMfaRequired(response: LoginResponse): response is MfaRequiredResponse {
  return 'mfa_required' in response
}

export function isMfaSetupRequired(response: LoginResponse): response is MfaSetupRequiredResponse {
  return 'mfa_setup_required' in response
}

export function isTokenResponse(response: LoginResponse): response is AuthTokenResponse {
  return 'token' in response
}
