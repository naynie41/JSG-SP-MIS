import { useState } from 'react'
import { CheckCircle2, Undo2 } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Modal } from '@/components/Modal/Modal'
import { TextareaField } from '@/components/Field/TextareaField'
import { useAuth } from '@/lib/auth/AuthProvider'
import { useProgrammeDecision, useProgrammes } from './hooks'
import type { Programme } from './types'
import styles from './programmes.module.css'

const when = (iso: string | null | undefined): string => (iso ? new Date(iso).toLocaleDateString() : '—')

/**
 * Programmes MDAs have created for themselves and are waiting on (§10, revised).
 *
 * Until one is decided, the MDA that submitted it can do nothing with it — no
 * activity, no enrolment, no delivery — so this queue is on the critical path of
 * that MDA's work, and it says who is waiting and since when rather than only what.
 *
 * Sending one back REQUIRES a reason: the reason is the only thing the MDA has to
 * work from, and the server refuses a rejection without one.
 */
export function ProgrammeApprovalsPanel() {
  const { hasPermission } = useAuth()
  const canDecide = hasPermission('programme.approve')
  const [rejecting, setRejecting] = useState<Programme | null>(null)
  const [reason, setReason] = useState('')
  const decide = useProgrammeDecision()

  const { data, isLoading } = useProgrammes({ approval: 'pending', per_page: 100 }, canDecide)

  if (!canDecide) {
    return <p className={styles.note}>Only the System Administrator approves the programmes MDAs create.</p>
  }

  const rows = data?.items ?? []

  const sendBack = async () => {
    if (rejecting === null || reason.trim() === '') return
    await decide.mutateAsync({ id: rejecting.id, action: 'reject', note: reason.trim() })
    setRejecting(null)
    setReason('')
  }

  const columns: Column<Programme>[] = [
    { key: 'name', header: 'Programme', render: (p) => p.name },
    { key: 'mda', header: 'Created by', render: (p) => p.owner_mda?.name ?? '—' },
    {
      key: 'objective',
      header: 'Objective',
      render: (p) => <span className={styles.decisionObjective}>{p.objective ?? '—'}</span>,
    },
    { key: 'type', header: 'Type', render: (p) => <Badge variant="info">{p.type}</Badge> },
    { key: 'submitted', header: 'Waiting since', render: (p) => when(p.submitted_at) },
    {
      key: 'actions',
      header: '',
      align: 'right',
      render: (p) => (
        <div className={styles.decisionActions}>
          <Button
            size="sm"
            variant="tertiary"
            leftIcon={Undo2}
            onClick={() => {
              setRejecting(p)
              setReason('')
            }}
          >
            Send back
          </Button>
          <Button
            size="sm"
            leftIcon={CheckCircle2}
            loading={decide.isPending}
            onClick={() => decide.mutate({ id: p.id, action: 'approve' })}
          >
            Approve
          </Button>
        </div>
      ),
    },
  ]

  return (
    <>
      <DataTable
        caption="Programmes waiting for approval"
        rows={rows}
        columns={columns}
        getRowId={(p) => p.id}
        getRowLabel={(p) => p.name}
        loading={isLoading}
        emptyTitle="Nothing is waiting for approval"
      />

      <Modal
        open={rejecting !== null}
        onClose={() => setRejecting(null)}
        title={`Send back “${rejecting?.name ?? ''}”`}
        footer={
          <>
            <Button variant="tertiary" onClick={() => setRejecting(null)}>
              Cancel
            </Button>
            <Button onClick={sendBack} loading={decide.isPending} disabled={reason.trim() === ''}>
              Send back
            </Button>
          </>
        }
      >
        <TextareaField
          label="Why it is being sent back"
          required
          rows={4}
          helper="The MDA sees this and works from it, so say what to change."
          value={reason}
          onChange={(e) => setReason(e.target.value)}
        />
      </Modal>
    </>
  )
}
