import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { AdminAssignmentsPanel } from './AdminAssignmentsPanel'
import { assignmentApi, programmeApi } from '@/features/programmes/api'
import { mdaApi } from '@/features/mdas/api'
import type { Programme } from '@/features/programmes/types'
import { ApiError } from '@/types/api'

vi.mock('@/features/programmes/api', () => ({
  programmeApi: { list: vi.fn() },
  assignmentApi: { forMda: vi.fn(), set: vi.fn(), remove: vi.fn() },
}))

vi.mock('@/features/mdas/api', () => ({
  mdaApi: { list: vi.fn() },
}))

const perms = { value: ['programme.edit', 'programme.view'] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { role: { key: 'system_administrator' }, name: 'Admin' },
    hasPermission: (p: string) => perms.value.includes(p),
  }),
}))

const listProgrammes = programmeApi.list as Mock
const listMdas = mdaApi.list as Mock
const forMda = assignmentApi.forMda as Mock
const setAssignments = assignmentApi.set as Mock

const programme = (over: Partial<Programme> & { id: string; name: string }): Programme => ({
  objective: null,
  type: 'individual',
  benefit_category: 'cash',
  eligibility: [],
  enforce_eligibility: false,
  status: 'active',
  approval_status: 'approved',
  is_central: true,
  created_by: null,
  created_at: null,
  updated_at: null,
  ...over,
})

const HEALTH_INSURANCE = programme({ id: 'p1', name: 'Health Insurance' })
const GOAT_SCHEME = programme({ id: 'p2', name: 'Goat Revolving Scheme' })
const SCHOOL_FEEDING = programme({ id: 'p3', name: 'School Feeding' })

function renderPanel() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <AdminAssignmentsPanel />
      </ToastProvider>
    </QueryClientProvider>,
  )
}

/**
 * Choose an organisation. The options arrive from a query, so they have to be on the
 * page before they can be selected — selecting into an empty select is a test that
 * races the fetch, not a test of the panel.
 */
async function choose(user: ReturnType<typeof userEvent.setup>, id: string, name: string | RegExp) {
  await screen.findByRole('option', { name })
  await user.selectOptions(screen.getByLabelText('Organisation'), id)
}

/**
 * Choose Health, then wait for its assignments to land.
 *
 * Waits on the ticked box rather than the "1 of 3 assigned" line: that line puts the
 * figure in its own element, so a text matcher spanning both is brittle in a way that
 * says nothing about the panel. A checked box is also the thing every test below reads.
 */
async function chooseHealth(user: ReturnType<typeof userEvent.setup>) {
  await choose(user, 'm1', 'Ministry of Health')
  await waitFor(() => expect(screen.getByRole('checkbox', { name: 'Health Insurance' })).toBeChecked())
}

describe('Programme assignment (admin console)', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms.value = ['programme.edit', 'programme.view']
    listProgrammes.mockResolvedValue({
      items: [HEALTH_INSURANCE, GOAT_SCHEME, SCHOOL_FEEDING],
      pagination: { page: 1, per_page: 100, total: 3, total_pages: 1 },
    })
    listMdas.mockResolvedValue([
      { id: 'm1', name: 'Ministry of Health', type: 'ministry', is_government: true, status: 'active' },
      { id: 'm2', name: 'Save the Children', type: 'partner', is_government: false, status: 'active' },
    ])
    forMda.mockResolvedValue({
      mda: { id: 'm1', name: 'Ministry of Health' },
      programmes: [
        { ...HEALTH_INSURANCE, activities_count: 2, assignment: { source: 'inventory', assigned_by: null, assigned_at: null } },
      ],
    })
    setAssignments.mockResolvedValue({
      mda: { id: 'm1', name: 'Ministry of Health' },
      assigned: ['p3'],
      unassigned: [],
      unchanged: 1,
      total: 2,
    })
  })

  it('asks for nothing until an organisation is chosen', async () => {
    renderPanel()

    expect(await screen.findByText(/choose an organisation/i)).toBeInTheDocument()
    expect(forMda).not.toHaveBeenCalled()
  })

  it('shows what the organisation is assigned, out of the whole catalogue', async () => {
    const user = userEvent.setup()
    renderPanel()
    await chooseHealth(user)

    expect(forMda).toHaveBeenCalledWith('m1')
    // The catalogue is the full list; the ticks are the subset this MDA offers.
    expect(screen.getByRole('checkbox', { name: 'Health Insurance' })).toBeChecked()
    expect(screen.getByRole('checkbox', { name: 'Goat Revolving Scheme' })).not.toBeChecked()
    expect(screen.getByRole('checkbox', { name: 'School Feeding' })).not.toBeChecked()
  })

  it('says where an assignment came from, and what is running under it', async () => {
    const user = userEvent.setup()
    renderPanel()
    await chooseHealth(user)

    // Provenance matters at the moment someone is about to untick a row: an inventory
    // row is a weaker claim than a decision, and 2 activities will refuse the removal.
    expect(screen.getByText('From inventory')).toBeInTheDocument()
    expect(screen.getByText('2 activities')).toBeInTheDocument()
  })

  it('sends the WHOLE list, not a diff', async () => {
    const user = userEvent.setup()
    renderPanel()
    await chooseHealth(user)

    await user.click(screen.getByRole('checkbox', { name: 'School Feeding' }))
    await user.click(screen.getByRole('button', { name: /save assignments/i }))

    // Both the one already assigned and the one just ticked. A diff would be wrong the
    // moment two administrators had this page open at once.
    await waitFor(() => expect(setAssignments).toHaveBeenCalledTimes(1))
    const [mdaId, ids] = setAssignments.mock.calls[0]
    expect(mdaId).toBe('m1')
    expect([...ids].sort()).toEqual(['p1', 'p3'])
  })

  it('will not save until something has actually changed', async () => {
    const user = userEvent.setup()
    renderPanel()
    await chooseHealth(user)

    expect(screen.getByRole('button', { name: /save assignments/i })).toBeDisabled()

    await user.click(screen.getByRole('checkbox', { name: 'School Feeding' }))
    expect(screen.getByRole('button', { name: /save assignments/i })).toBeEnabled()
    expect(screen.getByText(/1 to assign.*not saved yet/i)).toBeInTheDocument()
  })

  it('discards an edit without saving it', async () => {
    const user = userEvent.setup()
    renderPanel()
    await chooseHealth(user)

    await user.click(screen.getByRole('checkbox', { name: 'School Feeding' }))
    await user.click(screen.getByRole('button', { name: /discard changes/i }))

    expect(screen.getByRole('checkbox', { name: 'School Feeding' })).not.toBeChecked()
    expect(setAssignments).not.toHaveBeenCalled()
  })

  it('never carries one organisation’s draft to another', async () => {
    const user = userEvent.setup()
    renderPanel()
    await chooseHealth(user)

    await user.click(screen.getByRole('checkbox', { name: 'School Feeding' }))

    forMda.mockResolvedValue({ mda: { id: 'm2', name: 'Save the Children' }, programmes: [] })
    await choose(user, 'm2', /Save the Children/)

    // Without this, saving would apply Health's ticks to the partner.
    await waitFor(() => expect(screen.getByRole('checkbox', { name: 'Health Insurance' })).not.toBeChecked())
    expect(screen.getByRole('checkbox', { name: 'School Feeding' })).not.toBeChecked()
    expect(screen.queryByRole('button', { name: /discard changes/i })).toBeNull()
  })

  it('warns that an organisation with nothing assigned cannot create work', async () => {
    // Confirmed with the owner: a deliberate state, not a fault — but one with a
    // consequence the administrator should be told about rather than discover later.
    forMda.mockResolvedValue({ mda: { id: 'm2', name: 'Save the Children' }, programmes: [] })
    const user = userEvent.setup()
    renderPanel()

    await choose(user, 'm2', /Save the Children/)

    expect(await screen.findByText(/cannot create an activity or run an import/i)).toBeInTheDocument()
  })

  it('names the activities that refuse a removal, and keeps the edit', async () => {
    setAssignments.mockRejectedValue(
      new ApiError(409, 'PROGRAMME_ASSIGNMENT_IN_USE', 'still running', [
        { field: 'programme_id', message: 'Q1 Cash Round (active) — under Health Insurance' },
      ]),
    )
    const user = userEvent.setup()
    renderPanel()
    await chooseHealth(user)

    await user.click(screen.getByRole('checkbox', { name: 'Health Insurance' }))
    await user.click(screen.getByRole('button', { name: /save assignments/i }))

    // The blocking activity is on the page, not in a toast that says "failed": it is
    // the only part of the refusal anyone can act on.
    const alert = await screen.findByRole('alert')
    expect(within(alert).getByText(/Q1 Cash Round \(active\)/)).toBeInTheDocument()
    expect(within(alert).getByText(/nothing was changed/i)).toBeInTheDocument()

    // The untick is still there, so the administrator can archive the activity and
    // save again rather than redoing the edit from scratch.
    expect(screen.getByRole('checkbox', { name: 'Health Insurance' })).not.toBeChecked()
  })

  it('filters a long catalogue by name', async () => {
    const user = userEvent.setup()
    renderPanel()
    await chooseHealth(user)

    await user.type(screen.getByLabelText('Find a programme'), 'goat')

    expect(screen.getByRole('checkbox', { name: 'Goat Revolving Scheme' })).toBeInTheDocument()
    expect(screen.queryByRole('checkbox', { name: 'School Feeding' })).toBeNull()
  })

  it('refuses the whole panel without the permission', async () => {
    perms.value = []
    renderPanel()

    expect(await screen.findByText(/do not have permission to assign/i)).toBeInTheDocument()
    expect(listMdas).not.toHaveBeenCalled()
    expect(forMda).not.toHaveBeenCalled()
  })
})
