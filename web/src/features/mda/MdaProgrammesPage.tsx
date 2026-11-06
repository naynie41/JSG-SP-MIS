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
import { useWorkspaceIdentity } from './workspaceIdentity'
import { ProgrammeFormModal } from '@/features/programmes/ProgrammeFormModal'
import { useProgrammeDecision, useProgrammes } from '@/features/programmes/hooks'
import type { Programme } from '@/features/programmes/types'
import { titleCase } from './format'
import styles from './mda.module.css'

/**
 * Programmes — what THIS MDA delivers, of two kinds.
 *
 * Most rows are shared catalogue programmes an administrator ASSIGNED to this MDA
 * (§10, revised 2026-11-05) — what it offers. The catalogue itself is owned centrally
 * and read-only here, and an MDA is never assigned the whole of it.
 *
 * The rest are programmes this MDA created FOR ITSELF (§10, revised): no other MDA
 * sees them, and each waits for the System Administrator before it can carry any
 * activity. Both come from the same list — the server returns the assigned catalogue
 * plus the MDA's own programmes, including one still waiting for a decision, which
 * has no assignment row yet because approval is what writes it.
 *
 * An empty page therefore means one of two things, and the empty state says which:
 * nothing has been assigned to this organisation yet.
 */
export function MdaProgrammesPage() {
  const identity = useWorkspaceIdentity()
  const { hasPermission } = useAuth()
  const canView = hasPermission('programme.view')
  const canCreate = hasPermission('programme.create')
  const navigate = useNavigate()
  const [creating, setCreating] = useState(false)
  const [editing, setEditing] = useState<Programme | null>(null)
  const decide = useProgrammeDecision()

  // No `participating` filter any more.
  //
  // It meant "programmes this MDA has activities under", which was the only way to
  // tell what an MDA ran while every MDA could see the whole catalogue. Since §10's
  // 2026-11-05 revision the server returns the MDA's ASSIGNED catalogue by default,
  // which is the better answer to the same question: a programme an administrator
  // assigned belongs on this page from the moment it is assigned, not from the moment
  // someone builds the first activity under it. Keeping the filter would have hidden
  // every newly assigned programme — exactly the ones an officer is looking for.
  const { data, isLoading } = useProgrammes({ per_page: 100 }, canView)

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
      render: (p) => (p.is_central === false ? `Your ${identity.org}` : 'State catalogue'),
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
        <span className={styles.eyebrow}>{identity.workspace}</span>
        <h1 className={styles.pageTitle}>Programmes</h1>
        <p className={styles.lead}>
          The programmes your {identity.org} delivers — those assigned to you from the state catalogue, and any you
          have created for your own {identity.org}. Open one to see your activities, their budgets and targets, and to
          create another.
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
          caption={`Programmes your ${identity.org} delivers`}
          rows={programmes}
          columns={columns}
          getRowId={(p) => p.id}
          getRowLabel={(p) => p.name}
          loading={isLoading}
          emptyTitle="No programmes assigned yet"
          emptyAction={
            /* An organisation with nothing assigned offers nothing and cannot create
               an activity — a deliberate state (owner, 2026-11-05), not a fault. The
               empty table alone would read as "the system lost my programmes", so it
               says what happened and what to do about it. */
            <p className={styles.muted}>
              Your {identity.org} has not been assigned any catalogue programmes. Ask the System Administrator or SP
              Coordination to assign the ones you deliver — or create a programme of your own below, which they
              approve before you can deliver under it.
            </p>
          }
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
            several agencies, each through its own activities, and it is maintained by the System Administrator and SP
            Coordination. You see the programmes assigned to your {identity.org}, which is what you can choose when
            creating an activity or recording delivery; ask them to assign one you deliver but cannot find. A
            programme you create here belongs to your {identity.org} alone: no other agency can see it, and the System
            Administrator approves it before you can deliver under it.
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
