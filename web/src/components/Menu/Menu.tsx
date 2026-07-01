import { useEffect, useRef, useState } from 'react'
import type { LucideIcon } from 'lucide-react'
import { MoreHorizontal } from 'lucide-react'
import { cn } from '@/lib/utils/cn'
import { Icon } from '@/components/Icon/Icon'
import styles from './Menu.module.css'

export interface MenuAction {
  label: string
  onSelect: () => void
  icon?: LucideIcon
  danger?: boolean
  disabled?: boolean
}

export interface MenuProps {
  /** Accessible label for the trigger (e.g. "Actions for Amina Bello"). */
  label: string
  actions: MenuAction[]
}

/** Overflow action menu (DESIGN-SYSTEM.md §5.4) — closes on select, Esc, outside click. */
export function Menu({ label, actions }: MenuProps) {
  const [open, setOpen] = useState(false)
  const wrapRef = useRef<HTMLDivElement>(null)
  const menuRef = useRef<HTMLDivElement>(null)

  useEffect(() => {
    if (!open) return
    function onDocClick(event: MouseEvent) {
      if (!wrapRef.current?.contains(event.target as Node)) setOpen(false)
    }
    function onKey(event: KeyboardEvent) {
      if (event.key === 'Escape') setOpen(false)
    }
    document.addEventListener('mousedown', onDocClick)
    document.addEventListener('keydown', onKey)
    menuRef.current?.querySelector<HTMLElement>('[role="menuitem"]')?.focus()
    return () => {
      document.removeEventListener('mousedown', onDocClick)
      document.removeEventListener('keydown', onKey)
    }
  }, [open])

  function onMenuKeyDown(event: React.KeyboardEvent) {
    if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') return
    event.preventDefault()
    const items = Array.from(menuRef.current?.querySelectorAll<HTMLElement>('[role="menuitem"]') ?? [])
    const index = items.indexOf(document.activeElement as HTMLElement)
    const dir = event.key === 'ArrowDown' ? 1 : -1
    const next = items[(index + dir + items.length) % items.length]
    next?.focus()
  }

  return (
    <div className={styles.wrap} ref={wrapRef}>
      <button
        type="button"
        className={styles.trigger}
        aria-haspopup="menu"
        aria-expanded={open}
        aria-label={label}
        onClick={() => setOpen((value) => !value)}
      >
        <Icon icon={MoreHorizontal} size={18} />
      </button>
      {open && (
        <div className={styles.menu} role="menu" ref={menuRef} onKeyDown={onMenuKeyDown}>
          {actions.map((action, index) => (
            <button
              key={`${action.label}-${index}`}
              type="button"
              role="menuitem"
              tabIndex={-1}
              disabled={action.disabled}
              className={cn(styles.item, action.danger && styles.danger)}
              onClick={() => {
                setOpen(false)
                action.onSelect()
              }}
            >
              {action.icon && <Icon icon={action.icon} size={16} />}
              {action.label}
            </button>
          ))}
        </div>
      )}
    </div>
  )
}
