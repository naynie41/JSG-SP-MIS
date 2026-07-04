import { useState } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { Modal } from '@/components/Modal/Modal'
import { Button } from '@/components/Button/Button'
import { TextField } from '@/components/Field/TextField'
import { TextareaField } from '@/components/Field/TextareaField'
import { SelectField } from '@/components/Field/SelectField'
import { applyApiErrors } from '@/lib/forms/applyApiErrors'
import { koboToNaira, nairaToKobo } from '@/lib/utils/money'
import { LGA_OPTIONS } from '@/features/registry/constants'
import { ACTIVITY_STATUS_OPTIONS } from './constants'
import { activitySchema } from './schema'
import type { ActivityFormValues } from './schema'
import { useSaveActivity } from './hooks'
import type { Activity } from './types'
import formStyles from '@/features/shared/formLayout.module.css'

interface ActivityFormModalProps {
  open: boolean
  onClose: () => void
  programmeId: string
  activity?: Activity | null
}

const KNOWN = ['name', 'description', 'target_count', 'lga', 'ward', 'location_description', 'starts_on', 'ends_on', 'status'] as const

/** Create or edit an activity under a programme (PRD FR-PRG-02). */
export function ActivityFormModal({ open, onClose, programmeId, activity }: ActivityFormModalProps) {
  const save = useSaveActivity(programmeId)
  const [formError, setFormError] = useState<string | null>(null)

  const {
    register,
    handleSubmit,
    setError,
    formState: { errors, isSubmitting },
  } = useForm<ActivityFormValues>({
    resolver: zodResolver(activitySchema),
    defaultValues: {
      name: activity?.name ?? '',
      description: activity?.description ?? '',
      target_count: activity?.target_count != null ? String(activity.target_count) : '',
      lga: activity?.lga ?? '',
      ward: activity?.ward ?? '',
      location_description: activity?.location_description ?? '',
      budget_naira: koboToNaira(activity?.budget_amount),
      starts_on: activity?.starts_on ?? '',
      ends_on: activity?.ends_on ?? '',
      status: (activity?.status as ActivityFormValues['status']) ?? 'draft',
    },
  })

  const onSubmit = handleSubmit(async (values) => {
    setFormError(null)
    try {
      await save.mutateAsync({
        id: activity?.id,
        input: {
          name: values.name,
          description: values.description || null,
          target_count: values.target_count ? Number(values.target_count) : null,
          lga: values.lga || null,
          ward: values.ward || null,
          location_description: values.location_description || null,
          budget_amount: nairaToKobo(values.budget_naira) ?? null,
          starts_on: values.starts_on || null,
          ends_on: values.ends_on || null,
          status: values.status,
        },
      })
      onClose()
    } catch (error) {
      setFormError(applyApiErrors(error, setError, KNOWN))
    }
  })

  return (
    <Modal
      open={open}
      onClose={onClose}
      title={activity ? 'Edit activity' : 'Add activity'}
      footer={
        <>
          <Button variant="tertiary" onClick={onClose} disabled={isSubmitting}>
            Cancel
          </Button>
          <Button type="submit" form="activity-form" loading={isSubmitting}>
            {activity ? 'Save changes' : 'Add activity'}
          </Button>
        </>
      }
    >
      <form id="activity-form" onSubmit={onSubmit} noValidate className={formStyles.form}>
        {formError && (
          <p className={formStyles.alert} role="alert">
            {formError}
          </p>
        )}
        <TextField label="Name" required error={errors.name?.message} {...register('name')} />
        <TextareaField label="Description" rows={2} error={errors.description?.message} {...register('description')} />
        <div className={formStyles.grid2}>
          <TextField label="Target beneficiaries" type="number" min={0} error={errors.target_count?.message} {...register('target_count')} />
          <TextField label="Budget (₦)" type="number" min={0} step="0.01" error={errors.budget_naira?.message} {...register('budget_naira')} />
        </div>
        <div className={formStyles.grid2}>
          <SelectField label="LGA" placeholder="Select LGA" options={LGA_OPTIONS} error={errors.lga?.message} {...register('lga')} />
          <TextField label="Ward" error={errors.ward?.message} {...register('ward')} />
        </div>
        <TextField label="Location" error={errors.location_description?.message} {...register('location_description')} />
        <div className={formStyles.grid2}>
          <TextField label="Start date" type="date" error={errors.starts_on?.message} {...register('starts_on')} />
          <TextField label="End date" type="date" error={errors.ends_on?.message} {...register('ends_on')} />
        </div>
        <SelectField label="Status" required options={ACTIVITY_STATUS_OPTIONS} error={errors.status?.message} {...register('status')} />
      </form>
    </Modal>
  )
}
