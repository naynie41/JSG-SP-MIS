import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { notificationApi } from './api'

const UNREAD_KEY = ['notifications', 'unread-count'] as const

/** Unread badge for the bell; polled so it stays live without a socket. */
export function useUnreadCount(enabled = true) {
  return useQuery({
    queryKey: UNREAD_KEY,
    queryFn: () => notificationApi.unreadCount(),
    enabled,
    refetchInterval: 30_000,
    refetchOnWindowFocus: true,
  })
}

/** The panel list — recent notifications (open state gates the fetch). */
export function useNotifications(enabled: boolean) {
  return useQuery({
    queryKey: ['notifications', 'list'],
    queryFn: () => notificationApi.list({ perPage: 15 }),
    enabled,
  })
}

function useNotificationRefresh() {
  const qc = useQueryClient()
  return () => {
    qc.invalidateQueries({ queryKey: ['notifications', 'list'] })
    qc.invalidateQueries({ queryKey: UNREAD_KEY })
  }
}

export function useMarkNotificationRead() {
  const refresh = useNotificationRefresh()
  return useMutation({
    mutationFn: (id: string) => notificationApi.markRead(id),
    onSuccess: refresh,
  })
}

export function useMarkAllRead() {
  const refresh = useNotificationRefresh()
  return useMutation({
    mutationFn: () => notificationApi.markAllRead(),
    onSuccess: refresh,
  })
}

export function useNotificationPreferences(enabled: boolean) {
  return useQuery({ queryKey: ['notifications', 'preferences'], queryFn: () => notificationApi.preferences(), enabled })
}

export function useUpdateNotificationPreferences() {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: (emailEnabled: boolean) => notificationApi.updatePreferences(emailEnabled),
    onSuccess: (data) => qc.setQueryData(['notifications', 'preferences'], data),
  })
}
