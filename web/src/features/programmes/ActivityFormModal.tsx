import { useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { CheckCircle2, Eye, FileUp, UploadCloud, X } from 'lucide-react'
import { Modal } from '@/components/Modal/Modal'
import { Button } from '@/components/Button/Button'
import { TextField } from '@/components/Field/TextField'
import { TextareaField } from '@/components/Field/TextareaField'
import { SelectField } from '@/components/Field/SelectField'
import { Icon } from '@/components/Icon/Icon'
import { applyApiErrors } from '@/lib/forms/applyApiErrors'
import { koboToNaira, nairaToKobo } from '@/lib/utils/money'
import { LocationSetField } from '@/features/reference/LocationSetField'
import type { LocationSetEntry } from '@/features/reference/types'
import { usePreviewActivityImport } from '@/features/registry/hooks'
import { ACTIVITY_STATUS_OPTIONS } from './constants'
import { activitySchema } from './schema'
import type { ActivityFormValues } from './schema'
import { useProgrammeCatalog, useSaveActivity } from './hooks'
import type { Activity, ActivityInput } from './types'
import formStyles from '@/features/shared/formLayout.module.css'
import styles from './programmes.module.css'

interface ActivityFormModalProps {
  open: boolean
  onClose: () => void
  /** Fix the activity to one programme (from a programme page); omit for the
   *  standalone flow where the user picks the catalog programme first. */
  programmeId?: string
  activity?: Activity | null
}

const KNOWN = ['programme_id', 'involves_beneficiaries', 'name', 'description', 'target_beneficiaries', 'location_description', 'budget_naira', 'funding_source', 'starts_on', 'ends_on', 'status'] as const

/** The saved location set, back into the shape the picker edits. */
function toEntries(activity?: Activity | null): LocationSetEntry[] {
  return (activity?.locations ?? []).map((location) => ({
    lga_id: location.lga_id,
    ward_ids: location.wards.map((ward) => ward.ward_id),
    whole_lga: location.whole_lga,
  }))
}

/**
 * Pulls the `locations.*` field errors out of an API 422 and keys them by path.
 *
 * They are handled separately from `applyApiErrors` because the paths are positional
 * (`locations.1.ward_ids.0`) — the picker uses them to mark the exact ward chip the
 * server rejected, which is the only way a user can tell WHICH ward was wrong.
 */
function locationFieldErrors(error: unknown): Record<string, string> {
  const details = (error as { details?: Array<{ field?: string; message?: string }> })?.details ?? []
  const out: Record<string, string> = {}
  for (const detail of details) {
    if (detail.field?.startsWith('locations') && detail.message) out[detail.field] = detail.message
  }
  return out
}

/**
 * Create/edit an MDA-owned activity that runs a GLOBAL catalog programme (§10). Creation
 * branches on "does this activity involve beneficiaries?" (DESIGN.md §5.10):
 *  - No  → a single step; the activity is saved alone (no target, no upload).
 *  - Yes → a target is required, then a MANDATORY step 2 "Upload beneficiary data".
 *          Attaching a file stages a preview (dedup before saving) and continues on the
 *          import preview page to resolve duplicates and confirm.
 */
export function ActivityFormModal({ open, onClose, programmeId, activity }: ActivityFormModalProps) {
  const isCreate = !activity
  const save = useSaveActivity(programmeId)
  const previewImport = usePreviewActivityImport()
  const navigate = useNavigate()
  const catalog = useProgrammeCatalog(open)
  const lockProgramme = Boolean(activity) || Boolean(programmeId) // fixed when editing / page-scoped
  const programmeOptions = (catalog.data?.items ?? []).map((p) => ({ value: p.id, label: p.name }))

  const [step, setStep] = useState<1 | 2>(1)
  const [file, setFile] = useState<File | null>(null)
  const [formError, setFormError] = useState<string | null>(null)
  const [created, setCreated] = useState<Activity | null>(null)

  // The location set is held outside react-hook-form: it is a nested array whose errors
  // come back keyed by path (`locations.0.ward_ids.1`), which RHF cannot address.
  const [locations, setLocations] = useState<LocationSetEntry[]>(() => toEntries(activity))
  const [locationErrors, setLocationErrors] = useState<Record<string, string>>({})

  useEffect(() => {
    if (open) {
      setStep(1)
      setFile(null)
      setFormError(null)
      setCreated(null)
      setLocations(toEntries(activity))
      setLocationErrors({})
    }
  }, [open, activity])

  const {
    register,
    handleSubmit,
    trigger,
    watch,
    setError,
    formState: { errors, isSubmitting },
  } = useForm<ActivityFormValues>({
    resolver: zodResolver(activitySchema),
    defaultValues: {
      programme_id: activity?.programme_id ?? programmeId ?? '',
      involves_beneficiaries: activity ? (activity.involves_beneficiaries ? 'yes' : 'no') : 'no',
      name: activity?.name ?? '',
      description: activity?.description ?? '',
      target_beneficiaries: activity?.target_beneficiaries != null ? String(activity.target_beneficiaries) : '',
      location_description: activity?.location_description ?? '',
      budget_naira: koboToNaira(activity?.budget_amount),
      funding_source: activity?.funding_source ?? '',
      starts_on: activity?.starts_on ?? '',
      ends_on: activity?.ends_on ?? '',
      status: (activity?.status as ActivityFormValues['status']) ?? 'draft',
    },
  })

  const involves = watch('involves_beneficiaries') === 'yes'

  function buildInput(values: ActivityFormValues): ActivityInput {
    const involvesBeneficiaries = values.involves_beneficiaries === 'yes'
    return {
      programme_id: values.programme_id,
      involves_beneficiaries: involvesBeneficiaries,
      name: values.name,
      description: values.description || null,
      // No beneficiaries → no target (the API prohibits it on the no-file path).
      target_beneficiaries: involvesBeneficiaries && values.target_beneficiaries ? Number(values.target_beneficiaries) : null,
      // An LGA with no wards ticked is whole-LGA coverage — the same claim, so it is
      // sent the same way rather than as an empty ward list.
      locations: locations.map((entry) => ({
        lga_id: entry.lga_id,
        ...(entry.whole_lga || entry.ward_ids.length === 0
          ? { whole_lga: true }
          : { ward_ids: entry.ward_ids }),
      })),
      location_description: values.location_description || null,
      budget_amount: nairaToKobo(values.budget_naira) ?? null,
      funding_source: values.funding_source || null,
      starts_on: values.starts_on || null,
      ends_on: values.ends_on || null,
      status: values.status,
    }
  }

  async function goToUploadStep() {
    setFormError(null)
    if (await trigger()) setStep(2)
  }

  // No-beneficiary create / edit: save the activity alone. On create, show a
  // post-save confirmation with a "View activity" action; on edit, just close.
  const saveActivity = handleSubmit(async (values) => {
    setFormError(null)
    try {
      const saved = await save.mutateAsync({ id: activity?.id, input: buildInput(values) })
      if (isCreate) setCreated(saved)
      else onClose()
    } catch (error) {
      setLocationErrors(locationFieldErrors(error))
      setFormError(applyApiErrors(error, setError, KNOWN))
    }
  })

  // Attach a file: stage the preview (dedup before saving), then continue on the
  // import preview page to resolve duplicates and confirm.
  const uploadAndPreview = handleSubmit(async (values) => {
    setFormError(null)
    if (!file) {
      setFormError('Attach a beneficiary file to continue. The upload is required for this activity.')
      return
    }
    try {
      const batch = await previewImport.mutateAsync({ draft: buildInput(values), file })
      onClose()
      navigate(`/imports/${batch.id}`)
    } catch (error) {
      const locationProblems = locationFieldErrors(error)
      setLocationErrors(locationProblems)
      const message = applyApiErrors(error, setError, KNOWN)

      // The rejected field lives on step 1, so leaving the user on the upload step would
      // show an error next to a file input that is not what the server complained about.
      // Go back to where the problem is visible and fixable.
      if (Object.keys(locationProblems).length > 0) {
        setStep(1)
        setFormError(message ?? 'Check the areas declared for this activity.')
        return
      }
      setFormError(message)
    }
  })

  const busy = isSubmitting || save.isPending || previewImport.isPending

  const footer = created ? (
    // Post-save confirmation → the "View activity" affordance opens the detail page.
    <>
      <Button variant="tertiary" onClick={onClose}>Done</Button>
      <Button leftIcon={Eye} onClick={() => { onClose(); navigate(`/activities/${created.id}`) }}>View activity</Button>
    </>
  ) : !isCreate ? (
    <>
      <Button variant="tertiary" onClick={onClose} disabled={busy}>Cancel</Button>
      <Button onClick={saveActivity} loading={busy}>Save changes</Button>
    </>
  ) : !involves ? (
    // No beneficiaries → single step; create the activity on its own.
    <>
      <Button variant="tertiary" onClick={onClose} disabled={busy}>Cancel</Button>
      <Button onClick={saveActivity} loading={busy}>Create activity</Button>
    </>
  ) : step === 1 ? (
    <>
      <Button variant="tertiary" onClick={onClose} disabled={busy}>Cancel</Button>
      <Button rightIcon={FileUp} onClick={goToUploadStep}>Next: upload</Button>
    </>
  ) : (
    // Yes → the upload is mandatory (no skip).
    <>
      <Button variant="tertiary" onClick={() => setStep(1)} disabled={busy}>Back</Button>
      <Button leftIcon={UploadCloud} onClick={uploadAndPreview} loading={previewImport.isPending} disabled={!file}>Upload &amp; preview</Button>
    </>
  )

  if (created) {
    return (
      <Modal open={open} onClose={onClose} title="Activity created" footer={footer}>
        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 'var(--space-3)', textAlign: 'center', padding: 'var(--space-4) 0' }}>
          <span className={styles.dropzoneChip} aria-hidden="true"><Icon icon={CheckCircle2} size={26} /></span>
          <strong style={{ fontFamily: 'var(--font-display)', fontSize: 'var(--fs-h2)' }}>“{created.name}” is ready</strong>
          <p className={styles.note}>
            Open it to see its details{created.involves_beneficiaries ? ', the beneficiaries recorded under it,' : ''} and any pending
            service requests.
          </p>
        </div>
      </Modal>
    )
  }

  return (
    <Modal open={open} onClose={onClose} title={isCreate ? 'New activity' : 'Edit activity'} footer={footer}>
      <div className={formStyles.form}>
        {isCreate && involves && (
          <ol className={styles.wizardSteps} aria-label="Steps">
            <li className={step === 1 ? styles.wizardStepActive : styles.wizardStep} aria-current={step === 1 ? 'step' : undefined}>
              <span className={styles.wizardStepNo}>1</span> Activity details
            </li>
            <li className={step === 2 ? styles.wizardStepActive : styles.wizardStep} aria-current={step === 2 ? 'step' : undefined}>
              <span className={styles.wizardStepNo}>2</span> Upload beneficiary data
            </li>
          </ol>
        )}

        {formError && (
          <p className={formStyles.alert} role="alert">{formError}</p>
        )}

        {/* Step 1 — activity details (always shown when editing). */}
        <div hidden={isCreate && step !== 1}>
          <div className={formStyles.form}>
            <SelectField
              label="Programme"
              required
              placeholder="Select a catalog programme"
              options={programmeOptions}
              disabled={lockProgramme}
              helper="The catalog programme this activity delivers."
              error={errors.programme_id?.message}
              {...register('programme_id')}
            />
            <SelectField
              label="Does this activity involve beneficiaries?"
              required
              options={[
                { value: 'no', label: 'No, this activity has no beneficiaries (save it alone)' },
                { value: 'yes', label: 'Yes, onboard or serve beneficiaries' },
              ]}
              disabled={!isCreate}
              helper="Yes requires a target and a beneficiary upload; No saves the activity on its own."
              error={errors.involves_beneficiaries?.message}
              {...register('involves_beneficiaries')}
            />
            <TextField label="Name" required error={errors.name?.message} {...register('name')} />
            <TextareaField label="Description" rows={2} error={errors.description?.message} {...register('description')} />
            {involves ? (
              <div className={formStyles.grid2}>
                <TextField label="Target beneficiaries" required type="number" min={1} error={errors.target_beneficiaries?.message} {...register('target_beneficiaries')} />
                <TextField label="Budget (₦)" type="number" min={0} step="0.01" error={errors.budget_naira?.message} {...register('budget_naira')} />
              </div>
            ) : (
              <TextField label="Budget (₦)" type="number" min={0} step="0.01" error={errors.budget_naira?.message} {...register('budget_naira')} />
            )}
            <LocationSetField value={locations} onChange={setLocations} errors={locationErrors} disabled={busy} />
            <TextField label="Location detail" helper="Free description: a landmark or route, not an admin area." error={errors.location_description?.message} {...register('location_description')} />
            <TextField label="Funding source" error={errors.funding_source?.message} {...register('funding_source')} />
            <div className={formStyles.grid2}>
              <TextField label="Start date" type="date" error={errors.starts_on?.message} {...register('starts_on')} />
              <TextField label="End date" type="date" error={errors.ends_on?.message} {...register('ends_on')} />
            </div>
            <SelectField label="Status" required options={ACTIVITY_STATUS_OPTIONS} error={errors.status?.message} {...register('status')} />
          </div>
        </div>

        {/* Step 2 — mandatory beneficiary upload (Yes flow). */}
        {isCreate && involves && step === 2 && (
          <div>
            <p className={styles.note} style={{ marginBottom: 'var(--space-3)' }}>
              Attach the beneficiary file for this activity. It’s validated and screened for duplicates in a
              preview before anything is saved. You’ll resolve any matches and confirm on the next screen.
            </p>

            {file ? (
              <div className={styles.dropzoneFilled}>
                <span className={styles.dropzoneChip} aria-hidden="true"><Icon icon={FileUp} size={18} /></span>
                <span className={styles.dropzoneName}>{file.name}</span>
                <Button size="sm" variant="tertiary" leftIcon={X} onClick={() => setFile(null)}>Remove</Button>
              </div>
            ) : (
              <label htmlFor="activity-beneficiary-file" className={styles.dropzone}>
                <Icon icon={UploadCloud} size={26} className={styles.dropzoneIcon} />
                <span className={styles.dropzoneTitle}>Choose a beneficiary file</span>
                <span className={styles.dropzoneHint}>CSV or Excel · Kobo/ODK exports supported</span>
              </label>
            )}
            <input
              id="activity-beneficiary-file"
              type="file"
              accept=".csv,.txt,.xlsx,.xls"
              onChange={(e) => setFile(e.target.files?.[0] ?? null)}
              className={styles.visuallyHidden}
            />
          </div>
        )}
      </div>
    </Modal>
  )
}
