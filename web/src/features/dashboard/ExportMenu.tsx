import { useState } from 'react'
import { Download } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { dashboardApi } from './api'
import type { DashboardExportFormat } from './api'
import type { DashboardFilterValue } from './types'
import styles from './exportMenu.module.css'

const FORMATS: { value: DashboardExportFormat; label: string }[] = [
  { value: 'pdf', label: 'PDF' },
  { value: 'xlsx', label: 'Excel' },
  { value: 'csv', label: 'CSV' },
]

export interface ExportMenuProps {
  filter?: DashboardFilterValue
}

/**
 * Export the current (scoped + filtered) dashboard as an AGGREGATE PDF/Excel/CSV. Only
 * rendered for callers with `reporting.export` (the shell gates it); the file is always
 * de-identified aggregates — the server enforces scope + the permission matrix.
 */
export function ExportMenu({ filter }: ExportMenuProps) {
  const [open, setOpen] = useState(false)
  const [busy, setBusy] = useState<DashboardExportFormat | null>(null)

  const run = async (format: DashboardExportFormat) => {
    setBusy(format)
    try {
      await dashboardApi.export(format, filter)
      setOpen(false)
    } finally {
      setBusy(null)
    }
  }

  return (
    <div className={styles.wrap} onBlur={(e) => !e.currentTarget.contains(e.relatedTarget) && setOpen(false)}>
      <button type="button" className={styles.trigger} aria-haspopup="menu" aria-expanded={open} onClick={() => setOpen((v) => !v)}>
        <Icon icon={Download} size={14} />
        Export
      </button>
      {open && (
        <div className={styles.menu} role="menu" aria-label="Export format">
          {FORMATS.map((f) => (
            <button key={f.value} type="button" role="menuitem" className={styles.item} disabled={busy !== null} onClick={() => run(f.value)}>
              {busy === f.value ? 'Preparing…' : f.label}
            </button>
          ))}
        </div>
      )}
    </div>
  )
}
