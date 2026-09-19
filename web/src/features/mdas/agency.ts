import type { MdaType } from './types'

/**
 * How an implementing agency is named and told apart.
 *
 * "MDA" means Ministries, Departments and Agencies — government. Since a
 * development partner can now implement its own programmes, a list can hold both,
 * and calling every row an MDA would assert something false about half of them.
 * "Implementing agency" is the umbrella; a partner row is tagged so the distinction
 * is visible rather than inferred from the organisation's name.
 */
export const AGENCY_TYPE_LABELS: Record<MdaType, string> = {
  ministry: 'Ministry',
  department: 'Department',
  agency: 'Agency',
  partner: 'Development partner',
}

/** A government body, as opposed to a partner organisation that implements. */
export function isGovernmentAgency(type: MdaType): boolean {
  return type !== 'partner'
}
