import type { ReactNode } from 'react'
import { cn } from '@/lib/utils/cn'
import styles from './Card.module.css'

export interface CardProps {
  title?: string
  eyebrow?: string
  actions?: ReactNode
  variant?: 'default' | 'mint'
  flush?: boolean
  children: ReactNode
  className?: string
}

/** Card / panel (DESIGN-SYSTEM.md §5.5). */
export function Card({ title, eyebrow, actions, variant = 'default', flush, children, className }: CardProps) {
  return (
    <section className={cn(styles.card, variant === 'mint' && styles.mint, flush && styles.flush, className)}>
      {(title || actions || eyebrow) && (
        <header className={styles.header}>
          <div>
            {eyebrow && <div className={cn('eyebrow', styles.eyebrow)}>{eyebrow}</div>}
            {title && <h3 className={styles.title}>{title}</h3>}
          </div>
          {actions && <div>{actions}</div>}
        </header>
      )}
      {children}
    </section>
  )
}

export interface KpiPanelProps {
  label: string
  value: ReactNode
  hint?: string
  className?: string
}

/** Forest KPI panel (DESIGN-SYSTEM.md §5.5) — exec dashboards, use sparingly. */
export function KpiPanel({ label, value, hint, className }: KpiPanelProps) {
  return (
    <div className={cn(styles.kpi, className)}>
      <span className={styles.kpiLabel}>{label}</span>
      <span className={styles.kpiValue}>{value}</span>
      {hint && <span className={styles.kpiHint}>{hint}</span>}
    </div>
  )
}
