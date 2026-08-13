import { useEffect, useRef } from 'react'
import type { ReactNode } from 'react'
import { NavLink } from 'react-router-dom'
import type { LucideIcon } from 'lucide-react'
import { cn } from '@/lib/utils/cn'
import { Icon } from '@/components/Icon/Icon'
import styles from './SideNav.module.css'

/** Matches the `max-width: 1024px` drawer breakpoint in SideNav.module.css. */
const DRAWER_QUERY = '(max-width: 1024px)'

const FOCUSABLE =
  'a[href],button:not([disabled]),textarea,input,select,[tabindex]:not([tabindex="-1"])'

export interface NavItem {
  label: string
  to: string
  icon: LucideIcon
  /** Match the route exactly (NavLink `end`) — for index links like `/partner`. */
  end?: boolean
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
 * Forest side rail (DESIGN.md §5.6): grouped nav with mono eyebrows and a
 * lime active bar. Collapses to a drawer under the lg breakpoint. Marked
 * `.on-dark` so focus rings switch to lime.
 *
 * Below the breakpoint the rail is a modal drawer, so it carries dialog
 * semantics: focus moves in on open, is trapped while open, Escape closes, and
 * focus returns to the opener on close. The closed drawer is `visibility:
 * hidden` in CSS — a `transform` alone leaves every link in the tab order,
 * off-screen and invisible, which stranded keyboard focus for the length of the
 * whole rail.
 */
export function SideNav({ sections, footer, open = false, onClose }: SideNavProps) {
  const railRef = useRef<HTMLElement>(null)

  useEffect(() => {
    if (!open) return
    // Desktop renders the rail permanently; trapping focus there would be wrong.
    if (typeof window === 'undefined' || !window.matchMedia(DRAWER_QUERY).matches) return

    const previouslyFocused = document.activeElement as HTMLElement | null
    const rail = railRef.current
    const focusables = rail?.querySelectorAll<HTMLElement>(FOCUSABLE)
    ;(focusables && focusables.length > 0 ? focusables[0]! : rail)?.focus()

    function onKeyDown(event: KeyboardEvent) {
      if (event.key === 'Escape') {
        event.preventDefault()
        onClose?.()
        return
      }
      if (event.key !== 'Tab' || !rail) return

      const items = Array.from(rail.querySelectorAll<HTMLElement>(FOCUSABLE))
      if (items.length === 0) return
      const first = items[0]!
      const last = items[items.length - 1]!
      const active = document.activeElement

      if (event.shiftKey && active === first) {
        event.preventDefault()
        last.focus()
      } else if (!event.shiftKey && active === last) {
        event.preventDefault()
        first.focus()
      }
    }

    document.addEventListener('keydown', onKeyDown)
    return () => {
      document.removeEventListener('keydown', onKeyDown)
      previouslyFocused?.focus()
    }
  }, [open, onClose])

  return (
    <>
      {open && <div className={styles.backdrop} onClick={onClose} aria-hidden="true" />}
      <aside
        ref={railRef}
        className={cn(styles.rail, 'on-dark', open && styles.railOpen)}
        aria-label="Primary"
        tabIndex={-1}
      >
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
                  end={item.end}
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
