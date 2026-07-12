import type { LucideIcon } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { cn } from '@/lib/utils/cn'
import type { StatTone } from '@/components/StatCard/StatCard'
import styles from './MetricBand.module.css'

export interface Metric {
  icon: LucideIcon
  label: string
  value: string
  /** Tone of the small leading chip — use status tones (warning/danger) only for
   *  genuine attention/critical figures; brand tones for plain counts. */
  tone?: StatTone
}

/**
 * A single subtle band of compact stat tiles — page-level context metrics that read
 * as one system (per the dataviz stat-tile guidance) without the weight of a KPI
 * dashboard. Each tile pairs a tone chip with a label + value, so status is never
 * conveyed by colour alone.
 */
export function MetricBand({ items }: { items: Metric[] }) {
  if (items.length === 0) return null
  return (
    <div className={styles.band}>
      {items.map((m) => (
        <div key={m.label} className={styles.item}>
          <span className={cn(styles.chip, styles[m.tone ?? 'forest'])} aria-hidden="true">
            <Icon icon={m.icon} size={16} />
          </span>
          <span className={styles.meta}>
            <span className={styles.label}>{m.label}</span>
            <span className={styles.value}>{m.value}</span>
          </span>
        </div>
      ))}
    </div>
  )
}
