import { apiRequest } from '@/lib/api/client'
import type { GisCoverageResponse, GisLevel } from './types'

export const gisApi = {
  /** Scope-aware coverage for the map (choropleth) or the table fallback. */
  coverage(level: GisLevel): Promise<GisCoverageResponse> {
    return apiRequest<GisCoverageResponse>({ method: 'GET', url: '/gis/coverage', params: { level } })
  },
}
