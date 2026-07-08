import { useState } from 'react'
import { Check, X } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Modal } from '@/components/Modal/Modal'
import { TextField } from '@/components/Field/TextField'
import { SelectField } from '@/components/Field/SelectField'
import { TextareaField } from '@/components/Field/TextareaField'
import { ApiError } from '@/types/api'
import { useBeneficiaries } from '@/features/registry/hooks'
import { titleCase } from '@/features/registry/constants'
import type { Beneficiary } from '@/features/registry/types'
import { GRIEVANCE_CATEGORY_OPTIONS, GRIEVANCE_CHANNEL_OPTIONS } from './constants'
import { useLogGrievance } from './hooks'
import type { Grievance, GrievanceCategory, GrievanceChannel } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './grievances.module.css'

export interface LogGrievanceModalProps {
  open: boolean
  onClose: () => void
  onLogged?: (grievance: Grievance) => void
}

/**
 * Staff log a grievance on behalf of a beneficiary (FR-GRM-01). No citizen
 * self-service — the handling MDA is the logging staff's MDA. The beneficiary link
 * is optional (a general/anonymous grievance is valid).
 */
export function LogGrievanceModal({ open, onClose, onLogged }: LogGrievanceModalProps) {
  const [category, setCategory] = useState<GrievanceCategory>('payment')
  const [channel, setChannel] = useState<GrievanceChannel>('walk_in')
  const [beneficiary, setBeneficiary] = useState<Beneficiary | null>(null)
  const [query, setQuery] = useState('')
  const [description, setDescription] = useState('')
  const [error, setError] = useState<string | null>(null)

  const results = useBeneficiaries({ page: 1, search: query }, open && query.trim().length > 0 && !beneficiary)
  const log = useLogGrievance()

  function reset() {
    setCategory('payment')
    setChannel('walk_in')
    setBeneficiary(null)
    setQuery('')
    setDescription('')
    setError(null)
  }

  function close() {
    reset()
    onClose()
  }

  async function submit() {
    setError(null)
    if (description.trim() === '') return setError('Describe the grievance.')
    try {
      const grievance = await log.mutateAsync({
        category,
        channel,
        beneficiary_id: beneficiary?.id,
        description: description.trim(),
      })
      reset()
      onLogged?.(grievance)
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not log the grievance.')
    }
  }

  return (
    <Modal
      open={open}
      onClose={close}
      title="Log a grievance"
      footer={
        <>
          <Button variant="tertiary" onClick={close} disabled={log.isPending}>
            Cancel
          </Button>
          <Button onClick={submit} loading={log.isPending}>
            Log grievance
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

        <div className={layout.grid2}>
          <SelectField label="Category" required options={GRIEVANCE_CATEGORY_OPTIONS} value={category} onChange={(e) => setCategory(e.target.value as GrievanceCategory)} />
          <SelectField label="Channel" required options={GRIEVANCE_CHANNEL_OPTIONS} value={channel} onChange={(e) => setChannel(e.target.value as GrievanceChannel)} />
        </div>

        {beneficiary ? (
          <div className={styles.rowActions} style={{ justifyContent: 'space-between' }}>
            <span>
              <strong>{beneficiary.full_name}</strong>{' '}
              <span className={styles.note}>{beneficiary.lga ? titleCase(beneficiary.lga) : '—'}</span>
            </span>
            <Button size="sm" variant="tertiary" leftIcon={X} onClick={() => setBeneficiary(null)}>
              Remove
            </Button>
          </div>
        ) : (
          <div className={styles.stack} style={{ gap: 'var(--space-3)' }}>
            <TextField
              label="Beneficiary (optional)"
              placeholder="Search to link a beneficiary"
              value={query}
              onChange={(e) => setQuery(e.target.value)}
              helper="Leave empty for a general grievance."
            />
            {(results.data?.items ?? []).slice(0, 6).map((b) => (
              <div key={b.id} className={styles.rowActions} style={{ justifyContent: 'space-between' }}>
                <span>
                  {b.full_name} · <span className={styles.note}>{b.lga ? titleCase(b.lga) : '—'}</span>
                </span>
                <Button size="sm" variant="tertiary" leftIcon={Check} onClick={() => { setBeneficiary(b); setQuery('') }}>
                  Link
                </Button>
              </div>
            ))}
          </div>
        )}

        <TextareaField
          label="Description"
          required
          rows={4}
          value={description}
          onChange={(e) => setDescription(e.target.value)}
          helper="What the beneficiary reported. Avoid sensitive identifiers where possible."
        />
      </div>
    </Modal>
  )
}
