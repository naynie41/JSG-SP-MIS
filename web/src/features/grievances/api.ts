import { apiRequest, apiRequestList } from '@/lib/api/client'
import type { Paginated } from '@/lib/api/client'
import type { Grievance, GrievanceAction, GrievanceInput } from './types'

export interface GrievanceListParams {
  status?: string
  category?: string
  assignee?: 'me'
  page?: number
}

export interface GrievanceActionInput {
  resolution_notes?: string
}

export const grievanceApi = {
  list(params: GrievanceListParams = {}): Promise<Paginated<Grievance>> {
    return apiRequestList<Grievance>({
      method: 'GET',
      url: '/grievances',
      params: {
        page: params.page,
        'filter[status]': params.status || undefined,
        'filter[category]': params.category || undefined,
        'filter[assignee]': params.assignee || undefined,
      },
    })
  },
  get(id: string): Promise<Grievance> {
    return apiRequest<Grievance>({ method: 'GET', url: `/grievances/${id}` })
  },
  create(input: GrievanceInput): Promise<Grievance> {
    return apiRequest<Grievance>({ method: 'POST', url: '/grievances', data: input })
  },
  assign(id: string, assigneeUserId: string): Promise<Grievance> {
    return apiRequest<Grievance>({ method: 'POST', url: `/grievances/${id}/assign`, data: { assignee_user_id: assigneeUserId } })
  },
  act(id: string, action: GrievanceAction, input: GrievanceActionInput = {}): Promise<Grievance> {
    return apiRequest<Grievance>({ method: 'POST', url: `/grievances/${id}/${action}`, data: input })
  },
}
