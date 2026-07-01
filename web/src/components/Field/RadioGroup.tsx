import { useId } from 'react'
import { cn } from '@/lib/utils/cn'
import { FieldShell } from './FieldShell'
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
}

/** Radio group (DESIGN-SYSTEM.md §5.2). Rendered as a labelled fieldset. */
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
}: RadioGroupProps) {
  const id = useId()

  return (
    <FieldShell id={id} label={label} required={required} helper={helper} error={error}>
      <div role="radiogroup" aria-label={label} style={{ display: 'flex', flexDirection: 'column', gap: 'var(--space-2)' }}>
        {options.map((option) => (
          <label
            key={option.value}
            className={cn(styles.choice, styles.radio, option.disabled && styles.disabled)}
          >
            <input
              type="radio"
              className={styles.input}
              name={name}
              value={option.value}
              disabled={option.disabled}
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
