import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { MdaBeneficiariesPage } from './MdaBeneficiariesPage'
import { beneficiaryApi, householdApi, importApi } from '@/features/registry/api'
import { activityApi } from '@/features/programmes/api'

vi.mock('@/features/registry/api', () => ({
  beneficiaryApi: { list: vi.fn(), get: vi.fn(), update: vi.fn(), lookup: vi.fn() },
  householdApi: { list: vi.fn(), get: vi.fn() },
  importApi: { list: vi.fn(), get: vi.fn(), upload: vi.fn(), confirm: vi.fn(), resolveRow: vi.fn() },
  documentApi: { list: vi.fn(), upload: vi.fn(), remove: vi.fn() },
}))
vi.mock('@/features/programmes/api', () => ({
  programmeApi: { list: vi.fn(), get: vi.fn(), catalog: vi.fn() },
  activityApi: { list: vi.fn(), listForProgramme: vi.fn(), get: vi.fn() },
}))

const perms = { value: [] as string[] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { name: 'Amina', role: { key: 'mda_admin', name: 'MDA Admin' }, mda: { id: 'm1', name: 'Ministry of Health' } },
    hasPermission: (p: string) => perms.value.includes(p),
  }),
}))

const listBeneficiaries = beneficiaryApi.list as Mock
const listHouseholds = householdApi.list as Mock
const listImports = importApi.list as Mock
const upload = importApi.upload as Mock
const listActivities = activityApi.list as Mock

const BENEFICIARY = {
  id: 'b1', full_name: 'Aisha Bello', first_name: 'Aisha', middle_name: null, last_name: 'Bello',
  // The server masks identifiers in every payload; the UI never receives the raw value.
  nin: '*******8901', bvn: null, phone: '08030000001', date_of_birth: '1990-01-01',
  gender: 'female', address: null, lga: 'dutse', ward: null, status: 'active',
  owner_mda_id: 'm1', owner_mda: { id: 'm1', name: 'Ministry of Health' },
  registration_source: 'excel', registration_date: '2026-01-05',
  import_batch_id: 'ib1', original_record_id: 'ROW-001',
  created_at: null, updated_at: null,
}

// Shaped exactly like HouseholdResource, whose index eager-loads `currentMemberships`
// into `members`. The earlier fixture invented `member_count`, so the list's
// `h.members.length` threw during render — an uncaught error vitest warns can produce
// false positives in unrelated files.
const HOUSEHOLD = {
  id: 'h1dd4c02-0000', owner_mda_id: 'm1', head_beneficiary_id: null,
  registration_source: 'excel', registration_date: '2026-01-05',
  address: null, lga: 'dutse', ward: null,
  members: [], created_at: null, updated_at: null,
}

const ACTIVITIES = [
  { id: 'a1', programme_id: 'p1', owner_mda_id: 'm1', involves_beneficiaries: true, name: 'CCT Dutse Q1', status: 'active', description: null, target_beneficiaries: 500, lga: 'dutse', ward: null, location_description: null, schedule: null, starts_on: null, ends_on: null, budget_amount: null, funding_source: null, created_by: null, created_at: null, updated_at: null },
  { id: 'a2', programme_id: 'p1', owner_mda_id: 'm1', involves_beneficiaries: false, name: 'Staff training', status: 'active', description: null, target_beneficiaries: null, lga: null, ward: null, location_description: null, schedule: null, starts_on: null, ends_on: null, budget_amount: null, funding_source: null, created_by: null, created_at: null, updated_at: null },
]

const page = <T,>(items: T[]) => ({ items, pagination: { page: 1, per_page: 25, total: items.length, total_pages: 1 } })

function renderPage() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter>
          <Routes>
            <Route path="/" element={<MdaBeneficiariesPage />} />
          </Routes>
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('MDA console — Beneficiaries', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms.value = ['beneficiary.view', 'beneficiary.create', 'beneficiary.edit', 'household.view']
    listBeneficiaries.mockResolvedValue(page([BENEFICIARY]))
    listHouseholds.mockResolvedValue(page([HOUSEHOLD]))
    listImports.mockResolvedValue(page([]))
    listActivities.mockResolvedValue(page(ACTIVITIES))
  })

  /* ------------------------------------------------------------------ browse */

  it('browses the MDA’s registry', async () => {
    renderPage()

    expect(await screen.findByText('Aisha Bello')).toBeInTheDocument()
    expect(listBeneficiaries).toHaveBeenCalled()
  })

  it('browses households on their own tab', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Aisha Bello')

    await user.click(screen.getByRole('tab', { name: 'Households' }))
    await waitFor(() => expect(listHouseholds).toHaveBeenCalled())
  })

  it('hides households without household.view', async () => {
    perms.value = ['beneficiary.view']
    renderPage()
    await screen.findByRole('tab', { name: 'Registry' })

    expect(screen.queryByRole('tab', { name: 'Households' })).not.toBeInTheDocument()
  })

  it('refuses the module without beneficiary.view', async () => {
    perms.value = ['household.view']
    renderPage()

    expect(await screen.findByText(/do not have permission to view the beneficiary registry/i)).toBeInTheDocument()
    expect(listBeneficiaries).not.toHaveBeenCalled()
  })

  /* ------------------------------------------------------------ no create path */

  it('offers NO manual create form anywhere in the module', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Aisha Bello')

    // §9: bulk upload is the only way in. A create affordance must be absent, not
    // merely disabled — its presence would imply a path that does not exist.
    for (const label of [/add beneficiary/i, /new beneficiary/i, /create beneficiary/i, /register beneficiary/i, /add household/i, /create household/i]) {
      expect(screen.queryByRole('button', { name: label })).not.toBeInTheDocument()
      expect(screen.queryByRole('link', { name: label })).not.toBeInTheDocument()
    }

    await user.click(screen.getByRole('tab', { name: 'Households' }))
    expect(screen.queryByRole('button', { name: /add|create|new/i })).not.toBeInTheDocument()
  })

  it('says plainly that there is no keyed-in path', async () => {
    renderPage()
    await screen.findByText('Aisha Bello')

    expect(screen.getByText(/no .add beneficiary. form/i)).toBeInTheDocument()
  })

  /* ---------------------------------------------------------------- PII masking */

  it('renders only the masked identifier it was given', async () => {
    renderPage()
    await screen.findByText('Aisha Bello')

    // The server masks; the client must not reconstruct or expose a raw value.
    expect(document.body.textContent).not.toMatch(/\b\d{11}\b/)
  })

  /* --------------------------------------------------- Import Center reuse */

  it('binds an Import Center upload to an existing activity — the same pipeline', async () => {
    upload.mockResolvedValue({ id: 'ib9', status: 'preview_ready' })
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Aisha Bello')

    await user.click(screen.getByRole('tab', { name: 'Import Center' }))
    await screen.findByText('Upload a file')

    // Activity-first: the picker exists and offers only activities that accept
    // beneficiaries.
    const activity = screen.getByLabelText(/^activity/i)
    await user.selectOptions(activity, 'a1')
    expect(within(activity).queryByText('Staff training')).not.toBeInTheDocument()

    const file = new File(['first_name,last_name\nA,B\n'], 'rows.csv', { type: 'text/csv' })
    await user.upload(screen.getByLabelText(/^file/i), file)
    await user.click(screen.getByRole('button', { name: /upload & preview/i }))

    // The SAME endpoint the wizard's inline upload uses, now carrying the activity.
    await waitFor(() => expect(upload).toHaveBeenCalledWith(file, 'a1', undefined))
  })

  it('will not upload without naming an activity', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Aisha Bello')
    await user.click(screen.getByRole('tab', { name: 'Import Center' }))
    await screen.findByText('Upload a file')

    const file = new File(['x'], 'rows.csv', { type: 'text/csv' })
    await user.upload(screen.getByLabelText(/^file/i), file)
    await user.click(screen.getByRole('button', { name: /upload & preview/i }))

    expect(await screen.findByText(/choose the activity/i)).toBeInTheDocument()
    expect(upload).not.toHaveBeenCalled()
  })

  it('blocks upload when the MDA has no activity that accepts beneficiaries', async () => {
    listActivities.mockResolvedValue(page([ACTIVITIES[1]!])) // training only
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Aisha Bello')
    await user.click(screen.getByRole('tab', { name: 'Import Center' }))

    expect(await screen.findByText(/no activity that accepts beneficiaries yet/i)).toBeInTheDocument()
    expect(screen.getByRole('button', { name: /upload & preview/i })).toBeDisabled()
  })

  it('shows import history without the upload panel when the user cannot import', async () => {
    perms.value = ['beneficiary.view', 'household.view'] // no beneficiary.create
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Aisha Bello')

    await user.click(screen.getByRole('tab', { name: 'Import Center' }))
    await waitFor(() => expect(listImports).toHaveBeenCalled())
    expect(screen.queryByText('Upload a file')).not.toBeInTheDocument()
  })
})
