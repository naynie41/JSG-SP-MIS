import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { HouseholdDetailPage } from './HouseholdDetailPage'
import { beneficiaryApi, householdApi } from './api'

vi.mock('./api', () => ({
  householdApi: {
    get: vi.fn(),
    addMember: vi.fn(),
    moveMember: vi.fn(),
    removeMember: vi.fn(),
    designateHead: vi.fn(),
  },
  beneficiaryApi: { list: vi.fn() },
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

const get = householdApi.get as Mock
const moveMember = householdApi.moveMember as Mock
const list = beneficiaryApi.list as Mock

function makeHousehold() {
  return {
    id: 'h-1',
    owner_mda_id: 'm-1',
    head_beneficiary_id: 'b-1',
    registration_source: 'manual',
    registration_date: '2026-07-01',
    address: null,
    lga: 'dutse',
    ward: 'Ward 1',
    members: [
      { id: 'mem-1', household_id: 'h-1', beneficiary_id: 'b-1', beneficiary_name: 'Amina Sadiq', role_in_household: 'head', joined_at: '2026-07-01T00:00:00Z', left_at: null, is_open: true },
    ],
    history: [
      { id: 'mem-1', household_id: 'h-1', beneficiary_id: 'b-1', beneficiary_name: 'Amina Sadiq', role_in_household: 'head', joined_at: '2026-07-01T00:00:00Z', left_at: null, is_open: true },
      { id: 'mem-0', household_id: 'h-1', beneficiary_id: 'b-7', beneficiary_name: 'Departed Person', role_in_household: 'child', joined_at: '2026-06-01T00:00:00Z', left_at: '2026-06-20T00:00:00Z', is_open: false },
    ],
    created_at: null,
    updated_at: null,
  }
}

function renderPage(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter initialEntries={['/households/h-1']}>
          <Routes>
            <Route path="/households/:id" element={ui} />
          </Routes>
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('HouseholdDetailPage', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    list.mockResolvedValue({ items: [{ id: 'b-9', full_name: 'Musa Bello', owner_mda_id: 'm-1' }], pagination: undefined })
  })

  it('renders current members and the retained membership history', async () => {
    get.mockResolvedValue(makeHousehold())

    renderPage(<HouseholdDetailPage />)

    expect(await screen.findByText('Departed Person')).toBeInTheDocument()
    // A closed history row is shown as such.
    expect(screen.getByText('Closed')).toBeInTheDocument()
    expect(screen.getByText('Open')).toBeInTheDocument()
  })

  it('moves a beneficiary into the household (closing their prior membership)', async () => {
    get.mockResolvedValue(makeHousehold())
    moveMember.mockResolvedValue({ id: 'mem-2' })

    const user = userEvent.setup()
    renderPage(<HouseholdDetailPage />)

    await screen.findAllByText('Amina Sadiq')
    await user.click(screen.getByRole('button', { name: /move member in/i }))

    const dialog = await screen.findByRole('dialog')
    await user.selectOptions(within(dialog).getByLabelText('Beneficiary'), 'b-9')
    await user.click(within(dialog).getByRole('button', { name: /^move member$/i }))

    await waitFor(() =>
      expect(moveMember).toHaveBeenCalledWith('h-1', expect.objectContaining({ beneficiary_id: 'b-9' })),
    )
  })
})
