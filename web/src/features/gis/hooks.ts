import { useQuery } from '@tanstack/react-query'
import { gisApi } from './api'
import type { GisLevel } from './types'

export function useGisCoverage(level: GisLevel, enabled = true) {
  return useQuery({
    queryKey: ['gis-coverage', level],
    queryFn: () => gisApi.coverage(level),
    enabled,
  })
}
