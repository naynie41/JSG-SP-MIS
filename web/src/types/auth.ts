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
  mfa_enabled: boolean
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
