import { useState } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { Modal } from '@/components/Modal/Modal'
import { Button } from '@/components/Button/Button'
import { TextField } from '@/components/Field/TextField'
import { TextareaField } from '@/components/Field/TextareaField'
import { SelectField } from '@/components/Field/SelectField'
import { applyApiErrors } from '@/lib/forms/applyApiErrors'
import { GENDER_OPTIONS, LGA_OPTIONS } from './constants'
import { beneficiarySchema, toBeneficiaryPayload } from './schema'
import type { BeneficiaryFormValues } from './schema'
import { useUpdateBeneficiary } from './hooks'
import type { Beneficiary } from './types'
import formStyles from '@/features/shared/formLayout.module.css'

interface EditBeneficiaryModalProps {
  open: boolean
  onClose: () => void
  beneficiary: Beneficiary
}

const KNOWN_FIELDS = [
  'first_name', 'middle_name', 'last_name', 'nin', 'bvn', 'phone',
  'date_of_birth', 'gender', 'address', 'lga', 'ward',
] as const

/**
 * Owner-only CORRECTION of an existing beneficiary (PRD FR-OWN-02). This is not a
 * registration form — records are ingested from sources, never created by hand.
 */
export function EditBeneficiaryModal({ open, onClose, beneficiary }: EditBeneficiaryModalProps) {
  const updateBeneficiary = useUpdateBeneficiary()
  const [formError, setFormError] = useState<string | null>(null)

  const {
    register,
    handleSubmit,
    setError,
    formState: { errors, isSubmitting },
  } = useForm<BeneficiaryFormValues>({
    resolver: zodResolver(beneficiarySchema),
    defaultValues: {
      first_name: beneficiary.first_name ?? '',
      middle_name: beneficiary.middle_name ?? '',
      last_name: beneficiary.last_name ?? '',
      // NIN/BVN are masked in responses — leave blank to keep them unchanged.
      nin: '',
      bvn: '',
      phone: beneficiary.phone ?? '',
      date_of_birth: beneficiary.date_of_birth ?? '',
      gender: beneficiary.gender ?? undefined,
      address: beneficiary.address ?? '',
      lga: beneficiary.lga ?? '',
      ward: beneficiary.ward ?? '',
    },
  })

  const onSubmit = handleSubmit(async (values) => {
    setFormError(null)
    try {
      await updateBeneficiary.mutateAsync({ id: beneficiary.id, input: toBeneficiaryPayload(values) })
      onClose()
    } catch (error) {
      setFormError(applyApiErrors(error, setError, KNOWN_FIELDS))
    }
  })

  return (
    <Modal
      open={open}
      onClose={onClose}
      title="Edit beneficiary"
      footer={
        <>
          <Button variant="tertiary" onClick={onClose} disabled={isSubmitting}>
            Cancel
          </Button>
          <Button type="submit" form="beneficiary-edit-form" loading={isSubmitting}>
            Save changes
          </Button>
        </>
      }
    >
      <form id="beneficiary-edit-form" onSubmit={onSubmit} noValidate className={formStyles.form}>
        {formError && (
          <p className={formStyles.alert} role="alert">
            {formError}
          </p>
        )}

        <div className={formStyles.grid2}>
          <TextField label="First name" required error={errors.first_name?.message} {...register('first_name')} />
          <TextField label="Last name" required error={errors.last_name?.message} {...register('last_name')} />
        </div>
        <div className={formStyles.grid2}>
          <TextField label="Middle name" error={errors.middle_name?.message} {...register('middle_name')} />
          <TextField
            label="Date of birth"
            type="date"
            required
            error={errors.date_of_birth?.message}
            {...register('date_of_birth')}
          />
        </div>
        <div className={formStyles.grid2}>
          <SelectField
            label="Gender"
            required
            placeholder="Select gender"
            options={GENDER_OPTIONS}
            error={errors.gender?.message}
            {...register('gender')}
          />
          <TextField label="Phone" error={errors.phone?.message} {...register('phone')} />
        </div>
        <div className={formStyles.grid2}>
          <TextField label="NIN (leave blank to keep)" helper="11 digits" error={errors.nin?.message} {...register('nin')} />
          <TextField label="BVN (leave blank to keep)" helper="11 digits" error={errors.bvn?.message} {...register('bvn')} />
        </div>
        <div className={formStyles.grid2}>
          <SelectField
            label="LGA"
            required
            placeholder="Select LGA"
            options={LGA_OPTIONS}
            error={errors.lga?.message}
            {...register('lga')}
          />
          <TextField label="Ward" required error={errors.ward?.message} {...register('ward')} />
        </div>
        <TextareaField label="Address" error={errors.address?.message} {...register('address')} />
      </form>
    </Modal>
  )
}
