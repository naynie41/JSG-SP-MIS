import { forwardRef } from 'react'
import type { ButtonHTMLAttributes } from 'react'
import type { LucideIcon } from 'lucide-react'
import { cn } from '@/lib/utils/cn'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import styles from './Button.module.css'

export type ButtonVariant = 'primary' | 'secondary' | 'tertiary' | 'danger' | 'link'
export type ButtonSize = 'sm' | 'md' | 'lg'

export interface ButtonProps extends ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: ButtonVariant
  size?: ButtonSize
  loading?: boolean
  fullWidth?: boolean
  leftIcon?: LucideIcon
  rightIcon?: LucideIcon
}

/**
 * Pill button (DESIGN-SYSTEM.md §5.1). All variants/sizes + hover/active/
 * focus-visible/disabled/loading. Loading keeps width and blocks pointer.
 */
export const Button = forwardRef<HTMLButtonElement, ButtonProps>(function Button(
  {
    variant = 'primary',
    size = 'md',
    loading = false,
    fullWidth = false,
    leftIcon,
    rightIcon,
    disabled,
    className,
    children,
    type = 'button',
    ...rest
  },
  ref,
) {
  const isDisabled = disabled || loading

  return (
    <button
      ref={ref}
      type={type}
      className={cn(
        styles.button,
        styles[variant],
        styles[size],
        fullWidth && styles.fullWidth,
        className,
      )}
      disabled={isDisabled}
      aria-busy={loading || undefined}
      {...rest}
    >
      {loading ? (
        <Spinner size={size === 'lg' ? 20 : 16} />
      ) : (
        <>
          {leftIcon && (
            <span className={styles.icon}>
              <Icon icon={leftIcon} size={size === 'sm' ? 16 : 18} />
            </span>
          )}
          {children}
          {rightIcon && (
            <span className={styles.icon}>
              <Icon icon={rightIcon} size={size === 'sm' ? 16 : 18} />
            </span>
          )}
        </>
      )}
    </button>
  )
})
