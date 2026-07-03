import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { MemoryRouter } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { DuplicateSearchPage } from './DuplicateSearchPage'
import { beneficiaryApi, serveRequestApi } from './api'
import type { SearchCandidate } from './types'

vi.mock('./api', () => ({
  beneficiaryApi: { search: vi.fn() },
  serveRequestApi: { raise: vi.fn() },
}))

vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    hasPermission: () => true,
    hasAnyPermission: () => true,
    user: { mda: { id: 'm-1' } },
    status: 'authenticated',
  }),
}))

const search = beneficiaryApi.search as Mock
const raise = serveRequestApi.raise as Mock

const candidate: SearchCandidate = {
  band: 'probable',
  score: 0.87,
  matched_fields: ['last_name', 'date_of_birth'],
  beneficiary: {
    id: 'ben-5',
    full_name: 'Musa Bello',
    owner_mda: { id: 'm-2', name: 'Agriculture' },
    registration_source: 'csv',
    registration_date: '2024-06-01',
    lga: 'gumel',
    ward: 'Ward 2',
    status: 'active',
    programmes: [],
    benefits: { summary: null, items: [] },
  },
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

describe('DuplicateSearchPage', () => {
  beforeEach(() => vi.clearAllMocks())

  it('searches and shows ranked reveal candidates', async () => {
    search.mockResolvedValue([candidate])
    const user = userEvent.setup()
    renderPage(<DuplicateSearchPage />)

    await user.type(screen.getByLabelText('Last name'), 'Bello')
    await user.click(screen.getByRole('button', { name: /^search$/i }))

    expect(await screen.findByText('Musa Bello')).toBeInTheDocument()
    expect(screen.getByText('Agriculture')).toBeInTheDocument()
    expect(screen.getByText('Probable')).toBeInTheDocument()
    await waitFor(() => expect(search).toHaveBeenCalledWith({ last_name: 'Bello' }))
  })

  it('raises a request-to-serve from a result', async () => {
    search.mockResolvedValue([candidate])
    raise.mockResolvedValue({ id: 'sr-1', status: 'pending' })
    const user = userEvent.setup()
    renderPage(<DuplicateSearchPage />)

    await user.type(screen.getByLabelText('Last name'), 'Bello')
    await user.click(screen.getByRole('button', { name: /^search$/i }))

    await user.click(await screen.findByRole('button', { name: /request to serve/i }))
    // Dialog opens; send the request.
    await user.click(screen.getByRole('button', { name: /send request/i }))

    await waitFor(() => expect(raise).toHaveBeenCalledWith({ beneficiary_id: 'ben-5', reason: undefined }))
  })
})
