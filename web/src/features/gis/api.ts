import { apiRequest } from '@/lib/api/client'
import type { GisCoverageResponse, GisLevel } from './types'

export const gisApi = {
  /** Scope-aware coverage for the map (choropleth) or the table fallback. `params`
   * carries the active cross-cutting filter (period/programme/area/MDA). */
  coverage(level: GisLevel, params: Record<string, string | number> = {}): Promise<GisCoverageResponse> {
    return apiRequest<GisCoverageResponse>({ method: 'GET', url: '/gis/coverage', params: { level, ...params } })
  },
}
