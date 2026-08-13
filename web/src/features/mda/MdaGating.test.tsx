import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { MdaServiceDeliveryPage } from './MdaServiceDeliveryPage'
import { MdaReportsPage } from './MdaReportsPage'
import { navSectionsFor } from '@/app/nav'
import { mdaApi } from './api'
import { beneficiaryApi, serviceRequestApi } from '@/features/registry/api'
import { reportsApi } from '@/features/reports/api'

/*
 * The Officer/Admin gating matrix for the one shared navigation.
 *
 * The console ships ONE rail for both roles, so correctness rests on two claims: the
 * rail is identical (Officer's permissions are a subset), and every Admin-only ACTION is
 * absent for an Officer. The permission sets below are the seeder's, mirrored by
 * MdaRoleMatrixTest which pins the same split server-side — the UI gate is a courtesy,
 * the route is the boundary.
 */

vi.mock('./api', () => ({ mdaApi: { actionRequired: vi.fn() } }))
vi.mock('@/features/benefits/api', () => ({
  benefitApi: { record: vi.fn(), verify: vi.fn(), list: vi.fn(), ledger: vi.fn(), aggregate: vi.fn() },
  benefitImportApi: { upload: vi.fn(), get: vi.fn(), confirm: vi.fn() },
  flagApi: { list: vi.fn(), review: vi.fn() },
}))
vi.mock('@/features/referrals/api', () => ({
  referralApi: { list: vi.fn(), get: vi.fn(), create: vi.fn(), act: vi.fn() },
}))
vi.mock('@/features/registry/api', () => ({
  beneficiaryApi: { list: vi.fn(), get: vi.fn(), update: vi.fn(), remove: vi.fn(), lookup: vi.fn(), search: vi.fn(), export: vi.fn() },
  serviceRequestApi: { create: vi.fn(), inbox: vi.fn(), outbox: vi.fn(), forActivity: vi.fn(), accept: vi.fn(), decline: vi.fn() },
  matchingApi: { config: vi.fn(), versions: vi.fn(), publish: vi.fn() },
  householdApi: { list: vi.fn(), get: vi.fn() },
  importApi: { list: vi.fn(), get: vi.fn(), upload: vi.fn(), confirm: vi.fn(), resolveRow: vi.fn() },
  documentApi: { list: vi.fn(), upload: vi.fn(), remove: vi.fn() },
}))
vi.mock('@/features/programmes/api', () => ({
  programmeApi: { list: vi.fn(), get: vi.fn(), catalog: vi.fn() },
  activityApi: { list: vi.fn(), listForProgramme: vi.fn(), get: vi.fn() },
  enrollmentApi: { list: vi.fn(), create: vi.fn(), update: vi.fn() },
}))
vi.mock('@/features/reports/api', () => ({
  reportsApi: {
    datasets: vi.fn(), catalogue: vi.fn(), preview: vi.fn(), exportAdHoc: vi.fn(), generate: vi.fn(),
    runs: vi.fn(), run: vi.fn(), download: vi.fn(), saveDefinition: vi.fn(),
    schedules: vi.fn(), createSchedule: vi.fn(), updateSchedule: vi.fn(), deleteSchedule: vi.fn(),
  },
}))
vi.mock('@/lib/api/exportList', () => ({ exportListFile: vi.fn() }))

const auth = { roleKey: 'mda_officer', perms: [] as string[] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { name: 'Amina', role: { key: auth.roleKey, name: 'MDA' }, mda: { id: 'm1', name: 'Ministry of Health' } },
    hasPermission: (p: string) => auth.perms.includes(p),
  }),
}))

/** MDA Officer, exactly as RolesAndPermissionsSeeder grants it. */
const OFFICER = [
  'mda.view', 'user.view',
  'beneficiary.view', 'beneficiary.create', 'beneficiary.edit',
  'beneficiary-lookup.view', 'household.view', 'household.create', 'household.edit',
  'programme.view', 'activity.view', 'activity.create', 'activity.edit',
  'enrollment.view', 'enrollment.create', 'enrollment.edit',
  'benefit.view', 'benefit.create', 'benefit.approve',
  'referral.view', 'referral.create', 'referral.edit',
  'grievance.view', 'grievance.create', 'grievance.edit',
  'graduation.view', 'graduation.edit', 'dashboard.view', 'reporting.view', 'reporting.export',
]
/** MDA Admin = Officer + exactly six. */
const ADMIN_ONLY = [
  'beneficiary.approve', 'beneficiary.export', 'beneficiary.access_request',
  'user.create', 'user.edit', 'role.view',
]
const ADMIN = [...OFFICER, ...ADMIN_ONLY]

const PENDING_REQUEST = {
  id: 'sr-1', beneficiary_id: 'b1', beneficiary_name: 'Aisha Bello',
  from_mda_id: 'm2', to_mda_id: 'm1', from_mda: { id: 'm2', name: 'Ministry of Education' },
  owner_mda: { id: 'm1', name: 'Ministry of Health' }, activity_id: null,
  status: 'pending', reason: 'Feeding programme', decided_at: null, decision_reason: null, created_at: null,
}

const page = <T,>(items: T[]) => ({ items, pagination: { page: 1, per_page: 25, total: items.length, total_pages: 1 } })

function renderAt(element: React.ReactElement, path = '/mda/x') {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter initialEntries={[path]}>
          <Routes>
            <Route path="/mda/x" element={element} />
          </Routes>
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('MDA console — Officer vs Admin gating', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    auth.roleKey = 'mda_officer'
    auth.perms = OFFICER
    ;(mdaApi.actionRequired as Mock).mockResolvedValue({ pending_referrals: 1, pending_service_requests: 1, mda_id: 'm1' })
    ;(serviceRequestApi.inbox as Mock).mockResolvedValue([PENDING_REQUEST])
    ;(serviceRequestApi.outbox as Mock).mockResolvedValue([])
    ;(beneficiaryApi.list as Mock).mockResolvedValue(page([]))
    ;(reportsApi.datasets as Mock).mockResolvedValue([])
    ;(reportsApi.runs as Mock).mockResolvedValue(page([]))
    ;(reportsApi.schedules as Mock).mockResolvedValue([])
  })

  /* --------------------------------------------------------------- one nav */

  it('gives both roles the identical six-module rail', () => {
    const officer = navSectionsFor('mda_officer', (p) => OFFICER.includes(p))
    const admin = navSectionsFor('mda_admin', (p) => ADMIN.includes(p))
    const labels = (s: ReturnType<typeof navSectionsFor>) => s.flatMap((x) => x.items.map((i) => i.label))

    const expected = ['Overview', 'Programmes', 'Beneficiaries', 'Service Delivery', 'Duplicate Resolution', 'Reports']
    expect(labels(officer)).toEqual(expected)
    expect(labels(admin)).toEqual(expected)
  })

  it('keeps Settings off the rail for both — it is a header affordance', () => {
    for (const [role, perms] of [['mda_officer', OFFICER], ['mda_admin', ADMIN]] as const) {
      const labels = navSectionsFor(role, (p) => (perms as string[]).includes(p)).flatMap((s) => s.items.map((i) => i.label))
      expect(labels).not.toContain('Settings')
    }
  })

  it('never grants an Officer something the Admin lacks', () => {
    // The premise of one shared rail: the Officer set is a strict subset.
    expect(OFFICER.filter((p) => !ADMIN.includes(p))).toEqual([])
    expect(ADMIN.filter((p) => !OFFICER.includes(p)).sort()).toEqual([...ADMIN_ONLY].sort())
  })

  /* ------------------------------------------ Service Delivery: the approval */

  it('hides the request-to-serve decision from an Officer', async () => {
    renderAt(<MdaServiceDeliveryPage />, '/mda/x?tab=service-requests')
    await screen.findByText('Aisha Bello')

    // beneficiary.approve is Admin-only. Absent, not disabled — and the server refuses
    // the route regardless (MdaRoleMatrixTest).
    expect(screen.queryByRole('button', { name: 'Accept' })).not.toBeInTheDocument()
    expect(screen.queryByRole('button', { name: 'Decline' })).not.toBeInTheDocument()
    expect(screen.getByText(/MDA Administrator permission/i)).toBeInTheDocument()

    // …but the Officer still SEES the queue, so the MDA's workload is visible to all.
    await waitFor(() => expect(serviceRequestApi.inbox).toHaveBeenCalled())
  })

  it('gives an Admin the decision on the same screen', async () => {
    auth.roleKey = 'mda_admin'
    auth.perms = ADMIN
    renderAt(<MdaServiceDeliveryPage />, '/mda/x?tab=service-requests')
    await screen.findByText('Aisha Bello')

    expect(screen.getByRole('button', { name: 'Accept' })).toBeInTheDocument()
    expect(screen.getByRole('button', { name: 'Decline' })).toBeInTheDocument()
  })

  it('gives BOTH roles the shared Service Delivery actions', async () => {
    // benefit.create / benefit.approve / referral.create are Officer permissions too, so
    // an Officer is not a read-only user — only the Admin-only six are withheld.
    renderAt(<MdaServiceDeliveryPage />, '/mda/x?tab=referrals')
    expect(await screen.findByRole('button', { name: /raise referral/i })).toBeInTheDocument()

    renderAt(<MdaServiceDeliveryPage />, '/mda/x')
    expect(await screen.findByRole('tab', { name: 'Record delivery' })).toBeInTheDocument()
    expect(screen.getByRole('tab', { name: 'Delivery verification' })).toBeInTheDocument()
  })

  /* ----------------------------------------------- Reports: the export gate */

  it('withholds the beneficiary export from an Officer but not aggregate export', async () => {
    const user = userEvent.setup()
    renderAt(<MdaReportsPage />)
    await screen.findByRole('tab', { name: 'Report types' })

    await user.click(screen.getByRole('tab', { name: 'Beneficiary export' }))
    expect(screen.getByText('Not permitted')).toBeInTheDocument()

    // Aggregate reporting rides reporting.export, which an Officer holds.
    await user.click(screen.getByRole('tab', { name: 'Build & export' }))
    expect(screen.queryByText(/needs the reporting export permission/i)).not.toBeInTheDocument()
  })

  it('gives an Admin the beneficiary export', async () => {
    auth.roleKey = 'mda_admin'
    auth.perms = ADMIN
    const user = userEvent.setup()
    renderAt(<MdaReportsPage />)
    await screen.findByRole('tab', { name: 'Report types' })

    await user.click(screen.getByRole('tab', { name: 'Beneficiary export' }))
    expect(screen.getByText('You may export')).toBeInTheDocument()
  })

  it('masks identifiers even for an Admin — reveal is never an MDA permission', async () => {
    // export.reveal_pii is in RolePermissionService::NEVER_ROLE_GRANTABLE, so no MDA role
    // can hold it. Asserted on the Admin because they are the only MDA role that can
    // export at all: if anyone were going to see unmasked identifiers, it would be them.
    auth.roleKey = 'mda_admin'
    auth.perms = ADMIN
    const user = userEvent.setup()
    renderAt(<MdaReportsPage />)
    await screen.findByRole('tab', { name: 'Report types' })

    await user.click(screen.getByRole('tab', { name: 'Beneficiary export' }))

    expect(screen.getByText('NIN/BVN masked')).toBeInTheDocument()
    expect(screen.queryByText('NIN/BVN revealed')).not.toBeInTheDocument()
  })

  /* ------------------------------------------------ withheld permissions are real */

  it('surfaces no user- or role-administration anywhere in the MDA console', async () => {
    // user.create/user.edit/role.view are Admin-only, but they belong to the System
    // Administrator console — an MDA Admin must not find them in this workspace either.
    auth.roleKey = 'mda_admin'
    auth.perms = ADMIN
    renderAt(<MdaReportsPage />)
    await screen.findByRole('tab', { name: 'Report types' })

    for (const label of [/add user/i, /invite user/i, /manage roles/i, /permissions/i]) {
      expect(screen.queryByRole('button', { name: label })).not.toBeInTheDocument()
      expect(screen.queryByRole('link', { name: label })).not.toBeInTheDocument()
    }
  })

  it('drops a module the user cannot reach rather than showing a dead link', () => {
    const limited = navSectionsFor('mda_officer', (p) => OFFICER.filter((x) => x !== 'reporting.view').includes(p))
    const labels = limited.flatMap((s) => s.items.map((i) => i.label))

    expect(labels).not.toContain('Reports')
    expect(labels).toContain('Overview')
  })
})
