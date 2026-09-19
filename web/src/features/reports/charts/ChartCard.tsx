import { useId, useState } from 'react'
import type { ReactNode } from 'react'
import { BarChart3, Table2 } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import type { TipState } from './hooks'
import styles from './charts.module.css'

export interface ChartTable {
  caption: string
  columns: string[]
  /** Cells as display strings; every column after the first is right-aligned. */
  rows: string[][]
}

interface ChartCardProps {
  title: string
  sub?: string
  /** The chart's values as a table — every chart has one (dataviz accessibility floor). */
  table?: ChartTable
  actions?: ReactNode
  headingLevel?: 'h3' | 'h4'
  className?: string
  children: ReactNode
}

/** A white card holding one chart, with a Chart/Table switch in its header. */
export function ChartCard({ title, sub, table, actions, headingLevel = 'h3', className, children }: ChartCardProps) {
  const id = useId()
  const [asTable, setAsTable] = useState(false)
  const Heading = headingLevel

  return (
    <section className={className ? `${styles.card} ${className}` : styles.card} aria-labelledby={id}>
      <header className={styles.cardHead}>
        <div className={styles.cardTitles}>
          <Heading id={id} className={styles.cardTitle}>
            {title}
          </Heading>
          {sub && <p className={styles.cardSub}>{sub}</p>}
        </div>
        <div className={styles.cardActions}>
          {actions}
          {table && (
            <button
              type="button"
              className={styles.viewToggle}
              aria-pressed={asTable}
              aria-label={asTable ? `Show ${title} as a chart` : `Show ${title} as a table`}
              onClick={() => setAsTable((current) => !current)}
            >
              <Icon icon={asTable ? BarChart3 : Table2} size={14} />
              {asTable ? 'Chart' : 'Table'}
            </button>
          )}
        </div>
      </header>

      {asTable && table ? <ChartTableView table={table} /> : children}
    </section>
  )
}

function ChartTableView({ table }: { table: ChartTable }) {
  return (
    <div className={styles.tableWrap}>
      <table className={styles.table}>
        <caption className={styles.caption}>{table.caption}</caption>
        <thead>
          <tr>
            {table.columns.map((column, index) => (
              <th key={column} scope="col" className={index > 0 ? styles.num : undefined}>
                {column}
              </th>
            ))}
          </tr>
        </thead>
        <tbody>
          {table.rows.map((row) => (
            <tr key={row.join('|')}>
              {row.map((cell, index) => (
                <td key={index} className={index > 0 ? styles.num : undefined}>
                  {cell}
                </td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}

/** The one tooltip a chart owns. Hidden from assistive tech: marks carry the same text. */
export function ChartTooltip({ tip }: { tip: TipState | null }) {
  if (!tip) return null

  return (
    <div className={styles.tip} style={{ left: tip.x, top: tip.y }} aria-hidden="true">
      <p className={styles.tipTitle}>{tip.title}</p>
      {tip.rows.map((row) => (
        <p key={row.label} className={styles.tipRow}>
          {row.color && <span className={styles.tipKey} style={{ background: row.color }} />}
          <strong className={styles.tipValue}>{row.value}</strong>
          <span className={styles.tipLabel}>{row.label}</span>
        </p>
      ))}
    </div>
  )
}
