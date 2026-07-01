export type MdaType = 'ministry' | 'department' | 'agency'
export type MdaStatus = 'active' | 'inactive'

export interface Mda {
  id: string
  name: string
  type: MdaType
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
  contact_person?: string
  contact_email?: string
  contact_phone?: string
  address?: string
}
