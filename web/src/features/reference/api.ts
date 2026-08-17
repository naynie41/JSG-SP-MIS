import { apiRequest } from '@/lib/api/client'
import type { Lga, Ward } from './types'

/**
 * LGA/Ward reference lookups (GEO.1). Read-only — the list comes from an authoritative
 * dataset a maintainer loads, never from the UI.
 */
export const referenceApi = {
  lgas(): Promise<{ lgas: Lga[] }> {
    return apiRequest<{ lgas: Lga[] }>({ method: 'GET', url: '/reference/lgas' })
  },
  /** Wards of ONE LGA — the cascading selector's second step. */
  wards(lgaId: string): Promise<{ wards: Ward[] }> {
    return apiRequest<{ wards: Ward[] }>({ method: 'GET', url: '/reference/wards', params: { lga_id: lgaId } })
  },
}
