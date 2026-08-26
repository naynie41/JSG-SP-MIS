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
 * The gating matrix for the ONE MDA role (FR-UAM-01 — MDA Officer was merged into MDA
 * Admin).
 *
 * With the split gone, "which role are you" is no longer a question the console asks.
 * What it still asks is "which PERMISSIONS do you hold", because a System Administrator
 * can withhold any of them through the role-permission editor. So each capability below
 * is asserted twice: present with its permission, absent without it. Absent means the
 * affordance is not rendered, not merely disabled.
 *
 * The set is the seeder's, mirrored server-side by MdaRoleMatrixTest — the UI gate is a
 * courtesy, the route is the boundary.
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

const auth = { roleKey: 'mda_admin', perms: [] as string[] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { name: 'Amina', role: { key: auth.roleKey, name: 'MDA Admin' }, mda: { id: 'm1', name: 'Ministry of Health' } },
    hasPermission: (p: string) => auth.perms.includes(p),
  }),
}))

/** The MDA role, exactly as RolesAndPermissionsSeeder grants it after the merge. */
const MDA = [
  'mda.view', 'user.view',
  'beneficiary.view', 'beneficiary.create', 'beneficiary.edit',
  'beneficiary.approve', 'beneficiary.export', 'beneficiary.access_request',
  'beneficiary-lookup.view', 'household.view', 'household.edit',
  'programme.view', 'activity.view', 'activity.create', 'activity.edit',
  'enrollment.view', 'enrollment.create', 'enrollment.edit',
  'benefit.view', 'benefit.create', 'benefit.approve',
  'referral.view', 'referral.create', 'referral.edit',
  'grievance.view', 'grievance.create', 'grievance.edit',
  'graduation.view', 'graduation.edit', 'dashboard.view', 'reporting.view', 'reporting.export',
]

/** The three the merge deliberately did NOT carry over — account administration is central. */
const WITHHELD = ['user.create', 'user.edit', 'role.view']

/** The set minus one permission, for asserting a gate is on the permission and not the role. */
const without = (permission: string) => MDA.filter((p) => p !== permission)

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

describe('MDA console — permission gating for the single MDA role', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    auth.roleKey = 'mda_admin'
    auth.perms = MDA
    ;(mdaApi.actionRequired as Mock).mockResolvedValue({ pending_referrals: 1, pending_service_requests: 1, mda_id: 'm1' })
    ;(serviceRequestApi.inbox as Mock).mockResolvedValue([PENDING_REQUEST])
    ;(serviceRequestApi.outbox as Mock).mockResolvedValue([])
    ;(beneficiaryApi.list as Mock).mockResolvedValue(page([]))
    ;(reportsApi.datasets as Mock).mockResolvedValue([])
    ;(reportsApi.runs as Mock).mockResolvedValue(page([]))
    ;(reportsApi.schedules as Mock).mockResolvedValue([])
  })

  /* --------------------------------------------------------------- one nav */

  it('gives the MDA role the six-module rail', () => {
    const labels = navSectionsFor('mda_admin', (p) => MDA.includes(p)).flatMap((s) => s.items.map((i) => i.label))

    expect(labels).toEqual(['Overview', 'Programmes', 'Beneficiaries', 'Service Delivery', 'Duplicate Resolution', 'Reports'])
  })

  it('keeps Settings off the rail — it is a header affordance', () => {
    const labels = navSectionsFor('mda_admin', (p) => MDA.includes(p)).flatMap((s) => s.items.map((i) => i.label))

    expect(labels).not.toContain('Settings')
  })

  it('carries the merged capabilities and none of the withheld ones', () => {
    // What the merge moved to every MDA user…
    for (const permission of ['beneficiary.approve', 'beneficiary.export', 'beneficiary.access_request']) {
      expect(MDA).toContain(permission)
    }
    // …and what it pointedly did not. Account administration is System-Administrator work.
    for (const permission of WITHHELD) {
      expect(MDA).not.toContain(permission)
    }
  })

  /* ------------------------------------------ Service Delivery: the approval */

  it('gives the MDA role the request-to-serve decision', async () => {
    // Before the merge this was Admin-only; the seeded MDA role now holds it.
    renderAt(<MdaServiceDeliveryPage />, '/mda/x?tab=service-requests')
    await screen.findByText('Aisha Bello')

    expect(screen.getByRole('button', { name: 'Accept' })).toBeInTheDocument()
    expect(screen.getByRole('button', { name: 'Decline' })).toBeInTheDocument()
  })

  it('withdraws the decision when beneficiary.approve is withheld, not when the role changes', async () => {
    auth.perms = without('beneficiary.approve')
    renderAt(<MdaServiceDeliveryPage />, '/mda/x?tab=service-requests')
    await screen.findByText('Aisha Bello')

    // Absent, not disabled — and the server refuses the route regardless.
    expect(screen.queryByRole('button', { name: 'Accept' })).not.toBeInTheDocument()
    expect(screen.queryByRole('button', { name: 'Decline' })).not.toBeInTheDocument()
    expect(screen.getByText(/MDA Administrator permission/i)).toBeInTheDocument()

    // …but the queue is still VISIBLE, so the MDA's workload never disappears.
    await waitFor(() => expect(serviceRequestApi.inbox).toHaveBeenCalled())
  })

  it('gives the MDA role the shared Service Delivery actions', async () => {
    // benefit.create / benefit.approve / referral.create are seeded too, so this is a
    // working delivery account and not a read-only one.
    renderAt(<MdaServiceDeliveryPage />, '/mda/x?tab=referrals')
    expect(await screen.findByRole('button', { name: /raise referral/i })).toBeInTheDocument()

    renderAt(<MdaServiceDeliveryPage />, '/mda/x')
    expect(await screen.findByRole('tab', { name: 'Record delivery' })).toBeInTheDocument()
    expect(screen.getByRole('tab', { name: 'Delivery verification' })).toBeInTheDocument()
  })

  /* ----------------------------------------------- Reports: the export gate */

  it('gives the MDA role the beneficiary export', async () => {
    const user = userEvent.setup()
    renderAt(<MdaReportsPage />)
    await screen.findByRole('tab', { name: 'Dashboard' })

    await user.click(screen.getByRole('tab', { name: 'History' }))
    expect(screen.getByText('You may export')).toBeInTheDocument()
  })

  it('withholds the PII export without its permission but keeps aggregate export', async () => {
    auth.perms = without('beneficiary.export')
    const user = userEvent.setup()
    renderAt(<MdaReportsPage />)
    await screen.findByRole('tab', { name: 'Dashboard' })

    await user.click(screen.getByRole('tab', { name: 'History' }))
    expect(screen.getByText('Not permitted')).toBeInTheDocument()

    // The two gates are separate: aggregate reporting rides `reporting.export`, which is
    // untouched. Conflating them would either block reporting or open a PII path.
    await user.click(screen.getByRole('tab', { name: 'Build a report' }))
    await screen.findByLabelText(/what are you reporting on/i)
    expect(screen.queryByText(/needs the reporting export permission/i)).not.toBeInTheDocument()
    expect(screen.getByRole('button', { name: 'Export' })).toBeInTheDocument()
  })

  it('masks identifiers for the MDA role — reveal is never an MDA permission', async () => {
    // export.reveal_pii is in RolePermissionService::NEVER_ROLE_GRANTABLE, so no MDA role
    // can hold it. Asserted with the full seeded set: if unmasked identifiers were ever
    // going to leak, it would be to an account holding everything an MDA can hold.
    const user = userEvent.setup()
    renderAt(<MdaReportsPage />)
    await screen.findByRole('tab', { name: 'Dashboard' })

    await user.click(screen.getByRole('tab', { name: 'History' }))

    expect(screen.getByText('NIN/BVN masked')).toBeInTheDocument()
    expect(screen.queryByText('NIN/BVN revealed')).not.toBeInTheDocument()
  })

  /* ------------------------------------------------ withheld permissions are real */

  it('surfaces no user- or role-administration anywhere in the MDA console', async () => {
    // The point of the centralisation: account administration lives in the System
    // Administrator console, and the widest MDA account there is must not find it here.
    renderAt(<MdaReportsPage />)
    await screen.findByRole('tab', { name: 'Dashboard' })

    for (const label of [/add user/i, /invite user/i, /manage roles/i, /permissions/i]) {
      expect(screen.queryByRole('button', { name: label })).not.toBeInTheDocument()
      expect(screen.queryByRole('link', { name: label })).not.toBeInTheDocument()
    }
  })

  it('drops a module the user cannot reach rather than showing a dead link', () => {
    const limited = navSectionsFor('mda_admin', (p) => without('reporting.view').includes(p))
    const labels = limited.flatMap((s) => s.items.map((i) => i.label))

    expect(labels).not.toContain('Reports')
    expect(labels).toContain('Overview')
  })
})
