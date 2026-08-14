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

/** The connector's mapping screen — sampled live from the source. */
export function useConnectorMapping(connectorId: string | undefined, enabled = true) {
  return useQuery({
    queryKey: ['sync-connector', connectorId, 'mapping'],
    queryFn: () => syncApi.mapping(connectorId!),
    enabled: enabled && Boolean(connectorId),
  })
}

/**
 * Approve a connector's standing mapping.
 *
 * Invalidates the CONNECTOR LIST as well as the mapping: confirming changes the
 * connector's status (and clears a stale flag), which is what the list shows.
 */
export function useConfirmConnectorMapping(connectorId: string) {
  const qc = useQueryClient()
  const toast = useToast()

  return useMutation({
    mutationFn: (columnMap: Record<string, string | null>) => syncApi.confirmMapping(connectorId, columnMap),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['sync-connector', connectorId, 'mapping'] })
      qc.invalidateQueries({ queryKey: CONNECTORS_KEY })
      toast.success(
        'Mapping confirmed',
        'This connector may now sync. It will hold again if the source’s fields change.',
      )
    },
  })
}

/** Enable/disable a connector. The server refuses to enable an unmapped or stale one. */
export function useSetConnectorEnabled() {
  const qc = useQueryClient()
  const toast = useToast()

  return useMutation({
    mutationFn: ({ id, enabled }: { id: string; enabled: boolean }) => syncApi.setEnabled(id, enabled),
    onSuccess: (connector) => {
      qc.invalidateQueries({ queryKey: CONNECTORS_KEY })
      toast.success(connector.enabled ? 'Connector enabled' : 'Connector disabled', connector.name)
    },
    onError: (error) => {
      toast.error(
        'Could not change the connector',
        error instanceof ApiError ? error.message : 'Please try again.',
      )
    },
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
