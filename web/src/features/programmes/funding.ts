import type { SelectOption } from '@/components/Field/SelectField'
import type { Activity } from './types'

/** How an activity is funded. A partner activity is the only one linked to a partner. */
export const FUNDING_TYPE_OPTIONS: SelectOption[] = [
  { value: 'government', label: 'Government funded' },
  { value: 'partner', label: 'Social protection partners' },
  { value: 'individual', label: 'Individuals' },
]

/**
 * The funding line for an activity, in words.
 *
 * An activity recorded before the funding type existed has only the old free text, which
 * is shown as it was typed until someone edits the activity and chooses a type.
 */
export function describeFunding(
  activity: Pick<Activity, 'funding_source' | 'funding_type' | 'funding_partner' | 'co_funded_by_government'>,
): string {
  switch (activity.funding_type) {
    case 'government':
      return 'Government funded'
    case 'individual':
      return 'Individuals'
    case 'partner': {
      const partner = activity.funding_partner?.name ?? 'a social protection partner'
      return `Social protection partner: ${partner}${activity.co_funded_by_government ? ' · co-funded with government' : ''}`
    }
    default:
      return activity.funding_source ?? '—'
  }
}
