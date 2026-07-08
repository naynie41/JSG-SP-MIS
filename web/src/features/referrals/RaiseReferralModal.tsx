import { useState } from 'react'
import { Check, X } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Modal } from '@/components/Modal/Modal'
import { TextField } from '@/components/Field/TextField'
import { SelectField } from '@/components/Field/SelectField'
import { TextareaField } from '@/components/Field/TextareaField'
import { ApiError } from '@/types/api'
import { useAuth } from '@/lib/auth/AuthProvider'
import { useBeneficiaries } from '@/features/registry/hooks'
import { titleCase } from '@/features/registry/constants'
import type { Beneficiary } from '@/features/registry/types'
import { useMdas } from '@/features/mdas/hooks'
import { REFERRAL_NEED_OPTIONS } from './constants'
import { useCreateReferral } from './hooks'
import type { Referral } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './referrals.module.css'

export interface RaiseReferralModalProps {
  open: boolean
  onClose: () => void
  onCreated?: (referral: Referral) => void
}

/**
 * Raise a referral to another MDA (FR-REF-01): pick a beneficiary you can see, the
 * receiving MDA, the need, and optional notes. Ownership never changes — a referral
 * is an outbound request for the receiving MDA to serve.
 */
export function RaiseReferralModal({ open, onClose, onCreated }: RaiseReferralModalProps) {
  const { user } = useAuth()
  const [beneficiary, setBeneficiary] = useState<Beneficiary | null>(null)
  const [query, setQuery] = useState('')
  const [toMda, setToMda] = useState('')
  const [need, setNeed] = useState('Cash transfer')
  const [customNeed, setCustomNeed] = useState('')
  const [notes, setNotes] = useState('')
  const [error, setError] = useState<string | null>(null)

  const results = useBeneficiaries({ page: 1, search: query }, open && query.trim().length > 0 && !beneficiary)
  const mdas = useMdas(open)
  const create = useCreateReferral()

  // Cannot refer a beneficiary to your own MDA.
  const otherMdas = (mdas.data ?? []).filter((m) => m.id !== user?.mda?.id && m.status === 'active')

  function reset() {
    setBeneficiary(null)
    setQuery('')
    setToMda('')
    setNeed('Cash transfer')
    setCustomNeed('')
    setNotes('')
    setError(null)
  }

  function close() {
    reset()
    onClose()
  }

  async function submit() {
    setError(null)
    const resolvedNeed = need === 'Other' ? customNeed.trim() : need
    if (!beneficiary) return setError('Select a beneficiary to refer.')
    if (!toMda) return setError('Select the receiving MDA.')
    if (resolvedNeed === '') return setError('Describe the need.')
    try {
      const referral = await create.mutateAsync({
        beneficiary_id: beneficiary.id,
        to_mda_id: toMda,
        need: resolvedNeed,
        notes: notes.trim() || undefined,
      })
      reset()
      onCreated?.(referral)
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not raise the referral.')
    }
  }

  return (
    <Modal
      open={open}
      onClose={close}
      title="Raise a referral"
      footer={
        <>
          <Button variant="tertiary" onClick={close} disabled={create.isPending}>
            Cancel
          </Button>
          <Button onClick={submit} loading={create.isPending} disabled={!beneficiary || !toMda}>
            Raise referral
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

        {/* Beneficiary */}
        {beneficiary ? (
          <div className={styles.rowActions} style={{ justifyContent: 'space-between' }}>
            <span>
              <strong>{beneficiary.full_name}</strong>{' '}
              <span className={styles.note}>{beneficiary.lga ? titleCase(beneficiary.lga) : '—'}</span>
            </span>
            <Button size="sm" variant="tertiary" leftIcon={X} onClick={() => setBeneficiary(null)}>
              Change
            </Button>
          </div>
        ) : (
          <div className={styles.stack} style={{ gap: 'var(--space-3)' }}>
            <TextField
              label="Beneficiary"
              placeholder="Search beneficiaries by name"
              value={query}
              onChange={(e) => setQuery(e.target.value)}
            />
            {(results.data?.items ?? []).slice(0, 6).map((b) => (
              <div key={b.id} className={styles.rowActions} style={{ justifyContent: 'space-between' }}>
                <span>
                  {b.full_name} · <span className={styles.note}>{b.lga ? titleCase(b.lga) : '—'}</span>
                </span>
                <Button size="sm" variant="tertiary" leftIcon={Check} onClick={() => { setBeneficiary(b); setQuery('') }}>
                  Select
                </Button>
              </div>
            ))}
          </div>
        )}

        <SelectField
          label="Receiving MDA"
          required
          placeholder={mdas.isLoading ? 'Loading MDAs…' : 'Select an MDA'}
          options={otherMdas.map((m) => ({ value: m.id, label: m.name }))}
          value={toMda}
          onChange={(e) => setToMda(e.target.value)}
        />

        <div className={layout.grid2}>
          <SelectField label="Need" required options={REFERRAL_NEED_OPTIONS} value={need} onChange={(e) => setNeed(e.target.value)} />
          {need === 'Other' && (
            <TextField label="Describe the need" required value={customNeed} onChange={(e) => setCustomNeed(e.target.value)} />
          )}
        </div>

        <TextareaField
          label="Notes (optional)"
          rows={3}
          value={notes}
          onChange={(e) => setNotes(e.target.value)}
          helper="Context for the receiving MDA. Do not include sensitive identifiers."
        />
      </div>
    </Modal>
  )
}
