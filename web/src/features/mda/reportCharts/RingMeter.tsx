import { AlertTriangle } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import styles from './charts.module.css'

interface RingMeterProps {
  label: string
  hint: string
  /** 0..1, or null when there is nothing to measure. */
  ratio: number | null
  /** The weakest of a set: warning tone, and said in words beside it. */
  weakest?: boolean
}

const SIZE = 88
const RADIUS = 34
const STROKE = 9

/**
 * A single share against 100%, as a ring. The track is a light step of the same green
 * family, so the whole ring reads as one measure; the weakest in a set turns to the
 * warning tone and is labelled "Weakest", never marked by colour alone.
 */
export function RingMeter({ label, hint, ratio, weakest = false }: RingMeterProps) {
  const percent = ratio === null ? null : Math.round(ratio * 100)
  const circumference = 2 * Math.PI * RADIUS
  const filled = circumference * Math.min(1, Math.max(0, ratio ?? 0))

  return (
    <div className={styles.ring}>
      <svg
        width={SIZE}
        height={SIZE}
        viewBox={`0 0 ${SIZE} ${SIZE}`}
        role="img"
        aria-label={`${label}: ${percent === null ? 'not measured' : `${percent}%`}${weakest ? ', the weakest detail' : ''}`}
      >
        <circle cx={SIZE / 2} cy={SIZE / 2} r={RADIUS} fill="none" strokeWidth={STROKE} className={styles.ringTrack} />
        {filled > 0 && (
          <circle
            cx={SIZE / 2}
            cy={SIZE / 2}
            r={RADIUS}
            fill="none"
            strokeWidth={STROKE}
            strokeLinecap="round"
            strokeDasharray={`${filled} ${circumference}`}
            transform={`rotate(-90 ${SIZE / 2} ${SIZE / 2})`}
            className={weakest ? styles.ringWeak : styles.ringFill}
          />
        )}
        <text x={SIZE / 2} y={SIZE / 2} textAnchor="middle" dominantBaseline="central" className={styles.ringValue}>
          {percent === null ? '—' : `${percent}%`}
        </text>
      </svg>
      <span className={styles.ringLabel}>{label}</span>
      {weakest && (
        <span className={styles.ringFlag}>
          <Icon icon={AlertTriangle} size={12} />
          Weakest
        </span>
      )}
      <span className={styles.ringHint}>{hint}</span>
    </div>
  )
}
