import { apiRequest } from '@/lib/api/client'
import type { MdaActionRequired } from './types'

export const mdaApi = {
  /**
   * Live action-required counts. Deliberately separate from `/dashboard`, which serves
   * a snapshot refreshed on a 15-minute cycle — a stale work queue is worse than none.
   */
  actionRequired(): Promise<MdaActionRequired> {
    return apiRequest<MdaActionRequired>({ method: 'GET', url: '/mda/action-required' })
  },
}
