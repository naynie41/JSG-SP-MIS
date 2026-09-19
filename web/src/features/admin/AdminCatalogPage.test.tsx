import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { AdminCatalogPage } from './AdminCatalogPage'
import { programmeApi } from '@/features/programmes/api'
import type { Programme } from '@/features/programmes/types'

// The section must COMPOSE the Phase 4 catalog module — mock the catalog api layer and
// assert the section drives it, rather than introducing a console-local catalog.
vi.mock('@/features/programmes/api', () => ({
  programmeApi: {
    list: vi.fn(),
    get: vi.fn(),
    create: vi.fn(),
    update: vi.fn(),
    archive: vi.fn(),
    active: vi.fn(),
    submit: vi.fn(),
    approve: vi.fn(),
    reject: vi.fn(),
  },
}))

const perms = { value: [] as string[] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { role: { key: 'system_administrator' }, name: 'Admin' },
    hasPermission: (p: string) => perms.value.includes(p),
  }),
}))

const list = programmeApi.list as Mock

const programme = (over: Partial<Programme> & { id: string; name: string }): Programme => ({
  objective: null,
  type: 'individual',
  benefit_category: 'cash',
  eligibility: [],
  enforce_eligibility: false,
  status: 'active',
  approval_status: 'approved',
  activities_count: 0,
  mdas_count: 0,
  created_by: null,
  created_at: null,
  updated_at: null,
  ...over,
})

const catalog: Programme[] = [
  programme({ id: 'p1', name: 'Conditional Cash Transfer', activities_count: 3, mdas_count: 2 }),
  programme({ id: 'p2', name: 'School Feeding', type: 'household', benefit_category: 'food', activities_count: 1, mdas_count: 1 }),
  programme({ id: 'p3', name: 'Unused Programme', status: 'draft', activities_count: 0, mdas_count: 0 }),
]

function renderPage() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter>
          <AdminCatalogPage />
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('Admin console — Programme Catalog (composes Phase 4 / v1.3)', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms.value = ['programme.view', 'programme.create', 'programme.edit']
    list.mockResolvedValue({
      items: catalog,
      pagination: { page: 1, per_page: 100, total: catalog.length, total_pages: 1 },
    })
  })

  /* ------------------------------------------------------------- composition */

  it('manages the catalog through the EXISTING programme module — no second catalog', async () => {
    renderPage()

    expect(await screen.findByText('Conditional Cash Transfer')).toBeInTheDocument()
    expect(list).toHaveBeenCalled()
    // Create comes from the existing catalog page, gated by programme.create.
    expect(screen.getAllByRole('button', { name: /create programme/i }).length).toBeGreaterThan(0)
  })

  it('surfaces the catalog attributes an administrator configures', async () => {
    renderPage()
    await screen.findByText('Conditional Cash Transfer')

    // Programme category (type), benefit category and status all render.
    expect(screen.getAllByText('individual').length).toBeGreaterThan(0)
    expect(screen.getAllByText('active').length).toBeGreaterThan(0)
    expect(screen.getAllByText('cash').length).toBeGreaterThan(0)
  })

  /* ------------------------------------------------------------------ usage */

  it('reports cross-MDA usage per catalog programme', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Conditional Cash Transfer')

    await user.click(screen.getByRole('tab', { name: /usage across agencies/i }))

    expect(await screen.findByText('MDAs running it')).toBeInTheDocument()

    // One global programme, run by 2 MDAs through 3 activities.
    const row = screen.getAllByText('Conditional Cash Transfer').at(-1)?.closest('tr')
    expect(row).not.toBeNull()
    expect(within(row as HTMLElement).getByText('2')).toBeInTheDocument()
    expect(within(row as HTMLElement).getByText('3')).toBeInTheDocument()

    // A programme no MDA has adopted reads as such, rather than a bare zero.
    const unusedRow = screen.getAllByText('Unused Programme').at(-1)?.closest('tr')
    expect(within(unusedRow as HTMLElement).getByText('not adopted')).toBeInTheDocument()
  })

  it('summarises catalog adoption', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Conditional Cash Transfer')
    await user.click(screen.getByRole('tab', { name: /usage across agencies/i }))

    expect(await screen.findByText('Catalog programmes')).toBeInTheDocument()
    expect(screen.getByText('Adopted by an MDA')).toBeInTheDocument()
    expect(screen.getByText('1 not yet adopted')).toBeInTheDocument()
    // 3 + 1 + 0 activities reference the catalog.
    expect(screen.getByText('Activities referencing the catalog')).toBeInTheDocument()
    expect(screen.getByText('4')).toBeInTheDocument()
  })

  /* -------------------------------------------------------- permission gating */

  it('hides catalog writes from a role without programme.create', async () => {
    perms.value = ['programme.view'] // read the catalog, never write it
    renderPage()
    await screen.findByText('Conditional Cash Transfer')

    expect(screen.queryByRole('button', { name: /create programme/i })).not.toBeInTheDocument()
  })

  /* ------------------------------------------------------ approvals (§10 rev) */

  it('counts what is waiting on the Approvals tab and decides it there', async () => {
    const user = userEvent.setup()
    perms.value = [...perms.value, 'programme.approve']
    const waiting = programme({
      id: 'p9',
      name: 'Maternal Cash Support',
      approval_status: 'pending',
      is_central: false,
      owner_mda: { id: 'm1', name: 'Ministry of Health' },
      submitted_at: '2026-09-01T00:00:00Z',
    })
    // The page asks twice: the catalog list, and the pending queue.
    list.mockImplementation((params: { approval?: string }) =>
      Promise.resolve(
        params.approval === 'pending'
          ? { items: [waiting], pagination: { page: 1, per_page: 100, total: 1, total_pages: 1 } }
          : { items: catalog, pagination: { page: 1, per_page: 100, total: catalog.length, total_pages: 1 } },
      ),
    )
    ;(programmeApi.approve as Mock).mockResolvedValue({ ...waiting, approval_status: 'approved' })

    renderPage()

    // The count rides on the tab: an unopened queue stalls that MDA's whole programme.
    const tab = await screen.findByRole('tab', { name: /approvals \(1\)/i })
    await user.click(tab)

    expect(await screen.findByText('Maternal Cash Support')).toBeInTheDocument()
    expect(screen.getByText('Ministry of Health')).toBeInTheDocument()

    await user.click(screen.getByRole('button', { name: /approve/i }))
    await waitFor(() => expect(programmeApi.approve).toHaveBeenCalledWith('p9', undefined))
  })

  it('refuses to send a programme back without a reason', async () => {
    const user = userEvent.setup()
    perms.value = [...perms.value, 'programme.approve']
    const waiting = programme({ id: 'p9', name: 'Maternal Cash Support', approval_status: 'pending', is_central: false })
    list.mockImplementation((params: { approval?: string }) =>
      Promise.resolve(
        params.approval === 'pending'
          ? { items: [waiting], pagination: { page: 1, per_page: 100, total: 1, total_pages: 1 } }
          : { items: catalog, pagination: { page: 1, per_page: 100, total: catalog.length, total_pages: 1 } },
      ),
    )
    ;(programmeApi.reject as Mock).mockResolvedValue({ ...waiting, approval_status: 'rejected' })

    renderPage()
    await user.click(await screen.findByRole('tab', { name: /approvals/i }))
    await user.click(await screen.findByRole('button', { name: /send back/i }))

    const dialog = await screen.findByRole('dialog')
    // The reason is the only thing the MDA has to work from, so the control stays
    // disabled until there is one.
    expect(within(dialog).getByRole('button', { name: /send back/i })).toBeDisabled()

    await user.type(within(dialog).getByLabelText(/why it is being sent back/i), 'Name it after the benefit.')
    await user.click(within(dialog).getByRole('button', { name: /send back/i }))

    await waitFor(() => expect(programmeApi.reject).toHaveBeenCalledWith('p9', 'Name it after the benefit.'))
  })

  it('blocks the catalog entirely without programme.view', async () => {
    perms.value = []
    renderPage()

    expect(await screen.findByText(/do not have permission/i)).toBeInTheDocument()
    expect(list).not.toHaveBeenCalled()
  })
})
