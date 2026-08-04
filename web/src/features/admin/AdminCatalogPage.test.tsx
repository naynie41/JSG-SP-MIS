import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, within } from '@testing-library/react'
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
  programmeApi: { list: vi.fn(), get: vi.fn(), create: vi.fn(), update: vi.fn(), archive: vi.fn(), active: vi.fn() },
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

    await user.click(screen.getByRole('tab', { name: /usage across mdas/i }))

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
    await user.click(screen.getByRole('tab', { name: /usage across mdas/i }))

    expect(await screen.findByText('Catalog programmes')).toBeInTheDocument()
    expect(screen.getByText('Adopted by an MDA')).toBeInTheDocument()
    expect(screen.getByText('1 not yet adopted')).toBeInTheDocument()
    // 3 + 1 + 0 activities reference the catalog.
    expect(screen.getByText('Activities referencing the catalog')).toBeInTheDocument()
    expect(screen.getByText('4')).toBeInTheDocument()
  })

  /* -------------------------------------------------------- permission gating */

  it('hides catalog writes from a viewer — MDAs can never create programmes', async () => {
    perms.value = ['programme.view'] // an MDA role: read the catalog, never write it
    renderPage()
    await screen.findByText('Conditional Cash Transfer')

    expect(screen.queryByRole('button', { name: /create programme/i })).not.toBeInTheDocument()
  })

  it('blocks the catalog entirely without programme.view', async () => {
    perms.value = []
    renderPage()

    expect(await screen.findByText(/do not have permission/i)).toBeInTheDocument()
    expect(list).not.toHaveBeenCalled()
  })
})
