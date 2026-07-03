import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { MemoryRouter } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { MatchingConfigPage } from './MatchingConfigPage'
import { matchingApi } from './api'
import type { MatchingConfig } from './types'

vi.mock('./api', () => ({
  matchingApi: { getConfig: vi.fn(), versions: vi.fn(), publish: vi.fn() },
}))

vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    hasPermission: () => true,
    hasAnyPermission: () => true,
    user: { mda: { id: 'm-1' } },
    status: 'authenticated',
  }),
}))

const getConfig = matchingApi.getConfig as Mock
const publish = matchingApi.publish as Mock

function makeConfig(): MatchingConfig {
  return {
    id: 'cfg-1',
    version: 1,
    is_active: true,
    deterministic_rules: [['nin'], ['bvn']],
    fuzzy_fields: [{ field: 'last_name', comparator: 'phonetic', weight: 0.25 }],
    review_threshold: 0.75,
    auto_accept_threshold: 0.92,
    exact_match_behaviour: 'confirm',
    description: 'Default',
    created_by: null,
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

describe('MatchingConfigPage', () => {
  beforeEach(() => vi.clearAllMocks())

  it('edits the review threshold and publishes a new confirmed version', async () => {
    getConfig.mockResolvedValue(makeConfig())
    publish.mockResolvedValue({ ...makeConfig(), id: 'cfg-2', version: 2, review_threshold: 0.8 })
    const user = userEvent.setup()
    renderPage(<MatchingConfigPage />)

    const review = await screen.findByLabelText('Review threshold')
    await user.clear(review)
    await user.type(review, '0.8')

    await user.click(screen.getByRole('button', { name: /publish new version/i }))
    // Confirmation dialog names the consequence.
    expect(await screen.findByText(/new active version/i)).toBeInTheDocument()
    await user.click(screen.getByRole('button', { name: 'Publish' }))

    await waitFor(() =>
      expect(publish).toHaveBeenCalledWith(
        expect.objectContaining({
          review_threshold: 0.8,
          exact_match_behaviour: 'confirm',
          auto_accept_threshold: 0.92,
        }),
      ),
    )
  })

  it('blocks publishing when auto-accept is below the review threshold', async () => {
    getConfig.mockResolvedValue(makeConfig())
    const user = userEvent.setup()
    renderPage(<MatchingConfigPage />)

    const autoAccept = await screen.findByLabelText('Auto-accept threshold')
    await user.clear(autoAccept)
    await user.type(autoAccept, '0.5')

    await user.click(screen.getByRole('button', { name: /publish new version/i }))

    expect(await screen.findByText(/at least the review threshold/i)).toBeInTheDocument()
    expect(publish).not.toHaveBeenCalled()
  })
})
