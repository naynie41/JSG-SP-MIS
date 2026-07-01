import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { useToast } from '@/components/Toast/ToastProvider'
import { mdaApi } from './api'
import type { MdaInput } from './types'

const MDAS_KEY = ['mdas'] as const

export function useMdas(enabled = true) {
  return useQuery({ queryKey: MDAS_KEY, queryFn: mdaApi.list, enabled })
}

export function useCreateMda() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (input: MdaInput) => mdaApi.create(input),
    onSuccess: (mda) => {
      qc.invalidateQueries({ queryKey: MDAS_KEY })
      toast.success('MDA created', `${mda.name} was added.`)
    },
  })
}

export function useUpdateMda() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ id, input }: { id: string; input: Partial<MdaInput> }) => mdaApi.update(id, input),
    onSuccess: (mda) => {
      qc.invalidateQueries({ queryKey: MDAS_KEY })
      toast.success('MDA updated', `${mda.name} was saved.`)
    },
  })
}

export function useMdaStatus() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ id, action }: { id: string; action: 'activate' | 'deactivate' }) =>
      action === 'activate' ? mdaApi.activate(id) : mdaApi.deactivate(id),
    onSuccess: (mda) => {
      qc.invalidateQueries({ queryKey: MDAS_KEY })
      toast.success(mda.status === 'active' ? 'MDA activated' : 'MDA deactivated', mda.name)
    },
    onError: () => toast.error('Action failed', 'Please try again.'),
  })
}
