import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen } from '@testing-library/react'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { GraduationHistoryCard } from './GraduationHistoryCard'
import { graduationApi } from './api'
import type { GraduationEvent } from './types'

vi.mock('./api', () => ({ graduationApi: { history: vi.fn() } }))

let permissions = ['graduation.view']
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: (p: string) => permissions.includes(p) }),
}))

const historyApi = graduationApi.history as Mock

function event(overrides: Partial<GraduationEvent> = {}): GraduationEvent {
  return {
    id: 'g-1',
    enrollment_id: 'e-1',
    beneficiary_id: 'b-1',
    household_id: null,
    programme_id: 'p-1',
    activity_id: null,
    mda_id: 'm-1',
    criteria_id: 'c-1',
    reason: 'Income sustained above the threshold.',
    decided_by: 'u-1',
    decided_by_name: 'Amina Bello',
    criteria_name: 'Two years of support',
    subject: { type: 'beneficiary', name: 'Ada Okoye' },
    graduated_at: '2026-06-01T09:00:00Z',
    ...overrides,
  }
}

function renderCard(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(<QueryClientProvider client={qc}>{ui}</QueryClientProvider>)
}

/**
 * The graduation record (FR-GRD-02). It exists to be reviewed by the people accountable
 * for the judgements in it, so the load-bearing property is that each entry says WHO
 * graduated, on what basis, and who decided.
 */
describe('GraduationHistoryCard', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    permissions = ['graduation.view']
  })

  it('names who graduated, on which criteria, and who decided', async () => {
    historyApi.mockResolvedValue({ items: [event()], pagination: undefined })

    renderCard(<GraduationHistoryCard programmeId="p-1" />)

    expect(await screen.findByText('Ada Okoye')).toBeInTheDocument()
    expect(screen.getByText(/Two years of support/)).toBeInTheDocument()
    expect(screen.getByText(/decided by Amina Bello/)).toBeInTheDocument()
    expect(screen.getByText(/Income sustained above the threshold/)).toBeInTheDocument()
  })

  it('distinguishes a household graduation from an individual one', async () => {
    historyApi.mockResolvedValue({
      items: [event({ id: 'g-2', household_id: 'h-1', subject: { type: 'household', name: 'Musa Danjuma' } })],
      pagination: undefined,
    })

    renderCard(<GraduationHistoryCard programmeId="p-1" />)

    expect(await screen.findByText('Household')).toBeInTheDocument()
  })

  it('says the record is permanent, because that is the guarantee it carries', async () => {
    // Graduating changes the enrolment status; it never removes the person or their
    // ledger. An officer deciding whether to graduate someone needs to know that.
    historyApi.mockResolvedValue({ items: [], pagination: undefined })

    renderCard(<GraduationHistoryCard programmeId="p-1" />)

    expect(await screen.findByText(/never removes the person or their delivery history/i)).toBeInTheDocument()
  })

  it('says plainly when no one has graduated yet', async () => {
    historyApi.mockResolvedValue({ items: [], pagination: undefined })

    renderCard(<GraduationHistoryCard programmeId="p-1" />)

    expect(await screen.findByText(/no one has graduated from this programme yet/i)).toBeInTheDocument()
  })

  it('renders nothing without the graduation view permission', () => {
    permissions = []

    const { container } = renderCard(<GraduationHistoryCard programmeId="p-1" />)

    expect(container).toBeEmptyDOMElement()
    expect(historyApi).not.toHaveBeenCalled()
  })
})
