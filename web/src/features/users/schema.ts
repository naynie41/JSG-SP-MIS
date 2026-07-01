import { z } from 'zod'

export type UserFormMode = 'create' | 'edit'

/**
 * Single user form schema mirroring StoreUserRequest / UpdateUserRequest.
 * Password fields are only required in create mode. The min-12 policy is
 * mirrored client-side; the breached-password check is server-side and surfaces
 * as a field error. `requireMda` reflects an MDA-scoped actor (no cross-mda.view).
 */
export function userSchema(mode: UserFormMode, requireMda: boolean) {
  return z
    .object({
      name: z.string().min(1, 'Name is required').max(255),
      email: z.string().min(1, 'Email is required').email('Enter a valid email'),
      role_id: z.string().uuid('Select a role'),
      mda_id: requireMda ? z.string().uuid('Select an MDA') : z.string().optional().or(z.literal('')),
      password: z.string().optional().or(z.literal('')),
      password_confirmation: z.string().optional().or(z.literal('')),
    })
    .superRefine((data, ctx) => {
      if (mode !== 'create') return
      if (!data.password || data.password.length < 12) {
        ctx.addIssue({ path: ['password'], code: 'custom', message: 'Use at least 12 characters' })
      }
      if (data.password !== data.password_confirmation) {
        ctx.addIssue({ path: ['password_confirmation'], code: 'custom', message: 'Passwords do not match' })
      }
    })
}

export type UserFormValues = z.infer<ReturnType<typeof userSchema>>
