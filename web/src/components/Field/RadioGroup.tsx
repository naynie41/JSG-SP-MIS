import { useId } from 'react'
import { cn } from '@/lib/utils/cn'
import { FieldShell, fieldLabelId, fieldMessageId } from './FieldShell'
import styles from './choice.module.css'

export interface RadioOption {
  value: string
  label: string
  disabled?: boolean
}

export interface RadioGroupProps {
  label: string
  name: string
  options: RadioOption[]
  value?: string
  defaultValue?: string
  onChange?: (value: string) => void
  helper?: string
  error?: string
  required?: boolean
  /** Disable every option in the group (e.g. a read-only viewer). */
  disabled?: boolean
}

/**
 * Radio group (DESIGN.md §5.2). A `role="radiogroup"` is not a labelable
 * element, so `<label htmlFor>` cannot name it — the group points at the
 * FieldShell label with `aria-labelledby` and at its helper/error text with
 * `aria-describedby`, so both reach assistive tech.
 */
export function RadioGroup({
  label,
  name,
  options,
  value,
  defaultValue,
  onChange,
  helper,
  error,
  required,
  disabled,
}: RadioGroupProps) {
  const id = useId()
  const message = error ?? helper

  return (
    <FieldShell id={id} label={label} required={required} helper={helper} error={error}>
      <div
        role="radiogroup"
        aria-labelledby={fieldLabelId(id)}
        aria-describedby={message ? fieldMessageId(id) : undefined}
        aria-required={required || undefined}
        aria-invalid={error ? true : undefined}
        style={{ display: 'flex', flexDirection: 'column', gap: 'var(--space-2)' }}
      >
        {options.map((option) => (
          <label
            key={option.value}
            className={cn(
              styles.choice,
              styles.radio,
              (disabled || option.disabled) && styles.disabled,
            )}
          >
            <input
              type="radio"
              className={styles.input}
              name={name}
              value={option.value}
              disabled={disabled || option.disabled}
              checked={value !== undefined ? value === option.value : undefined}
              defaultChecked={defaultValue !== undefined ? defaultValue === option.value : undefined}
              onChange={(event) => onChange?.(event.target.value)}
            />
            <span className={styles.box} aria-hidden="true">
              <span className={styles.dot} />
            </span>
            <span className={styles.text}>{option.label}</span>
          </label>
        ))}
      </div>
    </FieldShell>
  )
}
