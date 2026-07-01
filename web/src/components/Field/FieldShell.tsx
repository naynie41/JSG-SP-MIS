import type { ReactNode } from 'react'
import { cn } from '@/lib/utils/cn'
import styles from './Field.module.css'

export interface FieldShellProps {
  id: string
  label: string
  required?: boolean
  helper?: string
  error?: string
  /** Hide the visible label (still read by screen readers via aria-label on the control). */
  hideLabel?: boolean
  children: ReactNode
  className?: string
}

/** Message element id for a field, for aria-describedby wiring. */
// eslint-disable-next-line react-refresh/only-export-components
export function fieldMessageId(id: string): string {
  return `${id}-msg`
}

/**
 * Label + control + helper/error layout shared by all text-like fields
 * (DESIGN-SYSTEM.md §5.2). Error text takes precedence over helper and is
 * announced (role="alert"); it renders the message from the API error envelope.
 */
export function FieldShell({
  id,
  label,
  required,
  helper,
  error,
  hideLabel,
  children,
  className,
}: FieldShellProps) {
  const message = error ?? helper

  return (
    <div className={cn(styles.field, className)}>
      <label htmlFor={id} className={cn(styles.label, hideLabel && 'sr-only')}>
        {label}
        {required && (
          <span className={styles.required} aria-hidden="true">
            *
          </span>
        )}
      </label>
      {children}
      {message && (
        <p
          id={fieldMessageId(id)}
          className={cn(styles.message, error && styles.error)}
          role={error ? 'alert' : undefined}
        >
          {message}
        </p>
      )}
    </div>
  )
}
