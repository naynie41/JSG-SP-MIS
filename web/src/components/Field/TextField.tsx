import { forwardRef, useId } from 'react'
import type { InputHTMLAttributes } from 'react'
import { cn } from '@/lib/utils/cn'
import { FieldShell, fieldMessageId } from './FieldShell'
import styles from './Field.module.css'

export interface TextFieldProps extends Omit<InputHTMLAttributes<HTMLInputElement>, 'id'> {
  label: string
  helper?: string
  error?: string
  hideLabel?: boolean
  id?: string
}

/**
 * Text-like input (DESIGN-SYSTEM.md §5.2): covers text, email, password, date,
 * number, tel, etc. via the native `type`. Focus/error/disabled states + full
 * aria wiring. `error` renders the API validation message and sets aria-invalid.
 */
export const TextField = forwardRef<HTMLInputElement, TextFieldProps>(function TextField(
  { label, helper, error, hideLabel, required, id, type = 'text', className, ...rest },
  ref,
) {
  const generatedId = useId()
  const fieldId = id ?? generatedId

  return (
    <FieldShell id={fieldId} label={label} required={required} helper={helper} error={error} hideLabel={hideLabel}>
      <input
        ref={ref}
        id={fieldId}
        type={type}
        className={cn(styles.control, className)}
        aria-invalid={error ? true : undefined}
        aria-describedby={helper || error ? fieldMessageId(fieldId) : undefined}
        aria-required={required || undefined}
        {...rest}
      />
    </FieldShell>
  )
})
