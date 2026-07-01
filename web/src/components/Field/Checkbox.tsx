import { forwardRef } from 'react'
import type { InputHTMLAttributes } from 'react'
import { Check } from 'lucide-react'
import { cn } from '@/lib/utils/cn'
import { Icon } from '@/components/Icon/Icon'
import styles from './choice.module.css'

export interface CheckboxProps extends Omit<InputHTMLAttributes<HTMLInputElement>, 'type'> {
  label: string
  /** Hide the visible label text (still announced to screen readers). */
  hideLabel?: boolean
}

/** Checkbox (DESIGN-SYSTEM.md §5.2): lime when checked, ink glyph, visible focus. */
export const Checkbox = forwardRef<HTMLInputElement, CheckboxProps>(function Checkbox(
  { label, hideLabel, disabled, className, ...rest },
  ref,
) {
  return (
    <label className={cn(styles.choice, styles.checkbox, disabled && styles.disabled, className)}>
      <input ref={ref} type="checkbox" className={styles.input} disabled={disabled} {...rest} />
      <span className={styles.box} aria-hidden="true">
        <span className={styles.glyph}>
          <Icon icon={Check} size={14} />
        </span>
      </span>
      <span className={hideLabel ? 'sr-only' : styles.text}>{label}</span>
    </label>
  )
})
