/** Shared presentation helpers for the resource library, admin and public alike. */

/**
 * A file size a person can read at a glance.
 *
 * Decimal units (1000, not 1024) on purpose: this sits next to a download button
 * for the general public, and "2.4 MB" should mean what their browser and their
 * operating system will also call it.
 */
export function formatBytes(bytes: number | null | undefined): string {
  if (bytes === null || bytes === undefined) return ''
  if (bytes < 1000) return `${bytes} B`

  const units = ['KB', 'MB', 'GB']
  let value = bytes / 1000
  let unit = 0

  while (value >= 1000 && unit < units.length - 1) {
    value /= 1000
    unit += 1
  }

  return `${value < 10 ? value.toFixed(1) : Math.round(value)} ${units[unit]}`
}

/**
 * The document type, for the badge on a card.
 *
 * Read from the FILENAME rather than the MIME type, because that is what the
 * visitor recognises — "DOCX" means something to them where
 * `application/vnd.openxmlformats-officedocument.wordprocessingml.document`
 * does not.
 */
export function fileKindLabel(filename: string | null | undefined): string {
  if (!filename) return 'File'

  const extension = filename.split('.').pop()?.toUpperCase() ?? ''

  return extension === '' || extension === filename.toUpperCase() ? 'File' : extension
}

/** "12 March 2026", or an empty string when a resource was never published. */
export function formatPublished(iso: string | null | undefined): string {
  if (!iso) return ''

  const date = new Date(iso)
  if (Number.isNaN(date.getTime())) return ''

  return date.toLocaleDateString(undefined, { day: 'numeric', month: 'long', year: 'numeric' })
}
