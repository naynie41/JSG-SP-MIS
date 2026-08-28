import { useMemo, useState } from 'react'
import { AlertTriangle, ArrowRight, BookmarkCheck, Check, ShieldAlert } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { Icon } from '@/components/Icon/Icon'
import { SelectField } from '@/components/Field/SelectField'
import { TextField } from '@/components/Field/TextField'
import { ApiError } from '@/types/api'
import { useConfirmMapping, useImportMapping } from './hooks'
import type { ImportMappingProposal, MappingConfidence } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

/** "Not present in this file" — distinct from "not yet answered". */
const ABSENT = '__absent__'

const FIELD_LABELS: Record<string, string> = {
  // One combined name column, split into first/last on the way in. Named so it is
  // obviously an alternative to the two below rather than a third name field.
  full_name: 'Full name (one column)',
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

const CONFIDENCE: Record<MappingConfidence, { label: string; variant: 'success' | 'warning' | 'neutral' }> = {
  high: { label: 'Likely', variant: 'success' },
  low: { label: 'Uncertain', variant: 'warning' },
  none: { label: 'No suggestion', variant: 'neutral' },
}

/**
 * The column-mapping step (CLAUDE.md §11, PRD v1.7) — between upload and validation.
 *
 * A source file uses the MDA's own column names; this is where they are declared to mean
 * a canonical field. The screen exists because that declaration must be made by a person
 * for NIN, BVN, name and phone: a wrong identity mapping does not fail, it succeeds
 * wrongly, and the duplicate cascade then merges two different citizens with confidence.
 *
 * So the design keeps three things visible at once — the suggestion, its confidence, and
 * REAL VALUES from the file. "Is `national_id` the NIN?" is unanswerable from the header
 * and obvious from three values, and a confirmation nobody can evaluate is theatre.
 *
 * The server enforces the guard (`MAPPING_INCOMPLETE`); the disabled button here is
 * courtesy, not protection.
 */
/**
 * One sentence saying where a pre-filled mapping came from.
 *
 * A saved template and a recognised earlier file warrant different scrutiny, and the
 * attribution ("confirmed by X on DATE") is what lets a reviewer judge whether to trust
 * it — so it is stated plainly rather than implied by a generic "pre-filled" note.
 */
function prefillSentence(source: NonNullable<ImportMappingProposal['prefilled_from']>): string {
  if (source.type === 'template') {
    return `Pre-filled from your saved mapping “${source.name}”.`
  }

  const attribution = [
    source.confirmed_by ? `confirmed by ${source.confirmed_by}` : null,
    source.confirmed_at ? `on ${source.confirmed_at.slice(0, 10)}` : null,
  ]
    .filter(Boolean)
    .join(' ')

  return `This file has the same columns as “${source.name}”, so its mapping has been pre-filled${
    attribution ? ` (${attribution})` : ''
  }.`
}

export function ImportMappingPanel({ batchId }: { batchId: string }) {
  const { data: proposal, isLoading } = useImportMapping(batchId)
  const confirm = useConfirmMapping(batchId)

  // `undefined` = untouched (fall back to the server's map/suggestion);
  // ABSENT = explicitly "not present"; a string = a chosen header.
  const [answers, setAnswers] = useState<Record<string, string | undefined>>({})
  const [templateName, setTemplateName] = useState('')
  const [error, setError] = useState<string | null>(null)

  const identityFields = proposal?.identity_fields ?? []

  /** The value shown in a field's selector: the officer's answer, else what the server pre-filled. */
  function valueFor(field: string, p: ImportMappingProposal): string {
    const answered = answers[field]
    if (answered !== undefined) return answered

    const prefilled = p.column_map[field]
    if (field in p.column_map) return prefilled ?? ABSENT

    // A suggestion is NOT an answer — it is shown, never pre-selected for identity
    // fields, or the guard would be satisfied by a machine guess.
    return identityFields.includes(field) ? '' : (p.suggestions[field]?.header ?? '')
  }

  const unanswered = useMemo(() => {
    if (!proposal) return []

    const missing = identityFields.filter((field) => valueFor(field, proposal) === '')

    /*
     * A SOCU-mined batch must also point at the column holding each row's SOCU id.
     * The batch flag says these people came from SOCU; this says WHICH SOCU record
     * each one is. Without it the claim cannot be traced back to the register.
     *
     * Unlike the identity fields, "not present" is not an answer here — the server
     * refuses the confirmation either way, so the button reflects that rather than
     * letting the officer submit into a 422.
     */
    if (proposal.requires_source_record_id && !answers.original_record_id) {
      missing.push('original_record_id')
    }

    return missing
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [proposal, answers, identityFields])

  if (isLoading || !proposal) {
    return (
      <Card>
        <p className={styles.cellSub}>Reading the file’s columns…</p>
      </Card>
    )
  }

  const headerOptions = [
    { value: '', label: 'Choose a column…' },
    ...proposal.detected_headers.map((h) => ({ value: h, label: h })),
    { value: ABSENT, label: 'Not present in this file' },
  ]

  async function submit() {
    if (!proposal) return
    setError(null)

    const columnMap: Record<string, string | null> = {}
    for (const field of Object.keys(proposal.suggestions)) {
      const value = valueFor(field, proposal)
      // Unanswered NON-identity fields are simply omitted; identity fields cannot be
      // unanswered by this point because the button is disabled until they are.
      if (value === '') continue
      columnMap[field] = value === ABSENT ? null : value
    }

    try {
      await confirm.mutateAsync({ columnMap, saveTemplateAs: templateName.trim() || undefined })
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not confirm the mapping.')
    }
  }

  const fields = Object.keys(proposal.suggestions)
  const identity = fields.filter((f) => identityFields.includes(f))
  const others = fields.filter((f) => !identityFields.includes(f))

  function renderField(field: string) {
    const suggestion = proposal!.suggestions[field] ?? { header: null, confidence: 'none' as const, reason: '' }
    const value = valueFor(field, proposal!)
    const isIdentity = identityFields.includes(field)

    /*
     * Values from the chosen column, or from the SUGGESTED one while nothing is chosen.
     * The samples are what make the decision possible, so they have to be visible
     * BEFORE the officer picks — showing them only after selection would mean deciding
     * blind and then being shown the evidence.
     */
    const shownHeader = value && value !== ABSENT ? value : suggestion.header
    const samples = shownHeader ? (proposal!.samples[shownHeader] ?? []) : []

    return (
      <div key={field} className={styles.mapRow}>
        <SelectField
          label={FIELD_LABELS[field] ?? field}
          required={isIdentity}
          options={headerOptions}
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
          {isIdentity && value === '' && (
            <Badge variant="danger" dot>
              <Icon icon={ShieldAlert} size={12} /> Confirmation required
            </Badge>
          )}
          {isIdentity && value !== '' && (
            <Badge variant="success" dot>
              <Icon icon={Check} size={12} /> Confirmed
            </Badge>
          )}
          {samples.length > 0 && (
            <span className={styles.cellSub}>
              e.g. <span className={styles.mono}>{samples.slice(0, 3).join(', ')}</span>
            </span>
          )}
        </div>
      </div>
    )
  }

  return (
    <div className={styles.stack}>
      {/* Where the pre-fill came from. Naming the source is what makes this a review
          rather than a formality — "confirm these choices" is unanswerable if the
          reviewer cannot see whose choices they were. */}
      {proposal.prefilled_from && (
        <Card variant="mint">
          <p className={styles.note}>
            <Icon icon={BookmarkCheck} size={14} />{' '}
            {prefillSentence(proposal.prefilled_from)}{' '}
            Check the identity fields below before continuing: NIN, BVN, name and phone are
            pre-filled but never pre-confirmed, and the columns behind them may have moved since.
          </p>
        </Card>
      )}

      <Card title="Map your columns" eyebrow="Step 3 · Mapping">
        {error && (
          <p className={layout.alert} role="alert">
            {error}
          </p>
        )}

        <p className={styles.note}>
          Your file’s columns are listed against the fields SP-MIS uses. Suggestions are a starting
          point only. Your MDA is not required to name its columns our way.
        </p>

        <section className={styles.mapGroup}>
          <h3 className="t-h3">
            <Icon icon={ShieldAlert} size={16} /> Identity fields
          </h3>
          <p className={styles.note}>
            These decide whether two records are the same person. Each one must be confirmed on
            every import. Point it at a column, or mark it not present.
          </p>
          {identity.map(renderField)}
        </section>

        <section className={styles.mapGroup}>
          <h3 className="t-h3">Other fields</h3>
          {others.map(renderField)}
        </section>

        <TextField
          label="Save this mapping for next time"
          placeholder="e.g. Health monthly returns"
          value={templateName}
          onChange={(e) => setTemplateName(e.target.value)}
          helper="Optional. A saved mapping pre-fills a future file with the same columns. It never skips identity confirmation."
        />

        <div className={styles.rowActions}>
          <Button
            rightIcon={ArrowRight}
            onClick={submit}
            loading={confirm.isPending}
            disabled={unanswered.length > 0}
          >
            Confirm mapping &amp; continue
          </Button>
          {/* Says WHICH answers are missing — a disabled button with no reason is a
              dead end, and this one is disabled on most first visits. */}
          {unanswered.length > 0 && (
            <span className={styles.cellSub}>
              Confirm {unanswered.map((f) => FIELD_LABELS[f] ?? f).join(', ')} to continue.
            </span>
          )}
        </div>
      </Card>

      {proposal.normalized_preview.length > 0 && (
        <Card title="How your values will be read" eyebrow="Step 4 · Preview">
          <p className={styles.note}>
            The value on the left is stored exactly as your file has it. The value on the right is
            what duplicate checking compares. This is where a wrong mapping shows itself.
          </p>
          <table className={styles.previewTable}>
            <caption className="sr-only">Original and normalized values from the first row</caption>
            <thead>
              <tr>
                <th scope="col">Field</th>
                <th scope="col">Your column</th>
                <th scope="col">As written</th>
                <th scope="col">Compared as</th>
              </tr>
            </thead>
            <tbody>
              {proposal.normalized_preview.map((row) => (
                <tr key={row.field}>
                  <td>{FIELD_LABELS[row.field] ?? row.field}</td>
                  <td className={styles.cellSub}>{row.header}</td>
                  <td className={styles.mono}>{row.original}</td>
                  <td className={styles.mono}>
                    {row.normalized ?? <span className={styles.cellSub}>— not usable</span>}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
          <p className={styles.footnote}>
            A row whose name, phone, NIN or BVN is present but malformed is rejected whole and never
            saved. Other fields that fail are dropped and the row still saves.
          </p>
        </Card>
      )}

      {proposal.unknown_headers.length > 0 && (
        <Card>
          <p className={layout.alert} role="status">
            <Icon icon={AlertTriangle} size={14} /> These columns are named in the saved mapping but
            are not in this file: <span className={styles.mono}>{proposal.unknown_headers.join(', ')}</span>.
            Re-map them before continuing.
          </p>
        </Card>
      )}
    </div>
  )
}
