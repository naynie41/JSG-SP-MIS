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
import type { ImportRow, ImportRowResolution, MatchCandidate } from './types'
import formStyles from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

interface ResolveRowControlsProps {
  batchId: string
  row: ImportRow
  canResolve: boolean
  /** Called after a decision saves — the queue advances to the next undecided row. */
  onResolved?: () => void
}

/**
 * Per-row resolution controls for a flagged import row (PRD FR-DUP-05/09, §9;
 * DESIGN.md §5.9). Two gates decide what is on offer.
 *
 * BAND gates adjudication: the same-person control ("create as new / distinct person")
 * is shown ONLY for **probable** (fuzzy) matches. An **exact** match is definitive and
 * is never adjudicated.
 *
 * OWNERSHIP gates the rest. A match on ANOTHER MDA's record offers provide-service,
 * which raises a request to serve. A match on a record this MDA ALREADY OWNS is a
 * re-upload of its own data: there is nobody to ask, so the offer is to record a new
 * intervention on the person who is already there. The two are never presented as one
 * choice, because they are different acts with different consequences — one needs
 * another MDA's approval before anything is delivered, the other does not.
 */
export function ResolveRowControls({ batchId, row, canResolve, onResolved }: ResolveRowControlsProps) {
  const resolve = useResolveRow(batchId)
  const registryCandidates = row.match.candidates.filter((c) => c.type === 'registry' && c.reveal?.id)

  // Split by ownership: each side drives its own option, and a row can legitimately
  // have both (your record and another MDA's both matched).
  const ownCandidates = registryCandidates.filter((c) => c.owned_by_you)
  const otherCandidates = registryCandidates.filter((c) => !c.owned_by_you)
  const canOwn = ownCandidates.length > 0
  const canLink = otherCandidates.length > 0

  // Adjudication is gated by band: an exact match cannot be resolved as "new".
  const isExact = row.match.band === 'exact'
  const canAdjudicate = !isExact

  const defaultResolution: ImportRowResolution = canOwn
    ? 'own'
    : canLink
      ? 'link'
      : canAdjudicate
        ? 'new'
        : 'skip'

  const [resolution, setResolution] = useState<ImportRowResolution>(row.resolution ?? defaultResolution)
  const [note, setNote] = useState(row.resolution_note ?? '')
  const targets: MatchCandidate[] = resolution === 'own' ? ownCandidates : otherCandidates
  const [beneficiaryId, setBeneficiaryId] = useState(
    row.resolved_beneficiary_id ?? (canOwn ? ownCandidates[0] : otherCandidates[0])?.reveal?.id ?? '',
  )
  const [error, setError] = useState<string | null>(null)

  if (!canResolve) {
    return <p className={styles.note}>You do not have permission to resolve rows.</p>
  }

  const options: RadioOption[] = [
    ...(canAdjudicate
      ? [{ value: 'new', label: 'Not the same person — create new (justification required)' }]
      : []),
    ...(canOwn
      ? [{ value: 'own', label: 'Already in your registry — record a new intervention on the existing record' }]
      : []),
    { value: 'link', label: 'Provide service — link to another MDA’s record (request to serve)', disabled: !canLink },
    { value: 'skip', label: 'Discard this row' },
  ]

  const needsTarget = resolution === 'own' || resolution === 'link'

  async function save() {
    setError(null)
    if (resolution === 'new' && note.trim() === '') {
      setError('A justification is required to create a new record for a flagged row.')
      return
    }
    if (needsTarget && !beneficiaryId) {
      setError('Choose which existing record this row refers to.')
      return
    }
    try {
      await resolve.mutateAsync({
        rowNumber: row.row_number,
        input: {
          resolution,
          note: resolution === 'new' ? note.trim() : undefined,
          beneficiary_id: needsTarget ? beneficiaryId : undefined,
        },
      })
      onResolved?.()
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not save the decision.')
    }
  }

  return (
    <div className={styles.resolveBox}>
      <div className={styles.candidateMeta}>
        <span className="eyebrow">Resolve row {row.row_number}</span>
        {canOwn && <Badge variant="info">Already in your registry</Badge>}
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

      {canOwn && (
        // Says what will happen, not just what matched. An officer re-uploading their
        // own list needs to know the person is kept and delivered to — the alarming
        // reading of "duplicate" is that the row is simply thrown away.
        <p className={styles.note}>
          This person is already in your registry, so no second record is created and no request to
          serve is raised — you own them. Recording an intervention keeps the existing record and
          delivers under this activity.
        </p>
      )}

      {isExact && (
        // Explains why the "not the same person" option is absent rather than
        // silently hiding it. The match strength component already states that
        // an identifier hit is definitive, so this says only what it adds.
        <p className={styles.note}>
          A new record cannot be created for an exact identifier match. Choose whether to record an
          intervention, provide service, or discard this row.
        </p>
      )}

      <RadioGroup
        label="Decision"
        name={`resolution-${row.row_number}`}
        options={options}
        value={resolution}
        onChange={(value) => {
          const next = value as ImportRowResolution
          setResolution(next)
          // The target list differs per outcome, so a selection carried over from the
          // other list would post a beneficiary the server rejects for that resolution.
          const list = next === 'own' ? ownCandidates : next === 'link' ? otherCandidates : []
          setBeneficiaryId(list[0]?.reveal?.id ?? '')
        }}
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

      {needsTarget && targets.length > 0 && (
        <RadioGroup
          label={resolution === 'own' ? 'Existing record to deliver under' : 'Link to existing record'}
          name={`target-${row.row_number}`}
          value={beneficiaryId}
          onChange={setBeneficiaryId}
          options={targets.map((c) => ({
            value: c.reveal!.id!,
            label:
              resolution === 'own'
                ? `${c.reveal!.full_name} · in your registry`
                : `${c.reveal!.full_name} · ${c.reveal!.owner_mda?.name ?? 'Unknown MDA'}`,
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
