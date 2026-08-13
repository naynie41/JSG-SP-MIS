import { isMdaRole } from '@/features/mda/roles'
import type { AppNotification } from './types'

/**
 * Where a notification should take the reader.
 *
 * `related.type` is the backend morph alias (`Str::snake(class_basename(...))`) — e.g.
 * `referral`, `service_request`, `import_batch` — and `notification.type` is the event
 * key (`import.duplicates_surfaced`, `system.announcement`, …). Both matter: the related
 * MODEL says which record, the event TYPE says which surface is the right one to land on.
 * A duplicate alert and an import result both relate to an `import_batch` but belong in
 * different modules.
 *
 * **Role-aware by necessity.** MDA Administrators work in the six-module MDA
 * workspace and no longer have the generic operator rail, so sending them to
 * `/referrals` would drop them outside their console. Everyone else keeps the generic
 * routes. This is presentation only — every destination re-checks permissions and scope
 * on arrival, and the server scopes the notification list to the recipient regardless.
 */
export function linkFor(notification: AppNotification, roleKey?: string | null): string | null {
  const isMda = isMdaRole(roleKey)
  const relatedType = notification.related?.type ?? ''
  const id = notification.related?.id
  const eventType = notification.type ?? ''

  // Import batches: the event decides the surface. A surfaced duplicate is adjudication
  // work; a finished import is a result to inspect.
  if (relatedType === 'import_batch') {
    if (eventType === 'import.duplicates_surfaced') {
      return isMda ? '/mda/duplicate-resolution' : id ? `/imports/${id}/adjudicate` : '/imports'
    }
    if (!id) return isMda ? '/mda/beneficiaries' : '/imports'
    return isMda ? `/imports/${id}` : `/imports/${id}`
  }

  if (!id) return null

  if (relatedType === 'referral') {
    // The detail page is the same screen in both consoles — it is where the lifecycle
    // is driven — so only the list-level fallback differs.
    return `/referrals/${id}`
  }

  if (relatedType === 'service_request' || relatedType === 'ownership_transfer_request') {
    return isMda ? '/mda/service-delivery?tab=service-requests' : '/service-requests'
  }

  if (relatedType === 'grievance') return `/grievances/${id}`

  if (relatedType === 'report_run') return isMda ? '/mda/reports' : '/reports'

  if (relatedType === 'beneficiary') return isMda ? `/beneficiaries/${id}` : `/beneficiaries/${id}`

  // A system announcement has no related record and nowhere better to go than the
  // message itself, which the panel already shows.
  return null
}
