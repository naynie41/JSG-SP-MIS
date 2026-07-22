import { apiRequest, apiRequestList } from '@/lib/api/client'
import type { Paginated } from '@/lib/api/client'
import type { EnrollmentGraduation, GraduationCriteria, GraduationCriteriaInput, GraduationEvent } from './types'

export const graduationApi = {
  /** The MDA's criteria sets for a programme (FR-GRD-01). */
  criteriaForProgramme(programmeId: string): Promise<{ criteria: GraduationCriteria[] }> {
    return apiRequest<{ criteria: GraduationCriteria[] }>({ method: 'GET', url: `/programmes/${programmeId}/graduation-criteria` })
  },
  createCriteria(programmeId: string, input: GraduationCriteriaInput): Promise<GraduationCriteria> {
    return apiRequest<GraduationCriteria>({ method: 'POST', url: `/programmes/${programmeId}/graduation-criteria`, data: input })
  },
  updateCriteria(criteriaId: string, input: GraduationCriteriaInput): Promise<GraduationCriteria> {
    return apiRequest<GraduationCriteria>({ method: 'PUT', url: `/graduation-criteria/${criteriaId}`, data: input })
  },
  removeCriteria(criteriaId: string): Promise<{ message: string }> {
    return apiRequest<{ message: string }>({ method: 'DELETE', url: `/graduation-criteria/${criteriaId}` })
  },
  /** A beneficiary/household's progress toward graduation + this enrolment's history (FR-GRD-02). */
  progress(enrollmentId: string): Promise<EnrollmentGraduation> {
    return apiRequest<EnrollmentGraduation>({ method: 'GET', url: `/enrollments/${enrollmentId}/graduation` })
  },
  /** Record a graduation — audited, notified; the beneficiary + ledger are preserved. */
  graduate(enrollmentId: string, reason?: string): Promise<GraduationEvent> {
    return apiRequest<GraduationEvent>({ method: 'POST', url: `/enrollments/${enrollmentId}/graduate`, data: { reason: reason || undefined } })
  },
  history(params: { programme_id?: string; page?: number } = {}): Promise<Paginated<GraduationEvent>> {
    return apiRequestList<GraduationEvent>({ method: 'GET', url: '/graduation-events', params })
  },
}
