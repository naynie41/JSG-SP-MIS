import type { ReactNode } from 'react'
import { NavLink } from 'react-router-dom'
import type { LucideIcon } from 'lucide-react'
import { cn } from '@/lib/utils/cn'
import { Icon } from '@/components/Icon/Icon'
import styles from './SideNav.module.css'

export interface NavItem {
  label: string
  to: string
  icon: LucideIcon
}

export interface NavSection {
  /** Mono eyebrow label for the group (e.g. "01 · REGISTRY"). */
  label: string
  items: NavItem[]
}

export interface SideNavProps {
  sections: NavSection[]
  footer?: ReactNode
  /** Mobile drawer open state. */
  open?: boolean
  onClose?: () => void
}

/**
 * Forest side rail (DESIGN-SYSTEM.md §5.6): grouped nav with mono eyebrows and a
 * lime active bar. Collapses to a drawer under the lg breakpoint. Marked
 * `.on-dark` so focus rings switch to lime.
 */
export function SideNav({ sections, footer, open = false, onClose }: SideNavProps) {
  return (
    <>
      {open && <div className={styles.backdrop} onClick={onClose} aria-hidden="true" />}
      <aside className={cn(styles.rail, 'on-dark', open && styles.railOpen)} aria-label="Primary">
        <div className={styles.brand}>
          <span className={styles.brandMark} aria-hidden="true">
            SP
          </span>
          <span>
            <span className={styles.brandName}>SP-MIS</span>
            <br />
            <span className={styles.brandSub}>Jigawa State</span>
          </span>
        </div>

        <nav className={styles.nav}>
          {sections.map((section, i) => (
            <div key={section.label || `group-${i}`} className={styles.group}>
              {section.label && <div className={styles.groupLabel}>{section.label}</div>}
              {section.items.map((item) => (
                <NavLink
                  key={item.to}
                  to={item.to}
                  onClick={onClose}
                  className={({ isActive }) => cn(styles.item, isActive && styles.active)}
                >
                  <Icon icon={item.icon} size={18} />
                  {item.label}
                </NavLink>
              ))}
            </div>
          ))}
        </nav>

        {footer && <div className={styles.footer}>{footer}</div>}
      </aside>
    </>
  )
}
