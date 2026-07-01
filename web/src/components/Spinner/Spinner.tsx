import { cn } from '@/lib/utils/cn'
import styles from './Spinner.module.css'

export interface SpinnerProps {
  /** px diameter. */
  size?: number
  label?: string
  className?: string
}

/** Indeterminate loading spinner; inherits `currentColor`. */
export function Spinner({ size = 18, label = 'Loading', className }: SpinnerProps) {
  return (
    <span
      className={cn(styles.spinner, className)}
      style={{ ['--size' as string]: `${size}px` }}
      role="status"
      aria-label={label}
    />
  )
}
