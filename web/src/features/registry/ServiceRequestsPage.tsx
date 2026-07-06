import { useState } from 'react'
import { Check, X } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Modal } from '@/components/Modal/Modal'
import { TextareaField } from '@/components/Field/TextareaField'
import { ApiError } from '@/types/api'
import { useAuth } from '@/lib/auth/AuthProvider'
import { SERVICE_STATUS_LABELS } from './constants'
import { useDecideServiceRequest, useServiceInbox, useServiceOutbox } from './hooks'
import type { ServiceRequest } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

const shortId = (id: string) => id.slice(0, 8)

function StatusChip({ status }: { status: ServiceRequest['status'] }) {
  return (
    <Badge variant={statusVariant(`service_request.${status}`)} dot>
      {SERVICE_STATUS_LABELS[status]}
    </Badge>
  )
}

/**
 * Service Requests (§12, FR-OWN-06/07; DESIGN-SYSTEM §5.9). Two views:
 *  - **Approval inbox** — requests routed to my MDA (owner). Accept opens a
 *    read-access grant for the requester and authorises serving; decline blocks
 *    (a reason is required and surfaced to the requester).
 *  - **My requests** — requests my MDA raised, each with a status chip so the
 *    requester sees pending / accepted / declined (and the decline reason).
 * Ownership never changes; every decision is audited.
 */
export function ServiceRequestsPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('beneficiary.view')
  const canDecide = hasPermission('beneficiary.approve')

  const { data: inbox, isLoading: inboxLoading } = useServiceInbox(canView)
  const { data: outbox, isLoading: outboxLoading } = useServiceOutbox(canView)
  const decide = useDecideServiceRequest()

  const [target, setTarget] = useState<{ request: ServiceRequest; accept: boolean } | null>(null)
  const [reason, setReason] = useState('')
  const [error, setError] = useState<string | null>(null)

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view service requests.</p>
      </Card>
    )
  }

  function openDecision(request: ServiceRequest, accept: boolean) {
    setError(null)
    setReason('')
    setTarget({ request, accept })
  }

  async function submitDecision() {
    if (!target) return
    setError(null)
    // Decline requires a reason (server-enforced; surfaced to the requester).
    if (!target.accept && reason.trim() === '') {
      setError('A reason is required to decline a request.')
      return
    }
    try {
      await decide.mutateAsync({ id: target.request.id, accept: target.accept, reason: reason.trim() || undefined })
      setTarget(null)
      setReason('')
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not record the decision.')
    }
  }

  const inboxColumns: Column<ServiceRequest>[] = [
    { key: 'ben', header: 'Beneficiary', render: (r) => <span className={styles.mono}>#{shortId(r.beneficiary_id)}</span> },
    { key: 'from', header: 'Requesting MDA', render: (r) => <span className={styles.mono}>#{shortId(r.from_mda_id)}</span> },
    { key: 'reason', header: 'Reason', render: (r) => r.reason ?? <span className={styles.cellSub}>—</span> },
    { key: 'status', header: 'Status', render: (r) => <StatusChip status={r.status} /> },
    {
      key: 'actions',
      header: 'Actions',
      render: (r) => {
        if (!canDecide || r.status !== 'pending') {
          return <span className={styles.cellSub}>—</span>
        }
        return (
          <div className={styles.rowActions}>
            <Button size="sm" leftIcon={Check} onClick={() => openDecision(r, true)}>
              Accept
            </Button>
            <Button size="sm" variant="tertiary" leftIcon={X} onClick={() => openDecision(r, false)}>
              Decline
            </Button>
          </div>
        )
      },
    },
  ]

  const outboxColumns: Column<ServiceRequest>[] = [
    { key: 'ben', header: 'Beneficiary', render: (r) => <span className={styles.mono}>#{shortId(r.beneficiary_id)}</span> },
    { key: 'to', header: 'Owner MDA', render: (r) => <span className={styles.mono}>#{shortId(r.to_mda_id)}</span> },
    { key: 'reason', header: 'Reason', render: (r) => r.reason ?? <span className={styles.cellSub}>—</span> },
    { key: 'status', header: 'Status', render: (r) => <StatusChip status={r.status} /> },
    {
      key: 'decision',
      header: 'Decision',
      render: (r) =>
        r.status === 'declined' && r.decision_reason ? (
          r.decision_reason
        ) : (
          <span className={styles.cellSub}>{r.status === 'accepted' ? 'Read access granted' : '—'}</span>
        ),
    },
  ]

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">03 · Registry</span>
          <h1 className="t-h1">Service requests</h1>
          <p className={styles.note}>
            A non-owner MDA asks to serve a beneficiary. Accepting grants the requester READ access to the full record
            and authorises serving — it never changes ownership. Declining blocks access.
          </p>
        </div>
      </div>

      <Card eyebrow="Owner MDA" title="Approval inbox">
        <DataTable
          caption="Incoming service requests"
          columns={inboxColumns}
          rows={inbox ?? []}
          getRowId={(r) => r.id}
          loading={inboxLoading}
          emptyTitle="No incoming requests"
        />
      </Card>

      <Card eyebrow="Requester" title="My requests">
        <DataTable
          caption="Service requests my MDA raised"
          columns={outboxColumns}
          rows={outbox ?? []}
          getRowId={(r) => r.id}
          loading={outboxLoading}
          emptyTitle="You have not raised any requests"
        />
      </Card>

      <Modal
        open={target !== null}
        onClose={() => setTarget(null)}
        title={target?.accept ? 'Accept service request' : 'Decline service request'}
        footer={
          <>
            <Button variant="tertiary" onClick={() => setTarget(null)} disabled={decide.isPending}>
              Cancel
            </Button>
            <Button variant={target?.accept ? 'primary' : 'danger'} onClick={submitDecision} loading={decide.isPending}>
              {target?.accept ? 'Accept' : 'Decline'}
            </Button>
          </>
        }
      >
        <div className={styles.stack}>
          <p className={styles.note}>
            {target?.accept
              ? 'The requesting MDA will gain READ access to the full record and may serve this beneficiary. Ownership is unchanged.'
              : 'The requesting MDA will not gain access. A reason is required and shared with them.'}
          </p>
          {error && (
            <p className={layout.alert} role="alert">
              {error}
            </p>
          )}
          <TextareaField
            label={target?.accept ? 'Note (optional)' : 'Reason'}
            required={!target?.accept}
            rows={3}
            value={reason}
            onChange={(event) => setReason(event.target.value)}
            helper="Recorded in the audit log."
          />
        </div>
      </Modal>
    </div>
  )
}
