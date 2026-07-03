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
import { SERVE_STATUS_LABELS } from './constants'
import { useDecideServeRequest, useServeRequests } from './hooks'
import type { ServeRequest } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

const shortId = (id: string) => id.slice(0, 8)

/**
 * Request-to-serve inbox (PRD FR-DUP-05). Lists requests involving the caller's
 * MDA; the OWNER MDA accepts or declines each pending request (grants serve
 * access — never changes ownership). Every decision is audited.
 */
export function ServeRequestsPage() {
  const { hasPermission, user } = useAuth()
  const canView = hasPermission('beneficiary.view')
  const canDecide = hasPermission('beneficiary.approve')
  const myMdaId = user?.mda?.id ?? null

  const { data: requests, isLoading } = useServeRequests(canView)
  const decide = useDecideServeRequest()

  const [target, setTarget] = useState<{ request: ServeRequest; accept: boolean } | null>(null)
  const [reason, setReason] = useState('')
  const [error, setError] = useState<string | null>(null)

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view serve requests.</p>
      </Card>
    )
  }

  const rows = requests ?? []

  async function submitDecision() {
    if (!target) return
    setError(null)
    try {
      await decide.mutateAsync({ id: target.request.id, accept: target.accept, reason: reason.trim() || undefined })
      setTarget(null)
      setReason('')
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not record the decision.')
    }
  }

  const columns: Column<ServeRequest>[] = [
    { key: 'ben', header: 'Beneficiary', render: (r) => <span className={styles.mono}>#{shortId(r.beneficiary_id)}</span> },
    {
      key: 'direction',
      header: 'Direction',
      render: (r) =>
        r.to_mda_id === myMdaId ? (
          <Badge variant="info">Incoming</Badge>
        ) : r.from_mda_id === myMdaId ? (
          <Badge variant="neutral">Outgoing</Badge>
        ) : (
          <Badge variant="outline">—</Badge>
        ),
    },
    {
      key: 'counterparty',
      header: 'Requesting MDA',
      render: (r) => <span className={styles.mono}>#{shortId(r.from_mda_id)}</span>,
    },
    { key: 'reason', header: 'Reason', render: (r) => r.reason ?? <span className={styles.cellSub}>—</span> },
    {
      key: 'status',
      header: 'Status',
      render: (r) => (
        <Badge variant={statusVariant(`serve.${r.status}`)} dot>
          {SERVE_STATUS_LABELS[r.status]}
        </Badge>
      ),
    },
    {
      key: 'actions',
      header: 'Actions',
      render: (r) => {
        const isOwner = r.to_mda_id === myMdaId
        if (!canDecide || !isOwner || r.status !== 'pending') {
          return <span className={styles.cellSub}>—</span>
        }
        return (
          <div className={styles.rowActions}>
            <Button size="sm" leftIcon={Check} onClick={() => setTarget({ request: r, accept: true })}>
              Accept
            </Button>
            <Button size="sm" variant="tertiary" leftIcon={X} onClick={() => setTarget({ request: r, accept: false })}>
              Decline
            </Button>
          </div>
        )
      },
    },
  ]

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">03 · Registry</span>
          <h1 className="t-h1">Serve requests</h1>
          <p className={styles.note}>
            Requests to serve beneficiaries. Accepting grants serve access to the requesting MDA — it never changes
            ownership.
          </p>
        </div>
      </div>

      <DataTable
        caption="Serve requests"
        columns={columns}
        rows={rows}
        getRowId={(r) => r.id}
        loading={isLoading}
        emptyTitle="No serve requests yet"
      />

      <Modal
        open={target !== null}
        onClose={() => setTarget(null)}
        title={target?.accept ? 'Accept serve request' : 'Decline serve request'}
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
              ? 'The requesting MDA will gain serve access to this beneficiary. Ownership is unchanged.'
              : 'The requesting MDA will not gain access. You can add a reason below.'}
          </p>
          {error && (
            <p className={layout.alert} role="alert">
              {error}
            </p>
          )}
          <TextareaField
            label="Reason (optional)"
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
