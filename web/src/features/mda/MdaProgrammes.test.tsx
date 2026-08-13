import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { MdaProgrammesPage } from './MdaProgrammesPage'
import { MdaProgrammeDetailPage } from './MdaProgrammeDetailPage'
import { MdaOverviewPage } from './MdaOverviewPage'
import { programmeApi, activityApi } from '@/features/programmes/api'

vi.mock('@/features/programmes/api', () => ({
  programmeApi: { list: vi.fn(), get: vi.fn(), catalog: vi.fn(), save: vi.fn(), archive: vi.fn(), budget: vi.fn() },
  activityApi: {
    list: vi.fn(),
    listForProgramme: vi.fn(),
    get: vi.fn(),
    save: vi.fn(),
    archive: vi.fn(),
    budget: vi.fn(),
    previewImport: vi.fn(),
  },
}))
// The Overview is one of the two entry points into the wizard.
vi.mock('./api', () => ({ mdaApi: { actionRequired: vi.fn().mockResolvedValue({ pending_referrals: 0, pending_service_requests: 0, mda_id: 'm1' }) } }))
vi.mock('@/features/dashboard/api', () => ({ dashboardApi: { get: vi.fn().mockResolvedValue({ metrics: {}, scope: {}, tier: 'operational', live: false, filters: {}, filter_options: {} }), opsMetrics: vi.fn(), export: vi.fn() } }))
vi.mock('@/features/notifications/api', () => ({
  notificationApi: { list: vi.fn().mockResolvedValue({ items: [] }), unreadCount: vi.fn().mockResolvedValue({ unread: 0 }), markRead: vi.fn(), markAllRead: vi.fn(), preferences: vi.fn(), updatePreferences: vi.fn() },
}))

const perms = { value: [] as string[] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { name: 'Amina', role: { key: 'mda_admin', name: 'MDA Admin' }, mda: { id: 'm1', name: 'Ministry of Health' } },
    hasPermission: (p: string) => perms.value.includes(p),
  }),
}))

const listProgrammes = programmeApi.list as Mock
const getProgramme = programmeApi.get as Mock
const listForProgramme = activityApi.listForProgramme as Mock

const PROGRAMME = {
  id: 'p1',
  name: 'Conditional Cash Transfer',
  objective: 'Reduce extreme poverty',
  type: 'individual',
  benefit_category: 'cash_transfer',
  eligibility: [],
  enforce_eligibility: false,
  status: 'active',
  activities_count: 2,
  created_by: null,
  created_at: null,
  updated_at: null,
}

const ACTIVITIES = [
  {
    id: 'a1', programme_id: 'p1', owner_mda_id: 'm1', involves_beneficiaries: true,
    name: 'CCT Dutse Q1', description: null, target_beneficiaries: 500,
    lga: 'dutse', ward: null, location_description: null, schedule: null,
    starts_on: '2026-01-01', ends_on: '2026-03-31',
    budget_amount: 250000000, funding_source: 'state_budget', status: 'active',
    created_by: null, created_at: null, updated_at: null,
  },
  {
    id: 'a2', programme_id: 'p1', owner_mda_id: 'm1', involves_beneficiaries: false,
    name: 'CCT staff training', description: null, target_beneficiaries: null,
    lga: 'hadejia', ward: null, location_description: null, schedule: null,
    starts_on: null, ends_on: null,
    budget_amount: 50000000, funding_source: 'donor', status: 'planned',
    created_by: null, created_at: null, updated_at: null,
  },
]

function renderAt(path: string) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter initialEntries={[path]}>
          <Routes>
            <Route path="/mda" element={<MdaOverviewPage />} />
            <Route path="/mda/programmes" element={<MdaProgrammesPage />} />
            <Route path="/mda/programmes/:id" element={<MdaProgrammeDetailPage />} />
            <Route path="/activities/:id" element={<div>Activity detail</div>} />
          </Routes>
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('MDA console — Programmes', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms.value = ['programme.view', 'activity.view', 'activity.create', 'dashboard.view', 'beneficiary.create']
    listProgrammes.mockResolvedValue({ items: [PROGRAMME], pagination: { page: 1, per_page: 100, total: 1, total_pages: 1 } })
    getProgramme.mockResolvedValue(PROGRAMME)
    listForProgramme.mockResolvedValue({ items: ACTIVITIES, pagination: { page: 1, per_page: 25, total: 2, total_pages: 1 } })
  })

  /* ----------------------------------------------------------- participation */

  it('asks the SERVER for participated programmes, not the whole catalogue', async () => {
    renderAt('/mda/programmes')
    await screen.findByText('Conditional Cash Transfer')

    // Filtering client-side on activities_count would drop matches past page one.
    expect(listProgrammes).toHaveBeenCalledWith(expect.objectContaining({ participating: true }))
  })

  it('shows the catalogue facts and this MDA’s activity count', async () => {
    renderAt('/mda/programmes')

    // Await the DATA, not the table — the table renders skeleton rows while loading.
    await screen.findByText('Conditional Cash Transfer')
    const table = screen.getByRole('table', { name: /programmes your MDA participates in/i })
    expect(within(table).getByText('Cash transfer')).toBeInTheDocument()
    expect(within(table).getByText('2')).toBeInTheDocument()
  })

  /* -------------------------------------------------- catalogue is read-only */

  it('offers no way to create or edit a catalogue programme', async () => {
    renderAt('/mda/programmes')
    await screen.findByText('Conditional Cash Transfer')

    // §10: MDAs never create or edit programmes. The control must be absent, not
    // merely disabled.
    expect(screen.queryByRole('button', { name: /create programme/i })).not.toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /edit programme/i })).not.toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /archive/i })).not.toBeInTheDocument()
  })

  it('refuses the module without programme.view', async () => {
    perms.value = ['activity.view']
    renderAt('/mda/programmes')

    expect(await screen.findByText(/do not have permission to view programmes/i)).toBeInTheDocument()
    expect(listProgrammes).not.toHaveBeenCalled()
  })

  /* ------------------------------------------------------- inside a programme */

  it('lists this MDA’s activities with timeline, budget, target, funding and status', async () => {
    renderAt('/mda/programmes/p1')

    await screen.findByText('CCT Dutse Q1')
    expect(listForProgramme).toHaveBeenCalledWith('p1')

    const row = screen.getByRole('row', { name: /CCT Dutse Q1/i })

    // Locale-independent, and it pins the timezone bug: a date-only 2026-01-01 parsed
    // as UTC then rendered locally shows 31 Dec 2025 for anyone behind UTC.
    const timelineCell = within(row).getByText(/2026/)
    expect(timelineCell.textContent).toMatch(/Jan/)
    expect(timelineCell.textContent).toMatch(/Mar/)
    expect(timelineCell.textContent).not.toMatch(/Dec|2025/)

    expect(within(row).getByText('500')).toBeInTheDocument()
    expect(within(row).getByText('₦2,500,000.00')).toBeInTheDocument()
    expect(within(row).getByText('State budget')).toBeInTheDocument()
    expect(within(row).getByText('active')).toBeInTheDocument()
    expect(within(row).getByText('Dutse')).toBeInTheDocument()
  })

  it('shows an em dash, not a zero, where an activity has no beneficiaries', async () => {
    renderAt('/mda/programmes/p1')
    await screen.findByText('CCT staff training')

    // The staff-training activity has involves_beneficiaries=false, so it has no
    // target by design — reporting 0 would read as "nobody reached".
    const row = screen.getByRole('row', { name: /CCT staff training/i })
    expect(within(row).getByText('—')).toBeInTheDocument()
    expect(within(row).getByText('Not scheduled')).toBeInTheDocument()
  })

  it('rolls up only the activities it actually lists', async () => {
    renderAt('/mda/programmes/p1')
    await screen.findByText('CCT Dutse Q1')

    // Scoped to the roll-up region: the same figures also appear in the table below,
    // and the point of this test is that the summary agrees with those rows.
    const summary = screen.getByRole('region', { name: /your delivery under this programme/i })
    expect(within(summary).getByText('2')).toBeInTheDocument()               // your activities
    expect(within(summary).getByText('500')).toBeInTheDocument()             // target total
    expect(within(summary).getByText('₦3,000,000.00')).toBeInTheDocument()   // 2.5m + 0.5m
    expect(within(summary).getByText(/across 1 activity with beneficiaries/i)).toBeInTheDocument()
  })

  /* ------------------------------------------------------- View Activity */

  it('opens the existing activity detail', async () => {
    const user = userEvent.setup()
    renderAt('/mda/programmes/p1')
    await screen.findByText('CCT Dutse Q1')

    await user.click(screen.getAllByRole('button', { name: 'View' })[0]!)
    expect(await screen.findByText('Activity detail')).toBeInTheDocument()
  })

  /* ------------------------------------------------ ONE wizard, two entry points */

  it('creates an activity in-context through the EXISTING wizard, programme pinned', async () => {
    const user = userEvent.setup()
    renderAt('/mda/programmes/p1')
    await screen.findByText('CCT Dutse Q1')

    await user.click(screen.getByRole('button', { name: /create activity/i }))

    // The wizard's own conditional question (FR-REG-11) is present, which is how we
    // know this is the real wizard and not a local form.
    const dialog = await screen.findByRole('dialog')
    expect(within(dialog).getByLabelText(/does this activity involve beneficiaries/i)).toBeInTheDocument()

    // Pinned: the catalogue picker is shown (so the officer can see WHICH programme)
    // but locked, so an in-context create can never land under the wrong one.
    expect(within(dialog).getByLabelText(/^programme$/i)).toBeDisabled()
  })

  it('is the SAME wizard component, not a re-implementation', async () => {
    const user = userEvent.setup()
    renderAt('/mda/programmes/p1')
    await screen.findByText('CCT Dutse Q1')
    await user.click(screen.getByRole('button', { name: /create activity/i }))

    const dialog = await screen.findByRole('dialog')
    // The wizard's distinctive affordances: the conditional branch (FR-REG-11), its
    // own field set, and the catalogue picker it locks when pinned. A bespoke
    // in-context form would have none of these.
    expect(within(dialog).getByLabelText(/does this activity involve beneficiaries/i)).toBeInTheDocument()
    expect(within(dialog).getByLabelText('Name')).toBeInTheDocument()
    expect(within(dialog).getByLabelText(/^programme$/i)).toBeDisabled()
  })

  it('hides Create Activity without activity.create', async () => {
    perms.value = ['programme.view', 'activity.view']
    renderAt('/mda/programmes/p1')
    await screen.findByText('CCT Dutse Q1')

    expect(screen.queryByRole('button', { name: /create activity/i })).not.toBeInTheDocument()
  })

  it('does not fetch activities without activity.view', async () => {
    perms.value = ['programme.view']
    renderAt('/mda/programmes/p1')

    // The programme itself still loads; its activity list is never requested.
    await screen.findByRole('heading', { name: 'Conditional Cash Transfer' })
    await waitFor(() => expect(getProgramme).toHaveBeenCalled())
    expect(listForProgramme).not.toHaveBeenCalled()
  })
})
