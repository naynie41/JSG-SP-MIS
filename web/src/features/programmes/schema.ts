import { z } from 'zod'

const optionalDate = z.string().optional().or(z.literal(''))

// A catalog programme (§10) holds type-level attributes only — no budget/funding/period.
export const programmeSchema = z.object({
  name: z.string().min(1, 'A name is required').max(255),
  objective: z.string().max(2000).optional().or(z.literal('')),
  type: z.enum(['individual', 'household']),
  benefit_category: z.string().max(255).optional().or(z.literal('')),
  status: z.enum(['draft', 'active', 'closed']),
  enforce_eligibility: z.boolean().optional(),
})

export type ProgrammeFormValues = z.infer<typeof programmeSchema>

export const activitySchema = z
  .object({
    // The catalog programme this activity runs — the first choice in the flow (§10).
    programme_id: z.string().min(1, 'Select a programme'),
    name: z.string().min(1, 'A name is required').max(255),
    description: z.string().max(2000).optional().or(z.literal('')),
    target_count: z.string().optional().or(z.literal('')),
    lga: z.string().optional().or(z.literal('')),
    ward: z.string().max(100).optional().or(z.literal('')),
    location_description: z.string().max(255).optional().or(z.literal('')),
    budget_naira: z.string().optional().or(z.literal('')),
    funding_source: z.string().max(255).optional().or(z.literal('')),
    starts_on: optionalDate,
    ends_on: optionalDate,
    status: z.enum(['draft', 'active', 'completed']),
  })
  .refine((v) => !v.starts_on || !v.ends_on || v.ends_on >= v.starts_on, {
    path: ['ends_on'],
    message: 'End date must be on or after the start date',
  })

export type ActivityFormValues = z.infer<typeof activitySchema>
