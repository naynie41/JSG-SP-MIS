import type { ReactNode } from 'react'
import { Navigate, useLocation } from 'react-router-dom'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'

/**
 * Gates protected routes. While the session is being restored it shows a
 * spinner; unauthenticated users are redirected to /login (remembering where
 * they were headed). The server remains the real security boundary.
 */
export function ProtectedRoute({ children }: { children: ReactNode }) {
  const { status } = useAuth()
  const location = useLocation()

  if (status === 'loading') {
    return (
      <div style={{ display: 'grid', placeItems: 'center', minHeight: '100vh' }}>
        <Spinner size={28} label="Restoring session" />
      </div>
    )
  }

  if (status === 'unauthenticated') {
    return <Navigate to="/login" replace state={{ from: location.pathname }} />
  }

  return <>{children}</>
}
