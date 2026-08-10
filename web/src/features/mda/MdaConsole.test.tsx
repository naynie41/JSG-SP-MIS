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

const auth = { roleKey: 'mda_officer', perms: [] as string[], mda: 'Ministry of Health' }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { name: 'Amina', role: { key: auth.roleKey, name: 'MDA Officer' }, mda: { id: 'm1', name: auth.mda } },
    hasPermission: (p: string) => auth.perms.includes(p),
  }),
}))

const actionRequired = mdaApi.actionRequired as Mock
const getDashboard = dashboardApi.get as Mock
const listNotifications = notificationApi.list as Mock

/** The permissions an MDA Officer actually holds (RolesAndPermissionsSeeder). */
const OFFICER = [
  'mda.view', 'user.view', 'beneficiary.view', 'beneficiary.create', 'beneficiary.edit',
  'beneficiary-lookup.view', 'household.view', 'household.create', 'household.edit',
  'programme.view', 'activity.view', 'activity.create', 'activity.edit',
  'enrollment.view', 'enrollment.create', 'enrollment.edit',
  'benefit.view', 'benefit.create', 'benefit.approve',
  'referral.view', 'referral.create', 'referral.edit',
  'grievance.view', 'grievance.create', 'grievance.edit',
  'graduation.view', 'graduation.edit', 'dashboard.view', 'reporting.view', 'reporting.export',
]
/** Admin = Officer + six. */
const ADMIN = [...OFFICER, 'user.create', 'user.edit', 'role.view', 'beneficiary.approve', 'beneficiary.export', 'beneficiary.access_request']

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
    auth.roleKey = 'mda_officer'
    auth.perms = OFFICER
    getDashboard.mockResolvedValue({ metrics: METRICS, scope: { kind: 'mda', label: 'Ministry of Health' }, tier: 'operational', live: false, filters: {}, filter_options: {} })
    actionRequired.mockResolvedValue({ pending_referrals: 3, pending_service_requests: 2, mda_id: 'm1' })
    listNotifications.mockResolvedValue({ items: [], pagination: { page: 1, per_page: 20, total: 0, total_pages: 1 } })
  })

  /* --------------------------------------------------------------- access */

  it('is available to both MDA roles and closed to everyone else', async () => {
    renderAt()
    expect(await screen.findByRole('heading', { name: 'Overview' })).toBeInTheDocument()

    auth.roleKey = 'mda_admin'
    renderAt()
    expect(await screen.findAllByRole('heading', { name: 'Overview' })).not.toHaveLength(0)

    auth.roleKey = 'executive'
    renderAt()
    expect(await screen.findByText(/available to MDA Officers and MDA Administrators/i)).toBeInTheDocument()
  })

  /* ------------------------------------------------------------------ nav */

  it('gives Officer and Admin the SAME six-module rail', () => {
    const officer = navSectionsFor('mda_officer', (p) => OFFICER.includes(p))
    const admin = navSectionsFor('mda_admin', (p) => ADMIN.includes(p))

    const labels = (s: ReturnType<typeof navSectionsFor>) => s.flatMap((x) => x.items.map((i) => i.label))
    const expected = ['Overview', 'Programmes', 'Beneficiaries', 'Service Delivery', 'Duplicate Resolution', 'Reports']

    expect(labels(officer)).toEqual(expected)
    expect(labels(admin)).toEqual(expected)
    // Settings is reached from the gear, never the rail.
    expect(labels(officer)).not.toContain('Settings')
  })

  it('drops a module the user cannot reach, without branching by role', () => {
    // Strip reporting.view — the Reports module must disappear for that user only.
    const limited = navSectionsFor('mda_officer', (p) => OFFICER.filter((x) => x !== 'reporting.view').includes(p))
    const labels = limited.flatMap((s) => s.items.map((i) => i.label))

    expect(labels).not.toContain('Reports')
    expect(labels).toContain('Overview')
  })

  it('does not show the MDA rail to other roles', () => {
    const exec = navSectionsFor('executive', () => true)
    expect(exec.flatMap((s) => s.items.map((i) => i.label))).not.toContain('Duplicate Resolution')

    // ...and the generic operator rail is no longer served to MDA roles.
    const officer = navSectionsFor('mda_officer', (p) => OFFICER.includes(p))
    expect(officer.flatMap((s) => s.items.map((i) => i.to))).not.toContain('/registry')
  })

  /* ------------------------------------------------------------------ KPIs */

  it('shows MDA KPIs from the Phase 6 aggregation', async () => {
    renderAt()
    await screen.findByRole('heading', { name: 'Action required' })

    expect(screen.getByText('4')).toBeInTheDocument()          // programmes participated in
    expect(screen.getByText('11')).toBeInTheDocument()         // activities total
    expect(screen.getByText('7 active')).toBeInTheDocument()   // activities active
    expect(screen.getByText('1,840')).toBeInTheDocument()      // registered beneficiaries
    expect(screen.getByText('962')).toBeInTheDocument()        // benefits delivered
    expect(getDashboard).toHaveBeenCalled()
  })

  it('names the signed-in user’s MDA so scope is legible', async () => {
    renderAt()
    expect(await screen.findByText(/Ministry of Health/)).toBeInTheDocument()
  })

  /* -------------------------------------------------- action-required counters */

  it('shows both action-required counters from the LIVE endpoint, not the snapshot', async () => {
    renderAt()
    await screen.findByRole('heading', { name: 'Action required' })

    const referrals = screen.getByRole('link', { name: /Referrals awaiting your response: 3/i })
    const approvals = screen.getByRole('link', { name: /Request-to-serve approvals: 2/i })

    expect(referrals).toBeInTheDocument()
    expect(approvals).toBeInTheDocument()
    expect(actionRequired).toHaveBeenCalled()
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
    actionRequired.mockResolvedValue({ pending_referrals: 0, pending_service_requests: 0, mda_id: 'm1' })
    renderAt()
    await screen.findByRole('heading', { name: 'Action required' })

    expect(screen.getAllByText('Nothing waiting on your MDA')).toHaveLength(2)
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
    auth.perms = OFFICER.filter((p) => p !== 'benefit.create' && p !== 'activity.create')
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
    await screen.findByRole('heading', { name: 'Action required' })

    expect(screen.getByText(/3 referrals past their SLA/i)).toBeInTheDocument()
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
    await screen.findByRole('heading', { name: 'Action required' })

    expect(screen.getByText(/Nothing overdue or unresolved in your MDA/i)).toBeInTheDocument()
  })

  /* ------------------------------------------------------------- permission */

  it('refuses the Overview without dashboard.view', async () => {
    auth.perms = OFFICER.filter((p) => p !== 'dashboard.view')
    renderAt()

    expect(await screen.findByText(/do not have permission to view the MDA dashboard/i)).toBeInTheDocument()
    expect(getDashboard).not.toHaveBeenCalled()
    expect(actionRequired).not.toHaveBeenCalled()
  })
})
