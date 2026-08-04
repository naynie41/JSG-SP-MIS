import { Badge } from '@/components/Badge/Badge'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Spinner } from '@/components/Spinner/Spinner'
import { Tabs } from '@/components/Tabs/Tabs'
import { useAuth } from '@/lib/auth/AuthProvider'
import { PermissionsPage } from '@/features/access/PermissionsPage'
import { RolesPage } from '@/features/access/RolesPage'
import { UserListPage } from '@/features/users/UserListPage'
import { useLoginActivity } from './hooks'
import type { LoginActivityEntry } from './types'
import styles from './admin.module.css'

const OUTCOME_VARIANT = { success: 'success', failure: 'danger', security: 'warning' } as const

/** 'auth.login_failed' → 'Auth login failed'. */
function humanize(action: string): string {
  const text = action.replace(/[._]/g, ' ')
  return text.charAt(0).toUpperCase() + text.slice(1)
}

function when(iso: string | null): string {
  if (!iso) return '—'
  const d = new Date(iso)
  return Number.isNaN(d.getTime())
    ? '—'
    : d.toLocaleString(undefined, { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' })
}

/**
 * Login activity — a read-only projection of the EXISTING audit trail (`auth.*`,
 * `mfa.*`), not a second login log. Shows the envelope only: who, what, from where,
 * when. Audit before/after payloads never reach the client.
 */
function LoginActivityPanel() {
  const { data, isLoading } = useLoginActivity()

  if (isLoading || !data) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={24} label="Loading login activity" />
      </div>
    )
  }

  const columns: Column<LoginActivityEntry>[] = [
    { key: 'action', header: 'Event', render: (e) => <strong>{humanize(e.action)}</strong> },
    {
      key: 'outcome',
      header: 'Outcome',
      render: (e) => (
        <Badge variant={OUTCOME_VARIANT[e.outcome]} dot>
          {e.outcome}
        </Badge>
      ),
    },
    {
      key: 'actor',
      header: 'Account',
      render: (e) => (
        <>
          {e.actor}
          {e.actor_mda && <span className={styles.activityMeta}> · {e.actor_mda}</span>}
        </>
      ),
    },
    { key: 'ip', header: 'IP address', render: (e) => e.ip_address ?? '—' },
    { key: 'at', header: 'When', align: 'right', render: (e) => when(e.at) },
  ]

  const s = data.summary
  const figures = [
    { label: 'Successful logins', value: s.logins },
    { label: 'Failed logins', value: s.failed_logins },
    { label: 'Lockouts', value: s.lockouts },
    { label: 'MFA resets', value: s.mfa_resets },
  ]

  return (
    <div className={styles.page}>
      <div className={styles.figureGrid}>
        {figures.map((f) => (
          <div key={f.label} className={styles.figure}>
            <span className={styles.figureLabel}>{f.label}</span>
            <span className={styles.figureValue}>{f.value.toLocaleString()}</span>
            <span className={styles.figureHint}>last {data.window_days} days</span>
          </div>
        ))}
      </div>

      <Card flush>
        <DataTable
          rows={data.entries}
          columns={columns}
          getRowId={(e) => e.id}
          caption="Authentication activity from the audit log"
        />
      </Card>

      <p className={styles.footnote}>
        Projected from the append-only audit log · audit payloads are never shown here
      </p>
    </div>
  )
}

/**
 * User &amp; Access (console section 2) — COMPOSES the Phase 1 capability rather than
 * reimplementing it:
 *
 *  - **Users** renders the existing {@link UserListPage} (create/edit, MDA + role
 *    assignment, suspend/deactivate/activate, force password reset, reset MFA) — the
 *    same component, endpoints and policies MDA administration already uses.
 *  - **Roles** renders {@link RolesPage}, which already surfaces each role's MFA
 *    requirement — the enforcement policy is role-driven (FR-UAM-04), so there is no
 *    per-user "enforce" toggle to offer.
 *  - **Permissions** renders {@link PermissionsPage} (module catalogue + role matrix).
 *  - **Login activity** is the only new view, and it merely projects the audit trail.
 *
 * Every action remains gated by its Phase 1 permission (`user.create`, `user.edit`, …);
 * the console adds a role gate on top, never a bypass.
 */
export function AdminAccessPage() {
  const { hasPermission } = useAuth()

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>Administration console</span>
        <h1 className={styles.pageTitle}>User &amp; Access</h1>
        <p className={styles.lead}>
          Accounts, roles, permissions and authentication activity — backed by the existing access module.
        </p>
      </header>

      <Tabs
        items={[
          { id: 'users', label: 'Users', content: <UserListPage /> },
          { id: 'roles', label: 'Roles', content: <RolesPage /> },
          {
            id: 'permissions',
            label: 'Permissions',
            content: <PermissionsPage />,
            disabled: !hasPermission('permission.view'),
          },
          { id: 'activity', label: 'Login activity', content: <LoginActivityPanel /> },
        ]}
      />
    </div>
  )
}
