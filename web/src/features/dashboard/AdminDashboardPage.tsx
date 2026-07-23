import { useNavigate } from 'react-router-dom'
import { Building2, DatabaseBackup, FileClock, LibraryBig, Share2, ShieldCheck, SlidersHorizontal, Users } from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Card } from '@/components/Card/Card'
import { Button } from '@/components/Button/Button'
import { MetricBand } from '@/components/MetricBand/MetricBand'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { DashboardView } from './DashboardView'
import { useDashboard, useOpsMetrics } from './hooks'
import type { OpsMetricsResponse } from './types'
import styles from './dashboard.module.css'

interface AdminAction {
  label: string
  to: string
  icon: LucideIcon
  permission: string
  primary?: boolean
}

// Administration launchpad — provisioning and platform configuration, not MDA
// operations (the admin is not an MDA operator; those tasks need an acting MDA).
const ACTIONS: AdminAction[] = [
  { label: 'Manage users', to: '/users', icon: Users, permission: 'user.view', primary: true },
  { label: 'MDAs', to: '/mdas', icon: Building2, permission: 'mda.view' },
  { label: 'Programme catalog', to: '/programmes/list', icon: LibraryBig, permission: 'programme.create' },
  { label: 'Matching rules', to: '/matching', icon: SlidersHorizontal, permission: 'matching.view' },
  { label: 'Cross-MDA access', to: '/grants', icon: Share2, permission: 'mda-access.view' },
  { label: 'Roles', to: '/roles', icon: ShieldCheck, permission: 'role.view' },
]

function backupLabel(ops: OpsMetricsResponse): { value: string; tone: 'success' | 'warning' | 'danger' } {
  const age = ops.backups.age_hours
  if (age === null) return { value: 'none yet', tone: 'warning' }
  const withinRpo = age <= ops.backups.rpo_hours
  return { value: `${age}h ago`, tone: withinRpo ? 'success' : 'danger' }
}

/** Operations health strip: backup recency vs RPO + snapshot freshness + volumes. */
function OpsStrip() {
  const { data: ops } = useOpsMetrics(true)
  if (!ops) return null
  const backup = backupLabel(ops)
  return (
    <MetricBand
      items={[
        { icon: DatabaseBackup, label: 'Last backup', value: backup.value, tone: backup.tone },
        {
          icon: FileClock,
          label: 'Snapshot freshness',
          value: ops.dashboard_snapshots.stale_minutes === null ? '—' : `${ops.dashboard_snapshots.stale_minutes} min`,
          tone: (ops.dashboard_snapshots.stale_minutes ?? 0) > 60 ? 'warning' : 'info',
        },
        { icon: Users, label: 'Beneficiaries', value: ops.volumes.beneficiaries.toLocaleString(), tone: 'forest' },
        { icon: ShieldCheck, label: 'Audit entries', value: ops.volumes.audit_entries.toLocaleString(), tone: 'forest' },
      ]}
    />
  )
}

function AdminQuickActions() {
  const { hasPermission } = useAuth()
  const navigate = useNavigate()
  const actions = ACTIONS.filter((a) => hasPermission(a.permission))
  if (actions.length === 0) return null
  return (
    <Card eyebrow="Administration" title="Provision and configure">
      <div className={styles.actions}>
        {actions.map((a) => (
          <Button
            key={a.label}
            variant={a.primary ? 'primary' : 'secondary'}
            leftIcon={a.icon}
            onClick={() => navigate(a.to)}
          >
            {a.label}
          </Button>
        ))}
      </div>
    </Card>
  )
}

/**
 * System Administrator dashboard: state-wide figures (the admin's scope resolves
 * state-wide) framed as administration, not MDA operations. Quick actions launch
 * provisioning/configuration tasks, and the ops strip surfaces platform health
 * (backup age vs RPO, snapshot freshness) — the admin's actual job. The MDA
 * operator quick actions never render here (they need an acting MDA).
 */
export function AdminDashboardPage() {
  const { user, hasPermission } = useAuth()
  const isAdmin = user?.role?.key === 'system_administrator'
  const canView = hasPermission('dashboard.view')
  const { data, isLoading, isFetching, refetch } = useDashboard(isAdmin && canView)

  if (!isAdmin) {
    return (
      <Card>
        <p className={styles.forbidden}>The administration dashboard is available to System Administrators only.</p>
      </Card>
    )
  }

  if (!canView) {
    return (
      <Card>
        <p className={styles.forbidden}>You do not have permission to view a dashboard.</p>
      </Card>
    )
  }

  if (isLoading || !data) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={28} label="Loading dashboard" />
      </div>
    )
  }

  return (
    <DashboardView
      eyebrow="Dashboard"
      title="System administration"
      lead="State-wide overview and platform health. Provision users, MDAs and access; the operational work of registering and serving beneficiaries belongs to MDA accounts."
      extras={
        <>
          <AdminQuickActions />
          <OpsStrip />
        </>
      }
      data={data}
      isFetching={isFetching}
      onRefresh={() => refetch()}
    />
  )
}
