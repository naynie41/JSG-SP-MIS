import { keepPreviousData, useMutation, useQuery } from '@tanstack/react-query'
import { useToast } from '@/components/Toast/ToastProvider'
import { ApiError } from '@/types/api'
import { adminApi } from './api'
import type { AuditFilters, BroadcastInput } from './types'

/** The console's governance summary. Refetched periodically so alerts stay current. */
export function useAdminSummary(enabled = true) {
  return useQuery({
    queryKey: ['admin-summary'],
    queryFn: () => adminApi.summary(),
    enabled,
    refetchInterval: 120_000,
  })
}

/** Authentication activity from the audit log (User & Access § login activity). */
export function useLoginActivity(enabled = true) {
  return useQuery({
    queryKey: ['admin-login-activity'],
    queryFn: () => adminApi.loginActivity(),
    enabled,
  })
}

/** Per-organization allocation + delivery roll-up (Organization section). */
export function useAdminOrganizations(enabled = true) {
  return useQuery({
    queryKey: ['admin-organizations'],
    queryFn: () => adminApi.organizations(),
    enabled,
  })
}

/** A filtered page of the immutable audit log. */
export function useAuditLogs(filters: AuditFilters, enabled = true) {
  return useQuery({
    queryKey: ['admin-audit-logs', filters],
    queryFn: () => adminApi.auditLogs(filters),
    enabled,
    placeholderData: keepPreviousData,
  })
}

/** The canonical registry validation ruleset — static policy, so cached hard. */
export function useRegistryRules(enabled = true) {
  return useQuery({
    queryKey: ['admin-registry-rules'],
    queryFn: () => adminApi.registryRules(),
    enabled,
    staleTime: 30 * 60_000,
  })
}

/** The effective configuration in force. Static per deployment, so cached hard. */
export function useAdminSettings(enabled = true) {
  return useQuery({
    queryKey: ['admin-settings'],
    queryFn: () => adminApi.settings(),
    enabled,
    staleTime: 30 * 60_000,
  })
}

/** Send a system-wide announcement via the Phase 5 notifier. */
export function useBroadcast() {
  const toast = useToast()

  return useMutation({
    mutationFn: (input: BroadcastInput) => adminApi.broadcast(input),
    onSuccess: (result) => toast.success('Broadcast sent', result.message),
    onError: (error) =>
      toast.error('Could not send the broadcast', error instanceof ApiError ? error.message : 'Please try again.'),
  })
}
