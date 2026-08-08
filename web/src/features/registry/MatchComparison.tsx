import { Check, Minus, X } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { cn } from '@/lib/utils/cn'
import { MATCH_FIELD_LABELS } from './constants'
import type { ComparisonVerdict, FieldComparison, ImportRow } from './types'
import styles from './registry.module.css'

interface MatchComparisonProps {
  /** The incoming spreadsheet row — the officer's own data, shown in full. */
  preview: ImportRow['preview']
  /** Server-computed verdicts. The existing record's values are never sent. */
  comparison: FieldComparison[]
}

const VERDICT_COPY: Record<ComparisonVerdict, string> = {
  exact: 'Same',
  near: 'Nearly the same',
  differs: 'Different',
  absent_incoming: 'Not in this row',
  absent_existing: 'Not on their record',
  absent_both: 'Absent from both',
}

const VERDICT_ICON = {
  exact: Check,
  near: Minus,
  differs: X,
  absent_incoming: Minus,
  absent_existing: Minus,
  absent_both: Minus,
} as const

/** Read a value off the row preview by the matcher's field name. */
function incomingValue(preview: ImportRow['preview'], field: string): string | null {
  const direct = (preview as unknown as Record<string, string | null>)[field]
  if (direct != null && direct !== '') return direct
  // The matcher scores `full_name`; the preview stores the parts.
  if (field === 'full_name') {
    const joined = [preview.first_name, preview.last_name].filter(Boolean).join(' ')
    return joined || null
  }
  return null
}

/**
 * The adjudication comparison (FR-DUP-09).
 *
 * Answering "is this the same person?" requires seeing which fields agreed and
 * which did not. The officer's own row is shown in full; the existing record is
 * represented only by a per-field verdict, because MatchReveal deliberately
 * withholds another MDA's NIN/BVN/phone/DOB (FR-DUP-04). Rendering the verdict
 * rather than the value is what lets this screen be both useful and compliant.
 */
export function MatchComparison({ preview, comparison }: MatchComparisonProps) {
  if (comparison.length === 0) {
    return (
      <p className={styles.note}>
        This match was screened before field-level comparison was recorded. Re-upload the file to
        see which fields agreed.
      </p>
    )
  }

  return (
    <table className={styles.comparison}>
      <caption className="sr-only">
        Field-by-field comparison between this row and the existing record
      </caption>
      <thead>
        <tr>
          <th scope="col">Field</th>
          <th scope="col">This row</th>
          <th scope="col">Existing record</th>
        </tr>
      </thead>
      <tbody>
        {comparison.map((c) => {
          const value = incomingValue(preview, c.field)
          return (
            <tr key={c.field} data-verdict={c.verdict}>
              <th scope="row" className={styles.comparisonField}>
                {MATCH_FIELD_LABELS[c.field] ?? c.field}
                {c.deterministic && (
                  <span className={styles.comparisonKey}> · identifier</span>
                )}
              </th>
              <td className={styles.mono}>{value ?? <span className={styles.cellSub}>—</span>}</td>
              <td>
                <span className={cn(styles.verdict, styles[`verdict_${c.verdict}`])}>
                  <Icon icon={VERDICT_ICON[c.verdict]} size={14} />
                  {VERDICT_COPY[c.verdict]}
                </span>
              </td>
            </tr>
          )
        })}
      </tbody>
    </table>
  )
}
