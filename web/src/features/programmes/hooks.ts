import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { useToast } from '@/components/Toast/ToastProvider'
import { activityApi, enrollmentApi, programmeApi } from './api'
import type { ProgrammeListParams } from './api'
import type { ActivityInput, ProgrammeInput } from './types'

/* ------------------------------------------------------------------ programmes */

export function useProgrammes(params: ProgrammeListParams, enabled = true) {
  return useQuery({ queryKey: ['programmes', params], queryFn: () => programmeApi.list(params), enabled })
}

export function useProgramme(id: string | undefined, enabled = true) {
  return useQuery({ queryKey: ['programme', id], queryFn: () => programmeApi.get(id!), enabled: enabled && Boolean(id) })
}

/** The active catalog (for the activity programme dropdown + read-only labels). */
export function useProgrammeCatalog(enabled = true) {
  return useQuery({ queryKey: ['programme-catalog'], queryFn: () => programmeApi.catalog(), enabled })
}

export function useProgrammeBudget(id: string | undefined, enabled = true) {
  return useQuery({ queryKey: ['programme-budget', id], queryFn: () => programmeApi.budget(id!), enabled: enabled && Boolean(id) })
}

export function useSaveProgramme() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ id, input }: { id?: string; input: ProgrammeInput }) =>
      id ? programmeApi.update(id, input) : programmeApi.create(input),
    onSuccess: (programme) => {
      qc.invalidateQueries({ queryKey: ['programmes'] })
      qc.invalidateQueries({ queryKey: ['programme', programme.id] })
      toast.success('Programme saved', programme.name)
    },
  })
}

export function useArchiveProgramme() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (id: string) => programmeApi.archive(id),
    onSuccess: (programme) => {
      qc.invalidateQueries({ queryKey: ['programmes'] })
      qc.invalidateQueries({ queryKey: ['programme', programme.id] })
      toast.success('Programme archived', programme.name)
    },
  })
}

/* ------------------------------------------------------------------ activities */

export function useActivities(programmeId: string | undefined, enabled = true) {
  return useQuery({
    queryKey: ['activities', programmeId],
    queryFn: () => activityApi.listForProgramme(programmeId!),
    enabled: enabled && Boolean(programmeId),
  })
}

/** All activities the caller's MDA owns, across catalog programmes. */
export function useAllActivities(enabled = true) {
  return useQuery({ queryKey: ['activities', 'all'], queryFn: () => activityApi.list(), enabled })
}

/**
 * Save an activity. Pass a fixed `programmeId` (from a programme detail page) or
 * omit it and let the form's programme dropdown supply `input.programme_id`.
 */
export function useSaveActivity(programmeId?: string) {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ id, input }: { id?: string; input: ActivityInput }) =>
      id ? activityApi.update(id, input) : activityApi.create({ ...input, programme_id: input.programme_id ?? programmeId }),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['activities'] }) // covers per-programme + all-activities lists
      toast.success('Activity saved')
    },
  })
}

export function useArchiveActivity() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (id: string) => activityApi.archive(id),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['activities'] })
      toast.success('Activity archived')
    },
  })
}

/* ----------------------------------------------------------------- enrollments */

export function useEnrollments(programmeId: string | undefined, enabled = true) {
  return useQuery({
    queryKey: ['enrollments', programmeId],
    queryFn: () => enrollmentApi.listForProgramme(programmeId!),
    enabled: enabled && Boolean(programmeId),
  })
}

export function useEnroll(programmeId: string) {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (input: { beneficiary_id?: string; household_id?: string; activity_id?: string }) =>
      enrollmentApi.enroll(programmeId, input),
    onSuccess: (enrollment) => {
      qc.invalidateQueries({ queryKey: ['enrollments', programmeId] })
      toast.success('Enrolled', enrollment.eligibility_flagged ? 'Enrolled with an eligibility flag.' : undefined)
    },
  })
}

export function useBulkEnroll(programmeId: string) {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (input: { beneficiary_ids?: string[]; household_ids?: string[]; activity_id?: string }) =>
      enrollmentApi.bulk(programmeId, input),
    onSuccess: (result) => {
      qc.invalidateQueries({ queryKey: ['enrollments', programmeId] })
      toast.success('Bulk enrollment done', `${result.enrolled} enrolled · ${result.skipped} skipped · ${result.rejected} rejected`)
    },
  })
}

export function useUpdateEnrollment(programmeId: string) {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ id, status, exit_reason }: { id: string; status: string; exit_reason?: string }) =>
      enrollmentApi.update(id, { status, exit_reason }),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['enrollments', programmeId] })
      toast.success('Enrollment updated')
    },
  })
}
