import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { useToast } from '@/components/Toast/ToastProvider'
import { ApiError } from '@/types/api'
import { syncApi } from './api'

const CONNECTORS_KEY = ['sync-connectors']
const RUNS_KEY = ['sync-runs']

/**
 * Phase 7 presence is decided at RUNTIME, not assumed. A 404/501 from the sync surface
 * means the synchronization engine is not enabled in this deployment, and the console
 * renders its pending state instead of fabricating data. Any other failure (403, 500) is
 * a real error and is surfaced as such — "not permitted" is not the same as "not built".
 */
export function isSyncUnavailable(error: unknown): boolean {
  return error instanceof ApiError && (error.status === 404 || error.status === 501)
}

/** Connected systems. `enabled: false` skips the probe entirely (e.g. no permission). */
export function useSyncConnectors(enabled = true) {
  return useQuery({
    queryKey: CONNECTORS_KEY,
    queryFn: () => syncApi.connectors(),
    enabled,
    retry: (count, error) => !isSyncUnavailable(error) && count < 2,
  })
}

/** Integration history — recent synchronization runs. */
export function useSyncRuns(enabled = true) {
  return useQuery({
    queryKey: RUNS_KEY,
    queryFn: () => syncApi.runs(),
    enabled,
    retry: (count, error) => !isSyncUnavailable(error) && count < 2,
  })
}

/** Manual synchronization — dispatches the connector's queued run. */
export function useTriggerSync() {
  const qc = useQueryClient()
  const toast = useToast()

  return useMutation({
    mutationFn: (connectorId: string) => syncApi.trigger(connectorId),
    onSuccess: (result) => {
      qc.invalidateQueries({ queryKey: RUNS_KEY })
      qc.invalidateQueries({ queryKey: CONNECTORS_KEY })
      toast.success('Synchronization started', result.message)
    },
    onError: (error) => {
      toast.error('Could not start synchronization', error instanceof ApiError ? error.message : 'Please try again.')
    },
  })
}
