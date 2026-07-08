import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { useToast } from '@/components/Toast/ToastProvider'
import { grievanceApi } from './api'
import type { GrievanceActionInput, GrievanceListParams } from './api'
import type { GrievanceAction, GrievanceInput } from './types'

export function useGrievances(params: GrievanceListParams, enabled = true) {
  return useQuery({ queryKey: ['grievances', params], queryFn: () => grievanceApi.list(params), enabled })
}

export function useGrievance(id: string | undefined, enabled = true) {
  return useQuery({
    queryKey: ['grievance', id],
    queryFn: () => grievanceApi.get(id!),
    enabled: enabled && Boolean(id),
  })
}

export function useLogGrievance() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (input: GrievanceInput) => grievanceApi.create(input),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['grievances'] })
      toast.success('Grievance logged', 'It is now open in the desk queue.')
    },
  })
}

function useGrievanceRefresh(id: string) {
  const qc = useQueryClient()
  return () => {
    qc.invalidateQueries({ queryKey: ['grievance', id] })
    qc.invalidateQueries({ queryKey: ['grievances'] })
  }
}

export function useAssignGrievance(id: string) {
  const refresh = useGrievanceRefresh(id)
  const toast = useToast()
  return useMutation({
    mutationFn: (assigneeUserId: string) => grievanceApi.assign(id, assigneeUserId),
    onSuccess: () => {
      refresh()
      toast.success('Grievance assigned', 'The assignee has been notified.')
    },
  })
}

const ACTION_TOAST: Record<GrievanceAction, string> = {
  start: 'Marked in progress',
  resolve: 'Grievance resolved',
  close: 'Grievance closed',
}

export function useGrievanceAction(id: string) {
  const refresh = useGrievanceRefresh(id)
  const toast = useToast()
  return useMutation({
    mutationFn: ({ action, input }: { action: GrievanceAction; input?: GrievanceActionInput }) =>
      grievanceApi.act(id, action, input),
    onSuccess: (_data, { action }) => {
      refresh()
      toast.success(ACTION_TOAST[action])
    },
  })
}
