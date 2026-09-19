import { useId } from 'react'
import { formatCount, isHeld } from '../counts'
import type { CountRow } from '../counts'
import { ChartTooltip } from './ChartCard'
import { columnPath } from './geometry'
import { useChartWidth, useTooltip } from './hooks'
import styles from './charts.module.css'

interface ColumnChartProps {
  label: string
  rows: CountRow[]
  unit: string
  minimum?: number | null
  emptyText: string
  height?: number
}

const PAD_TOP = 24
const PAD_BOTTOM = 26
const MAX_COLUMN = 24

/**
 * Ordered categories as columns: one hue, a value on each cap, the category below. The
 * whole column band is the hover target, not just the painted column. A group withheld
 * by the small-cell rule keeps a hatched stub and says so, rather than reading as zero.
 */
export function ColumnChart({ label, rows, unit, minimum = null, emptyText, height = 190 }: ColumnChartProps) {
  const { ref, width } = useChartWidth(360)
  const { tip, bind } = useTooltip(ref)
  const patternId = useId().replace(/:/g, '')

  if (rows.length === 0 || rows.every((row) => row.count === 0)) {
    return <p className={styles.empty}>{emptyText}</p>
  }

  const band = width / rows.length
  // A category name wider than its column breaks onto two lines instead of running into
  // its neighbour; the plot gives up a line of height to make room.
  const wraps = rows.some((row) => row.label.includes(' ') && row.label.length * 6.5 > band - 6)
  const padBottom = wraps ? PAD_BOTTOM + 14 : PAD_BOTTOM
  const innerHeight = height - PAD_TOP - padBottom
  const columnWidth = Math.min(MAX_COLUMN, band * 0.6)
  const max = Math.max(1, ...rows.filter((row) => !isHeld(row.count, minimum)).map((row) => row.count))
  const baseline = PAD_TOP + innerHeight

  return (
    <div ref={ref} className={`${styles.plot} ${styles.columns}`}>
      <svg width={width} height={height} className={styles.svg} role="group" aria-label={label}>
        <defs>
          <pattern id={patternId} width="6" height="6" patternUnits="userSpaceOnUse" patternTransform="rotate(45)">
            <line x1="0" y1="0" x2="0" y2="6" stroke="var(--border-strong)" strokeWidth="2" />
          </pattern>
        </defs>
        <line x1={0} x2={width} y1={baseline} y2={baseline} className={styles.axis} />

        {rows.map((row, index) => {
          const held = isHeld(row.count, minimum)
          const columnHeight = held ? innerHeight * 0.3 : (row.count / max) * innerHeight
          const center = band * index + band / 2
          const top = baseline - columnHeight
          return (
            <g key={row.key} className={styles.columnGroup}>
              <path
                d={columnPath(center - columnWidth / 2, top, columnWidth, columnHeight)}
                className={styles.column}
                fill={held ? `url(#${patternId})` : undefined}
                aria-hidden="true"
              />
              <text x={center} y={top - 7} textAnchor="middle" className={styles.valueText}>
                {formatCount(row.count, minimum)}
              </text>
              {wraps && row.label.includes(' ') && row.label.length * 6.5 > band - 6 ? (
                <text x={center} y={height - 21} textAnchor="middle" className={styles.axisText}>
                  <tspan x={center}>{row.label.slice(0, row.label.indexOf(' '))}</tspan>
                  <tspan x={center} dy="14">
                    {row.label.slice(row.label.indexOf(' ') + 1)}
                  </tspan>
                </text>
              ) : (
                <text x={center} y={height - 7} textAnchor="middle" className={styles.axisText}>
                  {row.label}
                </text>
              )}
              <rect
                x={band * index}
                y={PAD_TOP}
                width={band}
                height={innerHeight}
                className={`${styles.hit} ${styles.mark}`}
                {...bind({
                  title: row.label,
                  rows: [{ value: formatCount(row.count, minimum), label: held ? 'group too small to publish' : unit }],
                })}
              />
            </g>
          )
        })}
      </svg>
      <ChartTooltip tip={tip} />
    </div>
  )
}
