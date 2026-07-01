import { useState } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { Modal } from '@/components/Modal/Modal'
import { Button } from '@/components/Button/Button'
import { TextField } from '@/components/Field/TextField'
import { SelectField } from '@/components/Field/SelectField'
import type { SelectOption } from '@/components/Field/SelectField'
import { useAuth } from '@/lib/auth/AuthProvider'
import { applyApiErrors } from '@/lib/forms/applyApiErrors'
import { useMdas } from '@/features/mdas/hooks'
import { useCreateUser, useRoles, useUpdateUser } from './hooks'
import { userSchema } from './schema'
import type { UserFormValues } from './schema'
import type { ManagedUser } from './types'
import layout from '@/features/shared/formLayout.module.css'

interface UserFormModalProps {
  open: boolean
  onClose: () => void
  user?: ManagedUser | null
}

const KNOWN_FIELDS = ['name', 'email', 'role_id', 'mda_id', 'password', 'password_confirmation'] as const

export function UserFormModal({ open, onClose, user }: UserFormModalProps) {
  const isEdit = Boolean(user)
  const { hasPermission } = useAuth()
  const requireMda = !hasPermission('cross-mda.view')

  const { data: mdas = [] } = useMdas(hasPermission('mda.view'))
  const { data: roles = [] } = useRoles(hasPermission('role.view'))
  const createUser = useCreateUser()
  const updateUser = useUpdateUser()
  const [formError, setFormError] = useState<string | null>(null)

  // The user resource exposes role by key; resolve it to an id once roles load.
  const currentRoleId = user?.role ? (roles.find((r) => r.key === user.role?.key)?.id ?? '') : ''

  const {
    register,
    handleSubmit,
    setError,
    watch,
    formState: { errors, isSubmitting },
  } = useForm<UserFormValues>({
    resolver: zodResolver(userSchema(isEdit ? 'edit' : 'create', requireMda)),
    // `values` reactively syncs once async options resolve (edit pre-fill).
    values: {
      name: user?.name ?? '',
      email: user?.email ?? '',
      role_id: currentRoleId,
      mda_id: user?.mda?.id ?? '',
      password: '',
      password_confirmation: '',
    },
  })

  const roleOptions: SelectOption[] = roles.map((r) => ({ value: r.id, label: r.name }))
  const mdaOptions: SelectOption[] = [
    ...(requireMda ? [] : [{ value: '', label: 'No MDA (not MDA-bound)' }]),
    ...mdas.map((m) => ({ value: m.id, label: m.name })),
  ]

  const selectedRole = roles.find((r) => r.id === watch('role_id'))

  const onSubmit = handleSubmit(async (values) => {
    setFormError(null)
    try {
      if (isEdit && user) {
        await updateUser.mutateAsync({
          id: user.id,
          input: {
            name: values.name.trim(),
            email: values.email.trim(),
            role_id: values.role_id,
            mda_id: values.mda_id ? values.mda_id : null,
          },
        })
      } else {
        await createUser.mutateAsync({
          name: values.name.trim(),
          email: values.email.trim(),
          role_id: values.role_id,
          password: values.password ?? '',
          password_confirmation: values.password_confirmation ?? '',
          mda_id: values.mda_id ? values.mda_id : undefined,
        })
      }
      onClose()
    } catch (error) {
      setFormError(applyApiErrors(error, setError, KNOWN_FIELDS))
    }
  })

  return (
    <Modal
      open={open}
      onClose={onClose}
      title={isEdit ? 'Edit user' : 'Create user'}
      footer={
        <>
          <Button variant="tertiary" onClick={onClose} disabled={isSubmitting}>
            Cancel
          </Button>
          <Button type="submit" form="user-form" loading={isSubmitting}>
            {isEdit ? 'Save changes' : 'Create user'}
          </Button>
        </>
      }
    >
      <form id="user-form" onSubmit={onSubmit} noValidate className={layout.form}>
        {formError && (
          <p className={layout.alert} role="alert">
            {formError}
          </p>
        )}

        <TextField label="Full name" required error={errors.name?.message} {...register('name')} />
        <TextField label="Email" type="email" required error={errors.email?.message} {...register('email')} />

        <div className={layout.grid2}>
          <SelectField
            label="Role"
            required
            placeholder="Select a role"
            options={roleOptions}
            error={errors.role_id?.message}
            helper={selectedRole?.requires_mfa ? 'This role requires MFA at next sign-in.' : undefined}
            {...register('role_id')}
          />
          <SelectField
            label="MDA"
            required={requireMda}
            placeholder={requireMda ? 'Select an MDA' : undefined}
            options={mdaOptions}
            error={errors.mda_id?.message}
            {...register('mda_id')}
          />
        </div>

        {!isEdit && (
          <div className={layout.grid2}>
            <TextField
              label="Temporary password"
              type="password"
              required
              autoComplete="new-password"
              helper="Min 12 characters."
              error={errors.password?.message}
              {...register('password')}
            />
            <TextField
              label="Confirm password"
              type="password"
              required
              autoComplete="new-password"
              error={errors.password_confirmation?.message}
              {...register('password_confirmation')}
            />
          </div>
        )}
      </form>
    </Modal>
  )
}
