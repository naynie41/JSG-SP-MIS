import { apiRequest, apiRequestList } from '@/lib/api/client'
import type { Paginated } from '@/lib/api/client'
import type { Activity, ActivityDetail, ActivityInput, Budget, BulkEnrollResult, Enrollment, Programme, ProgrammeInput } from './types'

export interface ProgrammeListParams {
  page?: number
  /** Page size (API caps at 100). Used by the catalog-usage overview. */
  per_page?: number
  search?: string
  status?: string
  type?: string
  /**
   * Limit to catalog programmes the caller runs activities under. Server-side because
   * `activities_count > 0` filtered in the client would silently drop matches that
   * fall beyond the first page.
   */
  participating?: boolean
  /** Limit to programmes waiting for a decision (the System Administrator's queue). */
  approval?: 'pending' | 'approved' | 'rejected'
}

export const programmeApi = {
  list(params: ProgrammeListParams = {}): Promise<Paginated<Programme>> {
    return apiRequestList<Programme>({
      method: 'GET',
      url: '/programmes',
      params: {
        page: params.page,
        per_page: params.per_page,
        search: params.search || undefined,
        'filter[status]': params.status || undefined,
        'filter[type]': params.type || undefined,
        'filter[participating]': params.participating ? 1 : undefined,
        'filter[approval]': params.approval || undefined,
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
  /** Offer a programme that was sent back to the System Administrator again. */
  submit(id: string): Promise<Programme> {
    return apiRequest<Programme>({ method: 'POST', url: `/programmes/${id}/submit` })
  },
  approve(id: string, note?: string): Promise<Programme> {
    return apiRequest<Programme>({ method: 'POST', url: `/programmes/${id}/approve`, data: { decision_note: note || undefined } })
  },
  /** Send it back. The reason is mandatory — the server refuses one without it. */
  reject(id: string, note: string): Promise<Programme> {
    return apiRequest<Programme>({ method: 'POST', url: `/programmes/${id}/reject`, data: { decision_note: note } })
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
  /** Undo an archive. Returns the activity to COMPLETED, never straight to active. */
  restore(id: string): Promise<Activity> {
    return apiRequest<Activity>({ method: 'POST', url: `/activities/${id}/restore` })
  },
  budget(id: string): Promise<Budget> {
    return apiRequest<Budget>({ method: 'GET', url: `/activities/${id}/budget` })
  },
  /** Active social protection partner accounts an activity can be linked to (names only). */
  async fundingPartners(): Promise<import('./types').FundingPartnerOption[]> {
    const { partners } = await apiRequest<{ partners: import('./types').FundingPartnerOption[] }>({
      method: 'GET',
      url: '/activities/funding-partners',
    })
    return partners
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
