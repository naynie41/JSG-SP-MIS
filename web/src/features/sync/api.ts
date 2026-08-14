import { apiRequest, apiRequestList } from '@/lib/api/client'
import type { Paginated } from '@/lib/api/client'
import type { ConnectorMappingProposal, SyncConnector, SyncRun } from './types'

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
  /**
   * The connector's mapping screen: a live sample from the source, advisory suggestions,
   * and whether the source's shape has moved since the mapping was approved.
   */
  mapping(connectorId: string): Promise<ConnectorMappingProposal> {
    return apiRequest<ConnectorMappingProposal>({ method: 'GET', url: `/sync/connectors/${connectorId}/mapping` })
  },
  /**
   * Approve the connector's mapping (CLAUDE.md §11). Every identity field must be a KEY
   * in `column_map` — a source field, or `null` for "not present". Enforced server-side;
   * re-confirming also clears a stale flag.
   */
  confirmMapping(connectorId: string, columnMap: Record<string, string | null>): Promise<{ message: string }> {
    return apiRequest<{ message: string }>({
      method: 'PUT',
      url: `/sync/connectors/${connectorId}/mapping`,
      data: { column_map: columnMap },
    })
  },
  /** Enable/disable. The server refuses to ENABLE while the mapping is unconfirmed or stale. */
  setEnabled(connectorId: string, enabled: boolean): Promise<SyncConnector> {
    return apiRequest<SyncConnector>({
      method: 'PUT',
      url: `/sync/connectors/${connectorId}/enabled`,
      data: { enabled },
    })
  },
}
