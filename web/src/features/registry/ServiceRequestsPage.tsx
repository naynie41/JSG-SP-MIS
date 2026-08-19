import { useState } from 'react'
import { Link } from 'react-router-dom'
import { Check, X } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Modal } from '@/components/Modal/Modal'
import { SelectField } from '@/components/Field/SelectField'
import type { SelectOption } from '@/components/Field/SelectField'
import { TextareaField } from '@/components/Field/TextareaField'
import { useToast } from '@/components/Toast/ToastProvider'
import { ApiError } from '@/types/api'
import { cn } from '@/lib/utils/cn'
import { useAuth } from '@/lib/auth/AuthProvider'
import { SERVICE_STATUS_LABELS } from './constants'
import { useDecideServiceRequest, useServiceInbox, useServiceOutbox } from './hooks'
import type { ServiceRequest } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

const shortId = (id: string) => id.slice(0, 8)

/**
 * The beneficiary a request concerns. `beneficiary_name` is reveal-safe (never
 * NIN/BVN/contact) and is what makes the decision answerable; the short id stays
 * as a secondary line so a record can still be cited precisely in correspondence.
 */
function BeneficiaryCell({ request }: { request: ServiceRequest }) {
  if (!request.beneficiary_name) {
    return <span className={styles.mono}>#{shortId(request.beneficiary_id)}</span>
  }
  return (
    <div className={styles.cellStack}>
      <span>{request.beneficiary_name}</span>
      <span className={cn(styles.cellSub, styles.mono)}>#{shortId(request.beneficiary_id)}</span>
    </div>
  )
}

/** An agency, by name. Falls back to the short id only if the relation is absent. */
function MdaCell({ mda, id }: { mda?: { id: string; name: string } | null; id: string }) {
  if (!mda?.name) return <span className={styles.mono}>#{shortId(id)}</span>
  return <span>{mda.name}</span>
}

function StatusChip({ status }: { status: ServiceRequest['status'] }) {
  return (
    <Badge variant={statusVariant(`service_request.${status}`)} dot>
      {SERVICE_STATUS_LABELS[status]}
    </Badge>
  )
}

/**
 * Service Requests (§12, FR-OWN-06/07; DESIGN.md §5.9). Two views:
 *  - **Approval inbox** — requests routed to my MDA (owner). Accept opens a
 *    read-access grant for the requester and authorises serving; decline blocks
 *    (a reason is required and surfaced to the requester).
 *  - **My requests** — requests my MDA raised, each with a status chip so the
 *    requester sees pending / accepted / declined (and the decline reason).
 * Ownership never changes; every decision is audited.
 */
export interface ServiceRequestsPageProps {
  /** Rendered inside a host page that owns the heading (the MDA console).
   *  Suppresses this page's own title block so the document keeps a single h1. */
  embedded?: boolean
}

/**
 * Pending / Approved / Declined / History, applied to both directions.
 *
 * "History" is every state rather than a fourth status — a decided request is not
 * archived anywhere, so the full list IS the history.
 */
const STATUS_VIEWS: SelectOption[] = [
  { value: 'pending', label: 'Pending' },
  { value: 'accepted', label: 'Approved' },
  { value: 'declined', label: 'Declined' },
  { value: '', label: 'History — all' },
]

export function ServiceRequestsPage({ embedded = false }: ServiceRequestsPageProps = {}) {
  const { hasPermission } = useAuth()
  const canView = hasPermission('beneficiary.view')
  const canDecide = hasPermission('beneficiary.approve')

  const { data: inbox, isLoading: inboxLoading } = useServiceInbox(canView)
  const { data: outbox, isLoading: outboxLoading } = useServiceOutbox(canView)
  const decide = useDecideServiceRequest()
  // Bulk runs report once at the end; a toast per decision would bury the summary and
  // hide a partial failure among forty successes.
  const bulkDecide = useDecideServiceRequest({ silent: true })
  const toast = useToast()

  // Filtered client-side: both endpoints return the MDA's own requests unpaginated,
  // so this is a view over data already fetched rather than a new query per tab.
  const [inboxView, setInboxView] = useState('pending')
  const [outboxView, setOutboxView] = useState('')
  const [target, setTarget] = useState<{ request: ServiceRequest; accept: boolean } | null>(null)
  const [reason, setReason] = useState('')
  const [error, setError] = useState<string | null>(null)

  const [selected, setSelected] = useState<Set<string>>(new Set())
  const [bulkTarget, setBulkTarget] = useState<{ accept: boolean } | null>(null)
  const [bulkProgress, setBulkProgress] = useState<{ done: number; total: number } | null>(null)

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
    { key: 'ben', header: 'Beneficiary', render: (r) => <BeneficiaryCell request={r} /> },
    { key: 'from', header: 'Requesting MDA', render: (r) => <MdaCell mda={r.from_mda} id={r.from_mda_id} /> },
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
    { key: 'ben', header: 'Beneficiary', render: (r) => <BeneficiaryCell request={r} /> },
    { key: 'to', header: 'Owner MDA', render: (r) => <MdaCell mda={r.owner_mda} id={r.to_mda_id} /> },
    { key: 'reason', header: 'Reason', render: (r) => r.reason ?? <span className={styles.cellSub}>—</span> },
    { key: 'status', header: 'Status', render: (r) => <StatusChip status={r.status} /> },
    {
      key: 'decision',
      header: 'Decision',
      render: (r) => {
        if (r.status === 'accepted') {
          return <span className={styles.cellSub}>Read access granted</span>
        }
        if (r.status !== 'declined') return <span className={styles.cellSub}>—</span>
        // A decline used to be a dead end: a red chip and a reason, with no way
        // forward. A referral is the sanctioned next step when the owner will
        // not grant access, so offer it here (§FR-REF).
        return (
          <div className={styles.cellStack}>
            <span>{r.decision_reason ?? 'Declined'}</span>
            <Link to="/referrals" className={styles.cellSub}>
              Raise a referral instead →
            </Link>
          </div>
        )
      },
    },
  ]

  const inboxRows = (inbox ?? []).filter((r) => inboxView === '' || r.status === inboxView)

  // The selection only ever means PENDING rows. Derived from the live list rather than
  // trusted from state, so a row decided in another tab (or by the single-row buttons)
  // cannot be re-decided from a stale selection.
  const selectedPending = (inbox ?? []).filter((r) => selected.has(r.id) && r.status === 'pending')

  function toggleRow(id: string) {
    setSelected((current) => {
      const next = new Set(current)
      if (next.has(id)) next.delete(id)
      else next.add(id)
      return next
    })
  }

  function toggleAllPending(ids: string[], nextSelected: boolean) {
    const pendingOnPage = ids.filter((id) => (inbox ?? []).some((r) => r.id === id && r.status === 'pending'))
    setSelected((current) => {
      const next = new Set(current)
      for (const id of pendingOnPage) {
        if (nextSelected) next.add(id)
        else next.delete(id)
      }
      return next
    })
  }

  /**
   * Apply one decision to every selected pending request.
   *
   * Sequential because there is no batch endpoint, and each decision is separately
   * audited — which is the point: accepting grants READ access to one person's record,
   * so forty acceptances are forty recorded authorisations, not one. A failure part way
   * through keeps the rows it could not save selected so they can be retried.
   */
  async function runBulkDecision(accept: boolean, bulkReason: string) {
    const targets = selectedPending
    if (targets.length === 0) return

    const failed = new Set<string>()
    setBulkProgress({ done: 0, total: targets.length })

    for (const [index, request] of targets.entries()) {
      try {
        await bulkDecide.mutateAsync({ id: request.id, accept, reason: bulkReason || undefined })
      } catch {
        failed.add(request.id)
      }
      setBulkProgress({ done: index + 1, total: targets.length })
    }

    setBulkProgress(null)
    setSelected(failed)
    setBulkTarget(null)
    setReason('')

    const saved = targets.length - failed.size
    if (failed.size === 0) {
      toast.success(
        `${saved} ${saved === 1 ? 'request' : 'requests'} ${accept ? 'accepted' : 'declined'}`,
        accept ? 'Read access granted; each decision is in the audit log.' : 'Each decision is in the audit log.',
      )
    } else {
      toast.error(
        `${saved} of ${targets.length} saved`,
        `${failed.size} could not be saved and ${failed.size === 1 ? 'is' : 'are'} still selected. Try again.`,
      )
    }
  }
  const outboxRows = (outbox ?? []).filter((r) => outboxView === '' || r.status === outboxView)
  // Incoming AND pending is the only combination that is work waiting on this MDA —
  // the same definition the Overview counter uses (MdaActionRequiredService).
  const awaitingUs = (inbox ?? []).filter((r) => r.status === 'pending').length

  return (
    <div>
      {!embedded && (
        <div className={layout.pageHead}>
          <div className={layout.pageTitle}>
            <span className="eyebrow">03 · Registry</span>
            <h1 className="t-h1">Service requests</h1>
            <p className={styles.note}>
              A non-owner MDA asks to serve a beneficiary. Accepting grants the requester READ access to the full
              record and authorises serving — it never changes ownership. Declining blocks access.
            </p>
          </div>
        </div>
      )}

      {/*
        The inbox is action-required: standalone it carries its own count and tint so it
        reads as a queue rather than a second table of record.

        Embedded, it does NOT. The host (the MDA console) wraps this in a queue panel
        showing the LIVE `/mda/action-required` count — the same number the Overview
        shows. Deriving a second count from the rows fetched here would put two figures
        for one thing on one screen, free to disagree whenever the two requests are
        even slightly out of step. One headline, owned by whoever is authoritative.
      */}
      <Card
        eyebrow="Owner MDA · action required"
        title={!embedded && awaitingUs > 0 ? `Approval inbox — ${awaitingUs} awaiting you` : 'Approval inbox'}
        variant={!embedded && awaitingUs > 0 ? 'mint' : undefined}
      >
        <div className={styles.filters}>
          <SelectField
            className={styles.filterField}
            label="View"
            options={STATUS_VIEWS}
            value={inboxView}
            onChange={(event) => setInboxView(event.target.value)}
          />
        </div>
        {!canDecide && (
          <p className={styles.note}>
            Deciding a request-to-serve is an MDA Administrator permission. You can see what is waiting; an
            administrator accepts or declines it.
          </p>
        )}
        {/* Bulk decisions, offered only over the PENDING view. Selecting across a mixed
            list would let "accept 40" quietly include rows already decided, and an
            approval is not something to re-apply by accident. */}
        {canDecide && selectedPending.length > 0 && (
          <div className={styles.bulkBar}>
            <span className={styles.bulkCount}>
              {bulkProgress
                ? `Saving ${bulkProgress.done} of ${bulkProgress.total}…`
                : `${selectedPending.length} selected`}
            </span>
            <span className={styles.spacer} />
            <Button
              size="sm"
              leftIcon={Check}
              onClick={() => setBulkTarget({ accept: true })}
              loading={decide.isPending || bulkDecide.isPending}
            >
              Accept selected
            </Button>
            <Button
              size="sm"
              variant="tertiary"
              leftIcon={X}
              onClick={() => setBulkTarget({ accept: false })}
              loading={decide.isPending || bulkDecide.isPending}
            >
              Decline selected…
            </Button>
            <Button size="sm" variant="tertiary" onClick={() => setSelected(new Set())}>
              Clear
            </Button>
          </div>
        )}

        <DataTable
          caption="Incoming service requests"
          columns={inboxColumns}
          rows={inboxRows}
          getRowId={(r) => r.id}
          loading={inboxLoading}
          emptyTitle={inboxView === 'pending' ? 'Nothing awaiting your decision' : 'No incoming requests'}
          // Only a pending row can be decided, so only a pending row is selectable.
          selectedIds={canDecide ? selected : undefined}
          onToggleRow={canDecide ? toggleRow : undefined}
          onToggleAll={canDecide ? toggleAllPending : undefined}
        />
      </Card>

      <Card eyebrow="Requester" title="My requests">
        <div className={styles.filters}>
          <SelectField
            className={styles.filterField}
            label="View"
            options={STATUS_VIEWS}
            value={outboxView}
            onChange={(event) => setOutboxView(event.target.value)}
          />
        </div>
        <DataTable
          caption="Service requests my MDA raised"
          columns={outboxColumns}
          rows={outboxRows}
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
          {/* What is actually being decided. Without this the officer is
              accepting or refusing on behalf of a hash. */}
          {target && (
            <dl className={styles.dl}>
              <dt>Beneficiary</dt>
              <dd>
                {target.request.beneficiary_name ?? (
                  <span className={styles.mono}>#{shortId(target.request.beneficiary_id)}</span>
                )}
              </dd>
              <dt>Requesting MDA</dt>
              <dd>
                {target.request.from_mda?.name ?? (
                  <span className={styles.mono}>#{shortId(target.request.from_mda_id)}</span>
                )}
              </dd>
              <dt>Their reason</dt>
              <dd>{target.request.reason ?? '—'}</dd>
            </dl>
          )}
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

      {/* Bulk decision. Confirmed rather than immediate: accepting hands another MDA
          READ access to this many citizens' full records at once, which is the largest
          single authorisation this screen can perform. */}
      <Modal
        open={bulkTarget !== null}
        onClose={() => setBulkTarget(null)}
        title={
          bulkTarget?.accept
            ? `Accept ${selectedPending.length} service ${selectedPending.length === 1 ? 'request' : 'requests'}?`
            : `Decline ${selectedPending.length} service ${selectedPending.length === 1 ? 'request' : 'requests'}?`
        }
        footer={
          <>
            <Button variant="tertiary" onClick={() => setBulkTarget(null)} disabled={bulkDecide.isPending}>
              Cancel
            </Button>
            <Button
              variant={bulkTarget?.accept ? 'primary' : 'danger'}
              loading={decide.isPending || bulkDecide.isPending}
              onClick={() => {
                if (!bulkTarget) return
                if (!bulkTarget.accept && reason.trim() === '') {
                  setError('A reason is required to decline a request.')
                  return
                }
                setError(null)
                void runBulkDecision(bulkTarget.accept, reason.trim())
              }}
            >
              {bulkTarget?.accept ? 'Accept all' : 'Decline all'}
            </Button>
          </>
        }
      >
        <div className={styles.stack}>
          <p className={styles.note}>
            {bulkTarget?.accept
              ? `${requestingMdaSummary(selectedPending)} will gain READ access to the full record of each of these ${selectedPending.length} beneficiaries and may serve them. Ownership is unchanged, and every acceptance is recorded separately in the audit log.`
              : `${requestingMdaSummary(selectedPending)} will not gain access. The same reason is shared with them for every request.`}
          </p>
          {error && (
            <p className={layout.alert} role="alert">
              {error}
            </p>
          )}
          <TextareaField
            label={bulkTarget?.accept ? 'Note applied to all (optional)' : 'Reason applied to all'}
            required={!bulkTarget?.accept}
            rows={3}
            value={reason}
            onChange={(event) => setReason(event.target.value)}
            helper="Recorded against each decision in the audit log."
          />
        </div>
      </Modal>
    </div>
  )
}

/**
 * Who is asking, for the bulk confirmation.
 *
 * Names the MDA when a selection comes from one, and counts them when it does not —
 * "3 MDAs will gain READ access" is the fact that matters, and a list of names would
 * bury it.
 */
function requestingMdaSummary(requests: ServiceRequest[]): string {
  const names = [...new Set(requests.map((r) => r.from_mda?.name).filter((n): n is string => Boolean(n)))]

  if (names.length === 1) return names[0]!
  if (names.length === 0) return 'The requesting MDA'

  return `${names.length} MDAs`
}
