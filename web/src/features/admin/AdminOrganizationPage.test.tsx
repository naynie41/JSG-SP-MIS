import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { AdminOrganizationPage } from './AdminOrganizationPage'
import { adminApi } from './api'
import { mdaApi } from '@/features/mdas/api'
import type { AdminOrganizations } from './types'

// The section must COMPOSE the Phase 1 MDA module — so we mock the Phase 1 api layer
// and assert the section drives it, rather than mocking only the console endpoint.
vi.mock('@/features/mdas/api', () => ({
  mdaApi: { list: vi.fn(), create: vi.fn(), update: vi.fn(), deactivate: vi.fn(), activate: vi.fn() },
}))
vi.mock('./api', () => ({ adminApi: { summary: vi.fn(), loginActivity: vi.fn(), organizations: vi.fn() } }))

const perms = { value: [] as string[] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { role: { key: 'system_administrator' }, name: 'Admin' },
    hasPermission: (p: string) => perms.value.includes(p),
  }),
}))

const listMdas = mdaApi.list as Mock
const organizations = adminApi.organizations as Mock

const mdaRecords = [
  { id: 'm1', name: 'Ministry of Health', type: 'ministry', status: 'active', contact_person: null, contact_email: 'health@example.test', contact_phone: null, address: null },
  { id: 'm2', name: 'Women Affairs', type: 'ministry', status: 'inactive', contact_person: null, contact_email: null, contact_phone: null, address: null },
]

const rollup: AdminOrganizations = {
  mdas: [
    { id: 'm1', name: 'Ministry of Health', type: 'ministry', status: 'active', users_total: 3, mda_admins: 2, activities_total: 2, activities_active: 1 },
    { id: 'm2', name: 'Women Affairs', type: 'ministry', status: 'inactive', users_total: 1, mda_admins: 0, activities_total: 1, activities_active: 1 },
  ],
  partners: [
    { id: 'p1', name: 'World Bank', email: 'wb@example.test', status: 'active', is_active: true, funded_activities: 2, funded_programmes: 1, implementing_mdas: 2 },
  ],
  totals: { mdas: 2, mdas_active: 1, users_allocated: 4, users_unallocated: 3 },
}

function renderPage() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter>
          <AdminOrganizationPage />
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('Admin console — Organization (composes Phase 1 + Phase 4)', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms.value = ['mda.view', 'mda.create', 'mda.edit']
    listMdas.mockResolvedValue(mdaRecords)
    organizations.mockResolvedValue(rollup)
  })

  /* ------------------------------------------------------------- composition */

  it('manages organizations through the EXISTING MDA module — no second org store', async () => {
    renderPage()

    expect(await screen.findByText('Ministry of Health')).toBeInTheDocument()
    // The section drove the Phase 1 api layer.
    expect(listMdas).toHaveBeenCalled()
    // Create/edit affordances come from that existing page.
    expect(screen.getAllByRole('button', { name: /create mda/i }).length).toBeGreaterThan(0)
    expect(screen.getAllByRole('button', { name: /actions for/i }).length).toBeGreaterThan(0)
  })

  it('offers the existing activate/deactivate lifecycle, not a console-local one', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Ministry of Health')

    await user.click(screen.getByRole('button', { name: /actions for Ministry of Health/i }))
    expect(await screen.findByText('Edit')).toBeInTheDocument()
    expect(screen.getByText('Deactivate')).toBeInTheDocument()
    await user.keyboard('{Escape}')

    // The inactive organization offers Activate instead.
    await user.click(screen.getByRole('button', { name: /actions for Women Affairs/i }))
    expect(await screen.findByText('Activate')).toBeInTheDocument()
  })

  /* ------------------------------------------------------------- allocation */

  it('reports user allocation and activities per organization', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Ministry of Health')

    await user.click(screen.getByRole('tab', { name: /allocation & activity/i }))

    expect(await screen.findByText('Users allocated')).toBeInTheDocument()
    expect(screen.getByText('MDA admins')).toBeInTheDocument()

    // Health's row: 3 users, 2 admins, 1 of 2 activities active.
    const row = screen.getAllByText('Ministry of Health').at(-1)?.closest('tr')
    expect(row).not.toBeNull()
    expect(within(row as HTMLElement).getByText('3')).toBeInTheDocument()
    expect(within(row as HTMLElement).getByText('2')).toBeInTheDocument()
    expect(within(row as HTMLElement).getByText('of 2')).toBeInTheDocument()

    // Platform accounts (no MDA) are surfaced so allocation reconciles.
    expect(screen.getByText('Platform accounts')).toBeInTheDocument()
    expect(organizations).toHaveBeenCalled()
  })

  it('shows development partners with the delivery they fund', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Ministry of Health')

    await user.click(screen.getByRole('tab', { name: /development partners/i }))

    expect(await screen.findByText('World Bank')).toBeInTheDocument()
    expect(screen.getByText('Funded programmes')).toBeInTheDocument()
    expect(screen.getByText('Implementing MDAs')).toBeInTheDocument()
  })

  /* --------------------------------------------------------- permission gating */

  it('inherits Phase 1 MDA gating — a view-only admin gets no write actions', async () => {
    perms.value = ['mda.view'] // no mda.create / mda.edit
    renderPage()
    await screen.findByText('Ministry of Health')

    expect(screen.queryByRole('button', { name: /create mda/i })).not.toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /actions for/i })).not.toBeInTheDocument()
  })

  it('blocks the organizations view entirely without mda.view', async () => {
    perms.value = []
    renderPage()

    expect(await screen.findByText(/do not have permission/i)).toBeInTheDocument()
    expect(listMdas).not.toHaveBeenCalled()
  })
})
