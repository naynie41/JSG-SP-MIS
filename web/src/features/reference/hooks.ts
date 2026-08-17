import { useQuery } from '@tanstack/react-query'
import { referenceApi } from './api'

/**
 * Reference data changes only when a maintainer loads a new dataset, so it is cached
 * hard on the client too — a cascading selector should never re-fetch the LGA list
 * while the user is opening and closing LGA blocks.
 */
const STALE_MS = 60 * 60 * 1000

export function useLgas(enabled = true) {
  return useQuery({
    queryKey: ['reference', 'lgas'],
    queryFn: () => referenceApi.lgas(),
    staleTime: STALE_MS,
    enabled,
  })
}

/**
 * Wards of one LGA. Keyed by lgaId so each LGA's list is cached separately — this is
 * what keeps a ward selector scoped to its own LGA rather than showing the last-fetched
 * LGA's wards while a new request is in flight.
 */
export function useWards(lgaId: string | undefined) {
  return useQuery({
    queryKey: ['reference', 'wards', lgaId],
    queryFn: () => referenceApi.wards(lgaId!),
    staleTime: STALE_MS,
    enabled: Boolean(lgaId),
  })
}
