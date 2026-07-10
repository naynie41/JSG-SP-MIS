import styles from './Gauge.module.css'

export type GaugeTone = 'forest' | 'info' | 'success' | 'warning' | 'danger'

const STROKE: Record<GaugeTone, string> = {
  forest: 'var(--forest-2)',
  info: 'var(--info)',
  success: 'var(--success)',
  warning: 'var(--warning)',
  danger: 'var(--danger)',
}

export interface GaugeProps {
  label: string
  /** Percentage 0–100. */
  value: number
  caption?: string
  tone?: GaugeTone
}

/**
 * A donut gauge for a real ratio (DESIGN-SYSTEM §5.9). Accessible: the ring carries an
 * `aria-label` and the percentage is shown as visible text.
 */
export function Gauge({ label, value, caption, tone = 'forest' }: GaugeProps) {
  const r = 32
  const circ = 2 * Math.PI * r
  const dash = circ * Math.min(1, Math.max(0, value / 100))
  return (
    <div className={styles.card}>
      <div className={styles.gauge} role="img" aria-label={`${label}: ${value}%`}>
        <svg viewBox="0 0 80 80" width="72" height="72">
          <circle cx="40" cy="40" r={r} fill="none" stroke="var(--surface-mint)" strokeWidth="8" />
          <circle
            cx="40" cy="40" r={r} fill="none" stroke={STROKE[tone]} strokeWidth="8" strokeLinecap="round"
            strokeDasharray={`${dash} ${circ}`} transform="rotate(-90 40 40)"
          />
        </svg>
        <span className={styles.pct}>{value}%</span>
      </div>
      <div className={styles.meta}>
        <span className={styles.label}>{label}</span>
        {caption && <span className={styles.caption}>{caption}</span>}
      </div>
    </div>
  )
}
