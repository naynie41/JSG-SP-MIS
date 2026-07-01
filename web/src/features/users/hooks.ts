import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { useToast } from '@/components/Toast/ToastProvider'
import { roleApi, userApi } from './api'
import type { UserStatusAction } from './api'
import type { CreateUserInput, UpdateUserInput } from './types'

const USERS_KEY = ['users'] as const
const ROLES_KEY = ['roles'] as const

export function useUsers(enabled = true) {
  return useQuery({ queryKey: USERS_KEY, queryFn: userApi.list, enabled })
}

export function useRoles(enabled = true) {
  return useQuery({ queryKey: ROLES_KEY, queryFn: roleApi.list, enabled, staleTime: 5 * 60_000 })
}

export function useCreateUser() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (input: CreateUserInput) => userApi.create(input),
    onSuccess: (user) => {
      qc.invalidateQueries({ queryKey: USERS_KEY })
      toast.success('User created', `${user.name} was added.`)
    },
  })
}

export function useUpdateUser() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ id, input }: { id: string; input: UpdateUserInput }) => userApi.update(id, input),
    onSuccess: (user) => {
      qc.invalidateQueries({ queryKey: USERS_KEY })
      toast.success('User updated', `${user.name} was saved.`)
    },
  })
}

export function useUserStatus() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ id, action }: { id: string; action: UserStatusAction }) => userApi.changeStatus(id, action),
    onSuccess: (user) => {
      qc.invalidateQueries({ queryKey: USERS_KEY })
      toast.success('User updated', `${user.name} is now ${user.status}.`)
    },
    onError: () => toast.error('Action failed', 'Please try again.'),
  })
}

export function useForcePasswordReset() {
  const toast = useToast()
  return useMutation({
    mutationFn: (id: string) => userApi.forcePasswordReset(id),
    onSuccess: () => toast.success('Password reset triggered', 'The user must sign in again.'),
    onError: () => toast.error('Action failed', 'Please try again.'),
  })
}

export function useResetMfa() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (id: string) => userApi.resetMfa(id),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: USERS_KEY })
      toast.success('MFA reset', 'The user will re-enrol at next sign-in if required.')
    },
    onError: () => toast.error('Action failed', 'Please try again.'),
  })
}
