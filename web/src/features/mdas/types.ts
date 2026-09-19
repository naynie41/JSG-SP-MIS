/**
 * The kind of organisation that DELIVERS. The first three are government — the
 * "MDA" the module is named after. 'partner' is a development partner that
 * implements its own programmes rather than only funding someone else's; it owns
 * records exactly as an MDA does, but must never read as government.
 */
export type MdaType = 'ministry' | 'department' | 'agency' | 'partner'
export type MdaStatus = 'active' | 'inactive'

export interface Mda {
  id: string
  name: string
  type: MdaType
  type_label?: string
  is_government?: boolean
  /** The Development Partner account a partner organisation funds through. */
  funder_user_id?: string | null
  funder_name?: string | null
  status: MdaStatus
  contact_person: string | null
  contact_email: string | null
  contact_phone: string | null
  address: string | null
  created_at: string | null
  updated_at: string | null
}

export interface MdaInput {
  name: string
  type: MdaType
  /** Null for a government body, and to clear a link when the type changes. */
  funder_user_id?: string | null
  contact_person?: string
  contact_email?: string
  contact_phone?: string
  address?: string
}
