import { createContext, useCallback, useContext, useEffect, useMemo, useState } from 'react'
import type { ReactNode } from 'react'
import { authApi } from '@/lib/api/authApi'
import { setUnauthorizedHandler } from '@/lib/api/client'
import { tokenStore } from '@/lib/api/tokenStore'
import type { AuthTokenResponse, AuthUser } from '@/types/auth'

export type AuthStatus = 'loading' | 'authenticated' | 'unauthenticated'

interface AuthContextValue {
  status: AuthStatus
  user: AuthUser | null
  /** Persist a completed session (after login / MFA / setup). */
  completeSession: (response: AuthTokenResponse) => void
  logout: () => Promise<void>
  hasPermission: (permission: string) => boolean
  hasAnyPermission: (permissions: string[]) => boolean
}

const AuthContext = createContext<AuthContextValue | null>(null)

export function AuthProvider({ children }: { children: ReactNode }) {
  const [status, setStatus] = useState<AuthStatus>('loading')
  const [user, setUser] = useState<AuthUser | null>(null)

  const clearSession = useCallback(() => {
    tokenStore.clear()
    setUser(null)
    setStatus('unauthenticated')
  }, [])

  const completeSession = useCallback((response: AuthTokenResponse) => {
    tokenStore.set(response.token)
    setUser(response.user)
    setStatus('authenticated')
  }, [])

  const logout = useCallback(async () => {
    try {
      await authApi.logout()
    } catch {
      /* best effort — clear locally regardless */
    }
    clearSession()
  }, [clearSession])

  // React to server-side token rejection (expired/invalid) from any request.
  useEffect(() => {
    setUnauthorizedHandler(() => clearSession())
  }, [clearSession])

  // Restore the session on load if a token is present.
  useEffect(() => {
    if (!tokenStore.get()) {
      setStatus('unauthenticated')
      return
    }
    authApi
      .me()
      .then((me) => {
        setUser(me)
        setStatus('authenticated')
      })
      .catch(() => clearSession())
  }, [clearSession])

  const hasPermission = useCallback(
    (permission: string) => user?.permissions.includes(permission) ?? false,
    [user],
  )
  const hasAnyPermission = useCallback(
    (permissions: string[]) => permissions.some((p) => user?.permissions.includes(p)),
    [user],
  )

  const value = useMemo<AuthContextValue>(
    () => ({ status, user, completeSession, logout, hasPermission, hasAnyPermission }),
    [status, user, completeSession, logout, hasPermission, hasAnyPermission],
  )

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>
}

// eslint-disable-next-line react-refresh/only-export-components
export function useAuth(): AuthContextValue {
  const context = useContext(AuthContext)
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider')
  }
  return context
}
