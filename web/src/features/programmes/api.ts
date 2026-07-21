import { apiRequest, apiRequestList } from '@/lib/api/client'
import type { Paginated } from '@/lib/api/client'
import type { Activity, ActivityDetail, ActivityInput, Budget, BulkEnrollResult, Enrollment, Programme, ProgrammeInput } from './types'

export interface ProgrammeListParams {
  page?: number
  search?: string
  status?: string
  type?: string
}

export const programmeApi = {
  list(params: ProgrammeListParams = {}): Promise<Paginated<Programme>> {
    return apiRequestList<Programme>({
      method: 'GET',
      url: '/programmes',
      params: {
        page: params.page,
        search: params.search || undefined,
        'filter[status]': params.status || undefined,
        'filter[type]': params.type || undefined,
      },
    })
  },
  get(id: string): Promise<Programme> {
    return apiRequest<Programme>({ method: 'GET', url: `/programmes/${id}` })
  },
  /** The active catalog — for the activity programme dropdown + read-only labels. */
  catalog(): Promise<Paginated<Programme>> {
    return apiRequestList<Programme>({ method: 'GET', url: '/programmes', params: { 'filter[status]': 'active', per_page: 100 } })
  },
  create(input: ProgrammeInput): Promise<Programme> {
    return apiRequest<Programme>({ method: 'POST', url: '/programmes', data: input })
  },
  update(id: string, input: Partial<ProgrammeInput>): Promise<Programme> {
    return apiRequest<Programme>({ method: 'PATCH', url: `/programmes/${id}`, data: input })
  },
  archive(id: string): Promise<Programme> {
    return apiRequest<Programme>({ method: 'POST', url: `/programmes/${id}/archive` })
  },
  budget(id: string): Promise<Budget> {
    return apiRequest<Budget>({ method: 'GET', url: `/programmes/${id}/budget` })
  },
}

export const activityApi = {
  listForProgramme(programmeId: string): Promise<Paginated<Activity>> {
    return apiRequestList<Activity>({ method: 'GET', url: '/activities', params: { 'filter[programme_id]': programmeId, per_page: 100 } })
  },
  /** All activities the caller's MDA owns (across catalog programmes). */
  list(): Promise<Paginated<Activity>> {
    return apiRequestList<Activity>({ method: 'GET', url: '/activities', params: { per_page: 100 } })
  },
  /** Full "View Activity" detail: programme, counts, beneficiaries, import summary, service requests. */
  get(id: string): Promise<ActivityDetail> {
    return apiRequest<ActivityDetail>({ method: 'GET', url: `/activities/${id}` })
  },
  create(input: ActivityInput): Promise<Activity> {
    return apiRequest<Activity>({ method: 'POST', url: '/activities', data: input })
  },
  update(id: string, input: Partial<ActivityInput>): Promise<Activity> {
    return apiRequest<Activity>({ method: 'PATCH', url: `/activities/${id}`, data: input })
  },
  archive(id: string): Promise<Activity> {
    return apiRequest<Activity>({ method: 'POST', url: `/activities/${id}/archive` })
  },
  budget(id: string): Promise<Budget> {
    return apiRequest<Budget>({ method: 'GET', url: `/activities/${id}/budget` })
  },
}

export const enrollmentApi = {
  listForProgramme(programmeId: string): Promise<Paginated<Enrollment>> {
    return apiRequestList<Enrollment>({ method: 'GET', url: '/enrollments', params: { 'filter[programme_id]': programmeId, per_page: 100 } })
  },
  enroll(programmeId: string, input: { beneficiary_id?: string; household_id?: string; activity_id?: string }): Promise<Enrollment> {
    return apiRequest<Enrollment>({ method: 'POST', url: `/programmes/${programmeId}/enrollments`, data: input })
  },
  bulk(programmeId: string, input: { beneficiary_ids?: string[]; household_ids?: string[]; activity_id?: string }): Promise<BulkEnrollResult> {
    return apiRequest<BulkEnrollResult>({ method: 'POST', url: `/programmes/${programmeId}/enrollments/bulk`, data: input })
  },
  update(id: string, input: { status: string; exit_reason?: string }): Promise<Enrollment> {
    return apiRequest<Enrollment>({ method: 'PATCH', url: `/enrollments/${id}`, data: input })
  },
}
