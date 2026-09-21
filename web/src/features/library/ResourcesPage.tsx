import { useMemo, useState } from 'react'
import { Download, ExternalLink, FileText, Filter, Search } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import { LandingHeader } from '@/features/public/LandingHeader'
import { fileKindLabel, formatBytes, formatPublished } from './format'
import { usePublicLibrary } from './hooks'
import type { PublicResource } from './types'
import styles from './library.module.css'

/**
 * The public resource library (FR-RES-04) — the only page in SP-MIS that renders
 * real content to someone who is not signed in.
 *
 * Written for a member of the public, not an officer: no jargon, no system state,
 * and every card says what it is and how big before anyone commits to a download on
 * a metered connection. Filtering happens in the browser over one response, because
 * the library is small and editorial and an unauthenticated endpoint is the last
 * place to send a request per keystroke.
 */
export function ResourcesPage() {
  const { data, isLoading, isError } = usePublicLibrary()
  const [search, setSearch] = useState('')
  const [category, setCategory] = useState('')

  // Memoised because `?? []` mints a new array on every render while the query is
  // loading, which would re-run the filter below for nothing.
  const all = useMemo(() => data?.items ?? [], [data])
  const categories = data?.categories ?? []

  const matching = useMemo(() => {
    const term = search.trim().toLowerCase()

    return all.filter((r) => {
      if (category !== '' && r.category !== category) return false
      if (term === '') return true

      return (
        r.title.toLowerCase().includes(term) ||
        (r.description ?? '').toLowerCase().includes(term) ||
        r.category_label.toLowerCase().includes(term)
      )
    })
  }, [all, search, category])

  const featured = matching.filter((r) => r.featured)
  const rest = matching.filter((r) => !r.featured)

  return (
    <div className={styles.publicPage}>
      <LandingHeader solid />

      <header className={styles.publicHero}>
        <span className={styles.heroEyebrow}>SP-MIS resource library</span>
        <h1 className={styles.heroTitle}>Resources</h1>
        <p className={styles.heroLede}>
          Policies, guidelines, tools and reports supporting social protection across Jigawa State. Free to read and
          download — no account needed.
        </p>
      </header>

      <div className={styles.filterBar}>
        <div className={styles.searchWrap}>
          <Icon icon={Search} size={18} className={styles.searchIcon} aria-hidden="true" />
          <input
            type="search"
            className={styles.searchInput}
            placeholder="Search resources…"
            value={search}
            onChange={(event) => setSearch(event.target.value)}
            aria-label="Search resources"
          />
        </div>

        <div className={styles.categoryWrap}>
          <Icon icon={Filter} size={16} aria-hidden="true" />
          <select
            className={styles.categorySelect}
            value={category}
            onChange={(event) => setCategory(event.target.value)}
            aria-label="Filter by category"
          >
            <option value="">All categories</option>
            {categories.map((c) => (
              <option key={c.key} value={c.key}>
                {c.label}
              </option>
            ))}
          </select>
        </div>
      </div>

      <main className={styles.publicBody}>
        {isLoading && (
          <div className={styles.centered}>
            <Spinner size={24} label="Loading resources" />
          </div>
        )}

        {/* A failed request is not an empty library. Saying "none found" here would
            tell a visitor something untrue about what the State publishes. */}
        {isError && (
          <div className={styles.emptyPanel} role="alert">
            <p className={styles.emptyTitle}>Resources could not be loaded</p>
            <p className={styles.emptyNote}>
              This is a connection problem, not an empty library. Please try again shortly.
            </p>
          </div>
        )}

        {!isLoading && !isError && (
          <>
            {featured.length > 0 && (
              <ResourceSection title="Key Content" count={featured.length} resources={featured} />
            )}

            <ResourceSection
              title={featured.length > 0 ? 'All resources' : 'Key Content'}
              count={rest.length}
              resources={rest}
              emptyWhenNone={all.length === 0 ? 'Nothing has been published yet.' : 'No resources match your filters.'}
            />
          </>
        )}
      </main>
    </div>
  )
}

function ResourceSection({
  title,
  count,
  resources,
  emptyWhenNone,
}: {
  title: string
  count: number
  resources: PublicResource[]
  emptyWhenNone?: string
}) {
  return (
    <section className={styles.section} aria-label={title}>
      <div className={styles.sectionHead}>
        <h2 className={styles.sectionTitle}>{title}</h2>
        <span className={styles.sectionCount}>
          {count} {count === 1 ? 'resource' : 'resources'}
        </span>
      </div>

      {resources.length === 0 ? (
        emptyWhenNone ? (
          <div className={styles.emptyPanel}>
            <p className={styles.emptyNote}>{emptyWhenNone}</p>
          </div>
        ) : null
      ) : (
        <ul className={styles.grid}>
          {resources.map((resource) => (
            <li key={resource.id}>
              <ResourceCard resource={resource} />
            </li>
          ))}
        </ul>
      )}
    </section>
  )
}

function ResourceCard({ resource }: { resource: PublicResource }) {
  const isFile = resource.kind === 'file'
  const published = formatPublished(resource.published_at)

  return (
    <article className={styles.card}>
      <div className={styles.cover} data-category={resource.category}>
        {resource.thumbnail_url ? (
          <img src={resource.thumbnail_url} alt="" className={styles.coverImage} loading="lazy" />
        ) : (
          // No thumbnail is the normal case, not a broken one — so the fallback is a
          // designed cover carrying the category, never a grey box or a missing image.
          <span className={styles.coverFallback}>
            <Icon icon={isFile ? FileText : ExternalLink} size={26} aria-hidden="true" />
            <span>{resource.category_label}</span>
          </span>
        )}
        <span className={styles.coverTag}>{isFile ? fileKindLabel(resource.original_filename) : 'Link'}</span>
      </div>

      <div className={styles.cardBody}>
        <span className={styles.cardCategory}>{resource.category_label}</span>
        <h3 className={styles.cardTitle}>{resource.title}</h3>
        {resource.description && <p className={styles.cardText}>{resource.description}</p>}

        <div className={styles.cardMeta}>
          {published && <span>{published}</span>}
          {isFile && resource.size_bytes ? <span>{formatBytes(resource.size_bytes)}</span> : null}
        </div>

        {isFile ? (
          // A real link, not a fetch: the browser's own download handling is better
          // than anything re-implemented here, and it works without JavaScript.
          // aria-label rather than an sr-only span: a screen reader listing links
          // out of context needs the title, but repeating it as a text node makes
          // the card's own heading ambiguous to anything reading by text.
          <a
            className={styles.cardAction}
            href={resource.download_url}
            download
            aria-label={`Download ${resource.title}`}
          >
            <Icon icon={Download} size={16} aria-hidden="true" />
            Download
          </a>
        ) : (
          <a
            className={styles.cardAction}
            href={resource.external_url}
            target="_blank"
            // noreferrer as well as noopener: this leaves a government domain for a
            // third-party site, which has no business reading where it was opened from.
            rel="noopener noreferrer"
            aria-label={`Open ${resource.title} (opens in a new tab)`}
          >
            <Icon icon={ExternalLink} size={16} aria-hidden="true" />
            Open
          </a>
        )}
      </div>
    </article>
  )
}
