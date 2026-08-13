import { Outlet } from 'react-router-dom'
import { Card } from '@/components/Card/Card'
import { useAuth } from '@/lib/auth/AuthProvider'
import { isMdaRole } from './roles'
import styles from './mda.module.css'

/**
 * MDA console shell — the delivery workspace for an MDA Administrator.
 *
 * Six task-based modules (Overview · Programmes · Beneficiaries · Service Delivery ·
 * Duplicate Resolution · Reports) reached from the side rail; Settings opens from the
 * gear affordance in the top bar, not the rail.
 *
 * **One MDA role** (FR-UAM-01) — MDA Officer was merged into it. The rail still gates
 * each item on the signed-in user's PERMISSIONS rather than on the role, because
 * permissions can be re-granted per role without the navigation having to change.
 *
 * This guard is UX only. Every endpoint behind these pages carries its own
 * `permission:` middleware and the `MdaScope` global scope, so the data a user sees is
 * bounded by their MDA on the server regardless of what the client renders
 * (SECURITY.md §3 — the server is the security boundary).
 */
export function MdaLayout() {
  const { user } = useAuth()

  if (!isMdaRole(user?.role?.key)) {
    return (
      <Card>
        <p className={styles.forbidden}>The MDA workspace is available to MDA Administrators.</p>
      </Card>
    )
  }

  return (
    <div className={styles.console}>
      <Outlet />
    </div>
  )
}
