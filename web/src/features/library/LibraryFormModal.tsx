import { useEffect, useState } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { Modal } from '@/components/Modal/Modal'
import { Button } from '@/components/Button/Button'
import { Checkbox } from '@/components/Field/Checkbox'
import { FileField } from '@/components/Field/FileField'
import { SelectField } from '@/components/Field/SelectField'
import { TextField } from '@/components/Field/TextField'
import { TextareaField } from '@/components/Field/TextareaField'
import { applyApiErrors } from '@/lib/forms/applyApiErrors'
import { useCreateResource, useUpdateResource } from './hooks'
import { KIND_OPTIONS, resourceSchema } from './schema'
import type { ResourceFormValues } from './schema'
import type { LibraryCategory, LibraryResource, LibraryResourceInput } from './types'
import formStyles from '@/features/shared/formLayout.module.css'
import styles from './library.module.css'

interface LibraryFormModalProps {
  open: boolean
  onClose: () => void
  resource?: LibraryResource | null
  categories: LibraryCategory[]
}

const KNOWN_FIELDS = ['title', 'description', 'category', 'featured', 'kind', 'status', 'external_url', 'file', 'thumbnail'] as const

export function LibraryFormModal({ open, onClose, resource, categories }: LibraryFormModalProps) {
  const isEdit = Boolean(resource)
  const create = useCreateResource()
  const update = useUpdateResource()

  // FileField is uncontrolled, so the chosen files live here rather than in the
  // form state. Cleared whenever the modal opens, or a file picked for one
  // resource would be submitted against the next one edited.
  const [file, setFile] = useState<File | null>(null)
  const [thumbnail, setThumbnail] = useState<File | null>(null)

  const {
    register,
    handleSubmit,
    reset,
    setError,
    watch,
    formState: { errors, isSubmitting },
  } = useForm<ResourceFormValues>({
    resolver: zodResolver(resourceSchema),
    defaultValues: { kind: 'file', category: categories[0]?.key ?? '', featured: false },
  })

  const kind = watch('kind')

  useEffect(() => {
    if (!open) return

    setFile(null)
    setThumbnail(null)
    reset(
      resource
        ? {
            title: resource.title,
            description: resource.description ?? '',
            category: resource.category,
            featured: resource.featured,
            kind: resource.kind,
            external_url: resource.external_url ?? '',
          }
        : { kind: 'file', category: categories[0]?.key ?? '', featured: false, title: '', description: '', external_url: '' },
    )
  }, [open, resource, categories, reset])

  async function onSubmit(values: ResourceFormValues, publish: boolean) {
    const payload: Partial<LibraryResourceInput> = {
      title: values.title.trim(),
      description: values.description?.trim() || undefined,
      category: values.category,
      featured: values.featured ?? false,
      kind: values.kind,
      // A link resource must not carry a file, and vice versa — the API refuses a
      // payload with both, so the shape is settled here rather than sent and bounced.
      external_url: values.kind === 'link' ? values.external_url?.trim() : undefined,
      file: values.kind === 'file' ? file : undefined,
      thumbnail: thumbnail ?? undefined,
    }

    try {
      if (isEdit && resource) {
        // Publishing from the edit form is explicit; otherwise the status is left
        // alone so saving a change to a live resource does not quietly unpublish it.
        await update.mutateAsync({ id: resource.id, input: publish ? { ...payload, status: 'published' } : payload })
      } else {
        await create.mutateAsync({ ...payload, status: publish ? 'published' : 'draft' } as LibraryResourceInput)
      }
      onClose()
    } catch (error) {
      applyApiErrors(error, setError, KNOWN_FIELDS)
    }
  }

  const busy = isSubmitting || create.isPending || update.isPending

  return (
    <Modal
      open={open}
      onClose={onClose}
      title={isEdit ? 'Edit resource' : 'Add a resource'}
      footer={
        <div className={formStyles.modalActions}>
          <Button variant="secondary" onClick={onClose} disabled={busy}>
            Cancel
          </Button>
          <Button variant="secondary" onClick={handleSubmit((v) => onSubmit(v, false))} loading={busy}>
            {isEdit ? 'Save' : 'Save as draft'}
          </Button>
          <Button onClick={handleSubmit((v) => onSubmit(v, true))} loading={busy}>
            {isEdit && resource?.status === 'published' ? 'Save and keep public' : 'Publish now'}
          </Button>
        </div>
      }
    >
      <form className={formStyles.form} onSubmit={handleSubmit((v) => onSubmit(v, false))}>
        <TextField
          label="Title"
          required
          error={errors.title?.message}
          {...register('title')}
        />

        <TextareaField
          label="Description"
          helper="One or two sentences. This is what someone reads before deciding to open it."
          rows={3}
          error={errors.description?.message}
          {...register('description')}
        />

        <SelectField
          label="Category"
          required
          error={errors.category?.message}
          options={categories.map((c) => ({ value: c.key, label: c.label }))}
          {...register('category')}
        />

        <SelectField
          label="Type"
          required
          helper="A resource is either a file people download, or a link to somewhere else. Not both."
          error={errors.kind?.message}
          options={KIND_OPTIONS}
          {...register('kind')}
        />

        {kind === 'file' ? (
          <FileField
            label={isEdit ? 'Replace the file' : 'File'}
            required={!isEdit}
            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.csv,.txt"
            // The component's default copy promises virus scanning. There is no
            // scanner in this stack, and telling an administrator otherwise would be
            // a false assurance on the one upload path that becomes public.
            helper={
              isEdit
                ? 'Leave empty to keep the current file. PDF, Word, Excel, PowerPoint, CSV or text · max 25 MB.'
                : 'PDF, Word, Excel, PowerPoint, CSV or text · max 25 MB. Anyone will be able to download this.'
            }
            error={errors.file?.message}
            onFilesSelected={(files) => setFile(files[0] ?? null)}
          />
        ) : (
          <TextField
            label="Web address"
            required
            placeholder="https://"
            helper="Opens in a new tab. Nothing is stored here — check the link works before publishing."
            error={errors.external_url?.message}
            {...register('external_url')}
          />
        )}

        <FileField
          label={resource?.has_thumbnail ? 'Replace the thumbnail' : 'Thumbnail (optional)'}
          accept=".jpg,.jpeg,.png,.webp"
          helper="JPG, PNG or WebP · max 2 MB. Without one, the card shows a plain cover."
          error={errors.thumbnail?.message}
          onFilesSelected={(files) => setThumbnail(files[0] ?? null)}
        />

        <div className={styles.featuredRow}>
          <Checkbox label="Show in Key Content" {...register('featured')} />
          <p className={styles.featuredHint}>
            Pins this above the main list on the public page. Keep it to a handful, or nothing stands out.
          </p>
        </div>
      </form>
    </Modal>
  )
}
