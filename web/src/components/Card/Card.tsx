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
  /**
   * Heading level for `title`. Defaults to `h3`, which is right for a card nested
   * under a section heading. Pass `h2` when the card IS a top-level section of the
   * page, so the document outline has no hole — an `h1` followed straight by an `h3`
   * leaves assistive technology without a level to anchor to (WCAG 2.1 SC 1.3.1).
   */
  titleAs?: 'h2' | 'h3' | 'h4'
}

/** Card / panel (DESIGN.md §5.5). */
export function Card({
  title,
  eyebrow,
  actions,
  variant = 'default',
  flush,
  children,
  className,
  titleAs: TitleTag = 'h3',
}: CardProps) {
  return (
    <section className={cn(styles.card, variant === 'mint' && styles.mint, flush && styles.flush, className)}>
      {(title || actions || eyebrow) && (
        <header className={styles.header}>
          <div>
            {eyebrow && <div className={cn('eyebrow', styles.eyebrow)}>{eyebrow}</div>}
            {title && <TitleTag className={styles.title}>{title}</TitleTag>}
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

/** Forest KPI panel (DESIGN.md §5.5) — exec dashboards, use sparingly. */
export function KpiPanel({ label, value, hint, className }: KpiPanelProps) {
  return (
    <div className={cn(styles.kpi, className)}>
      <span className={styles.kpiLabel}>{label}</span>
      <span className={styles.kpiValue}>{value}</span>
      {hint && <span className={styles.kpiHint}>{hint}</span>}
    </div>
  )
}
