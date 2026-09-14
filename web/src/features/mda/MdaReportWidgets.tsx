import { useId } from 'react'
import type { ReactNode } from 'react'
import { formatCount, isHeld } from './mdaReportFormat'
import styles from './mdaReports.module.css'

/*
 * The small pieces the MDA report screens are built from. Presentational only: every
 * figure arrives from a feature hook, already scoped by the server.
 */

export interface CountRow {
  key: string
  label: string
  count: number
}

/**
 * One headline figure in the band's grid. `worded` is for a value that is a phrase
 * ("31.5 hours") rather than a count, set a step smaller so it holds one line.
 */
export function Figure({
  label,
  value,
  held = false,
  worded = false,
}: {
  label: string
  value: string
  held?: boolean
  worded?: boolean
}) {
  return (
    <div className={styles.figure}>
      <dt className={styles.figureLabel}>{label}</dt>
      <dd className={held ? styles.figureHeld : worded ? styles.figureWorded : styles.figureValue}>{value}</dd>
    </div>
  )
}

/** A white card with a heading, for one breakdown. */
export function Panel({ title, sub, children }: { title: string; sub?: string; children: ReactNode }) {
  const id = useId()

  return (
    <section className={styles.card} aria-labelledby={id}>
      <div className={styles.panelHead}>
        <h3 id={id} className={styles.panelTitle}>
          {title}
        </h3>
        {sub && <p className={styles.panelSub}>{sub}</p>}
      </div>
      {children}
    </section>
  )
}

/**
 * Counts across categories as bars on a shared baseline.
 *
 * One series, so one colour: the label carries identity and the length carries size.
 * Every row prints its number, so the bar is a comparison aid rather than the only way
 * to read the value.
 */
export function Bars({
  rows,
  emptyText,
  minimum = null,
}: {
  rows: CountRow[]
  emptyText: string
  minimum?: number | null
}) {
  if (rows.length === 0 || rows.every((row) => row.count === 0)) {
    return <p className={styles.empty}>{emptyText}</p>
  }

  const max = Math.max(1, ...rows.filter((row) => !isHeld(row.count, minimum)).map((row) => row.count))

  return (
    <ul className={styles.bars}>
      {rows.map((row) => {
        const held = isHeld(row.count, minimum)

        return (
          <li key={row.key} className={styles.bar}>
            <span className={styles.barLabel} title={row.label}>
              {row.label}
            </span>
            <span className={styles.barTrack} aria-hidden="true">
              <span
                className={held ? styles.barFillHeld : styles.barFill}
                style={{ width: held ? '100%' : `${(row.count / max) * 100}%` }}
              />
            </span>
            <span className={styles.barValue}>{formatCount(row.count, minimum)}</span>
          </li>
        )
      })}
    </ul>
  )
}
