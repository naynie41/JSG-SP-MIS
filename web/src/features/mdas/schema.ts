import { z } from 'zod'

/** Mirrors StoreMdaRequest / UpdateMdaRequest on the backend. */
export const mdaSchema = z.object({
  name: z.string().min(1, 'Name is required').max(255),
  type: z.enum(['ministry', 'department', 'agency']),
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
]
