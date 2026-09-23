import { useState } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { Modal } from '@/components/Modal/Modal'
import { Button } from '@/components/Button/Button'
import { TextField } from '@/components/Field/TextField'
import { TextareaField } from '@/components/Field/TextareaField'
import { SelectField } from '@/components/Field/SelectField'
import { SearchableSelectField } from '@/components/Field/SearchableSelectField'
import { applyApiErrors } from '@/lib/forms/applyApiErrors'
import { useFundingPartners } from '@/features/programmes/hooks'
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

const KNOWN_FIELDS = ['name', 'type', 'funder_user_id', 'contact_person', 'contact_email', 'contact_phone', 'address'] as const

function toPayload(values: MdaFormValues): MdaInput {
  const clean = (value?: string) => {
    const trimmed = value?.trim()
    return trimmed ? trimmed : undefined
  }
  return {
    name: values.name.trim(),
    type: values.type,
    // Only a partner organisation has one; sending null for the rest clears any
    // stale link if the type was changed away from partner.
    funder_user_id: values.type === 'partner' ? (values.funder_user_id || null) : null,
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
    watch,
    handleSubmit,
    setError,
    formState: { errors, isSubmitting },
  } = useForm<MdaFormValues>({
    resolver: zodResolver(mdaSchema),
    defaultValues: {
      name: mda?.name ?? '',
      type: mda?.type ?? 'ministry',
      funder_user_id: mda?.funder_user_id ?? '',
      contact_person: mda?.contact_person ?? '',
      contact_email: mda?.contact_email ?? '',
      contact_phone: mda?.contact_phone ?? '',
      address: mda?.address ?? '',
    },
  })

  // Only a partner organisation has a funding account, so the list is fetched only
  // once that type is chosen.
  const type = watch('type')
  const funders = useFundingPartners(open && type === 'partner')
  const funderOptions = (funders.data ?? []).map((partner) => ({ value: partner.id, label: partner.name }))

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
      title={isEdit ? 'Edit agency' : 'Add agency'}
      footer={
        <>
          <Button variant="tertiary" onClick={onClose} disabled={isSubmitting}>
            Cancel
          </Button>
          <Button type="submit" form="mda-form" loading={isSubmitting}>
            {isEdit ? 'Save changes' : 'Add agency'}
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

        {/* Only a partner organisation funds through an account of its own, so the
            field appears only once that type is chosen — and the server refuses the
            pairing anyway if it is sent for a ministry. */}
        {type === 'partner' && (
          <>
            <SearchableSelectField
              label="Funding account"
              options={funderOptions}
              pinnedValue={mda?.funder_user_id ?? ''}
              searchLabel="Filter accounts"
              helper="The Development Partner login this organisation funds through. Linking it never gives that account access to the records this organisation owns — the two stay separate on purpose."
              error={errors.funder_user_id?.message}
              {...register('funder_user_id')}
            />
            {funders.isError && (
              <p className={formStyles.alert} role="alert">
                The account list could not be loaded. Close the form and try again.
              </p>
            )}
          </>
        )}
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
