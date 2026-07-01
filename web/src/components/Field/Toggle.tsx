import { forwardRef } from 'react'
import type { InputHTMLAttributes } from 'react'
import { cn } from '@/lib/utils/cn'
import styles from './toggle.module.css'

export interface ToggleProps extends Omit<InputHTMLAttributes<HTMLInputElement>, 'type'> {
  label: string
  /** Visually hide the label (still announced). */
  hideLabel?: boolean
}

/** Switch (DESIGN-SYSTEM.md §5.2): off = grey track; on = lime track, dark knob. */
export const Toggle = forwardRef<HTMLInputElement, ToggleProps>(function Toggle(
  { label, hideLabel, disabled, className, ...rest },
  ref,
) {
  return (
    <label className={cn(styles.toggle, disabled && styles.disabled, className)}>
      <input ref={ref} type="checkbox" role="switch" className={styles.input} disabled={disabled} {...rest} />
      <span className={styles.track} aria-hidden="true">
        <span className={styles.knob} />
      </span>
      <span className={hideLabel ? 'sr-only' : undefined}>{label}</span>
    </label>
  )
})
