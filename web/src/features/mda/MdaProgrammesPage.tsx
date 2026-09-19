import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { ClipboardList, Eye, LibraryBig, Plus, Send } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Icon } from '@/components/Icon/Icon'
import { statusVariant } from '@/components/Badge/statusVariant'
import { useAuth } from '@/lib/auth/AuthProvider'
import { ProgrammeFormModal } from '@/features/programmes/ProgrammeFormModal'
import { useProgrammeDecision, useProgrammes } from '@/features/programmes/hooks'
import type { Programme } from '@/features/programmes/types'
import { titleCase } from './format'
import styles from './mda.module.css'

/**
 * Programmes — what THIS MDA delivers, of two kinds.
 *
 * Most rows are shared catalogue programmes the MDA participates in, meaning it has
 * activities under them; the catalogue itself is owned centrally and read-only here.
 * The rest are programmes this MDA created FOR ITSELF (§10, revised): no other MDA
 * sees them, and each waits for the System Administrator before it can carry any
 * activity. Both come from the same list — the server includes the MDA's own
 * programmes in `filter[participating]`, which a brand-new one would otherwise fail
 * for having no activities yet.
 */
export function MdaProgrammesPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('programme.view')
  const canCreate = hasPermission('programme.create')
  const navigate = useNavigate()
  const [creating, setCreating] = useState(false)
  const [editing, setEditing] = useState<Programme | null>(null)
  const decide = useProgrammeDecision()

  const { data, isLoading } = useProgrammes({ participating: true, per_page: 100 }, canView)

  if (!canView) {
    return (
      <Card>
        <p className={styles.forbidden}>You do not have permission to view programmes.</p>
      </Card>
    )
  }

  const programmes = data?.items ?? []
  const waiting = programmes.filter((p) => p.approval_status === 'pending').length
  const sentBack = programmes.filter((p) => p.approval_status === 'rejected').length

  const columns: Column<Programme>[] = [
    {
      key: 'name',
      header: 'Programme',
      render: (p) => (
        <>
          <Link to={`/mda/programmes/${p.id}`}>{p.name}</Link>
          {/* The reason belongs on the row. It is the only thing the MDA has to work
              from, and a reason behind another click is a reason nobody reads. */}
          {p.approval_status === 'rejected' && p.decision_note && (
            <span className={styles.rowNote}>{p.decision_note}</span>
          )}
        </>
      ),
    },
    {
      key: 'owner',
      header: 'Created by',
      render: (p) => (p.is_central === false ? 'Your MDA' : 'State catalogue'),
    },
    { key: 'category', header: 'Category', render: (p) => titleCase(p.benefit_category) },
    { key: 'type', header: 'Type', render: (p) => titleCase(p.type) },
    {
      key: 'activities',
      header: 'Your activities',
      align: 'right',
      render: (p) => (p.activities_count ?? 0).toLocaleString(),
    },
    {
      key: 'status',
      header: 'Status',
      render: (p) =>
        p.approval_status !== 'approved' ? (
          // While a decision is open it is the only status worth reading: whatever
          // the delivery lifecycle says, nothing can happen under it yet.
          <Badge variant={statusVariant(`approval.${p.approval_status}`)} dot>
            {p.approval_status === 'pending' ? 'Waiting for approval' : 'Sent back'}
          </Badge>
        ) : (
          <Badge variant={statusVariant(`programme.${p.status}`)} dot>
            {titleCase(p.status)}
          </Badge>
        ),
    },
    {
      key: 'actions',
      header: '',
      align: 'right',
      render: (p) =>
        p.approval_status === 'rejected' ? (
          <div className={styles.rowActions}>
            <Button size="sm" variant="tertiary" onClick={() => setEditing(p)}>
              Edit
            </Button>
            <Button
              size="sm"
              variant="secondary"
              leftIcon={Send}
              loading={decide.isPending}
              onClick={() => decide.mutate({ id: p.id, action: 'submit' })}
            >
              Send again
            </Button>
          </div>
        ) : (
          <Button size="sm" variant="tertiary" leftIcon={Eye} onClick={() => navigate(`/mda/programmes/${p.id}`)}>
            Open
          </Button>
        ),
    },
  ]

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>MDA workspace</span>
        <h1 className={styles.pageTitle}>Programmes</h1>
        <p className={styles.lead}>
          The programmes your MDA delivers — those you run activities under from the state catalogue, and any you have
          created for your own MDA. Open one to see your activities, their budgets and targets, and to create another.
        </p>
        {canCreate && (
          <div className={styles.pageActions}>
            <Button leftIcon={Plus} onClick={() => setCreating(true)}>
              New programme
            </Button>
          </div>
        )}
      </header>

      {(waiting > 0 || sentBack > 0) && (
        <p className={styles.muted}>
          {waiting > 0 && `${waiting} ${waiting === 1 ? 'programme is' : 'programmes are'} waiting for approval. `}
          {sentBack > 0 && `${sentBack} ${sentBack === 1 ? 'was' : 'were'} sent back, with the reason on the row. `}
          You can add activities to a programme once it has been approved.
        </p>
      )}

      <Card flush>
        <DataTable
          caption="Programmes your MDA delivers"
          rows={programmes}
          columns={columns}
          getRowId={(p) => p.id}
          getRowLabel={(p) => p.name}
          loading={isLoading}
          emptyTitle="No programmes yet"
        />
      </Card>

      <section className={styles.section} aria-label="About the catalogue">
        <div className={styles.sectionHead}>
          <Icon icon={LibraryBig} size={16} />
          <h2 className={styles.sectionTitle}>About the catalogue</h2>
        </div>
        <Card>
          <p className={styles.muted}>
            <Icon icon={ClipboardList} size={14} /> The state catalogue is shared — the same programme may be run by
            several MDAs, each through its own activities, and it is maintained by the System Administrator and SP
            Coordination. A programme you create here belongs to your MDA alone: no other MDA can see it, and the
            System Administrator approves it before you can deliver under it.
          </p>
          <p className={styles.footnote}>
            Budget, funding and targets belong to your activity, never to the programme
          </p>
        </Card>
      </section>

      {creating && <ProgrammeFormModal open onClose={() => setCreating(false)} forApproval />}
      {editing && <ProgrammeFormModal open onClose={() => setEditing(null)} programme={editing} forApproval />}
    </div>
  )
}
