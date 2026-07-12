import { http } from './client'

export type ExportFormat = 'csv' | 'excel'

/** Save a fetched Blob to the user's device (authenticated download). */
function triggerBlobDownload(blob: Blob, filename: string): void {
  const url = URL.createObjectURL(blob)
  const anchor = window.document.createElement('a')
  anchor.href = url
  anchor.download = filename
  window.document.body.appendChild(anchor)
  anchor.click()
  anchor.remove()
  URL.revokeObjectURL(url)
}

/**
 * Export a list endpoint's CURRENT filtered/searched view (DESIGN-SYSTEM.md §5.4).
 * Sends `params` (the grid's live filters/search) plus the chosen `format`. A small
 * export streams back and downloads immediately; a large one is queued server-side
 * (HTTP 202) and resolves `{ queued: true }` so the caller can tell the user a ready
 * notification is coming. Transport only — no UI — so any grid reuses it unchanged.
 */
export async function exportListFile(
  endpoint: string,
  params: Record<string, string | number | undefined>,
  format: ExportFormat,
): Promise<{ queued: boolean }> {
  const response = await http.get(endpoint, {
    params: { ...params, format },
    responseType: 'blob',
  })

  // Over the size threshold → queued; the JSON envelope arrives as a blob.
  if (response.status === 202) {
    return { queued: true }
  }

  const disposition = String(response.headers['content-disposition'] ?? '')
  const named = /filename="?([^";]+)"?/.exec(disposition)?.[1]
  triggerBlobDownload(response.data as Blob, named ?? `export.${format === 'excel' ? 'xlsx' : 'csv'}`)
  return { queued: false }
}
