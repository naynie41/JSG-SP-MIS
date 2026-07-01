import { cn } from '@/lib/utils/cn'
import styles from './Avatar.module.css'

export interface AvatarProps {
  name: string
  src?: string
  size?: 'sm' | 'md' | 'lg'
  className?: string
}

function initials(name: string): string {
  const parts = name.trim().split(/\s+/).filter(Boolean)
  if (parts.length === 0) return '?'
  if (parts.length === 1) return parts[0]!.charAt(0).toUpperCase()
  return (parts[0]!.charAt(0) + parts[parts.length - 1]!.charAt(0)).toUpperCase()
}

/** User avatar — image when available, otherwise initials (DESIGN-SYSTEM.md §5.7). */
export function Avatar({ name, src, size = 'md', className }: AvatarProps) {
  return (
    <span className={cn(styles.avatar, styles[size], className)} title={name}>
      {src ? (
        <img className={styles.image} src={src} alt={name} />
      ) : (
        <span aria-hidden="true">{initials(name)}</span>
      )}
    </span>
  )
}
