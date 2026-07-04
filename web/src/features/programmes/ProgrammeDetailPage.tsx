import { useState } from 'react'
import { Link, useParams } from 'react-router-dom'
import { Archive, Pencil, Plus, UserPlus } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card, KpiPanel } from '@/components/Card/Card'
import { Tabs } from '@/components/Tabs/Tabs'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Menu } from '@/components/Menu/Menu'
import { ConfirmDialog } from '@/components/Modal/ConfirmDialog'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { formatNaira } from '@/lib/utils/money'
import { titleCase } from '@/features/registry/constants'
import { cn } from '@/lib/utils/cn'
import { ProgrammeFormModal } from './ProgrammeFormModal'
import { ActivityFormModal } from './ActivityFormModal'
import { EnrollModal } from './EnrollModal'
import { useActivities, useArchiveActivity, useArchiveProgramme, useEnrollments, useProgramme, useProgrammeBudget, useUpdateEnrollment } from './hooks'
import type { Activity, Budget, Enrollment, Programme } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './programmes.module.css'

function BudgetHeadline({ budget }: { budget: Budget }) {
  const rate = budget.utilization_rate ?? 0
  const over = budget.remaining != null && budget.remaining < 0
  return (
    <>
      <div className={styles.kpiRow}>
        <KpiPanel label="Allocated" value={formatNaira(budget.allocated)} />
        <KpiPanel label="Utilised" value={formatNaira(budget.utilized_value)} hint={`${budget.benefit_count} deliveries`} />
        <KpiPanel label="Remaining" value={budget.allocated != null ? formatNaira(budget.remaining) : '—'} hint={budget.allocated != null ? `${Math.round(rate * 100)}% used` : 'No budget set'} />
      </div>
      {budget.allocated != null && (
        <div className={styles.budgetTrack} aria-hidden="true">
          <div className={cn(styles.budgetFill, over && styles.budgetOver)} style={{ width: `${Math.min(100, Math.round(rate * 100))}%` }} />
        </div>
      )}
    </>
  )
}

function DetailsTab({ programme }: { programme: Programme }) {
  return (
    <div className={styles.stack}>
      <Card title="Configuration" eyebrow="Programme">
        <dl className={styles.dl}>
          <dt>Objective</dt>
          <dd>{programme.objective ?? '—'}</dd>
          <dt>Type</dt>
          <dd style={{ textTransform: 'capitalize' }}>{programme.type}</dd>
          <dt>Funding source</dt>
          <dd>{programme.funding_source ?? '—'}</dd>
          <dt>Period</dt>
          <dd>{programme.starts_on ?? '—'} → {programme.ends_on ?? '—'}</dd>
          <dt>Owner MDA</dt>
          <dd>{programme.owner_mda?.name ?? '—'}</dd>
        </dl>
      </Card>
      <Card title="Eligibility" eyebrow="Criteria" variant="mint">
        {programme.eligibility.length === 0 ? (
          <p className={styles.note}>No criteria — all beneficiaries are eligible.</p>
        ) : (
          <div className={styles.chipRow}>
            {programme.eligibility.map((c, i) => (
              <Badge key={i} variant="neutral" mono>
                {c.attribute} = {String(c.value)}
              </Badge>
            ))}
          </div>
        )}
        <p className={styles.note} style={{ marginTop: 'var(--space-2)' }}>
          {programme.enforce_eligibility ? 'Enforced — ineligible beneficiaries are blocked.' : 'Advisory — ineligible beneficiaries are flagged, not blocked.'}
        </p>
      </Card>
    </div>
  )
}

export function ProgrammeDetailPage() {
  const { id } = useParams<{ id: string }>()
  const { hasPermission, user } = useAuth()
  const canView = hasPermission('programme.view')

  const { data: programme, isLoading } = useProgramme(id, canView)
  const { data: budget } = useProgrammeBudget(id, canView)
  const activities = useActivities(id, canView)
  const enrollments = useEnrollments(id, canView)
  const archiveProgramme = useArchiveProgramme()
  const archiveActivity = useArchiveActivity(id ?? '')
  const updateEnrollment = useUpdateEnrollment(id ?? '')

  const [editOpen, setEditOpen] = useState(false)
  const [activityForm, setActivityForm] = useState<{ open: boolean; activity: Activity | null }>({ open: false, activity: null })
  const [enrollOpen, setEnrollOpen] = useState(false)
  const [confirmArchive, setConfirmArchive] = useState(false)

  if (!canView) {
    return <Card><p className={layout.forbidden}>You do not have permission to view programmes.</p></Card>
  }
  if (isLoading || !programme) {
    return <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}><Spinner size={24} label="Loading programme" /></div>
  }

  const isOwner = user?.mda?.id != null && user.mda.id === programme.owner_mda_id
  const canEdit = hasPermission('programme.edit') && isOwner
  const canManageActivities = hasPermission('activity.create') && isOwner
  const canEnroll = hasPermission('enrollment.create') && isOwner

  const activityColumns: Column<Activity>[] = [
    { key: 'name', header: 'Activity', render: (a) => a.name },
    { key: 'status', header: 'Status', render: (a) => <Badge variant={statusVariant(`programme.${a.status === 'completed' ? 'closed' : a.status}`)} dot>{a.status}</Badge> },
    { key: 'lga', header: 'LGA', render: (a) => (a.lga ? titleCase(a.lga) : '—') },
    { key: 'target', header: 'Target', align: 'right', render: (a) => a.target_count ?? '—' },
    { key: 'budget', header: 'Budget', align: 'right', render: (a) => <span className={styles.mono}>{formatNaira(a.budget_amount)}</span> },
    {
      key: 'actions',
      header: '',
      render: (a) =>
        canManageActivities ? (
          <Menu
            label={`Actions for ${a.name}`}
            actions={[
              { label: 'Edit', icon: Pencil, onSelect: () => setActivityForm({ open: true, activity: a }) },
              { label: 'Archive', icon: Archive, danger: true, onSelect: () => archiveActivity.mutate(a.id) },
            ]}
          />
        ) : null,
    },
  ]

  const enrollmentColumns: Column<Enrollment>[] = [
    {
      key: 'target',
      header: 'Beneficiary / Household',
      render: (e) =>
        e.beneficiary_id ? <Link to={`/beneficiaries/${e.beneficiary_id}`} className={styles.mono}>#{e.beneficiary_id.slice(0, 8)}</Link> : <span className={styles.mono}>HH #{e.household_id?.slice(0, 8)}</span>,
    },
    { key: 'status', header: 'Status', render: (e) => <Badge variant={statusVariant(`enrollment.${e.status}`)} dot>{e.status}</Badge> },
    {
      key: 'eligibility',
      header: 'Eligibility',
      render: (e) => (e.eligibility_flagged ? <Badge variant="warning" dot>Flagged</Badge> : <span className={styles.note}>OK</span>),
    },
    { key: 'enrolled_on', header: 'Enrolled', render: (e) => <span className={styles.mono}>{e.enrolled_on}</span> },
    {
      key: 'actions',
      header: '',
      render: (e) =>
        canEnroll && e.status === 'enrolled' ? (
          <Menu
            label="Enrollment actions"
            actions={[
              { label: 'Mark exited', onSelect: () => updateEnrollment.mutate({ id: e.id, status: 'exited' }) },
              { label: 'Graduate', onSelect: () => updateEnrollment.mutate({ id: e.id, status: 'graduated' }) },
            ]}
          />
        ) : null,
    },
  ]

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">04 · Programmes</span>
          <h1 className="t-h1">{programme.name}</h1>
          <Link to="/programmes" className={styles.note}>← All programmes</Link>
        </div>
        <div className={styles.rowActions}>
          <Badge variant={statusVariant(`type.${programme.type}`)}>{programme.type}</Badge>
          <Badge variant={statusVariant(`programme.${programme.status}`)} dot>{programme.status}</Badge>
          {canEdit && <Button variant="tertiary" leftIcon={Pencil} onClick={() => setEditOpen(true)}>Configure</Button>}
          {canEdit && programme.status !== 'archived' && (
            <Button variant="tertiary" leftIcon={Archive} onClick={() => setConfirmArchive(true)}>Archive</Button>
          )}
        </div>
      </div>

      {budget && <BudgetHeadline budget={budget} />}

      <Tabs
        items={[
          { id: 'details', label: 'Details', content: <DetailsTab programme={programme} /> },
          {
            id: 'activities',
            label: `Activities (${activities.data?.items.length ?? 0})`,
            content: (
              <div className={styles.stack}>
                {canManageActivities && (
                  <div><Button size="sm" leftIcon={Plus} onClick={() => setActivityForm({ open: true, activity: null })}>Add activity</Button></div>
                )}
                <DataTable caption="Activities" columns={activityColumns} rows={activities.data?.items ?? []} getRowId={(a) => a.id} loading={activities.isLoading} emptyTitle="No activities yet" />
              </div>
            ),
          },
          {
            id: 'enrollments',
            label: `Enrollments (${enrollments.data?.items.length ?? 0})`,
            content: (
              <div className={styles.stack}>
                {canEnroll && (
                  <div><Button size="sm" leftIcon={UserPlus} onClick={() => setEnrollOpen(true)}>Enroll {programme.type === 'individual' ? 'beneficiaries' : 'households'}</Button></div>
                )}
                <DataTable caption="Enrollments" columns={enrollmentColumns} rows={enrollments.data?.items ?? []} getRowId={(e) => e.id} loading={enrollments.isLoading} emptyTitle="No enrollments yet" />
              </div>
            ),
          },
        ]}
      />

      {canEdit && <ProgrammeFormModal open={editOpen} onClose={() => setEditOpen(false)} programme={programme} />}
      {canManageActivities && id && (
        <ActivityFormModal open={activityForm.open} onClose={() => setActivityForm({ open: false, activity: null })} programmeId={id} activity={activityForm.activity} />
      )}
      {canEnroll && <EnrollModal open={enrollOpen} onClose={() => setEnrollOpen(false)} programme={programme} />}

      <ConfirmDialog
        open={confirmArchive}
        title="Archive programme"
        confirmLabel="Archive"
        danger
        loading={archiveProgramme.isPending}
        onCancel={() => setConfirmArchive(false)}
        onConfirm={() => id && archiveProgramme.mutate(id, { onSuccess: () => setConfirmArchive(false) })}
      >
        <p>Archiving retires "{programme.name}" (reversible). Its history and ledger stay intact. Continue?</p>
      </ConfirmDialog>
    </div>
  )
}
