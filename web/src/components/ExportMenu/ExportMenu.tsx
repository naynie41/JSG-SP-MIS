import { useEffect, useRef, useState } from 'react'
import { ChevronDown, Download, FileSpreadsheet, FileText } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Icon } from '@/components/Icon/Icon'
import type { ExportFormat } from '@/lib/api/exportList'
import styles from './ExportMenu.module.css'

export type { ExportFormat }

export interface ExportMenuProps {
  /** Run the export for the chosen format. Resolve to keep the control usable; the caller shows feedback. */
  onExport: (format: ExportFormat) => void | Promise<void>
  label?: string
  disabled?: boolean
}

const FORMATS: { value: ExportFormat; label: string; icon: typeof FileText }[] = [
  { value: 'csv', label: 'CSV (.csv)', icon: FileText },
  { value: 'excel', label: 'Excel (.xlsx)', icon: FileSpreadsheet },
]

/**
 * A reusable "Export" affordance any list can drop into its toolbar (DESIGN-SYSTEM.md
 * §5.1/§5.4): a pill button that opens a small format menu (CSV / Excel). Busy while an
 * export runs; closes on select, Esc, or outside click.
 */
export function ExportMenu({ onExport, label = 'Export', disabled = false }: ExportMenuProps) {
  const [open, setOpen] = useState(false)
  const [busy, setBusy] = useState(false)
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
    items[(index + dir + items.length) % items.length]?.focus()
  }

  async function choose(format: ExportFormat) {
    setOpen(false)
    setBusy(true)
    try {
      await onExport(format)
    } finally {
      setBusy(false)
    }
  }

  return (
    <div className={styles.wrap} ref={wrapRef}>
      <Button
        variant="tertiary"
        leftIcon={Download}
        rightIcon={ChevronDown}
        loading={busy}
        disabled={disabled}
        aria-haspopup="menu"
        aria-expanded={open}
        onClick={() => setOpen((value) => !value)}
      >
        {label}
      </Button>
      {open && (
        <div className={styles.menu} role="menu" ref={menuRef} onKeyDown={onMenuKeyDown}>
          {FORMATS.map((format) => (
            <button
              key={format.value}
              type="button"
              role="menuitem"
              tabIndex={-1}
              className={styles.item}
              onClick={() => void choose(format.value)}
            >
              <Icon icon={format.icon} size={16} />
              {format.label}
            </button>
          ))}
        </div>
      )}
    </div>
  )
}
