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
  /**
   * Applied to the FIELD (label + control + message), not the control.
   *
   * This is what callers mean when they size a field inside a filter row
   * (`flex: 0 1 180px`). Putting it on the control instead used to make that a HEIGHT —
   * the shell is a column flex container, so `flex-basis` runs down the main axis — and
   * every filter select in the app rendered as a 180px-tall box.
   */
  className?: string
}

/** Message element id for a field, for aria-describedby wiring. */
// eslint-disable-next-line react-refresh/only-export-components
export function fieldMessageId(id: string): string {
  return `${id}-msg`
}

/**
 * Label element id for a field. Grouping controls (radiogroup, checkbox group)
 * are not labelable elements, so `<label htmlFor>` does not associate with them —
 * they must point at this id with `aria-labelledby` instead.
 */
// eslint-disable-next-line react-refresh/only-export-components
export function fieldLabelId(id: string): string {
  return `${id}-label`
}

/**
 * Label + control + helper/error layout shared by all text-like fields
 * (DESIGN.md §5.2). Error text takes precedence over helper and is
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
      {/* The required "*" sits outside the <label> so it is not part of the
          field's accessible name (screen readers rely on aria-required instead). */}
      <span className={cn(styles.labelRow, hideLabel && 'sr-only')}>
        <label id={fieldLabelId(id)} htmlFor={id} className={styles.label}>
          {label}
        </label>
        {required && (
          <span className={styles.required} aria-hidden="true">
            *
          </span>
        )}
      </span>
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
