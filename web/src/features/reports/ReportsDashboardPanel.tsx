import { Spinner } from '@/components/Spinner/Spinner'
import { useDashboard } from '@/features/dashboard/hooks'
import { summariseReporting } from '@/features/dashboard/reportingSummary'
import styles from './reports.module.css'

/** Top slices of a `{ key: count }` map, largest first. */
function top(map: Record<string, number> | undefined, limit = 6): { key: string; count: number }[] {
  return Object.entries(map ?? {})
    .map(([key, count]) => ({ key, count: Number(count) || 0 }))
    .filter((row) => row.count > 0)
    .sort((a, b) => b.count - a.count)
    .slice(0, limit)
}

function titleise(value: string): string {
  return value.replace(/[_-]+/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase())
}

function computedAt(iso: string): string {
  const date = new Date(iso)
  return Number.isNaN(date.getTime())
    ? 'unknown'
    : date.toLocaleString(undefined, { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' })
}

/**
 * The reporting dashboard, in full (PRD FR-DSH-01).
 *
 * This is what the Overview's summary card expands into. It reads the same
 * `useDashboard()` query and runs the same `summariseReporting()` derivation for its
 * headline figures, so the condensed and the full view are the same numbers by
 * construction — not by two implementations agreeing today.
 *
 * Breakdowns are counts of the population above, never new measures: a dashboard that
 * introduces a figure its own headline cannot explain is where trust in the numbers
 * starts to go.
 */
export function ReportsDashboardPanel() {
  const { data, isLoading } = useDashboard()

  if (isLoading || !data) {
    return (
      <div className={styles.dashLoading}>
        <Spinner size={22} label="Loading reporting figures" />
      </div>
    )
  }

  const summary = summariseReporting(data)
  const m = data.metrics

  return (
    <div className={styles.dash}>
      <div className={styles.dashHead}>
        <p className={styles.dashScope}>{summary.scopeLabel}</p>
        <p className={styles.dashMeta}>Computed {computedAt(summary.computedAt)}</p>
      </div>

      {/*
        Six numbers at one size is a list; the reader has to do the ranking. Net-unique
        beneficiaries is THE headline (CLAUDE.md §11), so it leads — the same order of
        importance the Overview card states, stated the same way.
      */}
      <dl className={styles.dashFigures}>
        {summary.tiles.map((tile, index) => (
          <div
            key={tile.key}
            className={index === 0 ? `${styles.dashFigure} ${styles.dashLead}` : styles.dashFigure}
          >
            <dt className={styles.dashFigureLabel}>{tile.label}</dt>
            <dd className={tile.suppressed ? styles.dashFigureHeld : styles.dashFigureValue}>
              {tile.suppressed ? `< ${summary.minCellSize}` : tile.value?.toLocaleString()}
            </dd>
          </div>
        ))}
      </dl>

      {summary.suppressedCount > 0 && (
        <p className={styles.dashNote}>
          {summary.suppressedCount} figure{summary.suppressedCount === 1 ? '' : 's'} withheld:
          groups smaller than {summary.minCellSize} are not published, so individuals cannot be
          identified from a count.
        </p>
      )}

      <div className={styles.dashBreakdowns}>
        <Breakdown title="Beneficiaries by status" rows={top(m.registry.beneficiaries.by_status)} />
        <Breakdown title="Beneficiaries by source" rows={top(m.registry.beneficiaries.by_source)} />
        <Breakdown title="Beneficiaries by LGA" rows={top(m.registry.beneficiaries.by_lga)} />
        <Breakdown
          title="Benefits by type"
          rows={(m.benefits.by_type ?? []).map((group) => ({
            key: group.key ?? 'unspecified',
            count: group.benefit_count,
          }))}
        />
      </div>
    </div>
  )
}

/**
 * A count breakdown as proportional bars.
 *
 * Bars, not a pie or a ring: the reader's question here is "which is biggest, and by how
 * much" and a shared baseline answers it at a glance. Every row carries its number, so
 * the bar is a comparison aid rather than the only way to read the value.
 */
function Breakdown({ title, rows }: { title: string; rows: { key: string; count: number }[] }) {
  if (rows.length === 0) {
    return (
      <section className={styles.breakdown}>
        <h3 className={styles.breakdownTitle}>{title}</h3>
        <p className={styles.breakdownEmpty}>Nothing recorded in this scope yet.</p>
      </section>
    )
  }

  const max = Math.max(...rows.map((r) => r.count))

  return (
    <section className={styles.breakdown}>
      <h3 className={styles.breakdownTitle}>{title}</h3>
      <ul className={styles.breakdownList}>
        {rows.map((row) => (
          <li key={row.key} className={styles.breakdownRow}>
            <span className={styles.breakdownLabel}>{titleise(row.key)}</span>
            <span className={styles.breakdownTrack} aria-hidden>
              <span className={styles.breakdownBar} style={{ width: `${(row.count / max) * 100}%` }} />
            </span>
            <span className={styles.breakdownValue}>{row.count.toLocaleString()}</span>
          </li>
        ))}
      </ul>
    </section>
  )
}
