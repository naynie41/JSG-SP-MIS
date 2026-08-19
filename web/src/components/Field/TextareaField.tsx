import { forwardRef, useId } from 'react'
import type { TextareaHTMLAttributes } from 'react'
import { FieldShell, fieldMessageId } from './FieldShell'
import styles from './Field.module.css'

export interface TextareaFieldProps extends Omit<TextareaHTMLAttributes<HTMLTextAreaElement>, 'id'> {
  label: string
  helper?: string
  error?: string
  hideLabel?: boolean
  id?: string
}

/** Multi-line text field (DESIGN.md §5.2). */
export const TextareaField = forwardRef<HTMLTextAreaElement, TextareaFieldProps>(
  function TextareaField({ label, helper, error, hideLabel, required, id, className, ...rest }, ref) {
    const generatedId = useId()
    const fieldId = id ?? generatedId

    return (
      <FieldShell
        id={fieldId}
        label={label}
        required={required}
        helper={helper}
        error={error}
        hideLabel={hideLabel}
        className={className}
      >
        <textarea
          ref={ref}
          id={fieldId}
          className={styles.control}
          aria-invalid={error ? true : undefined}
          aria-describedby={helper || error ? fieldMessageId(fieldId) : undefined}
          aria-required={required || undefined}
          {...rest}
        />
      </FieldShell>
    )
  },
)
