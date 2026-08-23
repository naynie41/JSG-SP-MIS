import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { NotificationPreferencesPage } from './NotificationPreferencesPage'
import { notificationApi } from './api'

vi.mock('./api', () => ({
  notificationApi: { preferences: vi.fn(), updatePreferences: vi.fn() },
}))

const preferences = notificationApi.preferences as Mock
const updatePreferences = notificationApi.updatePreferences as Mock

function renderPage() {
  const client = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={client}>
      <NotificationPreferencesPage />
    </QueryClientProvider>,
  )
}

/**
 * The unsubscribe destination (FR-NOT-02).
 *
 * Every notification email has to tell the reader how to stop receiving them, which
 * means the setting needs an ADDRESS — the bell toggle alone cannot be linked to from
 * an inbox. This page is what `notifications.preferences_path` resolves to.
 */
describe('NotificationPreferencesPage', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    preferences.mockResolvedValue({ email_enabled: true })
    updatePreferences.mockResolvedValue({ email_enabled: false })
  })

  it('turns email notifications off from a linkable page', async () => {
    renderPage()

    const toggle = await screen.findByRole('switch', { name: /email notifications/i })
    await waitFor(() => expect(toggle).toBeChecked())

    await userEvent.click(toggle)

    expect(updatePreferences).toHaveBeenCalledWith(false)
  })

  it('does not offer to switch off in-app notifications', async () => {
    // The bell is the record of what a user was told. Letting an approver silence it
    // would let them make a request-to-serve invisible to themselves, leaving a
    // beneficiary unserved with nothing to show why.
    renderPage()

    await screen.findByRole('switch', { name: /email notifications/i })

    expect(screen.getAllByRole('switch')).toHaveLength(1)
    expect(screen.getByText(/cannot be switched off/i)).toBeInTheDocument()
  })

  it('says what the emails contain, and what they do not', async () => {
    renderPage()

    expect(await screen.findByText(/never contain beneficiary details/i)).toBeInTheDocument()
  })
})
