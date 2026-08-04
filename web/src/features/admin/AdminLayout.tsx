import { Outlet } from 'react-router-dom'
import { Card } from '@/components/Card/Card'
import { useAuth } from '@/lib/auth/AuthProvider'
import styles from './admin.module.css'

/**
 * System Administrator console shell (governance/administration).
 *
 * The nine sections are peer pages reached from the side rail (Overview · User & Access ·
 * Organization · Programme Catalog · Registry & Data Quality · Integrations · Matching
 * Rules & Registry Config · Audit & Security · Reports); Settings opens from the
 * gear/account affordance in the top bar, not the rail.
 *
 * This guard is UX only — every admin endpoint is gated server-side by
 * `role:system_administrator` (SECURITY.md §3: the server is the security boundary).
 * The console is a single ROLE, not a new scope tier.
 */
export function AdminLayout() {
  const { user } = useAuth()

  if (user?.role?.key !== 'system_administrator') {
    return (
      <Card>
        <p className={styles.forbidden}>
          The administration console is available to System Administrators only.
        </p>
      </Card>
    )
  }

  return (
    <div className={styles.console}>
      <Outlet />
    </div>
  )
}
