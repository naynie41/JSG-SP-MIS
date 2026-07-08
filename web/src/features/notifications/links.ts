import type { AppNotification } from './types'

/**
 * Map a notification's related model to an in-app route so the bell can deep-link to
 * the referral / grievance / approval. `related.type` is the backend morph alias
 * (`Str::snake(class_basename(...))`) — e.g. `referral`, `grievance`,
 * `service_request`, `ownership_transfer_request`.
 */
export function linkFor(notification: AppNotification): string | null {
  const type = notification.related?.type ?? ''
  const id = notification.related?.id
  if (!id) return null
  if (type === 'referral') return `/referrals/${id}`
  if (type === 'grievance') return `/grievances/${id}`
  if (type === 'service_request' || type === 'ownership_transfer_request') return '/service-requests'
  return null
}
