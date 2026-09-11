import { useEffect, useState } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { Modal } from '@/components/Modal/Modal'
import { Button } from '@/components/Button/Button'
import { TextField } from '@/components/Field/TextField'
import { SelectField } from '@/components/Field/SelectField'
import type { SelectOption } from '@/components/Field/SelectField'
import { SearchableSelectField } from '@/components/Field/SearchableSelectField'
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

  const { data: mdas = [] } = useMdas(hasPermission('mda.view'))
  const { data: roles = [] } = useRoles(hasPermission('role.view'))
  const createUser = useCreateUser()
  const updateUser = useUpdateUser()
  const [formError, setFormError] = useState<string | null>(null)

  // The user resource exposes role by key; resolve it to an id once roles load.
  const currentRoleId = user?.role ? (roles.find((r) => r.key === user.role?.key)?.id ?? '') : ''

  // Whether an MDA applies is a property of the ROLE, read from `/roles` so the
  // client never keeps its own list of which roles are MDA-scoped — one list on the
  // server, consumed by both.
  const mdaScopedRoleIds = roles.filter((r) => r.requires_mda).map((r) => r.id)

  const {
    register,
    handleSubmit,
    setError,
    setValue,
    watch,
    formState: { errors, isSubmitting },
  } = useForm<UserFormValues>({
    resolver: zodResolver(userSchema(isEdit ? 'edit' : 'create', mdaScopedRoleIds)),
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
    { value: '', label: 'Select an MDA' },
    ...mdas.map((m) => ({ value: m.id, label: m.name })),
  ]

  const selectedRoleId = watch('role_id')
  const mdaValue = watch('mda_id')
  const selectedRole = roles.find((r) => r.id === selectedRoleId)
  const requiresMda = selectedRole?.requires_mda ?? false

  // Switching to a state-level role must DROP any MDA already picked. Leaving the
  // value behind would submit a pairing the server rejects, from a field no longer
  // on screen to explain why.
  useEffect(() => {
    if (!requiresMda && mdaValue) {
      setValue('mda_id', '', { shouldValidate: false })
    }
  }, [requiresMda, mdaValue, setValue])

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
          {/*
            Rendered only for an MDA-scoped role. A state-level role has no MDA to
            pick, and showing a disabled or empty field would invite the question
            anyway — the server refuses that pairing regardless.
          */}
          {requiresMda && (
            <SearchableSelectField
              label="MDA"
              required
              options={mdaOptions}
              pinnedValue={mdaValue}
              searchLabel="Filter MDAs"
              helper={`${selectedRole?.name ?? 'This role'} operates a single MDA's workspace.`}
              error={errors.mda_id?.message}
              {...register('mda_id')}
            />
          )}
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
