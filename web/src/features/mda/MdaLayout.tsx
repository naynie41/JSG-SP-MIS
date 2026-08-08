import { Outlet } from 'react-router-dom'
import { Card } from '@/components/Card/Card'
import { useAuth } from '@/lib/auth/AuthProvider'
import { MDA_ROLES } from './roles'
import styles from './mda.module.css'

/**
 * MDA console shell — the delivery workspace for an MDA Officer and MDA Admin.
 *
 * Six task-based modules (Overview · Programmes · Beneficiaries · Service Delivery ·
 * Duplicate Resolution · Reports) reached from the side rail; Settings opens from the
 * gear affordance in the top bar, not the rail.
 *
 * **One nav for both roles.** MDA Officer's permission set is a strict subset of MDA
 * Admin's — Admin adds only `beneficiary.approve`, `beneficiary.export`,
 * `beneficiary.access_request`, `user.create/edit` and `role.view` — so the rail is
 * built once and each item gates on the signed-in user's permissions rather than
 * branching per role.
 *
 * This guard is UX only. Every endpoint behind these pages carries its own
 * `permission:` middleware and the `MdaScope` global scope, so the data a user sees is
 * bounded by their MDA on the server regardless of what the client renders
 * (SECURITY.md §3 — the server is the security boundary).
 */
export function MdaLayout() {
  const { user } = useAuth()

  if (!(MDA_ROLES as readonly string[]).includes(user?.role?.key ?? '')) {
    return (
      <Card>
        <p className={styles.forbidden}>
          The MDA workspace is available to MDA Officers and MDA Administrators.
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
