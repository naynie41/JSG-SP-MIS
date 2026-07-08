import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter, useLocation } from 'react-router-dom'
import { NotificationBell } from './NotificationBell'
import { linkFor } from './links'
import { notificationApi } from './api'
import type { AppNotification } from './types'

vi.mock('./api', () => ({
  notificationApi: {
    unreadCount: vi.fn(),
    list: vi.fn(),
    markRead: vi.fn(),
    markAllRead: vi.fn(),
    preferences: vi.fn(),
    updatePreferences: vi.fn(),
  },
}))

const unreadCount = notificationApi.unreadCount as Mock
const list = notificationApi.list as Mock
const markRead = notificationApi.markRead as Mock
const markAllRead = notificationApi.markAllRead as Mock
const preferences = notificationApi.preferences as Mock

const referralNote: AppNotification = {
  id: 'n-1',
  type: 'referral.accepted',
  subject: 'Referral accepted',
  body: 'Your service request was accepted.',
  payload: null,
  related: { type: 'referral', id: 'r-9' },
  read_at: null,
  created_at: new Date().toISOString(),
}

function LocationDisplay() {
  const location = useLocation()
  return <div data-testid="location">{location.pathname}</div>
}

function renderBell(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <MemoryRouter initialEntries={['/']}>
        {ui}
        <LocationDisplay />
      </MemoryRouter>
    </QueryClientProvider>,
  )
}

describe('linkFor (deep links)', () => {
  it('maps related models to routes', () => {
    expect(linkFor(referralNote)).toBe('/referrals/r-9')
    expect(linkFor({ ...referralNote, related: { type: 'grievance', id: 'g-3' } })).toBe('/grievances/g-3')
    expect(linkFor({ ...referralNote, related: { type: 'service_request', id: 's-1' } })).toBe('/service-requests')
    expect(linkFor({ ...referralNote, related: null })).toBeNull()
  })
})

describe('NotificationBell', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    preferences.mockResolvedValue({ email_enabled: true })
    markRead.mockResolvedValue(referralNote)
    markAllRead.mockResolvedValue({ marked_read: 2 })
  })

  it('shows the unread count on the bell', async () => {
    unreadCount.mockResolvedValue({ unread: 2 })
    list.mockResolvedValue({ items: [] })

    renderBell(<NotificationBell />)

    expect(await screen.findByRole('button', { name: /2 unread/i }, { timeout: 3000 })).toBeInTheDocument()
  })

  it('opens the panel, deep-links a notification, and marks it read', async () => {
    unreadCount.mockResolvedValue({ unread: 1 })
    list.mockResolvedValue({ items: [referralNote] })
    const user = userEvent.setup()

    renderBell(<NotificationBell />)
    await user.click(await screen.findByRole('button', { name: /1 unread/i }, { timeout: 3000 }))

    // Panel lists the notification; clicking it navigates + marks read.
    await user.click(await screen.findByText('Referral accepted', {}, { timeout: 3000 }))
    await waitFor(() => expect(markRead).toHaveBeenCalledWith('n-1'))
    expect(screen.getByTestId('location').textContent).toBe('/referrals/r-9')
  })

  it('marks all notifications read', async () => {
    unreadCount.mockResolvedValue({ unread: 3 })
    list.mockResolvedValue({ items: [referralNote] })
    const user = userEvent.setup()

    renderBell(<NotificationBell />)
    await user.click(await screen.findByRole('button', { name: /3 unread/i }, { timeout: 3000 }))
    await user.click(await screen.findByRole('button', { name: /Mark all read/i }, { timeout: 3000 }))

    await waitFor(() => expect(markAllRead).toHaveBeenCalled())
  })
})
