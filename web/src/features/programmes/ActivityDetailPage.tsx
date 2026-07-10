import { useMemo } from 'react'
import { Link, useParams } from 'react-router-dom'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { formatNaira } from '@/lib/utils/money'
import { titleCase } from '@/features/registry/constants'
import { useActivityServiceRequests } from '@/features/registry/hooks'
import type { ServiceRequest } from '@/features/registry/types'
import { useActivity, useProgrammeCatalog } from './hooks'
import layout from '@/features/shared/formLayout.module.css'
import styles from './programmes.module.css'

/**
 * Activity detail (§10). Shows the MDA-owned activity and, per §5.9, a
 * "Pending service requests" section listing request-to-serve items raised under it
 * that await Owner-MDA approval, with status chips (pending/accepted/declined).
 */
export function ActivityDetailPage() {
  const { id } = useParams<{ id: string }>()
  const { hasPermission } = useAuth()
  const canView = hasPermission('activity.view')

  const { data: activity, isLoading } = useActivity(id, canView)
  const catalog = useProgrammeCatalog(canView)
  const requests = useActivityServiceRequests(id, undefined, canView)

  const programmeName = useMemo(() => {
    const map = new Map((catalog.data?.items ?? []).map((p) => [p.id, p.name]))
    return (pid: string) => map.get(pid) ?? '—'
  }, [catalog.data])

  if (!canView) {
    return <Card><p className={layout.forbidden}>You do not have permission to view activities.</p></Card>
  }
  if (isLoading || !activity) {
    return <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}><Spinner size={24} label="Loading activity" /></div>
  }

  const srColumns: Column<ServiceRequest>[] = [
    { key: 'beneficiary', header: 'Beneficiary', render: (r) => r.beneficiary_name ?? <span className={styles.mono}>#{r.beneficiary_id.slice(0, 8)}</span> },
    { key: 'owner', header: 'Owner MDA', render: (r) => r.owner_mda?.name ?? '—' },
    { key: 'status', header: 'Status', render: (r) => <Badge variant={statusVariant(`service_request.${r.status}`)} dot>{r.status}</Badge> },
    { key: 'raised', header: 'Raised', align: 'right', render: (r) => <span className={styles.mono}>{r.created_at?.slice(0, 10) ?? '—'}</span> },
  ]

  const pendingCount = (requests.data ?? []).filter((r) => r.status === 'pending').length

  return (
    <div className={styles.stack}>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">Activities</span>
          <h1 className="t-h1">{activity.name}</h1>
          <Link to="/activities" className={styles.note}>← All activities</Link>
        </div>
        <div className={styles.rowActions}>
          <Badge variant="neutral">{programmeName(activity.programme_id)}</Badge>
          <Badge variant={statusVariant(`programme.${activity.status === 'completed' ? 'closed' : activity.status}`)} dot>{activity.status}</Badge>
        </div>
      </div>

      <Card title="Details" eyebrow="Activity">
        <dl className={styles.dl}>
          <dt>Programme</dt>
          <dd>{programmeName(activity.programme_id)}</dd>
          <dt>Location</dt>
          <dd>{activity.lga ? titleCase(activity.lga) : '—'}{activity.ward ? ` · ${activity.ward}` : ''}</dd>
          <dt>Budget</dt>
          <dd className={styles.mono}>{formatNaira(activity.budget_amount)}</dd>
          <dt>Funding</dt>
          <dd>{activity.funding_source ?? '—'}</dd>
          <dt>Period</dt>
          <dd>{activity.starts_on ?? '—'} → {activity.ends_on ?? '—'}</dd>
          <dt>Target</dt>
          <dd>{activity.target_count ?? '—'}</dd>
        </dl>
      </Card>

      <Card
        title="Pending service requests"
        eyebrow="Request-to-serve"
        actions={pendingCount > 0 ? <Badge variant="warning" dot>{pendingCount} awaiting approval</Badge> : undefined}
      >
        <p className={styles.note} style={{ marginBottom: 'var(--space-3)' }}>
          Duplicates chosen to serve during this activity's upload raise a request to the beneficiary's owner
          MDA. The intervention is deferred until the owner approves.
        </p>
        <DataTable
          caption="Service requests raised under this activity"
          columns={srColumns}
          rows={requests.data ?? []}
          getRowId={(r) => r.id}
          loading={requests.isLoading}
          emptyTitle="No service requests raised under this activity"
        />
      </Card>
    </div>
  )
}
