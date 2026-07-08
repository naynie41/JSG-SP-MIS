import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen } from '@testing-library/react'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { PartnerDashboardPage } from './PartnerDashboardPage'
import { dashboardApi } from './api'
import type { DashboardResponse } from './types'

vi.mock('./api', () => ({ dashboardApi: { get: vi.fn() } }))

const authState = { roleKey: 'development_partner', canView: true }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ user: { role: { key: authState.roleKey }, mda: null }, hasPermission: () => authState.canView }),
}))

const get = dashboardApi.get as Mock

// A partner-scoped payload: funded programmes only; coordination metrics are null.
const partnerPayload: DashboardResponse = {
  scope: { kind: 'partner', label: 'Funded programmes' },
  computed_at: new Date().toISOString(),
  metrics: {
    registry: { beneficiaries: { total: 150, by_status: { active: 150 }, by_source: {}, by_lga: { dutse: 150 } }, households: null },
    programmes: { total: 1, active: 1 },
    duplicates: null,
    benefits: {
      disbursed: { benefit_count: 60, total_value: 6_000_000, total_quantity: '0' },
      budget: { allocated: 20_000_000, utilized_value: 6_000_000, utilized_quantity: '0', benefit_count: 60, remaining: 14_000_000, utilization_rate: 0.3 },
      by_type: [{ key: 'cash', benefit_count: 60, total_value: 6_000_000, total_quantity: '0' }],
    },
    referrals: null,
    grievances: null,
    coverage: [{ lga: 'dutse', beneficiary_count: 140, benefit_count: 60, benefit_value: 6_000_000 }],
  },
}

function renderPage(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <MemoryRouter>{ui}</MemoryRouter>
    </QueryClientProvider>,
  )
}

describe('PartnerDashboardPage', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    authState.roleKey = 'development_partner'
    authState.canView = true
  })

  it('renders funded-programme metrics and omits coordination families', async () => {
    get.mockResolvedValue(partnerPayload)

    renderPage(<PartnerDashboardPage />)

    expect(await screen.findByRole('heading', { name: 'Partner dashboard' })).toBeInTheDocument()
    expect(screen.getByText('Beneficiaries served')).toBeInTheDocument() // partner-specific label
    expect(screen.getByText('150')).toBeInTheDocument()
    expect(screen.getByText('Cash')).toBeInTheDocument()
    expect(screen.getByText('Dutse')).toBeInTheDocument()

    // Coordination (referrals/grievances) does not apply to a partner scope.
    expect(screen.queryByText('Referrals')).toBeNull()
    expect(screen.queryByText('Grievances')).toBeNull()
    // Read-only.
    expect(screen.queryByRole('button', { name: /edit|create|save|delete/i })).toBeNull()
  })

  it('blocks non-partner roles from the partner view', () => {
    authState.roleKey = 'mda_officer'

    renderPage(<PartnerDashboardPage />)

    expect(screen.getByText(/available to Development Partner users only/i)).toBeInTheDocument()
    expect(get).not.toHaveBeenCalled()
  })
})
