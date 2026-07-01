import type { ReactNode } from 'react'
import { cn } from '@/lib/utils/cn'
import styles from './Badge.module.css'

export type BadgeVariant =
  | 'accent'
  | 'neutral'
  | 'dark'
  | 'outline'
  | 'success'
  | 'warning'
  | 'danger'
  | 'info'

export interface BadgeProps {
  variant?: BadgeVariant
  /** Show a leading status dot. */
  dot?: boolean
  /** Mono uppercase style (codes, match types). */
  mono?: boolean
  children: ReactNode
  className?: string
}

/** Pill badge/chip (DESIGN-SYSTEM.md §5.3). One badge per status cell. */
export function Badge({ variant = 'neutral', dot = false, mono = false, children, className }: BadgeProps) {
  return (
    <span className={cn(styles.badge, styles[variant], mono && styles.mono, className)}>
      {dot && <span className={styles.dot} aria-hidden="true" />}
      {children}
    </span>
  )
}
