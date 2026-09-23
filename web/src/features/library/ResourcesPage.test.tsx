import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { MemoryRouter } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ResourcesPage } from './ResourcesPage'
import { libraryApi } from './api'
import type { PublicResource } from './types'

vi.mock('./api', () => ({
  libraryApi: { publicList: vi.fn(), list: vi.fn(), create: vi.fn(), update: vi.fn(), remove: vi.fn() },
}))

const publicList = libraryApi.publicList as Mock

function file(over: Partial<PublicResource> = {}): PublicResource {
  return {
    id: 'r1',
    title: 'State Social Protection Policy',
    description: 'The governing policy document.',
    category: 'policy',
    category_label: 'Policies',
    featured: false,
    kind: 'file',
    original_filename: 'policy.pdf',
    size_bytes: 2_400_000,
    mime_type: 'application/pdf',
    download_url: 'http://api.test/api/v1/public/library/r1/download',
    thumbnail_url: null,
    published_at: '2026-03-12T00:00:00+00:00',
    download_count: 42,
    ...over,
  }
}

function link(over: Partial<PublicResource> = {}): PublicResource {
  return {
    ...file(),
    id: 'r2',
    title: 'National Social Register portal',
    kind: 'link',
    original_filename: undefined,
    size_bytes: undefined,
    mime_type: undefined,
    download_url: undefined,
    external_url: 'https://nassp.gov.ng/',
    ...over,
  }
}

function renderPage() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <MemoryRouter>
        <ResourcesPage />
      </MemoryRouter>
    </QueryClientProvider>,
  )
}

/**
 * The public resources page — the only screen in SP-MIS a member of the public
 * sees with content on it. These tests are written from a visitor's side: can they
 * find a thing, and can they tell what it is before they commit to downloading it.
 */
describe('ResourcesPage', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    publicList.mockResolvedValue({
      items: [file()],
      categories: [
        { key: 'policy', label: 'Policies' },
        { key: 'report', label: 'Reports' },
      ],
    })
  })

  it('renders a downloadable resource with the facts a visitor needs first', async () => {
    renderPage()

    expect(await screen.findByText('State Social Protection Policy')).toBeInTheDocument()

    // Type and size before the click — this page is read on metered connections.
    expect(screen.getByText('PDF')).toBeInTheDocument()
    expect(screen.getByText('2.4 MB')).toBeInTheDocument()
    // Not a fixed string: the date is rendered in the visitor's locale, so asserting
    // "12 March 2026" would pin the test to whichever locale the runner happens to use.
    expect(screen.getByText(/March.*2026|2026.*March/)).toBeInTheDocument()

    const download = screen.getByRole('link', { name: /download/i })
    expect(download).toHaveAttribute('href', 'http://api.test/api/v1/public/library/r1/download')
  })

  it('opens a link resource off-site, with the referrer withheld', async () => {
    publicList.mockResolvedValue({ items: [link()], categories: [] })
    renderPage()

    const open = await screen.findByRole('link', { name: /open/i })

    expect(open).toHaveAttribute('href', 'https://nassp.gov.ng/')
    expect(open).toHaveAttribute('target', '_blank')
    // A government domain should not tell a third-party site where the visitor came from.
    expect(open).toHaveAttribute('rel', 'noopener noreferrer')
  })

  it('pins featured resources into their own Key Content band', async () => {
    publicList.mockResolvedValue({
      items: [file({ id: 'a', title: 'Ordinary guidance' }), file({ id: 'b', title: 'Flagship policy', featured: true })],
      categories: [],
    })
    renderPage()

    const key = await screen.findByRole('region', { name: 'Key Content' })
    expect(within(key).getByText('Flagship policy')).toBeInTheDocument()
    expect(within(key).queryByText('Ordinary guidance')).not.toBeInTheDocument()

    const all = screen.getByRole('region', { name: 'All resources' })
    expect(within(all).getByText('Ordinary guidance')).toBeInTheDocument()
  })

  it('filters by search text', async () => {
    publicList.mockResolvedValue({
      items: [file({ id: 'a', title: 'Cash transfer manual' }), file({ id: 'b', title: 'Annual report' })],
      categories: [],
    })
    renderPage()

    await screen.findByText('Cash transfer manual')

    await userEvent.type(screen.getByLabelText('Search resources'), 'annual')

    await waitFor(() => expect(screen.queryByText('Cash transfer manual')).not.toBeInTheDocument())
    expect(screen.getByText('Annual report')).toBeInTheDocument()
  })

  it('filters by category', async () => {
    publicList.mockResolvedValue({
      items: [
        file({ id: 'a', title: 'A policy', category: 'policy', category_label: 'Policies' }),
        file({ id: 'b', title: 'A report', category: 'report', category_label: 'Reports' }),
      ],
      categories: [
        { key: 'policy', label: 'Policies' },
        { key: 'report', label: 'Reports' },
      ],
    })
    renderPage()

    await screen.findByText('A policy')

    await userEvent.selectOptions(screen.getByLabelText('Filter by category'), 'report')

    await waitFor(() => expect(screen.queryByText('A policy')).not.toBeInTheDocument())
    expect(screen.getByText('A report')).toBeInTheDocument()
  })

  it('says nothing matched the filters rather than that the library is empty', async () => {
    renderPage()
    await screen.findByText('State Social Protection Policy')

    await userEvent.type(screen.getByLabelText('Search resources'), 'zzzzz')

    expect(await screen.findByText(/no resources match your filters/i)).toBeInTheDocument()
  })

  it('distinguishes an empty library from a failed request', async () => {
    publicList.mockResolvedValue({ items: [], categories: [] })
    const empty = renderPage()
    expect(await screen.findByText(/nothing has been published yet/i)).toBeInTheDocument()
    empty.unmount()

    // A failed request must NOT read as "the State publishes nothing".
    publicList.mockRejectedValue(new Error('network'))
    renderPage()

    expect(await screen.findByText(/could not be loaded/i)).toBeInTheDocument()
    expect(screen.queryByText(/nothing has been published yet/i)).not.toBeInTheDocument()
  })

  it('needs no authenticated session to render', async () => {
    // The page imports no auth hook at all; this asserts the contract holds by
    // rendering it with no AuthProvider in the tree, which would throw if one
    // were introduced later.
    renderPage()

    expect(await screen.findByRole('heading', { name: 'Resources', level: 1 })).toBeInTheDocument()
  })
})
