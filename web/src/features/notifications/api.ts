import { apiRequest, apiRequestList } from '@/lib/api/client'
import type { Paginated } from '@/lib/api/client'
import type { AppNotification } from './types'

export const notificationApi = {
  list(params: { status?: 'unread'; page?: number; perPage?: number } = {}): Promise<Paginated<AppNotification>> {
    return apiRequestList<AppNotification>({
      method: 'GET',
      url: '/notifications',
      params: { page: params.page, per_page: params.perPage, 'filter[status]': params.status || undefined },
    })
  },
  unreadCount(): Promise<{ unread: number }> {
    return apiRequest<{ unread: number }>({ method: 'GET', url: '/notifications/unread-count' })
  },
  markRead(id: string): Promise<AppNotification> {
    return apiRequest<AppNotification>({ method: 'POST', url: `/notifications/${id}/read` })
  },
  markAllRead(): Promise<{ marked_read: number }> {
    return apiRequest<{ marked_read: number }>({ method: 'POST', url: '/notifications/read-all' })
  },
  preferences(): Promise<{ email_enabled: boolean }> {
    return apiRequest<{ email_enabled: boolean }>({ method: 'GET', url: '/notifications/preferences' })
  },
  updatePreferences(emailEnabled: boolean): Promise<{ email_enabled: boolean }> {
    return apiRequest<{ email_enabled: boolean }>({ method: 'PATCH', url: '/notifications/preferences', data: { email_enabled: emailEnabled } })
  },
}
