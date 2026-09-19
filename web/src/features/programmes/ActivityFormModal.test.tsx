import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { ActivityFormModal } from './ActivityFormModal'
import { activityApi, programmeApi } from './api'

vi.mock('./api', () => ({
  programmeApi: { catalog: vi.fn(), create: vi.fn(), update: vi.fn(), list: vi.fn(), get: vi.fn(), archive: vi.fn(), budget: vi.fn() },
  activityApi: { list: vi.fn(), listForProgramme: vi.fn(), create: vi.fn(), update: vi.fn(), archive: vi.fn(), budget: vi.fn(), fundingPartners: vi.fn() },
  enrollmentApi: {},
}))

const navigate = vi.fn()
vi.mock('react-router-dom', async (importOriginal) => ({
  ...(await importOriginal<typeof import('react-router-dom')>()),
  useNavigate: () => navigate,
}))

const previewImport = vi.fn()
vi.mock('@/features/registry/hooks', () => ({
  usePreviewActivityImport: () => ({ mutateAsync: previewImport, isPending: false }),
}))

vi.mock('@/features/reference/api', () => ({
  referenceApi: {
    lgas: vi.fn().mockResolvedValue({
      lgas: [
        { id: 'lga-dutse', code: 'dutse', name: 'Dutse', state: 'Jigawa', ward_count: 2 },
        { id: 'lga-kiyawa', code: 'kiyawa', name: 'Kiyawa', state: 'Jigawa', ward_count: 1 },
      ],
    }),
    wards: vi.fn((lgaId: string) =>
      Promise.resolve({
        wards:
          lgaId === 'lga-dutse'
            ? [
                { id: 'w-dutse-limawa', lga_id: 'lga-dutse', code: 'limawa', name: 'Limawa' },
                { id: 'w-dutse-madobi', lga_id: 'lga-dutse', code: 'madobi', name: 'Madobi' },
              ]
            : [{ id: 'w-kiyawa-kwanda', lga_id: 'lga-kiyawa', code: 'kwanda', name: 'Kwanda' }],
      }),
    ),
  },
}))

const catalog = programmeApi.catalog as Mock
const createActivity = activityApi.create as Mock
const fundingPartners = activityApi.fundingPartners as Mock

const CATALOG = {
  items: [
    { id: 'p-1', name: 'Cash Transfer', type: 'individual', status: 'active' },
    { id: 'p-2', name: 'Food Support', type: 'household', status: 'active' },
  ],
  pagination: { page: 1, per_page: 100, total: 2, total_pages: 1 },
}

function renderModal(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter>{ui}</MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('ActivityFormModal', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    catalog.mockResolvedValue(CATALOG)
    fundingPartners.mockResolvedValue([
      { id: 'dp-1', name: 'UNICEF Nigeria' },
      { id: 'dp-2', name: 'World Bank' },
    ])
  })

  async function addLga(user: ReturnType<typeof userEvent.setup>, id: string, name: string) {
    const add = await screen.findByLabelText('Add an LGA')
    await waitFor(() => expect(within(add).getByRole('option', { name })).toBeInTheDocument())
    await user.selectOptions(add, id)
  }

  async function selectProgramme(user: ReturnType<typeof userEvent.setup>, programme = 'p-1') {
    await screen.findByLabelText('Programme')
    await waitFor(() => expect(screen.getByRole('option', { name: 'Cash Transfer' })).toBeInTheDocument())
    await user.selectOptions(screen.getByLabelText('Programme'), programme)
    // Funding source is required; these flows are about something else, so government.
    await user.selectOptions(screen.getByLabelText('Funding source'), 'government')
  }

  async function choosePartner(user: ReturnType<typeof userEvent.setup>, partnerId: string) {
    await user.selectOptions(screen.getByLabelText('Funding source'), 'partner')
    const partner = await screen.findByLabelText('Social protection partner')
    await waitFor(() => expect(within(partner).getByRole('option', { name: 'UNICEF Nigeria' })).toBeInTheDocument())
    await user.selectOptions(partner, partnerId)
  }

  it('makes the catalog programme dropdown the first field and requires it', async () => {
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    const programme = await screen.findByLabelText('Programme')
    expect(programme.tagName).toBe('SELECT')
    await waitFor(() => expect(within(programme).getByRole('option', { name: 'Cash Transfer' })).toBeInTheDocument())
    // It is the first field.
    const fields = screen.getAllByRole('combobox').concat(screen.getAllByRole('textbox'))
    expect(fields[0]).toBe(programme)

    // Default is "No" → the action creates the activity alone, but still needs a programme.
    await user.type(screen.getByLabelText('Name of activity'), 'Q1 Round')
    await user.click(screen.getByRole('button', { name: /create activity/i }))
    expect(await screen.findByText(/select a programme/i)).toBeInTheDocument()
  })

  it('saves a no-beneficiary activity alone — no target field, no upload step', async () => {
    createActivity.mockResolvedValue({ id: 'a-1' })
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await selectProgramme(user, 'p-2')
    await user.type(screen.getByLabelText('Name of activity'), 'Dry-season Round')

    // "No" (default): no target, no upload affordance.
    expect(screen.queryByLabelText('Target beneficiaries')).toBeNull()
    expect(screen.queryByRole('button', { name: /next: upload/i })).toBeNull()

    await user.click(screen.getByRole('button', { name: /create activity/i }))

    await waitFor(() =>
      expect(createActivity).toHaveBeenCalledWith(
        expect.objectContaining({ programme_id: 'p-2', name: 'Dry-season Round', involves_beneficiaries: false, target_beneficiaries: null }),
      ),
    )
    expect(previewImport).not.toHaveBeenCalled()
    expect(navigate).not.toHaveBeenCalled()
  })

  it('shows a post-save confirmation with a View activity action (no-beneficiary path)', async () => {
    createActivity.mockResolvedValue({ id: 'a-9', name: 'Dry-season Round', involves_beneficiaries: false })
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await selectProgramme(user, 'p-2')
    await user.type(screen.getByLabelText('Name of activity'), 'Dry-season Round')
    await user.click(screen.getByRole('button', { name: /create activity/i }))

    // Post-save confirmation → View activity opens the detail page.
    await user.click(await screen.findByRole('button', { name: /view activity/i }))
    expect(navigate).toHaveBeenCalledWith('/activities/a-9')
  })

  it('requires a target before advancing when it involves beneficiaries', async () => {
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await selectProgramme(user, 'p-1')
    await user.selectOptions(screen.getByLabelText(/involve beneficiaries/i), 'yes')
    await user.type(screen.getByLabelText('Name of activity'), 'Q1 Round')
    await user.click(screen.getByRole('button', { name: /next: upload/i }))

    expect(await screen.findByText(/a target is required/i)).toBeInTheDocument()
  })

  it('requires a target and a mandatory upload when it involves beneficiaries', async () => {
    previewImport.mockResolvedValue({ id: 'batch-9' })
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await selectProgramme(user, 'p-1')
    await user.selectOptions(screen.getByLabelText(/involve beneficiaries/i), 'yes')
    await user.type(screen.getByLabelText('Name of activity'), 'Q1 Round')
    await user.type(screen.getByLabelText('Target beneficiaries'), '250')
    await user.click(screen.getByRole('button', { name: /next: upload/i }))

    // Step 2 is mandatory — there is no "skip & create" escape hatch.
    expect(await screen.findByLabelText(/choose a beneficiary file/i)).toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /skip/i })).toBeNull()

    await user.upload(screen.getByLabelText(/choose a beneficiary file/i), new File(['a,b'], 'people.csv', { type: 'text/csv' }))
    await user.click(screen.getByRole('button', { name: /upload & preview/i }))

    await waitFor(() =>
      expect(previewImport).toHaveBeenCalledWith(
        expect.objectContaining({ draft: expect.objectContaining({ programme_id: 'p-1', name: 'Q1 Round', involves_beneficiaries: true, target_beneficiaries: 250 }) }),
      ),
    )
    // The activity is NOT created directly — dedup runs in preview first.
    expect(createActivity).not.toHaveBeenCalled()
    expect(navigate).toHaveBeenCalledWith('/imports/batch-9')
  })

  it('names the activity fields in full', async () => {
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    expect(await screen.findByLabelText('Name of activity')).toBeInTheDocument()
    expect(screen.getByLabelText('Description of the activity')).toBeInTheDocument()
    expect(screen.getByLabelText('Budget of the activity (₦)')).toBeInTheDocument()
  })

  it('requires a funding source', async () => {
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await screen.findByLabelText('Programme')
    await waitFor(() => expect(screen.getByRole('option', { name: 'Cash Transfer' })).toBeInTheDocument())
    await user.selectOptions(screen.getByLabelText('Programme'), 'p-1')
    await user.type(screen.getByLabelText('Name of activity'), 'Q1 Round')
    await user.click(screen.getByRole('button', { name: /create activity/i }))

    expect(await screen.findByText('Choose how this activity is funded')).toBeInTheDocument()
    expect(createActivity).not.toHaveBeenCalled()
  })

  it('links a social protection partner, co-funded with government', async () => {
    createActivity.mockResolvedValue({ id: 'a-3' })
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await selectProgramme(user, 'p-1')
    await user.type(screen.getByLabelText('Name of activity'), 'Partner round')
    // Not asked for the partner list until partners is the funding source.
    expect(fundingPartners).not.toHaveBeenCalled()
    expect(screen.queryByLabelText('Social protection partner')).toBeNull()

    await choosePartner(user, 'dp-1')
    // Linking decides who sees the activity, and the form says so at the point of choice.
    expect(screen.getByText(/this partner will see this activity’s budget/i)).toBeInTheDocument()
    await user.click(screen.getByLabelText('Co-funded with government'))
    await user.click(screen.getByRole('button', { name: /create activity/i }))

    await waitFor(() =>
      expect(createActivity).toHaveBeenCalledWith(
        expect.objectContaining({ funding_type: 'partner', funding_partner_id: 'dp-1', co_funded_by_government: true }),
      ),
    )
  })

  it('requires the partner once partners is the funding source', async () => {
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await selectProgramme(user, 'p-1')
    await user.type(screen.getByLabelText('Name of activity'), 'Partner round')
    await user.selectOptions(screen.getByLabelText('Funding source'), 'partner')
    await screen.findByLabelText('Social protection partner')
    await user.click(screen.getByRole('button', { name: /create activity/i }))

    expect(await screen.findByText('Choose the partner funding this activity')).toBeInTheDocument()
    expect(createActivity).not.toHaveBeenCalled()
  })

  it('drops the partner and co-funding when the funding source changes away from partners', async () => {
    createActivity.mockResolvedValue({ id: 'a-4' })
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await selectProgramme(user, 'p-1')
    await user.type(screen.getByLabelText('Name of activity'), 'Donor round')
    await choosePartner(user, 'dp-2')
    await user.click(screen.getByLabelText('Co-funded with government'))

    await user.selectOptions(screen.getByLabelText('Funding source'), 'individual')
    expect(screen.queryByLabelText('Social protection partner')).toBeNull()
    await user.click(screen.getByRole('button', { name: /create activity/i }))

    await waitFor(() =>
      expect(createActivity).toHaveBeenCalledWith(
        expect.objectContaining({ funding_type: 'individual', funding_partner_id: null, co_funded_by_government: false }),
      ),
    )
  })

  it('locks the programme when a page fixes it', async () => {
    renderModal(<ActivityFormModal open onClose={() => {}} programmeId="p-1" />)

    const programme = await screen.findByLabelText('Programme')
    expect(programme).toBeDisabled()
  })

  it('submits a multi-LGA, multi-ward location set', async () => {
    createActivity.mockResolvedValue({ id: 'a-1' })
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await selectProgramme(user, 'p-1')
    await user.type(screen.getByLabelText('Name of activity'), 'Multi-area round')

    // Two LGAs: specific wards in one, the whole of the other.
    await addLga(user, 'lga-dutse', 'Dutse')
    const dutse = await screen.findByRole('region', { name: 'Dutse' })
    await waitFor(() => expect(within(dutse).getByLabelText('Limawa')).toBeInTheDocument())
    await user.click(within(dutse).getByLabelText('Limawa'))
    await user.click(within(dutse).getByLabelText('Madobi'))

    await addLga(user, 'lga-kiyawa', 'Kiyawa')
    const kiyawa = await screen.findByRole('region', { name: 'Kiyawa' })
    await user.click(within(kiyawa).getByLabelText('Whole LGA (all wards)'))

    await user.click(screen.getByRole('button', { name: /create activity/i }))

    await waitFor(() =>
      expect(createActivity).toHaveBeenCalledWith(
        expect.objectContaining({
          locations: [
            { lga_id: 'lga-dutse', ward_ids: ['w-dutse-limawa', 'w-dutse-madobi'] },
            { lga_id: 'lga-kiyawa', whole_lga: true },
          ],
        }),
      ),
    )
  })

  it('sends an LGA with no wards ticked as whole-LGA coverage', async () => {
    // Picking an LGA and no wards is the same claim as ticking "whole LGA"; sending it
    // as an empty ward list would store the same intent a second, different way.
    createActivity.mockResolvedValue({ id: 'a-2' })
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await selectProgramme(user, 'p-1')
    await user.type(screen.getByLabelText('Name of activity'), 'Whole LGA round')
    await addLga(user, 'lga-dutse', 'Dutse')

    await user.click(screen.getByRole('button', { name: /create activity/i }))

    await waitFor(() =>
      expect(createActivity).toHaveBeenCalledWith(
        expect.objectContaining({ locations: [{ lga_id: 'lga-dutse', whole_lga: true }] }),
      ),
    )
  })

  it('sends the location set on the upload path too', async () => {
    // The wizard's upload path is multipart, a different transport from the JSON create —
    // the set has to survive it, not arrive as "[object Object]".
    previewImport.mockResolvedValue({ id: 'batch-7' })
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await selectProgramme(user, 'p-1')
    await user.selectOptions(screen.getByLabelText('Does this activity involve beneficiaries?'), 'yes')
    await user.type(screen.getByLabelText('Name of activity'), 'Q1 Round')
    await user.type(screen.getByLabelText('Target beneficiaries'), '250')

    await addLga(user, 'lga-dutse', 'Dutse')
    const dutse = await screen.findByRole('region', { name: 'Dutse' })
    await waitFor(() => expect(within(dutse).getByLabelText('Limawa')).toBeInTheDocument())
    await user.click(within(dutse).getByLabelText('Limawa'))

    await user.click(screen.getByRole('button', { name: /next: upload/i }))
    await user.upload(screen.getByLabelText(/choose a beneficiary file/i), new File(['a,b'], 'people.csv', { type: 'text/csv' }))
    await user.click(screen.getByRole('button', { name: /upload & preview/i }))

    await waitFor(() =>
      expect(previewImport).toHaveBeenCalledWith(
        expect.objectContaining({
          draft: expect.objectContaining({
            locations: [{ lga_id: 'lga-dutse', ward_ids: ['w-dutse-limawa'] }],
          }),
        }),
      ),
    )
  })

  it('returns to step 1 when the server rejects the location set on upload', async () => {
    // The rejected field lives on step 1. Leaving the user on the upload step showed a
    // bare "The request is invalid." next to a file input that was not the problem.
    previewImport.mockRejectedValue({
      code: 'VALIDATION_ERROR',
      message: 'The request is invalid.',
      details: [{ field: 'locations.0.ward_ids.0', message: 'That ward does not belong to the selected LGA.' }],
    })
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await selectProgramme(user, 'p-1')
    await user.selectOptions(screen.getByLabelText('Does this activity involve beneficiaries?'), 'yes')
    await user.type(screen.getByLabelText('Name of activity'), 'Q1 Round')
    await user.type(screen.getByLabelText('Target beneficiaries'), '250')
    await addLga(user, 'lga-dutse', 'Dutse')
    const dutse = await screen.findByRole('region', { name: 'Dutse' })
    await waitFor(() => expect(within(dutse).getByLabelText('Limawa')).toBeInTheDocument())
    await user.click(within(dutse).getByLabelText('Limawa'))

    await user.click(screen.getByRole('button', { name: /next: upload/i }))
    await user.upload(screen.getByLabelText(/choose a beneficiary file/i), new File(['a,b'], 'people.csv', { type: 'text/csv' }))
    await user.click(screen.getByRole('button', { name: /upload & preview/i }))

    // Back on step 1, with the actual reason visible next to the ward that caused it.
    expect(await screen.findByText('That ward does not belong to the selected LGA.')).toBeInTheDocument()
    expect(screen.getByRole('button', { name: /next: upload/i })).toBeInTheDocument()
  })

  it('marks the ward the server rejected as belonging to another LGA', async () => {
    createActivity.mockRejectedValue({
      code: 'VALIDATION_ERROR',
      details: [{ field: 'locations.0.ward_ids.0', message: 'That ward does not belong to the selected LGA.' }],
    })
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await selectProgramme(user, 'p-1')
    await user.type(screen.getByLabelText('Name of activity'), 'Bad wards')
    await addLga(user, 'lga-dutse', 'Dutse')
    const dutse = await screen.findByRole('region', { name: 'Dutse' })
    await waitFor(() => expect(within(dutse).getByLabelText('Limawa')).toBeInTheDocument())
    await user.click(within(dutse).getByLabelText('Limawa'))

    await user.click(screen.getByRole('button', { name: /create activity/i }))

    expect(await screen.findByText('That ward does not belong to the selected LGA.')).toBeInTheDocument()
  })
})
