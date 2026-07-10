import type { ReactNode } from 'react'
import styles from './AccentPanel.module.css'

export interface AccentPanelProps {
  label: string
  value: ReactNode
  sub?: string
}

/**
 * The forest-gradient accent summary panel — a headline figure with a single, scarce
 * lime sparkline flourish (decorative, not data). Used as the dashboard/coverage hero.
 */
export function AccentPanel({ label, value, sub }: AccentPanelProps) {
  return (
    <div className={styles.card}>
      <span className={styles.label}>{label}</span>
      <span className={styles.value}>{value}</span>
      {sub && <span className={styles.sub}>{sub}</span>}
      <svg className={styles.spark} viewBox="0 0 320 90" preserveAspectRatio="none" aria-hidden="true">
        <path d="M0 66 C 40 66 52 30 92 34 C 132 38 150 74 196 60 C 242 46 262 18 320 26" className={styles.sparkLine} />
      </svg>
    </div>
  )
}
