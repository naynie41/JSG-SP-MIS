import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import type { ReactNode } from 'react'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { LibraryAdminPage } from './LibraryAdminPage'
import { libraryApi } from './api'
import type { LibraryResource } from './types'

vi.mock('./api', () => ({
  libraryApi: { publicList: vi.fn(), list: vi.fn(), create: vi.fn(), update: vi.fn(), remove: vi.fn() },
}))

const auth = { permissions: ['library.view', 'library.create', 'library.edit'] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    hasPermission: (p: string) => auth.permissions.includes(p),
    hasAnyPermission: (ps: string[]) => ps.some((p) => auth.permissions.includes(p)),
    user: { permissions: auth.permissions },
    status: 'authenticated',
  }),
}))

const list = libraryApi.list as Mock
const update = libraryApi.update as Mock
const create = libraryApi.create as Mock

function resource(over: Partial<LibraryResource> = {}): LibraryResource {
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
    external_url: null,
    has_thumbnail: false,
    thumbnail_url: null,
    status: 'published',
    status_label: 'Published',
    published_at: '2026-03-12T00:00:00+00:00',
    download_count: 42,
    created_by: 'u1',
    created_at: '2026-03-01T00:00:00+00:00',
    updated_at: '2026-03-12T00:00:00+00:00',
    ...over,
  }
}

function renderPage(ui: ReactNode = <LibraryAdminPage />) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>{ui}</ToastProvider>
    </QueryClientProvider>,
  )
}

describe('LibraryAdminPage', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    auth.permissions = ['library.view', 'library.create', 'library.edit']
    list.mockResolvedValue({
      items: [resource()],
      categories: { policy: 'Policies', report: 'Reports' },
    })
  })

  it('shows each resource with the state that decides whether it is public', async () => {
    renderPage()

    expect(await screen.findByText('State Social Protection Policy')).toBeInTheDocument()

    // Scoped to the row: "Published" is also a column header, so an unscoped query
    // matches two elements and would pass even if the badge were missing.
    const row = screen.getByRole('row', { name: /State Social Protection Policy/ })
    expect(within(row).getByText('Published')).toBeInTheDocument()
    expect(within(row).getByText('Policies')).toBeInTheDocument()
    expect(within(row).getByText('42')).toBeInTheDocument()
    expect(within(row).getByText(/PDF · 2.4 MB/)).toBeInTheDocument()
  })

  /** The administrator has to know this screen is not internal. */
  it('says plainly that published resources are public', async () => {
    renderPage()

    expect(await screen.findByText(/downloaded by anyone, without signing in/i)).toBeInTheDocument()
  })

  it('withdraws a published resource without deleting it', async () => {
    update.mockResolvedValue(resource({ status: 'archived', status_label: 'Archived' }))
    renderPage()

    await screen.findByText('State Social Protection Policy')
    await userEvent.click(screen.getByRole('button', { name: /actions for/i }))
    await userEvent.click(await screen.findByText(/withdraw from public page/i))

    await waitFor(() => expect(update).toHaveBeenCalledWith('r1', { status: 'archived' }))
  })

  it('publishes a draft', async () => {
    list.mockResolvedValue({
      items: [resource({ status: 'draft', status_label: 'Draft', published_at: null })],
      categories: { policy: 'Policies' },
    })
    update.mockResolvedValue(resource())
    renderPage()

    await screen.findByText('State Social Protection Policy')
    await userEvent.click(screen.getByRole('button', { name: /actions for/i }))
    await userEvent.click(await screen.findByText('Publish'))

    await waitFor(() => expect(update).toHaveBeenCalledWith('r1', { status: 'published' }))
  })

  /**
   * Removing is not withdrawing, and the difference matters: one keeps the download
   * history, the other does not. The dialog has to say so when it applies.
   */
  it('warns that withdrawing is the gentler option when removing something public', async () => {
    renderPage()

    await screen.findByText('State Social Protection Policy')
    await userEvent.click(screen.getByRole('button', { name: /actions for/i }))
    await userEvent.click(await screen.findByText('Remove'))

    const dialog = await screen.findByRole('dialog')
    expect(within(dialog).getByText(/withdraw/i)).toBeInTheDocument()
    expect(within(dialog).getByText(/keeps its download history/i)).toBeInTheDocument()
  })

  it('creates a link resource without asking for a file', async () => {
    create.mockResolvedValue(resource({ kind: 'link', external_url: 'https://example.test/x' }))
    renderPage()

    await screen.findByText('State Social Protection Policy')
    await userEvent.click(screen.getByRole('button', { name: /add resource/i }))

    const dialog = await screen.findByRole('dialog')
    await userEvent.type(within(dialog).getByLabelText(/^Title/), 'National portal')
    await userEvent.selectOptions(within(dialog).getByLabelText(/^Type/), 'link')

    // Switching to a link must replace the file picker, not sit beside it.
    expect(within(dialog).queryByLabelText(/^File/)).not.toBeInTheDocument()

    await userEvent.type(within(dialog).getByLabelText(/web address/i), 'https://example.test/x')
    await userEvent.click(within(dialog).getByRole('button', { name: /publish now/i }))

    await waitFor(() => expect(create).toHaveBeenCalled())
    expect(create.mock.calls[0][0]).toMatchObject({
      kind: 'link',
      external_url: 'https://example.test/x',
      status: 'published',
    })
  })

  it('refuses a link resource with no web address, before anything is sent', async () => {
    renderPage()

    await screen.findByText('State Social Protection Policy')
    await userEvent.click(screen.getByRole('button', { name: /add resource/i }))

    const dialog = await screen.findByRole('dialog')
    await userEvent.type(within(dialog).getByLabelText(/^Title/), 'Missing address')
    await userEvent.selectOptions(within(dialog).getByLabelText(/^Type/), 'link')
    await userEvent.click(within(dialog).getByRole('button', { name: /publish now/i }))

    expect(await within(dialog).findByText(/enter the web address/i)).toBeInTheDocument()
    expect(create).not.toHaveBeenCalled()
  })

  it('is closed to a user without the permission', async () => {
    auth.permissions = []
    renderPage()

    expect(await screen.findByText(/do not have permission/i)).toBeInTheDocument()
    expect(list).not.toHaveBeenCalled()
  })

  it('offers no write actions to a read-only administrator', async () => {
    auth.permissions = ['library.view']
    renderPage()

    await screen.findByText('State Social Protection Policy')
    expect(screen.queryByRole('button', { name: /add resource/i })).not.toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /actions for/i })).not.toBeInTheDocument()
  })
})
