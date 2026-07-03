import { useState } from 'react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { RadioGroup } from '@/components/Field/RadioGroup'
import type { RadioOption } from '@/components/Field/RadioGroup'
import { TextareaField } from '@/components/Field/TextareaField'
import { ApiError } from '@/types/api'
import { RESOLUTION_LABELS } from './constants'
import { useResolveRow } from './hooks'
import type { ImportRow, ImportRowResolution } from './types'
import formStyles from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

interface ResolveRowControlsProps {
  batchId: string
  row: ImportRow
  canResolve: boolean
}

/**
 * Per-row resolution controls for a flagged import row (PRD FR-DUP-05): create as
 * NEW (justification required), LINK / request-to-serve an existing record (no
 * duplicate; raises a serve request on commit), or SKIP. The decision is saved and
 * audited immediately; the effect is applied when the batch is confirmed.
 */
export function ResolveRowControls({ batchId, row, canResolve }: ResolveRowControlsProps) {
  const resolve = useResolveRow(batchId)
  const registryCandidates = row.match.candidates.filter((c) => c.type === 'registry' && c.reveal?.id)
  const canLink = registryCandidates.length > 0

  const [resolution, setResolution] = useState<ImportRowResolution>(
    row.resolution ?? (canLink ? 'link' : 'new'),
  )
  const [note, setNote] = useState(row.resolution_note ?? '')
  const [beneficiaryId, setBeneficiaryId] = useState(
    row.resolved_beneficiary_id ?? registryCandidates[0]?.reveal?.id ?? '',
  )
  const [error, setError] = useState<string | null>(null)

  if (!canResolve) {
    return <p className={styles.note}>You do not have permission to resolve rows.</p>
  }

  const options: RadioOption[] = [
    { value: 'new', label: 'Create as new — justification required' },
    { value: 'link', label: 'Link / request-to-serve existing', disabled: !canLink },
    { value: 'skip', label: 'Skip this row' },
  ]

  async function save() {
    setError(null)
    if (resolution === 'new' && note.trim() === '') {
      setError('A justification is required to create a new record for a flagged row.')
      return
    }
    if (resolution === 'link' && !beneficiaryId) {
      setError('Choose which existing record to link to.')
      return
    }
    try {
      await resolve.mutateAsync({
        rowNumber: row.row_number,
        input: {
          resolution,
          note: resolution === 'new' ? note.trim() : undefined,
          beneficiary_id: resolution === 'link' ? beneficiaryId : undefined,
        },
      })
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not save the decision.')
    }
  }

  return (
    <div className={styles.resolveBox}>
      <div className={styles.candidateMeta}>
        <span className="eyebrow">Resolve row {row.row_number}</span>
        {row.resolution && (
          <Badge variant={statusVariant(`resolution.${row.resolution}`)}>
            Saved: {RESOLUTION_LABELS[row.resolution]}
          </Badge>
        )}
      </div>

      {error && (
        <p className={formStyles.alert} role="alert">
          {error}
        </p>
      )}

      <RadioGroup
        label="Decision"
        name={`resolution-${row.row_number}`}
        options={options}
        value={resolution}
        onChange={(value) => setResolution(value as ImportRowResolution)}
      />

      {resolution === 'new' && (
        <TextareaField
          label="Justification"
          required
          rows={3}
          value={note}
          onChange={(event) => setNote(event.target.value)}
          helper="Recorded in the audit log with your name and the matched record."
        />
      )}

      {resolution === 'link' && canLink && (
        <RadioGroup
          label="Link to existing record"
          name={`link-${row.row_number}`}
          value={beneficiaryId}
          onChange={setBeneficiaryId}
          options={registryCandidates.map((c) => ({
            value: c.reveal!.id!,
            label: `${c.reveal!.full_name} · ${c.reveal!.owner_mda?.name ?? 'Unknown MDA'}`,
          }))}
        />
      )}

      <div>
        <Button size="sm" onClick={save} loading={resolve.isPending}>
          Save decision
        </Button>
      </div>
    </div>
  )
}
