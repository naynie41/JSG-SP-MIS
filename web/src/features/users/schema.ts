import { z } from 'zod'

export type UserFormMode = 'create' | 'edit'

/**
 * Single user form schema mirroring StoreUserRequest / UpdateUserRequest.
 * Password fields are only required in create mode. The min-12 policy is
 * mirrored client-side; the breached-password check is server-side and surfaces
 * as a field error.
 *
 * The MDA rule is a function of the SELECTED ROLE, not of the actor: an MDA Admin
 * must have an MDA, a state-level role must not (FR-UAM-02/03). It is checked in
 * `superRefine` against the submitted `role_id` rather than from a boolean captured
 * when the form mounted, so it cannot go stale when the role is changed mid-edit.
 *
 * @param mdaScopedRoleIds ids of roles carrying `requires_mda`, from `/roles`
 */
export function userSchema(mode: UserFormMode, mdaScopedRoleIds: readonly string[] = []) {
  return z
    .object({
      name: z.string().min(1, 'Name is required').max(255),
      email: z.string().min(1, 'Email is required').email('Enter a valid email'),
      role_id: z.string().uuid('Select a role'),
      mda_id: z.string().optional().or(z.literal('')),
      password: z.string().optional().or(z.literal('')),
      password_confirmation: z.string().optional().or(z.literal('')),
    })
    .superRefine((data, ctx) => {
      const needsMda = mdaScopedRoleIds.includes(data.role_id)

      if (needsMda && !data.mda_id) {
        // Distinct from the select's own "Select an MDA" placeholder, so the error
        // and the empty option can never be mistaken for one another.
        ctx.addIssue({ path: ['mda_id'], code: 'custom', message: 'Choose the MDA this user belongs to' })
      }

      if (!needsMda && data.mda_id) {
        ctx.addIssue({
          path: ['mda_id'],
          code: 'custom',
          message: 'This role works across all MDAs and cannot be assigned to one',
        })
      }

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
