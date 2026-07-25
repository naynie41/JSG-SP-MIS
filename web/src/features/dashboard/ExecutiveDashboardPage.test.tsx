import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { ExecutiveDashboardPage } from './ExecutiveDashboardPage'
import { dashboardApi } from './api'
import { makeExecutivePayload } from './executiveTestData'

vi.mock('./api', () => ({ dashboardApi: { get: vi.fn() } }))

const mockRole = { key: 'executive' }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ user: { role: mockRole }, hasPermission: () => true }),
}))

const get = dashboardApi.get as Mock

function renderPage(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <MemoryRouter>{ui}</MemoryRouter>
    </QueryClientProvider>,
  )
}

describe('ExecutiveDashboardPage', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    mockRole.key = 'executive'
  })

  it('renders the shared hero, headline and the tabbed suite', async () => {
    get.mockResolvedValue(makeExecutivePayload())

    renderPage(<ExecutiveDashboardPage />)

    expect(await screen.findByRole('heading', { name: /state of social protection in Jigawa/i })).toBeInTheDocument()
    expect(screen.getByText('Executive briefing')).toBeInTheDocument()
    expect(screen.getAllByText('8,420').length).toBeGreaterThan(0) // net-unique headline

    // Tabs; Overview is the default panel.
    expect(screen.getByRole('tab', { name: 'Overview' })).toBeInTheDocument()
    expect(screen.getByRole('tab', { name: 'Programmes' })).toBeInTheDocument()
    expect(screen.getByRole('tab', { name: 'Registry' })).toBeInTheDocument()
    expect(screen.getByText(/net-unique beneficiaries have been reached/i)).toBeInTheDocument()
  })

  it('switches to the Registry tab', async () => {
    get.mockResolvedValue(makeExecutivePayload())

    renderPage(<ExecutiveDashboardPage />)
    await screen.findByRole('tab', { name: 'Registry' })

    await userEvent.click(screen.getByRole('tab', { name: 'Registry' }))

    expect(screen.getByRole('heading', { name: 'Data quality' })).toBeInTheDocument()
    expect(screen.getByText('Verification rate')).toBeInTheDocument()
  })

  it('switches to the Programmes tab', async () => {
    get.mockResolvedValue(makeExecutivePayload())

    renderPage(<ExecutiveDashboardPage />)
    await screen.findByRole('tab', { name: 'Programmes' })

    await userEvent.click(screen.getByRole('tab', { name: 'Programmes' }))

    expect(screen.getByRole('heading', { name: 'Financials' })).toBeInTheDocument()
    expect(screen.getByRole('heading', { name: 'Comparison' })).toBeInTheDocument()
  })

  it('is strictly read-only — Refresh is the only mutfree control, no edit controls', async () => {
    get.mockResolvedValue(makeExecutivePayload())

    renderPage(<ExecutiveDashboardPage />)
    await screen.findAllByText('8,420')

    expect(screen.getByRole('button', { name: /refresh/i })).toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /edit|create|save|delete|add|new|remove|update/i })).toBeNull()
    expect(screen.queryByRole('textbox')).toBeNull()
  })

  it('blocks non-executive roles from the executive view', () => {
    mockRole.key = 'mda_officer'

    renderPage(<ExecutiveDashboardPage />)

    expect(screen.getByText(/available to Executive users only/i)).toBeInTheDocument()
    expect(screen.queryByText('8,420')).toBeNull()
    expect(get).not.toHaveBeenCalled()
  })
})
