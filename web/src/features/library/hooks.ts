import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { useToast } from '@/components/Toast/ToastProvider'
import { libraryApi } from './api'
import type { LibraryResourceInput, LibraryStatus } from './types'

const ADMIN_KEY = ['library-admin']
const PUBLIC_KEY = ['library-public']

/**
 * The published library, for the public /resources page.
 *
 * Filtering and searching happen on the client from this one response rather than
 * by refetching per keystroke: the library is a small, slow-moving editorial set,
 * and an unauthenticated endpoint is the last place to send a request per character
 * typed. The API supports both so that a future large library can switch.
 */
export function usePublicLibrary() {
  return useQuery({
    queryKey: PUBLIC_KEY,
    queryFn: () => libraryApi.publicList(),
    staleTime: 60_000,
  })
}

/** Every resource in every state — the administration list. */
export function useLibraryAdmin(enabled = true) {
  return useQuery({
    queryKey: ADMIN_KEY,
    queryFn: () => libraryApi.list(),
    enabled,
  })
}

/**
 * Both caches are invalidated on every write, always.
 *
 * It would be tempting to skip the public one when saving a draft, but the moment
 * that reasoning is wrong — publishing, archiving, editing something already live —
 * the public page shows stale content, and nobody notices because the administrator
 * is looking at the admin list.
 */
function invalidateBoth(qc: ReturnType<typeof useQueryClient>): void {
  void qc.invalidateQueries({ queryKey: ADMIN_KEY })
  void qc.invalidateQueries({ queryKey: PUBLIC_KEY })
}

export function useCreateResource() {
  const qc = useQueryClient()
  const toast = useToast()

  return useMutation({
    mutationFn: (input: LibraryResourceInput) => libraryApi.create(input),
    onSuccess: (resource) => {
      invalidateBoth(qc)
      toast.success(
        resource.status === 'published' ? 'Resource published' : 'Draft saved',
        resource.status === 'published'
          ? `${resource.title} is now on the public resources page.`
          : `${resource.title} was saved. It is not public until you publish it.`,
      )
    },
  })
}

export function useUpdateResource() {
  const qc = useQueryClient()
  const toast = useToast()

  return useMutation({
    mutationFn: ({ id, input }: { id: string; input: Partial<LibraryResourceInput> }) =>
      libraryApi.update(id, input),
    onSuccess: (resource) => {
      invalidateBoth(qc)
      toast.success('Resource saved', `${resource.title} was updated.`)
    },
  })
}

/** Publish or withdraw. Separate from the edit form because it is a one-click act. */
export function useResourceStatus() {
  const qc = useQueryClient()
  const toast = useToast()

  return useMutation({
    mutationFn: ({ id, status }: { id: string; status: LibraryStatus }) =>
      libraryApi.update(id, { status }),
    onSuccess: (resource) => {
      invalidateBoth(qc)
      toast.success(
        resource.status === 'published' ? 'Published' : 'Withdrawn',
        resource.status === 'published'
          ? `${resource.title} is now public.`
          : `${resource.title} is no longer public. Its download history is kept.`,
      )
    },
  })
}

export function useDeleteResource() {
  const qc = useQueryClient()
  const toast = useToast()

  return useMutation({
    mutationFn: (id: string) => libraryApi.remove(id),
    onSuccess: () => {
      invalidateBoth(qc)
      toast.success('Resource removed', 'It is no longer listed anywhere.')
    },
  })
}
