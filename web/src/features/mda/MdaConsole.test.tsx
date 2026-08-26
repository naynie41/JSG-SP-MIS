import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { MdaLayout } from './MdaLayout'
import { MdaOverviewPage } from './MdaOverviewPage'
import { MdaProgrammesPage } from './MdaProgrammesPage'
import { MdaBeneficiariesPage } from './MdaBeneficiariesPage'
import { MdaReportsPage } from './MdaReportsPage'
import { MdaServiceDeliveryPage } from './MdaServiceDeliveryPage'
import { mdaApi } from './api'
import { dashboardApi } from '@/features/dashboard/api'
import { notificationApi } from '@/features/notifications/api'
import { navSectionsFor } from '@/app/nav'

vi.mock('./api', () => ({ mdaApi: { actionRequired: vi.fn() } }))
vi.mock('@/features/dashboard/api', () => ({
  dashboardApi: { get: vi.fn(), opsMetrics: vi.fn(), export: vi.fn() },
}))
/**
 * Service Delivery is a real composed module now, not a scaffold, so landing on it
 * mounts the benefit/referral/service-request screens and their queries. Mocked here
 * so this file stays a test of the shell and the Overview rather than making network
 * calls into jsdom.
 */
vi.mock('@/features/benefits/api', () => ({
  benefitApi: {
    record: vi.fn(), verify: vi.fn(),
    list: vi.fn().mockResolvedValue({ items: [], pagination: { page: 1, per_page: 25, total: 0, total_pages: 1 } }),
    ledger: vi.fn(), aggregate: vi.fn().mockResolvedValue({ group_by: 'programme', groups: [], totals: { benefit_count: 0, total_value: 0, total_quantity: '0' } }),
  },
  benefitImportApi: { upload: vi.fn(), get: vi.fn(), confirm: vi.fn() },
  flagApi: { list: vi.fn().mockResolvedValue([]), review: vi.fn() },
}))
vi.mock('@/features/referrals/api', () => ({
  referralApi: {
    list: vi.fn().mockResolvedValue({ items: [], pagination: { page: 1, per_page: 25, total: 0, total_pages: 1 } }),
    get: vi.fn(), create: vi.fn(), act: vi.fn(),
  },
}))
vi.mock('@/features/registry/api', () => ({
  beneficiaryApi: { list: vi.fn().mockResolvedValue({ items: [], pagination: { page: 1, per_page: 25, total: 0, total_pages: 1 } }), get: vi.fn(), update: vi.fn(), remove: vi.fn(), lookup: vi.fn(), export: vi.fn() },
  serviceRequestApi: { create: vi.fn(), inbox: vi.fn().mockResolvedValue([]), outbox: vi.fn().mockResolvedValue([]), forActivity: vi.fn(), accept: vi.fn(), decline: vi.fn() },
  matchingApi: { config: vi.fn(), versions: vi.fn(), publish: vi.fn() },
  householdApi: { list: vi.fn(), get: vi.fn() },
  importApi: { list: vi.fn(), get: vi.fn(), upload: vi.fn(), confirm: vi.fn(), resolveRow: vi.fn() },
  documentApi: { list: vi.fn(), upload: vi.fn(), remove: vi.fn() },
}))
vi.mock('@/features/programmes/api', () => ({
  programmeApi: { list: vi.fn().mockResolvedValue({ items: [], pagination: { page: 1, per_page: 25, total: 0, total_pages: 1 } }), get: vi.fn(), catalog: vi.fn().mockResolvedValue({ items: [], pagination: { page: 1, per_page: 25, total: 0, total_pages: 1 } }) },
  activityApi: { list: vi.fn().mockResolvedValue({ items: [], pagination: { page: 1, per_page: 25, total: 0, total_pages: 1 } }), listForProgramme: vi.fn(), get: vi.fn() },
  enrollmentApi: { list: vi.fn(), create: vi.fn(), update: vi.fn() },
}))
vi.mock('@/features/notifications/api', () => ({
  notificationApi: {
    list: vi.fn(),
    unreadCount: vi.fn().mockResolvedValue({ unread: 0 }),
    markRead: vi.fn(),
    markAllRead: vi.fn(),
    preferences: vi.fn(),
    updatePreferences: vi.fn(),
  },
}))

const auth = { roleKey: 'mda_admin', perms: [] as string[], mda: 'Ministry of Health' }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { name: 'Amina', role: { key: auth.roleKey, name: 'MDA Admin' }, mda: { id: 'm1', name: auth.mda } },
    hasPermission: (p: string) => auth.perms.includes(p),
  }),
}))

const actionRequired = mdaApi.actionRequired as Mock
const getDashboard = dashboardApi.get as Mock
const listNotifications = notificationApi.list as Mock

/** The permissions the single MDA role actually holds (RolesAndPermissionsSeeder). */
const MDA = [
  'mda.view', 'user.view', 'beneficiary.view', 'beneficiary.create', 'beneficiary.edit',
  'beneficiary.approve', 'beneficiary.export', 'beneficiary.access_request',
  'beneficiary-lookup.view', 'household.view', 'household.edit',
  'programme.view', 'activity.view', 'activity.create', 'activity.edit',
  'enrollment.view', 'enrollment.create', 'enrollment.edit',
  'benefit.view', 'benefit.create', 'benefit.approve',
  'referral.view', 'referral.create', 'referral.edit',
  'grievance.view', 'grievance.create', 'grievance.edit',
  'graduation.view', 'graduation.edit', 'dashboard.view', 'reporting.view', 'reporting.export',
]

const METRICS = {
  registry: { beneficiaries: { total: 1840, by_status: {}, by_source: {}, by_lga: {} }, households: null },
  programmes: { total: 4, active: 4, activities_total: 11, activities_active: 7 },
  duplicates: { matches_surfaced: 30, resolved_new: 10, resolved_served: 8, resolved_skipped: 5 },
  benefits: {
    disbursed: { benefit_count: 962, total_value: 0, total_quantity: '0' },
    budget: { allocated: 0, utilized_value: 0, utilized_quantity: '0', benefit_count: 0, remaining: 0, utilization_rate: null },
    by_type: [],
  },
  referrals: { total: 12, by_status: {}, completed: 5, completion_rate: 0.4, overdue: 3, avg_completion_days: 4 },
  grievances: { total: 6, by_status: {}, sla_breaches: 2, avg_resolution_days: 3 },
  coverage: [],
}

function renderAt(path = '/mda') {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      {/* Service Delivery composes screens whose mutations raise toasts, so the
          provider the real app mounts at the root has to be here too. */}
      <ToastProvider>
        <MemoryRouter initialEntries={[path]}>
          <Routes>
          <Route path="/mda" element={<MdaLayout />}>
            <Route index element={<MdaOverviewPage />} />
            <Route path="programmes" element={<MdaProgrammesPage />} />
            <Route path="beneficiaries" element={<MdaBeneficiariesPage />} />
            <Route path="service-delivery" element={<MdaServiceDeliveryPage />} />
            <Route path="reports" element={<MdaReportsPage />} />
          </Route>
          {/* Destinations the Quick Actions launch into. */}
          <Route path="/activities" element={<div>Activities module</div>} />
          <Route path="/imports" element={<div>Import Center</div>} />
          <Route path="/benefits/record" element={<div>Record benefit</div>} />
          <Route path="/referrals" element={<div>Referrals module</div>} />
          <Route path="/duplicate-search" element={<div>Duplicate search</div>} />
          </Routes>
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('MDA console — shell + Overview', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    auth.roleKey = 'mda_admin'
    auth.perms = MDA
    getDashboard.mockResolvedValue({ metrics: METRICS, scope: { kind: 'mda', label: 'Ministry of Health' }, tier: 'operational', live: false, filters: {}, filter_options: {} })
    actionRequired.mockResolvedValue({ pending_referrals: 3, pending_service_requests: 2, open_grievances: 0, breached_grievances: 0, mda_id: 'm1' })
    listNotifications.mockResolvedValue({ items: [], pagination: { page: 1, per_page: 20, total: 0, total_pages: 1 } })
  })

  /* --------------------------------------------------------------- access */

  it('is available to the MDA role and closed to everyone else', async () => {
    const mda = renderAt()
    expect(await screen.findByRole('heading', { name: 'Overview' })).toBeInTheDocument()
    // Unmounted between roles: leaving a previous render mounted would let one role's
    // refusal notice satisfy the next role's assertion, and the test would pass vacuously.
    mda.unmount()

    // `mda_officer` is included deliberately — it is not a role any more, so it must get
    // the same refusal as any outsider rather than the workspace it used to open.
    for (const outsider of ['executive', 'mda_officer']) {
      auth.roleKey = outsider
      const view = renderAt()

      expect(await screen.findByText(/available to MDA Administrators/i)).toBeInTheDocument()
      expect(screen.queryByRole('heading', { name: 'Overview' })).not.toBeInTheDocument()
      view.unmount()
    }
  })

  /* ------------------------------------------------------------------ nav */

  it('gives the MDA role the six-module rail', () => {
    const labels = navSectionsFor('mda_admin', (p) => MDA.includes(p)).flatMap((s) => s.items.map((i) => i.label))

    expect(labels).toEqual(['Overview', 'Programmes', 'Beneficiaries', 'Service Delivery', 'Duplicate Resolution', 'Reports'])
    // Settings is reached from the gear, never the rail.
    expect(labels).not.toContain('Settings')
  })

  it('drops a module the user cannot reach, without branching by role', () => {
    // Strip reporting.view — the Reports module must disappear for that user only.
    const limited = navSectionsFor('mda_admin', (p) => MDA.filter((x) => x !== 'reporting.view').includes(p))
    const labels = limited.flatMap((s) => s.items.map((i) => i.label))

    expect(labels).not.toContain('Reports')
    expect(labels).toContain('Overview')
  })

  it('does not show the MDA rail to other roles', () => {
    const exec = navSectionsFor('executive', () => true)
    expect(exec.flatMap((s) => s.items.map((i) => i.label))).not.toContain('Duplicate Resolution')

    // ...and the generic operator rail is no longer served to MDA roles.
    const mda = navSectionsFor('mda_admin', (p) => MDA.includes(p))
    expect(mda.flatMap((s) => s.items.map((i) => i.to))).not.toContain('/registry')
  })

  /* ------------------------------------------------------------------ KPIs */

  it('summarises the reporting dashboard from the Phase 6 aggregation', async () => {
    // The hand-rolled KPI band was replaced by the shared ReportingSummaryCard, which
    // reads the SAME dashboard query the full Reports dashboard renders — so the two
    // can no longer disagree. It reports ACTIVE programmes/activities (what is running
    // now) rather than lifetime totals.
    renderAt()
    // Anchored on a dashboard figure: the Action-required heading now paints before the
    // dashboard resolves, so it no longer implies the snapshot has arrived.
    expect(await screen.findByText('1,840')).toBeInTheDocument() // net-unique beneficiaries

    expect(screen.getByText('7')).toBeInTheDocument()          // active activities
    expect(screen.getByText('962')).toBeInTheDocument()        // benefit deliveries
    expect(getDashboard).toHaveBeenCalled()
  })

  it('expands from the Overview summary into the full reports dashboard', async () => {
    renderAt()

    expect(await screen.findByRole('link', { name: /open full dashboard/i })).toHaveAttribute(
      'href',
      '/mda/reports',
    )
  })

  it('names the signed-in user’s MDA so scope is legible', async () => {
    renderAt()
    expect(await screen.findByText(/Ministry of Health/)).toBeInTheDocument()
  })

  /* -------------------------------------------------- action-required counters */

  it('shows both action-required counters from the LIVE endpoint, not the snapshot', async () => {
    renderAt()

    const referrals = await screen.findByRole('link', { name: /Referrals awaiting your response: 3/i })
    const approvals = screen.getByRole('link', { name: /Request-to-serve approvals: 2/i })

    expect(referrals).toBeInTheDocument()
    expect(approvals).toBeInTheDocument()
    expect(actionRequired).toHaveBeenCalled()
  })

  it('surfaces grievances on the Overview instead of burying them behind a tab', async () => {
    // They were two clicks deep, behind a tab that opens on Benefits — the one queue in
    // the console with a running SLA, and nothing said so.
    actionRequired.mockResolvedValue({
      pending_referrals: 0, pending_service_requests: 0,
      open_grievances: 4, breached_grievances: 2, mda_id: 'm1',
    })
    renderAt()

    const tile = await screen.findByRole('link', { name: /Grievances on your desk: 4/i })
    expect(tile).toHaveAttribute('href', '/mda/service-delivery?tab=grievances')
    // Says what is LATE, not only how much there is.
    expect(screen.getByText('2 past their SLA.')).toBeInTheDocument()
  })
  it('deep-links both counters into Service Delivery', async () => {
    renderAt()
    await screen.findByRole('heading', { name: 'Action required' })

    expect(screen.getByRole('link', { name: /Referrals awaiting your response/i })).toHaveAttribute(
      'href',
      '/mda/service-delivery?tab=referrals',
    )
    expect(screen.getByRole('link', { name: /Request-to-serve approvals/i })).toHaveAttribute(
      'href',
      '/mda/service-delivery?tab=service-requests',
    )
  })

  it('the deep-link actually lands on Service Delivery', async () => {
    const user = userEvent.setup()
    renderAt()
    await screen.findByRole('heading', { name: 'Action required' })

    await user.click(screen.getByRole('link', { name: /Request-to-serve approvals/i }))
    expect(await screen.findByRole('heading', { name: 'Service Delivery' })).toBeInTheDocument()
  })

  it('reads quietly when the queue is clear rather than shouting a zero', async () => {
    actionRequired.mockResolvedValue({ pending_referrals: 0, pending_service_requests: 0, open_grievances: 0, breached_grievances: 0, mda_id: 'm1' })
    renderAt()

    // Three inbound queues now: referrals, request-to-serve, grievances.
    expect(await screen.findAllByText('Nothing waiting on your MDA')).toHaveLength(3)
  })

  /* --------------------------------------------------------- quick actions */

  it('offers the six launchers and routes into the EXISTING flow', async () => {
    const user = userEvent.setup()
    renderAt()
    await screen.findByRole('heading', { name: 'Action required' })

    for (const label of ['Create Activity', 'Upload Beneficiaries', 'Record Benefit Delivery', 'Create Referral', 'Request to Serve', 'Generate Report']) {
      expect(screen.getByRole('button', { name: label })).toBeInTheDocument()
    }

    await user.click(screen.getByRole('button', { name: 'Create Activity' }))
    expect(await screen.findByText('Activities module')).toBeInTheDocument()
  })

  it('filters quick actions by permission', async () => {
    auth.perms = MDA.filter((p) => p !== 'benefit.create' && p !== 'activity.create')
    renderAt()
    await screen.findByRole('heading', { name: 'Action required' })

    expect(screen.queryByRole('button', { name: 'Record Benefit Delivery' })).not.toBeInTheDocument()
    expect(screen.queryByRole('button', { name: 'Create Activity' })).not.toBeInTheDocument()
    // The ones they can still do remain.
    expect(screen.getByRole('button', { name: 'Upload Beneficiaries' })).toBeInTheDocument()
  })

  /* ---------------------------------------------------------------- alerts */

  it('derives alerts from real scoped figures', async () => {
    renderAt()

    expect(await screen.findByText(/3 referrals past their SLA/i)).toBeInTheDocument()
    expect(screen.getByText(/2 grievances past their SLA/i)).toBeInTheDocument()
    // 30 surfaced − (10+8+5) resolved = 7 outstanding.
    expect(screen.getByText(/7 possible duplicates awaiting review/i)).toBeInTheDocument()
  })

  it('says so plainly when there is nothing wrong', async () => {
    getDashboard.mockResolvedValue({
      metrics: { ...METRICS, referrals: { ...METRICS.referrals, overdue: 0 }, grievances: { ...METRICS.grievances, sla_breaches: 0 }, duplicates: { matches_surfaced: 5, resolved_new: 5, resolved_served: 0, resolved_skipped: 0 } },
      scope: { kind: 'mda', label: 'Ministry of Health' }, tier: 'operational', live: false, filters: {}, filter_options: {},
    })
    renderAt()

    expect(await screen.findByText(/Nothing overdue or unresolved in your MDA/i)).toBeInTheDocument()
  })

  /* ------------------------------------------------------------- permission */

  it('refuses the Overview without dashboard.view', async () => {
    auth.perms = MDA.filter((p) => p !== 'dashboard.view')
    renderAt()

    expect(await screen.findByText(/do not have permission to view the MDA dashboard/i)).toBeInTheDocument()
    expect(getDashboard).not.toHaveBeenCalled()
    expect(actionRequired).not.toHaveBeenCalled()
  })
})
