import { ExportMenu } from '@/components/ExportMenu/ExportMenu'
import { useToast } from '@/components/Toast/ToastProvider'
import { useAuth } from '@/lib/auth/AuthProvider'
import { exportListFile } from '@/lib/api/exportList'
import type { ExportFormat } from '@/lib/api/exportList'
import { ApiError } from '@/types/api'

export interface DataTableExportProps {
  /** The list's export endpoint, e.g. "/beneficiaries/export". */
  endpoint: string
  /**
   * The grid's CURRENT filters/search — sent verbatim as query params (use the exact
   * keys the endpoint expects, e.g. `{ search, 'filter[lga]': lga }`). Read at click
   * time, so the export always matches what the user is looking at.
   */
  params: Record<string, string | number | undefined>
  /** Permission key required to export; the control hides itself for users without it. */
  permission: string
  label?: string
}

/**
 * The reusable Data Table "Export" action (DESIGN-SYSTEM.md §5.4). Drop it into any
 * grid's toolbar: it renders the CSV/Excel menu, sends the current filtered view to the
 * grid's export endpoint, downloads the file (or reports a queued export via toast), and
 * hides itself for users without the export permission. Grids opt in without
 * re-implementing the transport, feedback, or gating — masking/scope/audit live server-side.
 */
export function DataTableExport({ endpoint, params, permission, label }: DataTableExportProps) {
  const { hasPermission } = useAuth()
  const toast = useToast()

  async function onExport(format: ExportFormat) {
    try {
      const { queued } = await exportListFile(endpoint, params, format)
      if (queued) {
        toast.info('Export queued', "It's a large export — we'll notify you when the file is ready to download.")
      } else {
        toast.success('Export started', 'Your download should begin shortly.')
      }
    } catch (error) {
      toast.error('Export failed', error instanceof ApiError ? error.message : 'Could not export the list. Please try again.')
    }
  }

  if (!hasPermission(permission)) {
    return null
  }

  return <ExportMenu label={label} onExport={onExport} />
}
