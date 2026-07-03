import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { MemoryRouter } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { ServeRequestsPage } from './ServeRequestsPage'
import { serveRequestApi } from './api'
import type { ServeRequest } from './types'

vi.mock('./api', () => ({
  serveRequestApi: { list: vi.fn(), accept: vi.fn(), decline: vi.fn() },
}))

vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    hasPermission: () => true,
    hasAnyPermission: () => true,
    user: { mda: { id: 'm-1' } },
    status: 'authenticated',
  }),
}))

const list = serveRequestApi.list as Mock
const accept = serveRequestApi.accept as Mock

const incoming: ServeRequest = {
  id: 'sr-1',
  beneficiary_id: 'ben-1234abcd',
  from_mda_id: 'm-2',
  to_mda_id: 'm-1', // owned by the current user's MDA → decidable
  status: 'pending',
  reason: 'Enrolling into cash transfer',
  decided_at: null,
  decision_reason: null,
  created_at: null,
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

describe('ServeRequestsPage', () => {
  beforeEach(() => vi.clearAllMocks())

  it('lets the owner MDA accept an incoming pending request', async () => {
    list.mockResolvedValue([incoming])
    accept.mockResolvedValue({ ...incoming, status: 'accepted' })
    const user = userEvent.setup()
    renderPage(<ServeRequestsPage />)

    expect(await screen.findByText('Incoming')).toBeInTheDocument()
    await user.click(screen.getByRole('button', { name: /accept/i }))
    // Dialog confirms; ownership-unchanged copy is shown.
    expect(await screen.findByText(/ownership is unchanged/i)).toBeInTheDocument()
    const dialog = screen.getByRole('dialog')
    await user.click(within(dialog).getByRole('button', { name: 'Accept' }))

    await waitFor(() => expect(accept).toHaveBeenCalledWith('sr-1', undefined))
  })
})
