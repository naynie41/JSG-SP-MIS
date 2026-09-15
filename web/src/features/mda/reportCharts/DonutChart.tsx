import { useRef } from 'react'
import { formatCount, isHeld } from '../mdaReportFormat'
import { ChartTooltip } from './ChartCard'
import { arcPath } from './geometry'
import { useTooltip } from './hooks'
import styles from './charts.module.css'

export interface DonutSlice {
  key: string
  label: string
  value: number
  /** A chart token (`var(--chart-1)`), never a raw hex. */
  color: string
}

interface DonutChartProps {
  label: string
  slices: DonutSlice[]
  centerValue: string
  centerLabel: string
  /** What the values count, for the tooltip ("people"). */
  unit: string
  minimum?: number | null
  emptyText: string
}

const SIZE = 176
const CENTER = SIZE / 2
const OUTER = 84
const INNER = 58

/**
 * Part-to-whole at a glance, for a handful of slices. Slices are separated by a 2px
 * surface gap, the legend beside it carries every label, count and share, and the core
 * states the one figure the chart is about.
 */
export function DonutChart({ label, slices, centerValue, centerLabel, unit, minimum = null, emptyText }: DonutChartProps) {
  const container = useRef<HTMLDivElement>(null)
  const { tip, bind } = useTooltip(container)
  const total = slices.reduce((sum, slice) => sum + slice.value, 0)

  if (total === 0) return <p className={styles.empty}>{emptyText}</p>

  let cursor = 0

  return (
    <div className={styles.donut}>
      <div ref={container} className={styles.plot} style={{ width: SIZE }}>
        <svg width={SIZE} height={SIZE} className={`${styles.svg} ${styles.donutRing}`} role="group" aria-label={label}>
          {slices
            .filter((slice) => slice.value > 0)
            .map((slice) => {
              const start = cursor
              cursor += (slice.value / total) * 360
              const held = isHeld(slice.value, minimum)
              const share = Math.round((slice.value / total) * 100)
              return (
                <path
                  key={slice.key}
                  d={arcPath(CENTER, CENTER, OUTER, INNER, start, cursor)}
                  fill={slice.color}
                  className={`${styles.slice} ${styles.mark}`}
                  {...bind({
                    title: slice.label,
                    rows: [
                      {
                        value: formatCount(slice.value, minimum),
                        label: held ? 'group too small to publish' : `${unit} · ${share}%`,
                        color: slice.color,
                      },
                    ],
                  })}
                />
              )
            })}
          <text x={CENTER} y={CENTER - 4} textAnchor="middle" className={styles.donutValue}>
            {centerValue}
          </text>
          <text x={CENTER} y={CENTER + 16} textAnchor="middle" className={styles.donutLabel}>
            {centerLabel}
          </text>
        </svg>
        <ChartTooltip tip={tip} />
      </div>

      <ul className={styles.legend}>
        {slices.map((slice) => {
          const held = isHeld(slice.value, minimum)
          return (
            <li key={slice.key} className={styles.legendRow}>
              <span className={styles.swatch} style={{ background: slice.color }} aria-hidden="true" />
              <span className={styles.legendName}>{slice.label}</span>
              <span className={styles.legendValue}>{formatCount(slice.value, minimum)}</span>
              <span className={styles.legendShare}>{held ? '' : `${Math.round((slice.value / total) * 100)}%`}</span>
            </li>
          )
        })}
      </ul>
    </div>
  )
}
