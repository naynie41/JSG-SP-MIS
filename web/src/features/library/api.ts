import { apiRequest } from '@/lib/api/client'
import type { LibraryCategory, LibraryResource, LibraryResourceInput, PublicResource } from './types'

/**
 * Build the multipart body.
 *
 * A resource can carry a file and a thumbnail, so these requests are multipart
 * rather than JSON. Two details that are easy to get wrong:
 *
 *  - **Booleans must be sent as '1'/'0'.** A multipart field is always a string,
 *    and PHP reads the string "false" as truthy — `featured` would be permanently
 *    on.
 *  - **Undefined is omitted, null is not.** The update endpoint is a PATCH: a field
 *    that is absent keeps its stored value, so sending `undefined` as the string
 *    "undefined" would overwrite it with nonsense.
 */
function toFormData(input: Partial<LibraryResourceInput>): FormData {
  const form = new FormData()

  for (const [key, value] of Object.entries(input)) {
    if (value === undefined || value === null) continue

    if (typeof value === 'boolean') {
      form.append(key, value ? '1' : '0')
    } else if (value instanceof File) {
      form.append(key, value)
    } else {
      form.append(key, String(value))
    }
  }

  return form
}

export const libraryApi = {
  /* ------------------------------------------------------------------ public */

  /**
   * The published library. Deliberately has no token requirement — this is the one
   * call in the app that works signed out, and it must keep working that way.
   */
  publicList(params?: { category?: string; search?: string }): Promise<{
    items: PublicResource[]
    categories: LibraryCategory[]
  }> {
    return apiRequest({ method: 'GET', url: '/public/library', params })
  },

  /* ------------------------------------------------------------------- admin */

  list(params?: { status?: string; category?: string }): Promise<{
    items: LibraryResource[]
    categories: Record<string, string>
  }> {
    return apiRequest({ method: 'GET', url: '/library', params })
  },

  async create(input: LibraryResourceInput): Promise<LibraryResource> {
    const { resource } = await apiRequest<{ resource: LibraryResource }>({
      method: 'POST',
      url: '/library',
      data: toFormData(input),
    })
    return resource
  },

  /**
   * POST with a `_method` override rather than a real PATCH: PHP does not populate
   * $_FILES for PATCH bodies, so a genuine PATCH would silently arrive with no file
   * attached — the update would appear to succeed and change nothing.
   */
  async update(id: string, input: Partial<LibraryResourceInput>): Promise<LibraryResource> {
    const form = toFormData(input)
    form.append('_method', 'PATCH')

    const { resource } = await apiRequest<{ resource: LibraryResource }>({
      method: 'POST',
      url: `/library/${id}`,
      data: form,
    })
    return resource
  },

  remove(id: string): Promise<{ deleted: boolean }> {
    return apiRequest({ method: 'DELETE', url: `/library/${id}` })
  },
}
