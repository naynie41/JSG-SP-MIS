import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen } from '@testing-library/react'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { MdaOverviewPage } from './MdaOverviewPage'
import { MdaProgrammeDetailPage } from './MdaProgrammeDetailPage'
import { programmeApi, activityApi } from '@/features/programmes/api'
import { dashboardApi } from '@/features/dashboard/api'

vi.mock('@/features/programmes/api', () => ({
  programmeApi: { list: vi.fn(), get: vi.fn(), catalog: vi.fn(), save: vi.fn(), archive: vi.fn(), budget: vi.fn() },
  activityApi: { list: vi.fn(), listForProgramme: vi.fn(), get: vi.fn(), save: vi.fn(), archive: vi.fn(), budget: vi.fn(), previewImport: vi.fn() },
}))
vi.mock('./api', () => ({
  mdaApi: { actionRequired: vi.fn().mockResolvedValue({ pending_referrals: 0, pending_service_requests: 0, mda_id: 'm1' }) },
}))
vi.mock('@/features/dashboard/api', () => ({
  dashboardApi: { get: vi.fn(), opsMetrics: vi.fn(), export: vi.fn() },
}))
vi.mock('@/features/notifications/api', () => ({
  notificationApi: {
    list: vi.fn().mockResolvedValue({ items: [] }),
    unreadCount: vi.fn().mockResolvedValue({ unread: 0 }),
    markRead: vi.fn(), markAllRead: vi.fn(), preferences: vi.fn(), updatePreferences: vi.fn(),
  },
}))

const perms = { value: ['dashboard.view', 'programme.view', 'activity.view'] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { name: 'Amina', role: { key: 'mda_admin', name: 'MDA Admin' }, mda: { id: 'm1', name: 'Ministry of Health' } },
    hasPermission: (p: string) => perms.value.includes(p),
  }),
}))

const getDashboard = dashboardApi.get as Mock
const getProgramme = programmeApi.get as Mock
const listActivities = activityApi.listForProgramme as Mock

function renderPage(ui: React.ReactNode, path = '/mda') {
  const client = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={client}>
      <ToastProvider>
        <MemoryRouter initialEntries={[path]}>
          <Routes>
            <Route path="/mda" element={ui} />
            <Route path="/mda/programmes/:id" element={ui} />
          </Routes>
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

/**
 * What the MDA console does when a request FAILS.
 *
 * It used to do the most dangerous thing available: render the failure as an empty
 * result. A dead `/dashboard` painted the green "Nothing overdue or unresolved in your
 * MDA" panel; a dead programme fetch showed "Loading programme" forever. An officer
 * clearing a queue would have been told their work was done when nothing had been
 * checked — the exact failure PRODUCT.md principle 5 exists to prevent.
 */
describe('MDA console — failure states', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms.value = ['dashboard.view', 'programme.view', 'activity.view']
  })

  it('never reports an MDA as all-clear when the check itself failed', async () => {
    getDashboard.mockRejectedValue(new Error('network'))
    renderPage(<MdaOverviewPage />)

    expect(await screen.findByText(/Could not load your alerts/i)).toBeInTheDocument()
    expect(screen.queryByText(/Nothing overdue or unresolved/i)).not.toBeInTheDocument()
  })

  it('says a failure is a failure, not an empty result', async () => {
    getDashboard.mockRejectedValue(new Error('network'))
    renderPage(<MdaOverviewPage />)

    expect(await screen.findByText(/not showing what your MDA has/i)).toBeInTheDocument()
    expect(screen.getByRole('button', { name: /try again/i })).toBeInTheDocument()
  })

  it('keeps the live work queue visible while the slow snapshot loads', async () => {
    // Action-required counts come from their own endpoint. The page used to hold them
    // behind the dashboard snapshot, so the one genuinely live thing on the Overview
    // waited on the one thing that can be fifteen minutes old.
    getDashboard.mockImplementation(() => new Promise(() => {}))
    renderPage(<MdaOverviewPage />)

    expect(await screen.findByRole('heading', { name: 'Action required' })).toBeInTheDocument()
  })

  it('does not spin forever when a programme cannot be loaded', async () => {
    // The guard was `isLoading || !programme`, so an error left isLoading false and
    // programme undefined — a permanent "Loading programme" with no way out.
    getProgramme.mockRejectedValue(new Error('404'))
    listActivities.mockResolvedValue({ items: [] })
    renderPage(<MdaProgrammeDetailPage />, '/mda/programmes/p1')

    expect(await screen.findByText(/Could not load this programme/i)).toBeInTheDocument()
    expect(screen.queryByText(/Loading programme/i)).not.toBeInTheDocument()
  })

  it('tells a refused user who can grant access', async () => {
    perms.value = []
    renderPage(<MdaOverviewPage />)

    expect(await screen.findByText(/do not have permission/i)).toBeInTheDocument()
    expect(screen.getByText(/administrator/i)).toBeInTheDocument()
  })
})
