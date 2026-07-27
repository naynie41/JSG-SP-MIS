import { useQuery } from '@tanstack/react-query'
import { gisApi } from './api'
import type { GisLevel } from './types'

export function useGisCoverage(level: GisLevel, params: Record<string, string | number> = {}, enabled = true) {
  return useQuery({
    queryKey: ['gis-coverage', level, params],
    queryFn: () => gisApi.coverage(level, params),
    enabled,
  })
}
