import { useState } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { Modal } from '@/components/Modal/Modal'
import { Button } from '@/components/Button/Button'
import { TextField } from '@/components/Field/TextField'
import { TextareaField } from '@/components/Field/TextareaField'
import { SelectField } from '@/components/Field/SelectField'
import { applyApiErrors } from '@/lib/forms/applyApiErrors'
import { useCreateMda, useUpdateMda } from './hooks'
import { MDA_TYPE_OPTIONS, mdaSchema } from './schema'
import type { MdaFormValues } from './schema'
import type { Mda, MdaInput } from './types'
import formStyles from '@/features/shared/formLayout.module.css'

interface MdaFormModalProps {
  open: boolean
  onClose: () => void
  mda?: Mda | null
}

const KNOWN_FIELDS = ['name', 'type', 'contact_person', 'contact_email', 'contact_phone', 'address'] as const

function toPayload(values: MdaFormValues): MdaInput {
  const clean = (value?: string) => {
    const trimmed = value?.trim()
    return trimmed ? trimmed : undefined
  }
  return {
    name: values.name.trim(),
    type: values.type,
    contact_person: clean(values.contact_person),
    contact_email: clean(values.contact_email),
    contact_phone: clean(values.contact_phone),
    address: clean(values.address),
  }
}

export function MdaFormModal({ open, onClose, mda }: MdaFormModalProps) {
  const isEdit = Boolean(mda)
  const createMda = useCreateMda()
  const updateMda = useUpdateMda()
  const [formError, setFormError] = useState<string | null>(null)

  const {
    register,
    handleSubmit,
    setError,
    formState: { errors, isSubmitting },
  } = useForm<MdaFormValues>({
    resolver: zodResolver(mdaSchema),
    defaultValues: {
      name: mda?.name ?? '',
      type: mda?.type ?? 'ministry',
      contact_person: mda?.contact_person ?? '',
      contact_email: mda?.contact_email ?? '',
      contact_phone: mda?.contact_phone ?? '',
      address: mda?.address ?? '',
    },
  })

  const onSubmit = handleSubmit(async (values) => {
    setFormError(null)
    const payload = toPayload(values)
    try {
      if (isEdit && mda) {
        await updateMda.mutateAsync({ id: mda.id, input: payload })
      } else {
        await createMda.mutateAsync(payload)
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
      title={isEdit ? 'Edit MDA' : 'Create MDA'}
      footer={
        <>
          <Button variant="tertiary" onClick={onClose} disabled={isSubmitting}>
            Cancel
          </Button>
          <Button type="submit" form="mda-form" loading={isSubmitting}>
            {isEdit ? 'Save changes' : 'Create MDA'}
          </Button>
        </>
      }
    >
      <form id="mda-form" onSubmit={onSubmit} noValidate className={formStyles.form}>
        {formError && (
          <p className={formStyles.alert} role="alert">
            {formError}
          </p>
        )}
        <TextField label="Name" required error={errors.name?.message} {...register('name')} />
        <SelectField label="Type" required options={MDA_TYPE_OPTIONS} error={errors.type?.message} {...register('type')} />
        <div className={formStyles.grid2}>
          <TextField label="Contact person" error={errors.contact_person?.message} {...register('contact_person')} />
          <TextField
            label="Contact email"
            type="email"
            error={errors.contact_email?.message}
            {...register('contact_email')}
          />
        </div>
        <TextField label="Contact phone" error={errors.contact_phone?.message} {...register('contact_phone')} />
        <TextareaField label="Address" error={errors.address?.message} {...register('address')} />
      </form>
    </Modal>
  )
}
