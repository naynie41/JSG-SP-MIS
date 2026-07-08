import { useState } from 'react'
import { Link, useParams } from 'react-router-dom'
import { ArrowLeft, Check, Info, Play, Reply, X } from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import type { ButtonVariant } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { Modal } from '@/components/Modal/Modal'
import { Spinner } from '@/components/Spinner/Spinner'
import { TextareaField } from '@/components/Field/TextareaField'
import { Icon } from '@/components/Icon/Icon'
import { ApiError } from '@/types/api'
import { useAuth } from '@/lib/auth/AuthProvider'
import { formatNaira } from '@/lib/utils/money'
import { StatusBadge, SlaIndicator } from './ReferralsPage'
import { REFERRAL_STATUS_LABELS } from './constants'
import { useReferral, useReferralAction } from './hooks'
import type { Referral, ReferralAction } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './referrals.module.css'

type Role = 'receiving' | 'originating' | 'observer'
type InputKind = 'reason' | 'note' | 'outcome'

interface ActionSpec {
  action: ReferralAction
  label: string
  icon: LucideIcon
  variant: ButtonVariant
  input?: { kind: InputKind; label: string; required: boolean; helper?: string }
  title: string
}

/** Lifecycle actions available given the caller's role + the referral's status. */
function actionsFor(referral: Referral, role: Role): ActionSpec[] {
  if (role === 'receiving') {
    switch (referral.status) {
      case 'created':
        return [
          { action: 'accept', label: 'Accept', icon: Check, variant: 'primary', title: 'Accept referral' },
          { action: 'request-info', label: 'Request info', icon: Info, variant: 'tertiary', title: 'Request more information', input: { kind: 'note', label: 'What do you need to know?', required: false } },
          { action: 'reject', label: 'Reject', icon: X, variant: 'danger', title: 'Reject referral', input: { kind: 'reason', label: 'Reason', required: true, helper: 'Shared with the referring MDA.' } },
        ]
      case 'accepted':
        return [
          { action: 'start', label: 'Mark in progress', icon: Play, variant: 'primary', title: 'Mark in progress' },
          { action: 'close', label: 'Close', icon: X, variant: 'tertiary', title: 'Close referral', input: { kind: 'outcome', label: 'Outcome (optional)', required: false } },
        ]
      case 'in_progress':
        return [
          { action: 'complete', label: 'Complete', icon: Check, variant: 'primary', title: 'Complete referral', input: { kind: 'outcome', label: 'Outcome', required: false, helper: 'What was delivered to the beneficiary.' } },
          { action: 'close', label: 'Close', icon: X, variant: 'tertiary', title: 'Close referral', input: { kind: 'outcome', label: 'Outcome (optional)', required: false } },
        ]
      default:
        return []
    }
  }
  if (role === 'originating' && referral.status === 'more_info_requested') {
    return [{ action: 'respond-info', label: 'Respond', icon: Reply, variant: 'primary', title: 'Respond to request', input: { kind: 'note', label: 'Your response', required: true } }]
  }
  return []
}

const TIMELINE_STEPS: { key: keyof Referral['timeline']; label: string }[] = [
  { key: 'created_at', label: 'Referral raised' },
  { key: 'info_requested_at', label: 'More info requested' },
  { key: 'info_responded_at', label: 'Info provided' },
  { key: 'accepted_at', label: 'Accepted' },
  { key: 'rejected_at', label: 'Rejected' },
  { key: 'started_at', label: 'In progress' },
  { key: 'completed_at', label: 'Completed' },
  { key: 'closed_at', label: 'Closed' },
]

function formatTime(iso: string | null): string {
  if (!iso) return ''
  return new Date(iso).toLocaleString(undefined, { dateStyle: 'medium', timeStyle: 'short' })
}

export function ReferralDetailPage() {
  const { id } = useParams<{ id: string }>()
  const { user, hasPermission } = useAuth()
  const { data: referral, isLoading } = useReferral(id)
  const act = useReferralAction(id ?? '')

  const [pending, setPending] = useState<ActionSpec | null>(null)
  const [inputValue, setInputValue] = useState('')
  const [error, setError] = useState<string | null>(null)

  if (isLoading) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={28} />
      </div>
    )
  }
  if (!referral) {
    return (
      <Card>
        <p className={layout.forbidden}>This referral could not be found.</p>
      </Card>
    )
  }

  const canEdit = hasPermission('referral.edit')
  const myMda = user?.mda?.id
  const role: Role = myMda === referral.to_mda_id ? 'receiving' : myMda === referral.from_mda_id ? 'originating' : 'observer'
  const actions = canEdit ? actionsFor(referral, role) : []

  function openAction(spec: ActionSpec) {
    setError(null)
    setInputValue('')
    if (spec.input) {
      setPending(spec)
    } else {
      void run(spec)
    }
  }

  async function run(spec: ActionSpec) {
    setError(null)
    const value = inputValue.trim()
    if (spec.input?.required && value === '') {
      setError(`${spec.input.label} is required.`)
      return
    }
    const input =
      spec.input?.kind === 'reason' ? { reason: value } :
      spec.input?.kind === 'note' ? { note: value || undefined } :
      spec.input?.kind === 'outcome' ? { outcome: value || undefined } : {}
    try {
      await act.mutateAsync({ action: spec.action, input })
      setPending(null)
      setInputValue('')
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not complete the action.')
    }
  }

  const roleLabel =
    role === 'receiving' ? 'Your MDA is the receiving MDA' :
    role === 'originating' ? 'Your MDA raised this referral' : 'Your MDA is an observer'

  return (
    <div>
      <Link to="/referrals" className={styles.backLink}>
        <Icon icon={ArrowLeft} size={14} /> All referrals
      </Link>

      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">05 · Coordination · Referral</span>
          <h1 className="t-h1">{referral.need}</h1>
          <div className={styles.rowActions}>
            <StatusBadge status={referral.status} />
            <SlaIndicator referral={referral} />
            <span className={styles.mono}>· {roleLabel}</span>
          </div>
        </div>
      </div>

      <div className={styles.detailGrid}>
        <div className={styles.stack}>
          <Card title="Details" eyebrow="Referral">
            <dl className={styles.dl}>
              <dt>Beneficiary</dt>
              <dd className={styles.mono}>#{referral.beneficiary_id.slice(0, 8)}</dd>
              <dt>Referring MDA</dt>
              <dd className={styles.mono}>#{referral.from_mda_id.slice(0, 8)}</dd>
              <dt>Receiving MDA</dt>
              <dd className={styles.mono}>#{referral.to_mda_id.slice(0, 8)}</dd>
              <dt>Need</dt>
              <dd>{referral.need}</dd>
              <dt>Notes</dt>
              <dd>{referral.notes ?? '—'}</dd>
              {referral.info_request && (<><dt>Info requested</dt><dd>{referral.info_request}</dd></>)}
              {referral.info_response && (<><dt>Info provided</dt><dd>{referral.info_response}</dd></>)}
              {referral.reason && (<><dt>Reject reason</dt><dd>{referral.reason}</dd></>)}
            </dl>

            {referral.outcome && (
              <p className={styles.outcome} style={{ marginTop: 'var(--space-4)' }}>
                <strong>Outcome:</strong> {referral.outcome}
              </p>
            )}
          </Card>

          <Card title="Timeline" eyebrow="Lifecycle">
            <ol className={styles.timeline}>
              {TIMELINE_STEPS.filter((step) => referral.timeline[step.key]).map((step) => (
                <li key={step.key} className={styles.timelineItem}>
                  <span className={styles.timelineDot} />
                  <span>
                    <span className={styles.timelineLabel}>{step.label}</span>
                    <span className={styles.timelineTime}>{formatTime(referral.timeline[step.key])}</span>
                  </span>
                </li>
              ))}
            </ol>
          </Card>
        </div>

        <div className={styles.stack}>
          <Card title="Actions" eyebrow={REFERRAL_STATUS_LABELS[referral.status]}>
            {actions.length === 0 ? (
              <p className={styles.note}>
                {role === 'observer'
                  ? 'Only the referring and receiving MDAs can act on this referral.'
                  : 'No actions are available in this state.'}
              </p>
            ) : (
              <div className={styles.actionBar}>
                {actions.map((spec) => (
                  <Button key={spec.action} variant={spec.variant} leftIcon={spec.icon} onClick={() => openAction(spec)} loading={act.isPending && pending === null}>
                    {spec.label}
                  </Button>
                ))}
              </div>
            )}
          </Card>

          {referral.ledger && (referral.status === 'completed' || referral.status === 'closed') && (
            <Card title="Ledger reconciliation" eyebrow="FR-REF-03" variant="mint">
              <dl className={styles.dl}>
                <dt>Deliveries</dt>
                <dd>{referral.ledger.benefit_count}</dd>
                <dt>Recorded value</dt>
                <dd>{formatNaira(referral.ledger.benefit_value_total)}</dd>
              </dl>
              <p className={styles.note} style={{ marginTop: 'var(--space-3)' }}>
                Deliveries the receiving MDA recorded for this beneficiary since acceptance. SP-MIS records delivery, not payment.
              </p>
            </Card>
          )}
        </div>
      </div>

      <Modal
        open={pending !== null}
        onClose={() => setPending(null)}
        title={pending?.title ?? ''}
        footer={
          <>
            <Button variant="tertiary" onClick={() => setPending(null)} disabled={act.isPending}>
              Cancel
            </Button>
            <Button variant={pending?.variant === 'danger' ? 'danger' : 'primary'} onClick={() => pending && run(pending)} loading={act.isPending}>
              {pending?.label}
            </Button>
          </>
        }
      >
        <div className={styles.stack}>
          {error && (
            <p className={layout.alert} role="alert">
              {error}
            </p>
          )}
          {pending?.input && (
            <TextareaField
              label={pending.input.label}
              required={pending.input.required}
              helper={pending.input.helper}
              rows={3}
              value={inputValue}
              onChange={(e) => setInputValue(e.target.value)}
            />
          )}
        </div>
      </Modal>
    </div>
  )
}
