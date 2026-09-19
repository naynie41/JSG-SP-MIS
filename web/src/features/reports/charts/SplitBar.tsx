import { useRef } from 'react'
import { formatCount, isHeld } from '../counts'
import { ChartTooltip } from './ChartCard'
import { useTooltip } from './hooks'
import styles from './charts.module.css'

export interface SplitSegment {
  key: string
  label: string
  value: number
  color: string
}

interface SplitBarProps {
  label: string
  segments: SplitSegment[]
  unit: string
  minimum?: number | null
  emptyText: string
}

/**
 * A whole divided into a few parts, as one horizontal bar with 2px surface gaps and a
 * legend that carries every label, count and share. For few categories; past five, the
 * caller folds the tail into "Other".
 */
export function SplitBar({ label, segments, unit, minimum = null, emptyText }: SplitBarProps) {
  const container = useRef<HTMLDivElement>(null)
  const { tip, bind } = useTooltip(container)
  const total = segments.reduce((sum, segment) => sum + segment.value, 0)

  if (total === 0) return <p className={styles.empty}>{emptyText}</p>

  return (
    <div className={styles.split}>
      <div ref={container} className={styles.plot}>
        <div className={styles.splitTrack} role="group" aria-label={label}>
          {segments
            .filter((segment) => segment.value > 0)
            .map((segment) => {
              const held = isHeld(segment.value, minimum)
              const share = Math.round((segment.value / total) * 100)
              return (
                <span
                  key={segment.key}
                  className={`${styles.splitPart} ${styles.mark}`}
                  style={{ flexGrow: segment.value, flexBasis: 0, background: segment.color }}
                  {...bind({
                    title: segment.label,
                    rows: [
                      {
                        value: formatCount(segment.value, minimum),
                        label: held ? 'group too small to publish' : `${unit} · ${share}%`,
                        color: segment.color,
                      },
                    ],
                  })}
                />
              )
            })}
        </div>
        <ChartTooltip tip={tip} />
      </div>

      <ul className={styles.legend}>
        {segments.map((segment) => (
          <li key={segment.key} className={styles.legendRow}>
            <span className={styles.swatch} style={{ background: segment.color }} aria-hidden="true" />
            <span className={styles.legendName}>{segment.label}</span>
            <span className={styles.legendValue}>{formatCount(segment.value, minimum)}</span>
            <span className={styles.legendShare}>
              {isHeld(segment.value, minimum) ? '' : `${Math.round((segment.value / total) * 100)}%`}
            </span>
          </li>
        ))}
      </ul>
    </div>
  )
}
