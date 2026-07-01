import { apiRequest } from './client'
import type { AuthTokenResponse, AuthUser, LoginResponse } from '@/types/auth'

/** Bearer header for an explicit (intermediate) token, e.g. the MFA token. */
function bearer(token: string) {
  return { Authorization: `Bearer ${token}` }
}

export interface MfaEnrollment {
  secret: string
  provisioning_uri: string
  recovery_codes: string[]
}

export const authApi = {
  login(email: string, password: string) {
    return apiRequest<LoginResponse>({
      method: 'POST',
      url: '/auth/login',
      data: { email, password },
    })
  },

  me() {
    return apiRequest<AuthUser>({ method: 'GET', url: '/auth/me' })
  },

  logout() {
    return apiRequest<{ message: string }>({ method: 'POST', url: '/auth/logout' })
  },

  /** Exchange an MFA challenge token + code (TOTP or recovery) for a session. */
  mfaChallenge(mfaToken: string, code: string) {
    return apiRequest<AuthTokenResponse>({
      method: 'POST',
      url: '/auth/mfa/challenge',
      data: { code },
      headers: bearer(mfaToken),
    })
  },

  /** Begin enrolment for an MFA-required user (returns secret + recovery codes). */
  mfaEnroll(setupToken: string) {
    return apiRequest<MfaEnrollment>({
      method: 'POST',
      url: '/auth/mfa/enroll',
      headers: bearer(setupToken),
    })
  },

  /** Verify the first TOTP code and complete first-time MFA setup (→ session). */
  mfaVerify(setupToken: string, code: string) {
    return apiRequest<AuthTokenResponse>({
      method: 'POST',
      url: '/auth/mfa/verify',
      data: { code },
      headers: bearer(setupToken),
    })
  },
}
