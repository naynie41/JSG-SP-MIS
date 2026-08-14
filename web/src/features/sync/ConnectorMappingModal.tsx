import { useMemo, useState } from 'react'
import { AlertTriangle, Check, ShieldAlert } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Icon } from '@/components/Icon/Icon'
import { Modal } from '@/components/Modal/Modal'
import { SelectField } from '@/components/Field/SelectField'
import { Spinner } from '@/components/Spinner/Spinner'
import { ApiError } from '@/types/api'
import { useConfirmConnectorMapping, useConnectorMapping } from './hooks'
import type { ConnectorMappingProposal, SyncConnector } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './sync.module.css'

/** "Not present in this source" — an answer, distinct from "not yet answered". */
const ABSENT = '__absent__'

const FIELD_LABELS: Record<string, string> = {
  first_name: 'First name',
  middle_name: 'Middle name',
  last_name: 'Last name',
  nin: 'NIN',
  bvn: 'BVN',
  phone: 'Phone',
  date_of_birth: 'Date of birth',
  gender: 'Gender',
  address: 'Address',
  lga: 'LGA',
  ward: 'Ward',
  household_ref: 'Household reference',
  household_role: 'Household role',
  household_head: 'Household head',
  original_record_id: 'Source record ID',
}

const CONFIDENCE = {
  high: { label: 'Likely', variant: 'success' as const },
  low: { label: 'Uncertain', variant: 'warning' as const },
  none: { label: 'No suggestion', variant: 'neutral' as const },
}

/**
 * A sync connector's STANDING column mapping (CLAUDE.md §11).
 *
 * A file import asks a person which column holds the NIN every time. A connector cannot
 * — it runs on a schedule with nobody present — so the same question is asked ONCE here,
 * at configuration time, and the answer stands for later runs.
 *
 * That makes this screen carry more weight than the file one, not less: a wrong identity
 * mapping on a connector merges citizens on every run rather than once. So it shows the
 * same evidence — suggestion, confidence, and real values sampled from the source — and
 * withholds the same thing: an identity field is never pre-selected from a guess.
 *
 * This reuses the Data Import & Mapping layer (the server samples the source and answers
 * with the same proposal shape a file upload produces). It is not a second mapping
 * engine, and normalization is inherited whole — compare-normalized, store-original.
 */
export function ConnectorMappingModal({
  connector,
  onClose,
}: {
  connector: SyncConnector
  onClose: () => void
}) {
  const { data: proposal, isLoading, error } = useConnectorMapping(connector.id)
  const confirm = useConfirmConnectorMapping(connector.id)

  const [answers, setAnswers] = useState<Record<string, string | undefined>>({})
  const [submitError, setSubmitError] = useState<string | null>(null)

  const identityFields = proposal?.identity_fields ?? []

  function valueFor(field: string, p: ConnectorMappingProposal): string {
    const answered = answers[field]
    if (answered !== undefined) return answered

    if (field in p.column_map) return p.column_map[field] ?? ABSENT

    // A suggestion is shown, never pre-selected for an identity field — otherwise a
    // machine guess would satisfy the very confirmation this screen exists for.
    return identityFields.includes(field) ? '' : (p.suggestions[field]?.header ?? '')
  }

  const unanswered = useMemo(() => {
    if (!proposal) return []
    return identityFields.filter((f) => valueFor(f, proposal) === '')
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [proposal, answers, identityFields])

  async function submit() {
    if (!proposal) return
    setSubmitError(null)

    const columnMap: Record<string, string | null> = {}
    for (const field of Object.keys(proposal.suggestions)) {
      const value = valueFor(field, proposal)
      if (value === '') continue
      columnMap[field] = value === ABSENT ? null : value
    }

    try {
      await confirm.mutateAsync(columnMap)
      onClose()
    } catch (err) {
      setSubmitError(err instanceof ApiError ? err.message : 'Could not confirm the mapping.')
    }
  }

  const fieldOptions = proposal
    ? [
        { value: '', label: 'Choose a source field…' },
        ...proposal.detected_fields.map((f) => ({ value: f, label: f })),
        { value: ABSENT, label: 'Not present in this source' },
      ]
    : []

  function renderField(field: string) {
    const p = proposal!
    const suggestion = p.suggestions[field] ?? { header: null, confidence: 'none' as const, reason: '' }
    const value = valueFor(field, p)
    const isIdentity = identityFields.includes(field)
    const shown = value && value !== ABSENT ? value : suggestion.header
    const samples = shown ? (p.samples[shown] ?? []) : []

    return (
      <div key={field} className={styles.mapRow}>
        <SelectField
          label={FIELD_LABELS[field] ?? field}
          required={isIdentity}
          options={fieldOptions}
          value={value}
          onChange={(e) => setAnswers((a) => ({ ...a, [field]: e.target.value }))}
          helper={suggestion.reason || undefined}
        />
        <div className={styles.mapMeta}>
          {suggestion.header && (
            <Badge variant={CONFIDENCE[suggestion.confidence].variant}>
              {CONFIDENCE[suggestion.confidence].label}: {suggestion.header}
            </Badge>
          )}
          {isIdentity &&
            (value === '' ? (
              <Badge variant="danger" dot>
                <Icon icon={ShieldAlert} size={12} /> Confirmation required
              </Badge>
            ) : (
              <Badge variant="success" dot>
                <Icon icon={Check} size={12} /> Confirmed
              </Badge>
            ))}
          {samples.length > 0 && (
            <span className={styles.sub}>
              e.g. <span className={styles.mono}>{samples.slice(0, 3).join(', ')}</span>
            </span>
          )}
        </div>
      </div>
    )
  }

  return (
    <Modal
      open
      onClose={onClose}
      title={`Column mapping — ${connector.name}`}
      footer={
        <>
          <Button variant="tertiary" onClick={onClose}>
            Cancel
          </Button>
          <Button onClick={submit} loading={confirm.isPending} disabled={!proposal || unanswered.length > 0}>
            Confirm mapping
          </Button>
        </>
      }
    >
      {isLoading && <Spinner size={20} label="Sampling the source" />}

      {error != null && (
        <p className={layout.alert} role="alert">
          Could not read a sample from this source. The mapping cannot be confirmed until it responds.
        </p>
      )}

      {proposal && (
        <>
          {submitError && (
            <p className={layout.alert} role="alert">
              {submitError}
            </p>
          )}

          {proposal.signature_changed && (
            <p className={layout.alert} role="status">
              <Icon icon={AlertTriangle} size={14} /> This source’s fields have changed since the mapping was
              confirmed, so syncing is on hold. Review every identity field below — a field that moved will
              otherwise be read from the wrong column.
            </p>
          )}

          <p className={styles.note}>
            This connector runs unattended, so this mapping is confirmed once here and stands for later
            syncs. It holds automatically if the source’s fields change.
          </p>

          <section className={styles.mapGroup}>
            <h3 className="t-h3">
              <Icon icon={ShieldAlert} size={16} /> Identity fields
            </h3>
            <p className={styles.note}>
              These decide whether two records are the same person. Each must be answered — point it at a
              source field, or mark it not present.
            </p>
            {Object.keys(proposal.suggestions).filter((f) => identityFields.includes(f)).map(renderField)}
          </section>

          <section className={styles.mapGroup}>
            <h3 className="t-h3">Other fields</h3>
            {Object.keys(proposal.suggestions).filter((f) => !identityFields.includes(f)).map(renderField)}
          </section>

          {proposal.normalized_preview.length > 0 && (
            <section className={styles.mapGroup}>
              <h3 className="t-h3">How values will be read</h3>
              <p className={styles.note}>
                Stored exactly as the source sends it; compared using the value on the right.
              </p>
              <table className={styles.previewTable}>
                <caption className="sr-only">Original and normalized values from a sampled record</caption>
                <thead>
                  <tr>
                    <th scope="col">Field</th>
                    <th scope="col">As sent</th>
                    <th scope="col">Compared as</th>
                  </tr>
                </thead>
                <tbody>
                  {proposal.normalized_preview.map((row) => (
                    <tr key={row.field}>
                      <td>{FIELD_LABELS[row.field] ?? row.field}</td>
                      <td className={styles.mono}>{row.original}</td>
                      <td className={styles.mono}>
                        {row.normalized ?? <span className={styles.sub}>— not usable</span>}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </section>
          )}

          {unanswered.length > 0 && (
            <p className={styles.sub}>
              Confirm {unanswered.map((f) => FIELD_LABELS[f] ?? f).join(', ')} to continue.
            </p>
          )}
        </>
      )}
    </Modal>
  )
}
