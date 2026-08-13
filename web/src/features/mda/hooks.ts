import { useQuery } from '@tanstack/react-query'
import { mdaApi } from './api'

/**
 * The action-required queue. Kept deliberately fresh — this drives a "do this now"
 * counter, so it refetches on focus and on a short interval rather than sitting on a
 * cached value while the officer works through the queue in another tab.
 */
export function useMdaActionRequired(enabled = true) {
  return useQuery({
    queryKey: ['mda-action-required'],
    queryFn: () => mdaApi.actionRequired(),
    enabled,
    staleTime: 30_000,
    refetchOnWindowFocus: true,
    refetchInterval: 120_000,
  })
}
