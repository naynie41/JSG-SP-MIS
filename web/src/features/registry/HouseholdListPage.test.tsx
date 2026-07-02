import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen } from '@testing-library/react'
import { MemoryRouter } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { HouseholdListPage } from './HouseholdListPage'
import { householdApi } from './api'

vi.mock('./api', () => ({
  householdApi: { list: vi.fn(), remove: vi.fn() },
  beneficiaryApi: {},
  documentApi: {},
  importApi: {},
}))

vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    hasPermission: () => true,
    hasAnyPermission: () => true,
    user: { mda: { id: 'm-1' } },
    status: 'authenticated',
  }),
}))

const list = householdApi.list as Mock

function makeHousehold() {
  return {
    id: 'household-00000001',
    owner_mda_id: 'm-1',
    head_beneficiary_id: 'b-1',
    registration_source: 'csv',
    registration_date: '2026-07-01',
    address: null,
    lga: 'dutse',
    ward: 'Ward 1',
    members: [{ id: 'mem-1' }],
    created_at: null,
    updated_at: null,
  }
}

function renderPage(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter>{ui}</MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('HouseholdListPage', () => {
  beforeEach(() => vi.clearAllMocks())

  it('lists households without any manual "create household" action', async () => {
    list.mockResolvedValue({ items: [makeHousehold()], pagination: { page: 1, per_page: 25, total: 1, total_pages: 1 } })

    renderPage(<HouseholdListPage />)

    // The household row is browsable…
    expect(await screen.findByText('Head assigned')).toBeInTheDocument()
    // …but there is no create/registration entry point.
    expect(screen.queryByRole('button', { name: /create household/i })).not.toBeInTheDocument()
  })

  it('explains that households are formed by source ingestion when empty', async () => {
    list.mockResolvedValue({ items: [], pagination: { page: 1, per_page: 25, total: 0, total_pages: 1 } })

    renderPage(<HouseholdListPage />)

    expect(await screen.findByText(/formed when a source groups its records/i)).toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /create household/i })).not.toBeInTheDocument()
  })
})
