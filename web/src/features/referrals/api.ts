import { apiRequest, apiRequestList } from '@/lib/api/client'
import type { Paginated } from '@/lib/api/client'
import type { Referral, ReferralAction, ReferralDirection, ReferralInput, ReferralWithLedger } from './types'

export interface ReferralListParams {
  direction?: ReferralDirection
  status?: string
  page?: number
}

/** Payload a lifecycle transition may carry (reason / note / outcome). */
export interface ReferralActionInput {
  reason?: string
  note?: string
  outcome?: string
}

const ACTION_PATH: Record<ReferralAction, string> = {
  accept: 'accept',
  reject: 'reject',
  'request-info': 'request-info',
  'respond-info': 'respond-info',
  start: 'start',
  complete: 'complete',
  close: 'close',
}

export const referralApi = {
  list(params: ReferralListParams = {}): Promise<Paginated<Referral>> {
    return apiRequestList<Referral>({
      method: 'GET',
      url: '/referrals',
      params: {
        page: params.page,
        'filter[direction]': params.direction || undefined,
        'filter[status]': params.status || undefined,
      },
    })
  },
  get(id: string): Promise<ReferralWithLedger> {
    return apiRequest<ReferralWithLedger>({ method: 'GET', url: `/referrals/${id}` })
  },
  create(input: ReferralInput): Promise<Referral> {
    return apiRequest<Referral>({ method: 'POST', url: '/referrals', data: input })
  },
  act(id: string, action: ReferralAction, input: ReferralActionInput = {}): Promise<ReferralWithLedger> {
    return apiRequest<ReferralWithLedger>({ method: 'POST', url: `/referrals/${id}/${ACTION_PATH[action]}`, data: input })
  },
}
