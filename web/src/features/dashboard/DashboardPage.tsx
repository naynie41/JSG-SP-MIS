import { Badge } from '@/components/Badge/Badge'
import { Card, KpiPanel } from '@/components/Card/Card'
import { useAuth } from '@/lib/auth/AuthProvider'
import styles from './Dashboard.module.css'

export function DashboardPage() {
  const { user } = useAuth()

  return (
    <div className={styles.page}>
      <header className={styles.header}>
        <span className="eyebrow">01 · Overview</span>
        <h1 className="t-h1">Welcome back, {user?.name?.split(' ')[0] ?? 'there'}</h1>
        <p className={styles.lead}>
          Coordination, registry and reporting arrive in the next phases. Your account and access are
          set up below.
        </p>
      </header>

      {/* KPI panels are illustrative until the registry module lands. */}
      <div className={styles.kpis}>
        <KpiPanel label="Beneficiaries" value="—" hint="Registry — Phase 2" />
        <KpiPanel label="Active programmes" value="—" hint="Programmes — Phase 4" />
        <KpiPanel label="MDAs onboarded" value="—" hint="Administration" />
      </div>

      <Card eyebrow="Account" title="Your access">
        <dl className={styles.meta}>
          <div>
            <dt className={styles.metaLabel}>Role</dt>
            <dd>{user?.role ? <Badge variant="dark">{user.role.name}</Badge> : '—'}</dd>
          </div>
          <div>
            <dt className={styles.metaLabel}>MDA</dt>
            <dd>{user?.mda ? user.mda.name : <span className="t-muted">Not MDA-bound</span>}</dd>
          </div>
        </dl>

        <div className={styles.permsBlock}>
          <span className="eyebrow">Permissions</span>
          <div className={styles.perms}>
            {(user?.permissions ?? []).map((permission) => (
              <Badge key={permission} variant="outline" mono>
                {permission}
              </Badge>
            ))}
            {user && user.permissions.length === 0 && <span className="t-muted">No permissions assigned.</span>}
          </div>
        </div>
      </Card>
    </div>
  )
}
