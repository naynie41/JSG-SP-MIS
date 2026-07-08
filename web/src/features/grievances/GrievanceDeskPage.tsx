import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { AlertTriangle, ArrowRight, Plus } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Icon } from '@/components/Icon/Icon'
import { SelectField } from '@/components/Field/SelectField'
import { useAuth } from '@/lib/auth/AuthProvider'
import { GRIEVANCE_CATEGORY_FILTER, GRIEVANCE_CATEGORY_LABELS, GRIEVANCE_STATUS_FILTER, GRIEVANCE_STATUS_LABELS } from './constants'
import { useGrievances } from './hooks'
import { LogGrievanceModal } from './LogGrievanceModal'
import type { Grievance } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './grievances.module.css'

const shortId = (id: string) => id.slice(0, 8)

export function GrievanceStatusBadge({ status }: { status: Grievance['status'] }) {
  return (
    <Badge variant={statusVariant(`grievance.${status}`)} dot>
      {GRIEVANCE_STATUS_LABELS[status]}
    </Badge>
  )
}

/** Overdue/escalation signal (FR-GRM-03). */
export function GrievanceSla({ grievance }: { grievance: Pick<Grievance, 'sla_breached_at' | 'escalation_level' | 'status'> }) {
  const terminal = grievance.status === 'resolved' || grievance.status === 'closed'
  if (grievance.sla_breached_at === null && grievance.escalation_level === 0) {
    return <span className={styles.mono}>{terminal ? '—' : 'On track'}</span>
  }
  return (
    <span className={styles.sla}>
      <Badge variant="danger" dot>
        <Icon icon={AlertTriangle} size={12} /> {`Overdue · L${grievance.escalation_level}`}
      </Badge>
    </span>
  )
}

/**
 * Grievance desk (FR-GRM, §8.4). A filterable queue of grievances the caller's MDA
 * handles, each with its lifecycle status and an SLA/escalation flag. Staff log new
 * grievances here and open one to assign + resolve it.
 */
export function GrievanceDeskPage() {
  const { hasPermission } = useAuth()
  const navigate = useNavigate()
  const canView = hasPermission('grievance.view')
  const canCreate = hasPermission('grievance.create')

  const [status, setStatus] = useState('')
  const [category, setCategory] = useState('')
  const [mineOnly, setMineOnly] = useState(false)
  const [logging, setLogging] = useState(false)

  const { data, isLoading } = useGrievances(
    { status: status || undefined, category: category || undefined, assignee: mineOnly ? 'me' : undefined },
    canView,
  )

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view grievances.</p>
      </Card>
    )
  }

  const columns: Column<Grievance>[] = [
    { key: 'ref', header: 'Reference', render: (g) => <span className={styles.mono}>#{shortId(g.id)}</span> },
    { key: 'category', header: 'Category', render: (g) => GRIEVANCE_CATEGORY_LABELS[g.category] },
    {
      key: 'ben',
      header: 'Beneficiary',
      render: (g) => (g.beneficiary_id ? <span className={styles.mono}>#{shortId(g.beneficiary_id)}</span> : <span className={layout.cellSub}>General</span>),
    },
    { key: 'status', header: 'Status', render: (g) => <GrievanceStatusBadge status={g.status} /> },
    { key: 'sla', header: 'SLA', render: (g) => <GrievanceSla grievance={g} /> },
    {
      key: 'view',
      header: '',
      align: 'right',
      render: (g) => (
        <Button size="sm" variant="tertiary" rightIcon={ArrowRight} onClick={() => navigate(`/grievances/${g.id}`)}>
          Open
        </Button>
      ),
    },
  ]

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">05 · Coordination</span>
          <h1 className="t-h1">Grievance desk</h1>
          <p className={styles.note}>
            Staff log grievances on behalf of beneficiaries, then assign and track them to resolution. Overdue items are
            flagged against their category SLA.
          </p>
        </div>
        {canCreate && (
          <Button leftIcon={Plus} onClick={() => setLogging(true)}>
            Log grievance
          </Button>
        )}
      </div>

      <div className={styles.tableTools}>
        <div className={styles.filter}>
          <SelectField label="Status" options={GRIEVANCE_STATUS_FILTER} value={status} onChange={(e) => setStatus(e.target.value)} />
        </div>
        <div className={styles.filter}>
          <SelectField label="Category" options={GRIEVANCE_CATEGORY_FILTER} value={category} onChange={(e) => setCategory(e.target.value)} />
        </div>
        <Button variant={mineOnly ? 'primary' : 'tertiary'} onClick={() => setMineOnly((v) => !v)}>
          {mineOnly ? 'Assigned to me' : 'All assignees'}
        </Button>
      </div>

      <DataTable
        caption="Grievance queue"
        columns={columns}
        rows={data?.items ?? []}
        getRowId={(g) => g.id}
        loading={isLoading}
        emptyTitle="No grievances match these filters"
        emptyAction={canCreate ? <Button size="sm" leftIcon={Plus} onClick={() => setLogging(true)}>Log grievance</Button> : undefined}
      />

      <LogGrievanceModal open={logging} onClose={() => setLogging(false)} onLogged={(g) => { setLogging(false); navigate(`/grievances/${g.id}`) }} />
    </div>
  )
}
