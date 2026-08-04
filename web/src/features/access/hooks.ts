import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { useToast } from '@/components/Toast/ToastProvider'
import { ApiError } from '@/types/api'
import { accessApi } from './api'
import type { CreateGrantInput } from './types'

const GRANTS_KEY = ['mda-access-grants'] as const

export function useAccessRoles(enabled = true) {
  return useQuery({ queryKey: ['access-roles'], queryFn: accessApi.roles, enabled, staleTime: 5 * 60_000 })
}

export function usePermissionModules(enabled = true) {
  return useQuery({ queryKey: ['permission-modules'], queryFn: accessApi.permissions, enabled, staleTime: 5 * 60_000 })
}

export function usePermissionMatrix(enabled = true) {
  return useQuery({ queryKey: ['permission-matrix'], queryFn: accessApi.matrix, enabled, staleTime: 5 * 60_000 })
}

/**
 * Save a role's permission set. On success the matrix is refetched rather than patched
 * locally, so what the editor shows is always what the server actually stored.
 */
export function useUpdateRolePermissions() {
  const qc = useQueryClient()
  const toast = useToast()

  return useMutation({
    mutationFn: ({ roleId, permissions }: { roleId: string; permissions: string[] }) =>
      accessApi.updateRolePermissions(roleId, permissions),
    onSuccess: ({ role }) => {
      qc.invalidateQueries({ queryKey: ['permission-matrix'] })
      qc.invalidateQueries({ queryKey: ['access-roles'] })
      toast.success('Permissions updated', `${role.name} now holds ${role.permissions.length} permissions.`)
    },
    onError: (error) =>
      toast.error(
        'Could not update permissions',
        error instanceof ApiError ? error.message : 'Please try again.',
      ),
  })
}

export function useGrants(enabled = true) {
  return useQuery({ queryKey: GRANTS_KEY, queryFn: accessApi.grants, enabled })
}

export function useCreateGrant() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (input: CreateGrantInput) => accessApi.createGrant(input),
    onSuccess: (grant) => {
      qc.invalidateQueries({ queryKey: GRANTS_KEY })
      toast.success('Access granted', grant.user && grant.mda ? `${grant.user.name} → ${grant.mda.name}` : undefined)
    },
  })
}

export function useRevokeGrant() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (id: string) => accessApi.revokeGrant(id),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: GRANTS_KEY })
      toast.success('Access revoked')
    },
    onError: () => toast.error('Could not revoke access', 'Please try again.'),
  })
}

/** Group `module.action` permission keys by their module (text before the first dot). */
export function groupByModule(keys: string[]): Record<string, string[]> {
  const groups: Record<string, string[]> = {}
  for (const key of [...keys].sort()) {
    const module = key.includes('.') ? key.slice(0, key.indexOf('.')) : key
    ;(groups[module] ??= []).push(key)
  }
  return groups
}
