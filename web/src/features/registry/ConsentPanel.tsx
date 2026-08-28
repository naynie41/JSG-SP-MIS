import { useState } from 'react'
import { ShieldCheck, ShieldOff, ShieldQuestion } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { Icon } from '@/components/Icon/Icon'
import { Modal } from '@/components/Modal/Modal'
import { TextField } from '@/components/Field/TextField'
import { TextareaField } from '@/components/Field/TextareaField'
import { ApiError } from '@/types/api'
import { useAuth } from '@/lib/auth/AuthProvider'
import { useRecordConsent } from './hooks'
import type { Beneficiary, ConsentStatus } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

const when = (iso: string | null): string => {
  if (!iso) return '—'
  const d = new Date(iso)
  return Number.isNaN(d.getTime())
    ? '—'
    : d.toLocaleString(undefined, { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}

const PRESENTATION: Record<ConsentStatus, { label: string; variant: 'success' | 'warning' | 'neutral'; icon: typeof ShieldCheck; note: string }> = {
  granted: {
    label: 'Granted',
    variant: 'success',
    icon: ShieldCheck,
    note: 'This person has consented to their record being shared with another MDA that has been granted access.',
  },
  withdrawn: {
    label: 'Withdrawn',
    variant: 'warning',
    icon: ShieldOff,
    note: 'Consent has been withdrawn. Any cross-MDA grant on this record is suspended for as long as it stays withdrawn.',
  },
  // Deliberately not "No" — an absent decision is not a refusal, and the distinction
  // matters to whoever has to justify the record's handling.
  unknown: {
    label: 'Not recorded',
    variant: 'neutral',
    icon: ShieldQuestion,
    note: 'No consent decision has been recorded. Consent is never assumed: while the sharing gate is on, this behaves the same as withdrawn.',
  },
}

/**
 * Cross-MDA data-sharing consent for one beneficiary (NFR-PRV-01, FR-DSH-01).
 *
 * The server already captured, enforced and audited consent — the two gates
 * (`DataSharingGuard` for sharing, `ConsentGate` for processing) read it on every
 * cross-MDA read and every new intervention. What was missing was any way to SEE or SET
 * it, so a gate could refuse an operation with no in-product way to resolve the refusal.
 *
 * **Owner MDA only.** Consent belongs to the data controller — the MDA that owns the
 * record (FR-OWN-02 semantics). Everyone else sees the status read-only, because
 * whether they may act on this record depends on it.
 *
 * The lawful basis is captured with the decision and lands in the audit trail; it is
 * what an NDPA/NDPR review actually asks for.
 */
export function ConsentPanel({ beneficiary }: { beneficiary: Beneficiary }) {
  const { user } = useAuth()
  const record = useRecordConsent()

  const [target, setTarget] = useState<'granted' | 'withdrawn' | null>(null)
  const [basis, setBasis] = useState('')
  const [note, setNote] = useState('')
  const [error, setError] = useState<string | null>(null)

  const status = beneficiary.sharing_consent ?? 'unknown'
  const presentation = PRESENTATION[status] ?? PRESENTATION.unknown
  const isOwner = user?.mda?.id != null && user.mda.id === beneficiary.owner_mda_id

  function open(next: 'granted' | 'withdrawn') {
    setError(null)
    setBasis('')
    setNote('')
    setTarget(next)
  }

  async function submit() {
    if (!target) return
    setError(null)
    // A grant needs a recorded basis; a withdrawal never does — a person withdrawing
    // consent does not have to justify it.
    if (target === 'granted' && basis.trim() === '') {
      setError('Record how consent was obtained.')
      return
    }
    try {
      await record.mutateAsync({
        id: beneficiary.id,
        input: {
          status: target,
          basis: basis.trim() || undefined,
          note: note.trim() || undefined,
        },
      })
      setTarget(null)
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not record the consent decision.')
    }
  }

  return (
    <Card title="Data-sharing consent" eyebrow="Privacy">
      <div className={styles.candidateMeta}>
        <Badge variant={presentation.variant} dot>
          <Icon icon={presentation.icon} size={12} /> {presentation.label}
        </Badge>
        <span className={styles.cellSub}>Last recorded {when(beneficiary.sharing_consent_at)}</span>
      </div>

      <p className={styles.note}>{presentation.note}</p>

      {isOwner ? (
        <div className={styles.rowActions}>
          {status !== 'granted' && (
            <Button size="sm" leftIcon={ShieldCheck} onClick={() => open('granted')}>
              Record consent
            </Button>
          )}
          {status === 'granted' && (
            <Button size="sm" variant="danger" leftIcon={ShieldOff} onClick={() => open('withdrawn')}>
              Withdraw consent
            </Button>
          )}
        </div>
      ) : (
        <p className={styles.cellSub}>
          Only {beneficiary.owner_mda?.name ?? 'the owning MDA'} can record this person&apos;s consent.
        </p>
      )}

      <p className={styles.note}>
        Every change is kept as an immutable history entry and written to the audit log with who recorded it.
      </p>

      <Modal
        open={target !== null}
        onClose={() => setTarget(null)}
        title={target === 'granted' ? 'Record data-sharing consent' : 'Withdraw data-sharing consent'}
        footer={
          <>
            <Button variant="tertiary" onClick={() => setTarget(null)} disabled={record.isPending}>
              Cancel
            </Button>
            <Button
              variant={target === 'granted' ? 'primary' : 'danger'}
              onClick={submit}
              loading={record.isPending}
            >
              {target === 'granted' ? 'Record consent' : 'Withdraw consent'}
            </Button>
          </>
        }
      >
        <div className={styles.stack}>
          <p className={styles.note}>
            {target === 'granted'
              ? 'Confirms this person agreed their record may be shared with another MDA that has been granted access. It does not itself grant anyone access.'
              : 'Cross-MDA access to this record stops immediately. The record, its history and any audit trail are unaffected.'}
          </p>

          {error && (
            <p className={layout.alert} role="alert">
              {error}
            </p>
          )}

          {target === 'granted' && (
            <TextField
              label="How was consent obtained?"
              required
              value={basis}
              onChange={(event) => setBasis(event.target.value)}
              helper="e.g. signed enrolment form, or verbal at registration. Recorded in the audit log."
            />
          )}

          <TextareaField
            label="Note (optional)"
            rows={2}
            value={note}
            onChange={(event) => setNote(event.target.value)}
          />
        </div>
      </Modal>
    </Card>
  )
}
