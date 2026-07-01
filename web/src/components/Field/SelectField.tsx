import { forwardRef, useId } from 'react'
import type { SelectHTMLAttributes } from 'react'
import { cn } from '@/lib/utils/cn'
import { FieldShell, fieldMessageId } from './FieldShell'
import styles from './Field.module.css'

export interface SelectOption {
  value: string
  label: string
  disabled?: boolean
}

export interface SelectFieldProps extends Omit<SelectHTMLAttributes<HTMLSelectElement>, 'id'> {
  label: string
  helper?: string
  error?: string
  hideLabel?: boolean
  id?: string
  options: SelectOption[]
  placeholder?: string
}

/** Native select styled per DESIGN-SYSTEM.md §5.2 (custom chevron, tokens). */
export const SelectField = forwardRef<HTMLSelectElement, SelectFieldProps>(function SelectField(
  { label, helper, error, hideLabel, required, id, options, placeholder, className, ...rest },
  ref,
) {
  const generatedId = useId()
  const fieldId = id ?? generatedId

  return (
    <FieldShell id={fieldId} label={label} required={required} helper={helper} error={error} hideLabel={hideLabel}>
      <select
        ref={ref}
        id={fieldId}
        className={cn(styles.control, className)}
        aria-invalid={error ? true : undefined}
        aria-describedby={helper || error ? fieldMessageId(fieldId) : undefined}
        aria-required={required || undefined}
        defaultValue={placeholder ? '' : undefined}
        {...rest}
      >
        {placeholder && (
          <option value="" disabled>
            {placeholder}
          </option>
        )}
        {options.map((option) => (
          <option key={option.value} value={option.value} disabled={option.disabled}>
            {option.label}
          </option>
        ))}
      </select>
    </FieldShell>
  )
})
