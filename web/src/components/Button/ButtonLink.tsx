import { forwardRef } from 'react'
import type { AnchorHTMLAttributes } from 'react'
import { Link } from 'react-router-dom'
import type { LucideIcon } from 'lucide-react'
import { cn } from '@/lib/utils/cn'
import { Icon } from '@/components/Icon/Icon'
import styles from './Button.module.css'
import type { ButtonSize, ButtonVariant } from './Button'

export interface ButtonLinkProps extends Omit<AnchorHTMLAttributes<HTMLAnchorElement>, 'href'> {
  /** Route path. An absolute URL renders a plain anchor instead of a router link. */
  to: string
  variant?: ButtonVariant
  size?: ButtonSize
  fullWidth?: boolean
  leftIcon?: LucideIcon
  rightIcon?: LucideIcon
}

/**
 * A pill button that is actually a link (DESIGN.md §5.1).
 *
 * Shares {@link Button}'s stylesheet rather than restating it, so the pill has one
 * definition — §8.3 forbids a second one. It exists because navigation must be an
 * anchor: a `<button onClick={navigate}>` cannot be opened in a new tab, copied, or
 * read as a link by a screen reader, and a `<button>` inside an `<a>` is invalid.
 */
export const ButtonLink = forwardRef<HTMLAnchorElement, ButtonLinkProps>(function ButtonLink(
  { to, variant = 'primary', size = 'md', fullWidth = false, leftIcon, rightIcon, className, children, ...rest },
  ref,
) {
  const classes = cn(styles.button, styles[variant], styles[size], fullWidth && styles.fullWidth, className)
  const iconSize = size === 'sm' ? 16 : 18
  const content = (
    <>
      {leftIcon && <Icon icon={leftIcon} size={iconSize} aria-hidden="true" />}
      {children}
      {rightIcon && <Icon icon={rightIcon} size={iconSize} aria-hidden="true" />}
    </>
  )

  if (/^https?:\/\//.test(to)) {
    return (
      <a ref={ref} href={to} className={classes} {...rest}>
        {content}
      </a>
    )
  }

  return (
    <Link ref={ref} to={to} className={classes} {...rest}>
      {content}
    </Link>
  )
})
