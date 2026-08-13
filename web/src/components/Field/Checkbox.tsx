import { forwardRef, useEffect, useImperativeHandle, useRef } from 'react'
import type { InputHTMLAttributes } from 'react'
import { Check, Minus } from 'lucide-react'
import { cn } from '@/lib/utils/cn'
import { Icon } from '@/components/Icon/Icon'
import styles from './choice.module.css'

export interface CheckboxProps extends Omit<InputHTMLAttributes<HTMLInputElement>, 'type'> {
  label: string
  /** Hide the visible label text (still announced to screen readers). */
  hideLabel?: boolean
  /**
   * Mixed state — some but not all of the rows this checkbox governs are
   * selected. `indeterminate` is a DOM property with no HTML attribute, so it
   * has to be assigned to the node rather than passed as a prop.
   */
  indeterminate?: boolean
}

/**
 * Checkbox (DESIGN.md §5.2): lime when checked, ink glyph, visible focus.
 * Supports the mixed state a select-all header needs.
 */
export const Checkbox = forwardRef<HTMLInputElement, CheckboxProps>(function Checkbox(
  { label, hideLabel, disabled, className, indeterminate, ...rest },
  ref,
) {
  const inputRef = useRef<HTMLInputElement>(null)
  useImperativeHandle(ref, () => inputRef.current as HTMLInputElement)

  useEffect(() => {
    if (inputRef.current) inputRef.current.indeterminate = Boolean(indeterminate)
  }, [indeterminate])

  return (
    <label className={cn(styles.choice, styles.checkbox, disabled && styles.disabled, className)}>
      <input
        ref={inputRef}
        type="checkbox"
        className={styles.input}
        disabled={disabled}
        aria-checked={indeterminate ? 'mixed' : undefined}
        {...rest}
      />
      <span className={styles.box} aria-hidden="true">
        <span className={styles.glyph}>
          <Icon icon={indeterminate ? Minus : Check} size={14} />
        </span>
      </span>
      <span className={hideLabel ? 'sr-only' : styles.text}>{label}</span>
    </label>
  )
})
