import { apiRequest, apiRequestList } from '@/lib/api/client'
import type { Paginated } from '@/lib/api/client'
import type { Aggregate, Benefit, BenefitFlag, BenefitImportBatch, BenefitInput } from './types'

export interface AggregateParams {
  group_by: string
  programme_id?: string
  mda_id?: string
  benefit_type?: string
  lga?: string
  date_from?: string
  date_to?: string
}

export const benefitApi = {
  record(input: BenefitInput): Promise<Benefit> {
    return apiRequest<Benefit>({ method: 'POST', url: '/benefits', data: input })
  },
  verify(id: string, input: { verification_method: string; verification_reference?: string }): Promise<Benefit> {
    return apiRequest<Benefit>({ method: 'POST', url: `/benefits/${id}/verify`, data: input })
  },
  list(params: { page?: number; programme_id?: string; status?: string; benefit_type?: string } = {}): Promise<Paginated<Benefit>> {
    return apiRequestList<Benefit>({
      method: 'GET',
      url: '/benefits',
      params: {
        page: params.page,
        'filter[programme_id]': params.programme_id || undefined,
        'filter[status]': params.status || undefined,
        'filter[benefit_type]': params.benefit_type || undefined,
      },
    })
  },
  ledger(beneficiaryId: string): Promise<Paginated<Benefit>> {
    return apiRequestList<Benefit>({ method: 'GET', url: `/beneficiaries/${beneficiaryId}/benefits` })
  },
  aggregate(params: AggregateParams): Promise<Aggregate> {
    return apiRequest<Aggregate>({ method: 'GET', url: '/benefits/aggregate', params })
  },
}

export const benefitImportApi = {
  upload(activityId: string, file: File): Promise<BenefitImportBatch> {
    const form = new FormData()
    form.append('file', file)
    form.append('activity_id', activityId)
    return apiRequest<BenefitImportBatch>({ method: 'POST', url: '/benefit-imports', data: form })
  },
  get(id: string): Promise<BenefitImportBatch> {
    return apiRequest<BenefitImportBatch>({ method: 'GET', url: `/benefit-imports/${id}` })
  },
  confirm(id: string): Promise<BenefitImportBatch> {
    return apiRequest<BenefitImportBatch>({ method: 'POST', url: `/benefit-imports/${id}/confirm` })
  },
}

export const flagApi = {
  async list(params: { status?: string; beneficiary_id?: string } = {}): Promise<BenefitFlag[]> {
    const { items } = await apiRequestList<BenefitFlag>({
      method: 'GET',
      url: '/benefit-flags',
      params: {
        'filter[status]': params.status || undefined,
        'filter[beneficiary_id]': params.beneficiary_id || undefined,
      },
    })
    return items
  },
  review(id: string, input: { status: 'confirmed' | 'dismissed'; review_note?: string }): Promise<BenefitFlag> {
    return apiRequest<BenefitFlag>({ method: 'POST', url: `/benefit-flags/${id}/review`, data: input })
  },
}
