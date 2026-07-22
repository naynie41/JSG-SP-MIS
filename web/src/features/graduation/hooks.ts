import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { useToast } from '@/components/Toast/ToastProvider'
import { graduationApi } from './api'
import type { GraduationCriteriaInput } from './types'

/* -------------------------------------------------------------------- criteria */

export function useGraduationCriteria(programmeId: string | undefined, enabled = true) {
  return useQuery({
    queryKey: ['graduation-criteria', programmeId],
    queryFn: () => graduationApi.criteriaForProgramme(programmeId!),
    enabled: enabled && Boolean(programmeId),
  })
}

export function useSaveGraduationCriteria(programmeId: string) {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ id, input }: { id?: string; input: GraduationCriteriaInput }) =>
      id ? graduationApi.updateCriteria(id, input) : graduationApi.createCriteria(programmeId, input),
    onSuccess: (criteria) => {
      qc.invalidateQueries({ queryKey: ['graduation-criteria', programmeId] })
      toast.success('Graduation criteria saved', criteria.name)
    },
  })
}

export function useRemoveGraduationCriteria(programmeId: string) {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (id: string) => graduationApi.removeCriteria(id),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['graduation-criteria', programmeId] })
      toast.success('Graduation criteria removed')
    },
  })
}

/* ---------------------------------------------------------- progress + graduate */

export function useGraduationProgress(enrollmentId: string | undefined, enabled = true) {
  return useQuery({
    queryKey: ['graduation-progress', enrollmentId],
    queryFn: () => graduationApi.progress(enrollmentId!),
    enabled: enabled && Boolean(enrollmentId),
  })
}

export function useGraduate() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ enrollmentId, reason }: { enrollmentId: string; reason?: string }) =>
      graduationApi.graduate(enrollmentId, reason),
    onSuccess: (_event, { enrollmentId }) => {
      qc.invalidateQueries({ queryKey: ['graduation-progress', enrollmentId] })
      qc.invalidateQueries({ queryKey: ['enrollments'] })
      qc.invalidateQueries({ queryKey: ['graduation-events'] })
      toast.success('Graduation recorded', 'The beneficiary and their benefit history are preserved.')
    },
  })
}
