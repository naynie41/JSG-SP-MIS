/**
 * The public resource library (PRD §7.14).
 *
 * Two payload shapes on purpose, because the API has two: an anonymous visitor is
 * served strictly less than an administrator. Modelling them as one optional-heavy
 * type would invite a component to read `status` on the public page and quietly
 * render nothing when it is absent.
 */

export type LibraryKind = 'file' | 'link'
export type LibraryStatus = 'draft' | 'published' | 'archived'

/** A category as the API describes it — key stored, label shown. */
export interface LibraryCategory {
  key: string
  label: string
}

/** What `/public/library` returns. Nothing internal is present at all. */
export interface PublicResource {
  id: string
  title: string
  description: string | null
  category: string
  category_label: string
  featured: boolean
  kind: LibraryKind
  /** Present only for a file resource. */
  original_filename?: string
  size_bytes?: number
  mime_type?: string
  download_url?: string
  /** Present only for a link resource. */
  external_url?: string
  thumbnail_url: string | null
  published_at: string | null
  download_count: number
}

/** What `/library` returns to an administrator: the above plus editorial state. */
export interface LibraryResource {
  id: string
  title: string
  description: string | null
  category: string
  category_label: string
  featured: boolean
  kind: LibraryKind
  original_filename: string | null
  size_bytes: number | null
  mime_type: string | null
  external_url: string | null
  has_thumbnail: boolean
  thumbnail_url: string | null
  status: LibraryStatus
  status_label: string
  published_at: string | null
  download_count: number
  created_by: string | null
  created_at: string | null
  updated_at: string | null
}

/**
 * What the form sends. Not a JSON body — a resource can carry a file and a
 * thumbnail, so the request is multipart and the API layer builds the FormData.
 */
export interface LibraryResourceInput {
  title: string
  description?: string
  category: string
  featured?: boolean
  kind: LibraryKind
  status?: LibraryStatus
  external_url?: string
  file?: File | null
  thumbnail?: File | null
}
