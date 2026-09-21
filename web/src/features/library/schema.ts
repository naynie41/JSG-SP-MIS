import { z } from 'zod'

/**
 * Mirrors StoreLibraryItemRequest / UpdateLibraryItemRequest.
 *
 * The file-or-link rule is expressed here too, but the server holds the real one —
 * this exists so the administrator is told before uploading a large file, not to be
 * the control. Anything that matters is refused again by the API, the model and a
 * Postgres CHECK.
 */
export const resourceSchema = z
  .object({
    title: z.string().min(1, 'Give the resource a title').max(255),
    description: z.string().max(2000).optional().or(z.literal('')),
    category: z.string().min(1, 'Choose a category'),
    featured: z.boolean().optional(),
    kind: z.enum(['file', 'link']),
    status: z.enum(['draft', 'published', 'archived']).optional(),
    external_url: z.string().optional().or(z.literal('')),
    // Never set by an input — FileField is uncontrolled and the chosen files live in
    // component state. They are declared so the form type has somewhere to put a
    // SERVER error about them: the API's size and type rules are the real ones, and
    // "that file type is not accepted" has to land under the file picker, not
    // nowhere.
    file: z.unknown().optional(),
    thumbnail: z.unknown().optional(),
  })
  .superRefine((values, ctx) => {
    if (values.kind !== 'link') return

    const url = (values.external_url ?? '').trim()

    if (url === '') {
      ctx.addIssue({
        code: 'custom',
        path: ['external_url'],
        message: 'Enter the web address this resource points to',
      })
      return
    }

    if (!/^https?:\/\//i.test(url)) {
      ctx.addIssue({
        code: 'custom',
        path: ['external_url'],
        message: 'Enter a full web address beginning with https://',
      })
    }
  })

export type ResourceFormValues = z.infer<typeof resourceSchema>

export const KIND_OPTIONS = [
  { value: 'file', label: 'Upload a file' },
  { value: 'link', label: 'Link to somewhere else' },
]
