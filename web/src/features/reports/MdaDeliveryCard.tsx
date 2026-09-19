import { Badge } from '@/components/Badge/Badge'
import { formatNaira } from '@/lib/utils/money'
import { ChartCard } from './charts/ChartCard'
import { compactNaira } from './charts/geometry'
import { formatCount } from './counts'
import type { MdaDeliveryRow } from '@/features/dashboard/types'
import styles from './reportBoard.module.css'

/** Share of a budget actually delivered, as a whole percent. */
function used(row: MdaDeliveryRow): number | null {
  return row.allocated > 0 ? Math.round((row.delivered_value / row.allocated) * 100) : null
}

/**
 * Delivery by agency — the cross-agency comparison, state-wide scope only.
 *
 * Two things are encoded per row, because either alone misleads: the BAR is value
 * delivered against the largest delivering agency, which answers "who is delivering
 * most", and the caption carries how much of that agency's OWN budget that
 * represents, which answers "who is delivering what they planned". A small MDA that
 * has delivered all of its budget outranks a large one sitting on half of its own,
 * and a single bar would say the opposite.
 *
 * An agency with activities and no deliveries keeps its row. That empty bar is the
 * most useful line on the card, and dropping it would hide exactly the finding an
 * oversight reader came for.
 */
export function MdaDeliveryCard({ rows, minimum }: { rows: MdaDeliveryRow[]; minimum: number | null }) {
  const max = Math.max(1, ...rows.map((row) => row.delivered_value))

  const table = {
    caption: 'Delivery by agency',
    columns: ['Agency', 'Type', 'Value delivered', 'Budget used', 'People reached', 'Active activities'],
    rows: rows.map((row) => [
      row.mda ?? 'Unnamed agency',
      row.kind === 'partner' ? 'Development partner' : 'Government',
      formatNaira(row.delivered_value),
      used(row) === null ? '—' : `${used(row)}%`,
      formatCount(row.reached, minimum),
      `${row.activities_active.toLocaleString()} of ${row.activities_total.toLocaleString()}`,
    ]),
  }

  return (
    <ChartCard
      title="Delivery by agency"
      sub="Value delivered by each agency, and how much of its own budget that is"
      table={table}
    >
      <ul className={styles.agencies}>
        {rows.map((row) => {
          const pct = used(row)
          const name = row.mda ?? 'Unnamed agency'
          const reached = formatCount(row.reached, minimum)

          return (
            <li key={row.mda_id} className={styles.agency}>
              <div className={styles.agencyHead}>
                <span className={styles.agencyName} title={name}>
                  {name}
                  {/* Only the partner is tagged. Government is the norm here, and
                      badging every row would make the exception invisible again. */}
                  {row.kind === 'partner' && <Badge variant="accent">Partner</Badge>}
                </span>
                <span className={styles.agencyValue}>{formatNaira(row.delivered_value)}</span>
              </div>
              <span
                className={styles.barTrack}
                role="img"
                aria-label={`${name}: ${compactNaira(row.delivered_value)} delivered to ${reached} people`}
              >
                <span className={styles.barFill} style={{ width: `${(row.delivered_value / max) * 100}%` }} />
              </span>
              <p className={styles.agencyMeta}>
                {pct === null ? 'No budget set' : `${pct}% of its ${compactNaira(row.allocated)} budget`} ·{' '}
                {reached} {row.reached === 1 ? 'person' : 'people'} reached ·{' '}
                {row.activities_active.toLocaleString()} of {row.activities_total.toLocaleString()} activities active
              </p>
            </li>
          )
        })}
      </ul>
      <p className={styles.footnote}>
        A person served by two MDAs is counted once by each, so the people reached here add up to more than the
        state total
      </p>
    </ChartCard>
  )
}
