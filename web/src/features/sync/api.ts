import { apiRequest, apiRequestList } from '@/lib/api/client'
import type { Paginated } from '@/lib/api/client'
import type { SyncConnector, SyncRun } from './types'

export const syncApi = {
  /** Configured connectors and their status. */
  async connectors(): Promise<SyncConnector[]> {
    const { connectors } = await apiRequest<{ connectors: SyncConnector[] }>({
      method: 'GET',
      url: '/sync/connectors',
    })
    return connectors
  },
  /** Recent synchronization runs (integration history). */
  runs(perPage = 20): Promise<Paginated<SyncRun>> {
    return apiRequestList<SyncRun>({ method: 'GET', url: '/sync/runs', params: { per_page: perPage } })
  },
  /** Trigger a connector manually — queued, idempotent, unique per connector. */
  trigger(connectorId: string): Promise<{ message: string }> {
    return apiRequest<{ message: string }>({ method: 'POST', url: `/sync/connectors/${connectorId}/run` })
  },
}
