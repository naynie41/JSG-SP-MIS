import type { LucideIcon } from 'lucide-react'

export interface IconProps {
  icon: LucideIcon
  /** px size — 20 is the design-system default. */
  size?: number
  /** Accessible label. Omit for decorative icons (aria-hidden). */
  label?: string
  className?: string
}

/**
 * Consistent icon wrapper around Lucide (one icon set, 1.5px stroke, 20px
 * default — DESIGN-SYSTEM.md §6). Decorative by default; pass `label` when the
 * icon conveys meaning on its own.
 */
export function Icon({ icon: LucideComponent, size = 20, label, className }: IconProps) {
  return (
    <LucideComponent
      size={size}
      strokeWidth={1.5}
      className={className}
      aria-hidden={label ? undefined : true}
      aria-label={label}
      role={label ? 'img' : undefined}
      focusable="false"
    />
  )
}
