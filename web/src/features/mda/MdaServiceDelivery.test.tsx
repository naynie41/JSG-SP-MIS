import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { MdaServiceDeliveryPage } from './MdaServiceDeliveryPage'
import { mdaApi } from './api'
import { benefitApi, flagApi } from '@/features/benefits/api'
import { referralApi } from '@/features/referrals/api'
import { beneficiaryApi, serviceRequestApi } from '@/features/registry/api'
import { programmeApi } from '@/features/programmes/api'
import { grievanceApi } from '@/features/grievances/api'

/*
 * Every api layer the module composes is mocked at the SOURCE module. That is what
 * makes these tests evidence of reuse: if the module ever grew its own endpoint or its
 * own copy of a table, these mocks would stop being called and the assertions below
 * would fail.
 */
const page = <T,>(items: T[]) => ({ items, pagination: { page: 1, per_page: 25, total: items.length, total_pages: 1 } })

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
  beneficiaryApi: { list: vi.fn(), get: vi.fn(), update: vi.fn(), remove: vi.fn(), lookup: vi.fn(), export: vi.fn() },
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
// The Grievances tab composes the real desk, which queries on mount. Unmocked it would
// issue a real request in jsdom and settle on the network's schedule, not React's.
vi.mock('@/features/grievances/api', () => ({
  grievanceApi: { list: vi.fn(), get: vi.fn(), create: vi.fn(), assign: vi.fn(), act: vi.fn() },
}))

const auth = { perms: [] as string[] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { name: 'Amina', role: { key: 'mda_admin', name: 'MDA Admin' }, mda: { id: 'm1', name: 'Ministry of Health' } },
    hasPermission: (p: string) => auth.perms.includes(p),
  }),
}))

const actionRequired = mdaApi.actionRequired as Mock
const listBenefits = benefitApi.list as Mock
const aggregate = benefitApi.aggregate as Mock
const listFlags = flagApi.list as Mock
const listReferrals = referralApi.list as Mock
const inbox = serviceRequestApi.inbox as Mock
const outbox = serviceRequestApi.outbox as Mock
const accept = serviceRequestApi.accept as Mock
const listBeneficiaries = beneficiaryApi.list as Mock
const ledger = benefitApi.ledger as Mock
const listGrievances = grievanceApi.list as Mock

const OFFICER = [
  'beneficiary.view', 'beneficiary.create', 'beneficiary-lookup.view',
  'programme.view', 'activity.view', 'enrollment.view',
  'benefit.view', 'benefit.create', 'benefit.approve',
  'referral.view', 'referral.create', 'referral.edit', 'dashboard.view',
  // The seeded MDA role holds these (RolesAndPermissionsSeeder) — a grievance is a
  // complaint about this MDA's own delivery.
  'grievance.view', 'grievance.create', 'grievance.edit',
]
/** Admin = Officer + the request-to-serve decision. */
const ADMIN = [...OFFICER, 'beneficiary.approve']

const INCOMING_REFERRAL = {
  id: 'r-in-1', beneficiary_id: 'b1111111-aaaa', from_mda_id: 'm2222222-bbbb', to_mda_id: 'm1',
  need: 'Health service', status: 'created', notes: null, sla_due_at: null, sla_breached_at: null,
  escalation_level: 0, created_at: null, updated_at: null,
}
const OUTGOING_REFERRAL = { ...INCOMING_REFERRAL, id: 'r-out-1', from_mda_id: 'm1', to_mda_id: 'm3333333-cccc', need: 'Nutrition support' }

const PENDING_REQUEST = {
  id: 'sr-1', beneficiary_id: 'b9999999-zzzz', beneficiary_name: 'Aisha Bello',
  from_mda_id: 'm2', to_mda_id: 'm1', from_mda: { id: 'm2', name: 'Ministry of Education' },
  owner_mda: { id: 'm1', name: 'Ministry of Health' }, activity_id: null,
  status: 'pending', reason: 'Enrolling her in a school feeding activity',
  decided_at: null, decision_reason: null, created_at: null,
}
const DECLINED_REQUEST = { ...PENDING_REQUEST, id: 'sr-2', status: 'declined', decision_reason: 'Already served', beneficiary_name: 'Musa Sani' }

const GRIEVANCE = {
  id: 'g1111111-aaaa', handling_mda_id: 'm1', beneficiary_id: null,
  category: 'service_quality' as const, channel: 'walk_in' as const,
  description: 'Poor treatment at the clinic', status: 'open' as const,
  assignee_user_id: null, resolution_notes: null, submitted_by: null,
  escalation_level: 0, sla_breached_at: null,
  timeline: { created_at: null, assigned_at: null, started_at: null, resolved_at: null, closed_at: null },
}

function renderAt(path = '/mda/service-delivery') {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter initialEntries={[path]}>
          <Routes>
            <Route path="/mda/service-delivery" element={<MdaServiceDeliveryPage />} />
            <Route path="/referrals/:id" element={<div>Referral detail</div>} />
          </Routes>
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('MDA console — Service Delivery', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    auth.perms = OFFICER
    actionRequired.mockResolvedValue({ pending_referrals: 3, pending_service_requests: 2, mda_id: 'm1' })
    listBenefits.mockResolvedValue(page([]))
    aggregate.mockResolvedValue({ group_by: 'programme', groups: [], totals: { benefit_count: 0, total_value: 0, total_quantity: '0' } })
    listFlags.mockResolvedValue([])
    listReferrals.mockImplementation((params: { direction?: string }) =>
      Promise.resolve(page(params?.direction === 'incoming' ? [INCOMING_REFERRAL] : [OUTGOING_REFERRAL])),
    )
    inbox.mockResolvedValue([PENDING_REQUEST, DECLINED_REQUEST])
    outbox.mockResolvedValue([])
    listBeneficiaries.mockResolvedValue(page([]))
    ledger.mockResolvedValue(page([]))
    programmeApi.list = vi.fn().mockResolvedValue(page([]))
    ;(programmeApi.catalog as Mock).mockResolvedValue(page([]))
    listGrievances.mockResolvedValue(page([GRIEVANCE]))
  })

  /* ----------------------------------------------------------- grievances */

  /**
   * The MDA role holds `grievance.view`/`create`/`edit`, but the "Grievance desk" rail
   * item was dropped when the consoles were restructured and the Coordination hub that
   * inherited it is not on the MDA rail — so the permission was granted and the feature
   * unreachable. A capability nobody can reach is the same defect as a permission
   * nothing can consume, just inverted.
   */
  it('gives the MDA a way to reach grievances', async () => {
    const user = userEvent.setup()
    renderAt()

    await user.click(await screen.findByRole('tab', { name: 'Grievances' }))

    expect(await screen.findByText('#g1111111')).toBeInTheDocument()
    await waitFor(() => expect(listGrievances).toHaveBeenCalled())
  })

  it('reuses the existing grievance desk rather than a second copy', async () => {
    // Composed, not rebuilt (CLAUDE.md Phase MDA): the desk keeps its own filters and
    // its log action, and reads the same endpoint the Coordination hub reads.
    const user = userEvent.setup()
    renderAt()

    await user.click(await screen.findByRole('tab', { name: 'Grievances' }))

    expect(await screen.findByRole('button', { name: /log grievance/i })).toBeInTheDocument()
  })

  it('keeps one h1 on the page when the desk is embedded', async () => {
    // The host page owns the heading; a second h1 would break the document outline.
    const user = userEvent.setup()
    renderAt()

    await user.click(await screen.findByRole('tab', { name: 'Grievances' }))
    await screen.findByText('#g1111111')

    expect(screen.getAllByRole('heading', { level: 1 })).toHaveLength(1)
  })

  it('is deep-linkable, like the other queues', async () => {
    renderAt('/mda/service-delivery?tab=grievances')

    expect(await screen.findByText('#g1111111')).toBeInTheDocument()
  })

  it('refuses the tab without the grievance permission', async () => {
    auth.perms = OFFICER.filter((p) => p !== 'grievance.view')
    const user = userEvent.setup()
    renderAt()

    await user.click(await screen.findByRole('tab', { name: 'Grievances' }))

    expect(await screen.findByText(/do not have permission to view grievances/i)).toBeInTheDocument()
    expect(listGrievances).not.toHaveBeenCalled()
  })

  /* --------------------------------------------------------------- structure */

  it('groups the three areas under one module', async () => {
    renderAt()
    expect(await screen.findByRole('heading', { name: 'Service Delivery' })).toBeInTheDocument()

    for (const label of ['Benefits', 'Referrals', 'Request to serve']) {
      expect(screen.getByRole('tab', { name: label })).toBeInTheDocument()
    }
  })

  /* ------------------------------------------------------------- deep-links */

  it('opens the tab the Overview counter deep-links to', async () => {
    renderAt('/mda/service-delivery?tab=service-requests')

    // Landing straight on the approvals queue rather than the default Benefits tab.
    expect(await screen.findByRole('tab', { name: 'Request to serve', selected: true })).toBeInTheDocument()
  })

  it('opens the referrals tab from its own deep-link', async () => {
    renderAt('/mda/service-delivery?tab=referrals')
    expect(await screen.findByRole('tab', { name: 'Referrals', selected: true })).toBeInTheDocument()
  })

  it('falls back to Benefits when the tab param is missing or unknown', async () => {
    renderAt('/mda/service-delivery?tab=not-a-tab')
    expect(await screen.findByRole('tab', { name: 'Benefits', selected: true })).toBeInTheDocument()
  })

  /* ------------------------------------------------------------- benefits */

  it('composes the §8.3 record flow and the three ledger views', async () => {
    const user = userEvent.setup()
    renderAt()
    await screen.findByRole('heading', { name: 'Service Delivery' })

    // The existing record-delivery form, not a new one — its own step scaffolding is
    // what proves which screen is mounted.
    expect(await screen.findByText('Beneficiary')).toBeInTheDocument()
    expect(screen.getByText('Programme & activity')).toBeInTheDocument()
    expect(screen.getByText(/SP-MIS does not move money/i)).toBeInTheDocument()

    await user.click(screen.getByRole('tab', { name: 'Benefits delivered' }))
    await waitFor(() => expect(listBenefits).toHaveBeenCalled())

    await user.click(screen.getByRole('tab', { name: 'Benefit ledger' }))
    await waitFor(() => expect(aggregate).toHaveBeenCalled())

    await user.click(screen.getByRole('tab', { name: 'Delivery verification' }))
    await waitFor(() => expect(listFlags).toHaveBeenCalled())
  })

  it('hides the record flow without benefit.create and verification without benefit.approve', async () => {
    auth.perms = OFFICER.filter((p) => p !== 'benefit.create' && p !== 'benefit.approve')
    renderAt()
    await screen.findByRole('heading', { name: 'Service Delivery' })

    expect(screen.queryByRole('tab', { name: 'Record delivery' })).not.toBeInTheDocument()
    expect(screen.queryByRole('tab', { name: 'Delivery verification' })).not.toBeInTheDocument()
    // What they can still do remains.
    expect(screen.getByRole('tab', { name: 'Benefits delivered' })).toBeInTheDocument()
  })

  it('refuses the benefits group without benefit.view', async () => {
    auth.perms = OFFICER.filter((p) => p !== 'benefit.view')
    renderAt()

    expect(await screen.findByText(/do not have permission to view benefits/i)).toBeInTheDocument()
    expect(listBenefits).not.toHaveBeenCalled()
  })

  it('shows a beneficiary’s cross-MDA intervention history through the existing panel', async () => {
    listBeneficiaries.mockResolvedValue(page([{ id: 'b1', full_name: 'Aisha Bello', lga: 'dutse', first_name: 'Aisha', last_name: 'Bello', middle_name: null, nin: null, bvn: null, phone: null, date_of_birth: null, gender: null, address: null, ward: null, status: 'active', owner_mda_id: 'm1', owner_mda: null, registration_source: 'excel', registration_date: null, import_batch_id: null, original_record_id: null, created_at: null, updated_at: null }]))
    const user = userEvent.setup()
    renderAt()
    await screen.findByRole('heading', { name: 'Service Delivery' })

    await user.click(screen.getByRole('tab', { name: 'Intervention history' }))
    await user.type(screen.getByLabelText(/find a beneficiary/i), 'Aisha')
    await user.click(await screen.findByRole('button', { name: /view history/i }))

    // The panel's own endpoint — the cross-MDA ledger for that person.
    await waitFor(() => expect(ledger).toHaveBeenCalledWith('b1'))
  })

  /* ------------------------------------------------------------- referrals */

  it('shows referrals in both directions from the same endpoint', async () => {
    renderAt('/mda/service-delivery?tab=referrals')
    await screen.findByRole('tab', { name: 'Referrals', selected: true })

    await waitFor(() => {
      expect(listReferrals).toHaveBeenCalledWith(expect.objectContaining({ direction: 'incoming' }))
      expect(listReferrals).toHaveBeenCalledWith(expect.objectContaining({ direction: 'outgoing' }))
    })
    expect(await screen.findByRole('region', { name: 'Referrals received' })).toBeInTheDocument()
    expect(screen.getByRole('region', { name: 'Referrals sent' })).toBeInTheDocument()
  })

  it('marks RECEIVED referrals as action-required and sent ones not', async () => {
    renderAt('/mda/service-delivery?tab=referrals')

    const received = await screen.findByRole('region', { name: 'Referrals received' })
    // The inbound queue carries the live count; the outbound record does not.
    expect(await within(received).findByText(/3 awaiting you/i)).toBeInTheDocument()

    const sent = screen.getByRole('region', { name: 'Referrals sent' })
    expect(within(sent).queryByText(/\d+ awaiting you/)).not.toBeInTheDocument()
  })

  it('says ownership does not transfer on a referral', async () => {
    renderAt('/mda/service-delivery?tab=referrals')
    expect(await screen.findByText(/never\s+transfers ownership/i)).toBeInTheDocument()
  })

  it('offers the raise-referral flow only with referral.create', async () => {
    renderAt('/mda/service-delivery?tab=referrals')
    expect(await screen.findByRole('button', { name: /raise referral/i })).toBeInTheDocument()

    auth.perms = OFFICER.filter((p) => p !== 'referral.create')
    renderAt('/mda/service-delivery?tab=referrals')
    await waitFor(() => expect(screen.queryByRole('button', { name: /raise referral/i })).not.toBeInTheDocument())
  })

  it('refuses referrals without referral.view', async () => {
    auth.perms = OFFICER.filter((p) => p !== 'referral.view')
    renderAt('/mda/service-delivery?tab=referrals')

    expect(await screen.findByText(/do not have permission to view referrals/i)).toBeInTheDocument()
    expect(listReferrals).not.toHaveBeenCalled()
  })

  /* ------------------------------------------------------ request-to-serve */

  it('surfaces the incoming approval queue as action-required, tied to the counter', async () => {
    renderAt('/mda/service-delivery?tab=service-requests')

    const queue = await screen.findByRole('region', { name: 'Approvals awaiting your MDA' })
    // Same number the Overview shows, from the same live endpoint.
    expect(await within(queue).findByText(/2 awaiting you/i)).toBeInTheDocument()
    expect(actionRequired).toHaveBeenCalled()
    await waitFor(() => expect(inbox).toHaveBeenCalled())
  })

  it('reconciles with the Overview: clearing the queue drops the emphasis', async () => {
    actionRequired.mockResolvedValue({ pending_referrals: 0, pending_service_requests: 0, mda_id: 'm1' })
    renderAt('/mda/service-delivery?tab=service-requests')

    const queue = await screen.findByRole('region', { name: 'Approvals awaiting your MDA' })
    await waitFor(() => expect(inbox).toHaveBeenCalled())
    // The COUNT chip is gone. Anchored on the digits, because the panel's own title
    // legitimately contains the words "awaiting your MDA" whether or not work is due.
    expect(within(queue).queryByText(/\d+ awaiting you/)).not.toBeInTheDocument()
  })

  it('defaults the inbox to Pending and can show Declined and full history', async () => {
    const user = userEvent.setup()
    renderAt('/mda/service-delivery?tab=service-requests')

    // Pending by default: the declined row is filtered out.
    expect(await screen.findByText('Aisha Bello')).toBeInTheDocument()
    expect(screen.queryByText('Musa Sani')).not.toBeInTheDocument()

    const views = screen.getAllByLabelText('View')
    await user.selectOptions(views[0]!, 'declined')
    expect(await screen.findByText('Musa Sani')).toBeInTheDocument()
    expect(screen.queryByText('Aisha Bello')).not.toBeInTheDocument()

    await user.selectOptions(views[0]!, '')
    expect(await screen.findByText('Aisha Bello')).toBeInTheDocument()
    expect(screen.getByText('Musa Sani')).toBeInTheDocument()
  })

  it('lets an ADMIN decide a request through the existing endpoint', async () => {
    auth.perms = ADMIN
    accept.mockResolvedValue({ ...PENDING_REQUEST, status: 'accepted' })
    const user = userEvent.setup()
    renderAt('/mda/service-delivery?tab=service-requests')

    await user.click(await screen.findByRole('button', { name: 'Accept' }))

    // Confirm inside the decision dialog — the row button and the dialog button share
    // a label, so the dialog has to be the scope.
    const dialog = await screen.findByRole('dialog')
    await user.click(within(dialog).getByRole('button', { name: 'Accept' }))

    await waitFor(() => expect(accept).toHaveBeenCalled())
  })

  it('does NOT offer the decision to an Officer — approving is an Admin permission', async () => {
    renderAt('/mda/service-delivery?tab=service-requests')
    await screen.findByText('Aisha Bello')

    expect(screen.queryByRole('button', { name: 'Accept' })).not.toBeInTheDocument()
    expect(screen.queryByRole('button', { name: 'Decline' })).not.toBeInTheDocument()
    // …and it says why, rather than silently omitting the control.
    expect(screen.getByText(/MDA Administrator permission/i)).toBeInTheDocument()
  })

  it('states that accepting grants read access without changing ownership', async () => {
    renderAt('/mda/service-delivery?tab=service-requests')
    expect(await screen.findByText(/ownership stays with you/i)).toBeInTheDocument()
  })

  /* ----------------------------------------------------- authorization gate */

  it('explains the serving-MDA gate without re-deriving it client-side', async () => {
    renderAt()
    await screen.findByRole('heading', { name: 'Service Delivery' })

    expect(
      screen.getByText(/accepted request-to-serve or an accepted referral is required/i),
    ).toBeInTheDocument()
    // The delivery value is programme data, never treasury expenditure.
    expect(screen.getByText(/not a treasury transaction/i)).toBeInTheDocument()
  })
})
