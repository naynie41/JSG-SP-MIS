import { z } from 'zod'

/** Mirrors StoreMdaRequest / UpdateMdaRequest on the backend. */
export const mdaSchema = z.object({
  name: z.string().min(1, 'Name is required').max(255),
  type: z.enum(['ministry', 'department', 'agency', 'partner']),
  // Optional here; the server holds the real rule (only a partner may have one,
  // and one account belongs to one organisation).
  funder_user_id: z.string().optional().or(z.literal('')),
  contact_person: z.string().max(255).optional().or(z.literal('')),
  contact_email: z.union([z.literal(''), z.string().email('Enter a valid email')]).optional(),
  contact_phone: z.string().max(30).optional().or(z.literal('')),
  address: z.string().max(500).optional().or(z.literal('')),
})

export type MdaFormValues = z.infer<typeof mdaSchema>

export const MDA_TYPE_OPTIONS = [
  { value: 'ministry', label: 'Ministry' },
  { value: 'department', label: 'Department' },
  { value: 'agency', label: 'Agency' },
  { value: 'partner', label: 'Development partner' },
]
