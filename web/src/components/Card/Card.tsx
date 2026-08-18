import { useId, useState } from 'react'
import type { ReactNode } from 'react'
import { ChevronDown } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
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
  /**
   * Let the reader fold the card's body away, leaving the header (and `actions`)
   * visible. For sections that are long rather than unimportant — a 200-row table
   * should not force everything below it off the page.
   *
   * Requires `title`: without one there is nothing to label the control, and a
   * disclosure the reader cannot name is worse than none.
   */
  collapsible?: boolean
  /** Whether a collapsible card starts open. Defaults to open — collapsing is the
   *  reader's choice to make, and content that vanishes on load reads as missing. */
  defaultOpen?: boolean
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
  collapsible,
  defaultOpen = true,
}: CardProps) {
  const [open, setOpen] = useState(defaultOpen)
  const bodyId = useId()
  // A disclosure with no accessible name is useless, so collapsing needs a title.
  const canCollapse = Boolean(collapsible && title)

  return (
    <section className={cn(styles.card, variant === 'mint' && styles.mint, flush && styles.flush, className)}>
      {(title || actions || eyebrow) && (
        <header className={styles.header}>
          <div>
            {eyebrow && <div className={cn('eyebrow', styles.eyebrow)}>{eyebrow}</div>}
            {title &&
              (canCollapse ? (
                // The BUTTON sits inside the heading rather than replacing it: the
                // document outline keeps its level, and screen readers still announce
                // the section by name before offering the control.
                <TitleTag className={styles.title}>
                  <button
                    type="button"
                    className={styles.disclosure}
                    aria-expanded={open}
                    aria-controls={bodyId}
                    onClick={() => setOpen((v) => !v)}
                  >
                    <Icon
                      icon={ChevronDown}
                      size={16}
                      className={cn(styles.disclosureIcon, !open && styles.disclosureIconClosed)}
                      aria-hidden="true"
                    />
                    {title}
                  </button>
                </TitleTag>
              ) : (
                <TitleTag className={styles.title}>{title}</TitleTag>
              ))}
          </div>
          {actions && <div>{actions}</div>}
        </header>
      )}
      {/* Unmounted rather than hidden when closed: a folded-away 200-row table should
          stop costing render time, not just stop being visible. */}
      {canCollapse ? <div id={bodyId}>{open ? children : null}</div> : children}
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
