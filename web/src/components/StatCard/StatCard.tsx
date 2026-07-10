import type { ReactNode } from 'react'
import type { LucideIcon } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { cn } from '@/lib/utils/cn'
import styles from './StatCard.module.css'

export type StatTone = 'forest' | 'info' | 'mint' | 'success' | 'warning' | 'danger'

export interface StatCardProps {
  icon: LucideIcon
  label: string
  value: ReactNode
  hint?: string
  /** Soft icon-chip colour, drawn from the token palette. */
  tone?: StatTone
}

/**
 * A headline stat card (DESIGN-SYSTEM §5.5): a soft, tone-varied icon chip, a mono
 * uppercase label, a large display value, and a muted hint. The shared building block
 * behind the dashboard, section hubs and the coverage screen.
 */
export function StatCard({ icon, label, value, hint, tone = 'forest' }: StatCardProps) {
  return (
    <div className={styles.card}>
      <div className={styles.top}>
        <span className={cn(styles.chip, styles[tone])} aria-hidden="true">
          <Icon icon={icon} size={18} />
        </span>
        <span className={styles.label}>{label}</span>
      </div>
      <span className={styles.value}>{value}</span>
      {hint && <span className={styles.hint}>{hint}</span>}
    </div>
  )
}
