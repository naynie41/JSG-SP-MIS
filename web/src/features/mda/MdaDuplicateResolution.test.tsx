import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { MdaDuplicateResolutionPage } from './MdaDuplicateResolutionPage'
import { importApi } from '@/features/registry/api'
import type { ImportBatch, ImportRow } from '@/features/registry/types'

/*
 * Mocked at the SOURCE module, so these tests are evidence of reuse: the module has no
 * endpoint of its own, and if it grew one these mocks would stop being called.
 */
vi.mock('@/features/registry/api', () => ({
  beneficiaryApi: { list: vi.fn(), get: vi.fn(), update: vi.fn(), remove: vi.fn(), lookup: vi.fn(), search: vi.fn(), export: vi.fn() },
  serviceRequestApi: { create: vi.fn(), inbox: vi.fn(), outbox: vi.fn(), forActivity: vi.fn(), accept: vi.fn(), decline: vi.fn() },
  matchingApi: { config: vi.fn(), versions: vi.fn(), publish: vi.fn() },
  householdApi: { list: vi.fn(), get: vi.fn() },
  importApi: { list: vi.fn(), get: vi.fn(), upload: vi.fn(), confirm: vi.fn(), resolveRow: vi.fn() },
  documentApi: { list: vi.fn(), upload: vi.fn(), remove: vi.fn() },
}))

const auth = { perms: [] as string[] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { name: 'Amina', role: { key: 'mda_officer', name: 'MDA Officer' }, mda: { id: 'm1', name: 'Ministry of Health' } },
    hasPermission: (p: string) => auth.perms.includes(p),
  }),
}))

const listImports = importApi.list as Mock
const getImport = importApi.get as Mock
const resolveRow = importApi.resolveRow as Mock

const OFFICER = ['beneficiary.view', 'beneficiary.create', 'beneficiary-lookup.view']

function row(overrides: Partial<ImportRow> & { row_number: number }): ImportRow {
  return {
    original_record_id: null,
    is_valid: true,
    errors: [],
    beneficiary_id: null,
    resolution: null,
    resolution_note: null,
    resolved_beneficiary_id: null,
    resolved_at: null,
    match: { band: 'none', candidates: [] },
    preview: {
      first_name: 'Aisha', last_name: 'Bello', nin: '*******8901', bvn: null,
      phone: '08030000001', date_of_birth: '1990-01-01', gender: 'female', lga: 'dutse', ward: null,
    },
    ...overrides,
  }
}

const registryCandidate = (band: 'exact' | 'probable', stage: 'deterministic' | 'fuzzy') => ({
  type: 'registry' as const,
  band,
  score: band === 'exact' ? 1 : 0.86,
  matched_fields: band === 'exact' ? ['nin'] : ['last_name', 'date_of_birth'],
  comparison: [],
  stage,
  // The full reveal contract — BeneficiaryRevealPresenter always emits every field,
  // including the (possibly empty) programmes and benefits sections.
  reveal: {
    id: 'ben-existing',
    full_name: 'Aisha Bello',
    owner_mda: { id: 'm2', name: 'Ministry of Education' },
    registration_source: 'excel',
    registration_date: '2026-01-05',
    lga: 'dutse',
    ward: null,
    status: 'active',
    programmes: [],
    benefits: { summary: null, items: [] },
  },
})

/** An EXACT match: matched on a unique identifier, definitively the same person. */
const EXACT_ROW = row({
  row_number: 1,
  match: { band: 'exact', candidates: [registryCandidate('exact', 'deterministic')] },
})
/** A PROBABLE match: fuzzy, genuinely uncertain. */
const PROBABLE_ROW = row({
  row_number: 2,
  preview: { ...row({ row_number: 2 }).preview, first_name: 'Musa', last_name: 'Sani' },
  match: { band: 'probable', candidates: [registryCandidate('probable', 'fuzzy')] },
})
/** Not flagged at all — must never appear in this module. */
const CLEAN_ROW = row({
  row_number: 3,
  preview: { ...row({ row_number: 3 }).preview, first_name: 'Halima', last_name: 'Yusuf' },
})
/** A probable match already decided. */
const DECIDED_ROW = row({
  row_number: 4,
  preview: { ...row({ row_number: 4 }).preview, first_name: 'Ibrahim', last_name: 'Danjuma' },
  match: { band: 'probable', candidates: [registryCandidate('probable', 'fuzzy')] },
  resolution: 'link',
  resolved_beneficiary_id: 'ben-existing',
  resolved_at: '2026-08-01T10:30:00+01:00',
})

function batch(overrides: Partial<ImportBatch> = {}): ImportBatch {
  return {
    id: 'ib1', owner_mda_id: 'm1', uploaded_by: 'u1',
    original_filename: 'dutse-q1.csv', source: 'csv', activity_id: 'a1',
    draft_activity_name: null, draft_target_beneficiaries: null, target_mismatch: false,
    status: 'preview_ready',
    summary: { total_rows: 4, valid_rows: 4, invalid_rows: 0, committed_rows: 0, served_rows: 0, skipped_rows: 0 },
    error: null,
    matching_thresholds: { review: 0.75, auto_accept: 0.95 },
    rows: [EXACT_ROW, PROBABLE_ROW, CLEAN_ROW, DECIDED_ROW],
    created_at: '2026-07-30T09:00:00+01:00',
    updated_at: null,
    ...overrides,
  } as ImportBatch
}

const page = <T,>(items: T[]) => ({ items, pagination: { page: 1, per_page: 25, total: items.length, total_pages: 1 } })

function renderPage() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter initialEntries={['/mda/duplicate-resolution']}>
          <Routes>
            <Route path="/mda/duplicate-resolution" element={<MdaDuplicateResolutionPage />} />
            <Route path="/imports/:id/adjudicate" element={<div>Adjudication queue</div>} />
          </Routes>
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

/**
 * Wait for the module to finish loading, anchored on a TAB.
 *
 * The page header renders outside the loading branch, so awaiting the `<h1>` proves only
 * that the component mounted — the tabs appear one request later. Anchoring on
 * something that exists only after the data arrives is what makes the synchronous
 * assertions that follow meaningful.
 */
const ready = () => screen.findByRole('tab', { name: /Exact matches/ })

/** Open a tab and return its panel. */
async function openTab(user: ReturnType<typeof userEvent.setup>, name: RegExp) {
  await user.click(await screen.findByRole('tab', { name }))
  return screen.getByRole('tabpanel')
}

describe('MDA console — Duplicate Resolution', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    auth.perms = OFFICER
    listImports.mockResolvedValue(page([batch()]))
    getImport.mockResolvedValue(batch())
  })

  /* --------------------------------------------------------------- the five views */

  it('offers the five views over the surfaced matches', async () => {
    renderPage()
    await ready()

    expect(screen.getByRole('heading', { name: 'Duplicate Resolution' })).toBeInTheDocument()
    for (const name of [/Exact matches/, /Possible matches/, /Pending reviews/, /Duplicate decisions/, /Match history/]) {
      expect(screen.getByRole('tab', { name })).toBeInTheDocument()
    }
  })

  it('reads matches from the existing import endpoints, not a new one', async () => {
    renderPage()
    await ready()

    await waitFor(() => {
      expect(listImports).toHaveBeenCalled()
      expect(getImport).toHaveBeenCalledWith('ib1')
    })
  })

  it('never lists a row the engine did not flag', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const history = await openTab(user, /Match history/)
    // Halima matched nothing, so she is not a duplicate question at all.
    expect(within(history).queryByText('Halima Yusuf')).not.toBeInTheDocument()
    expect(within(history).getByText('Aisha Bello')).toBeInTheDocument()
  })

  it('separates exact from possible', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const exact = await openTab(user, /Exact matches/)
    expect(within(exact).getByText('Aisha Bello')).toBeInTheDocument()
    expect(within(exact).queryByText('Musa Sani')).not.toBeInTheDocument()

    const possible = await openTab(user, /Possible matches/)
    expect(within(possible).getByText('Musa Sani')).toBeInTheDocument()
    expect(within(possible).queryByText('Aisha Bello')).not.toBeInTheDocument()
  })

  /* ------------------------------------------- FR-DUP-09: the adjudication gate */

  it('offers discard-or-serve on an EXACT match and NO same-person adjudication', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const exact = await openTab(user, /Exact matches/)
    await user.click(within(exact).getByRole('button', { name: 'Decide' }))

    // Both discard-or-serve choices are present…
    expect(await screen.findByRole('radio', { name: /Provide service/i })).toBeInTheDocument()
    expect(screen.getByRole('radio', { name: /Discard this row/i })).toBeInTheDocument()
    // …and the same-person judgement is ABSENT, not merely disabled: an exact match is
    // a settled duplicate (FR-DUP-09).
    expect(screen.queryByRole('radio', { name: /Not the same person/i })).not.toBeInTheDocument()
  })

  it('says WHY adjudication is absent on an exact match', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const exact = await openTab(user, /Exact matches/)
    await user.click(within(exact).getByRole('button', { name: 'Decide' }))

    expect(
      await screen.findByText(/A new record cannot be created for an exact identifier match/i),
    ).toBeInTheDocument()
  })

  it('presents an exact match as established rather than as a question', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const exact = await openTab(user, /Exact matches/)
    expect(within(exact).getByRole('heading', { name: 'Definitive duplicates' })).toBeInTheDocument()
    expect(within(exact).getByText(/same person as a matter of fact, not of opinion/i)).toBeInTheDocument()
  })

  it('offers adjudication AND discard-or-serve on a POSSIBLE match', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const possible = await openTab(user, /Possible matches/)
    await user.click(within(possible).getByRole('button', { name: 'Decide' }))

    expect(await screen.findByRole('radio', { name: /Not the same person/i })).toBeInTheDocument()
    expect(screen.getByRole('radio', { name: /Provide service/i })).toBeInTheDocument()
    expect(screen.getByRole('radio', { name: /Discard this row/i })).toBeInTheDocument()
  })

  it('requires a justification before recording "not the same person"', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const possible = await openTab(user, /Possible matches/)
    await user.click(within(possible).getByRole('button', { name: 'Decide' }))
    await user.click(await screen.findByRole('radio', { name: /Not the same person/i }))
    await user.click(screen.getByRole('button', { name: /Save decision/i }))

    // Scoped to the alert: the tab's own intro also mentions that a justification is
    // required, so a bare text query matches the explanation as well as the error.
    expect(await screen.findByRole('alert')).toHaveTextContent(/justification is required/i)
    expect(resolveRow).not.toHaveBeenCalled()
  })

  /* ------------------------------------------------------ decisions are recorded */

  it('records a discard through the existing resolve endpoint', async () => {
    resolveRow.mockResolvedValue({ ...EXACT_ROW, resolution: 'skip' })
    const user = userEvent.setup()
    renderPage()
    await ready()

    const exact = await openTab(user, /Exact matches/)
    await user.click(within(exact).getByRole('button', { name: 'Decide' }))
    await user.click(await screen.findByRole('radio', { name: /Discard this row/i }))
    await user.click(screen.getByRole('button', { name: /Save decision/i }))

    await waitFor(() =>
      expect(resolveRow).toHaveBeenCalledWith('ib1', 1, expect.objectContaining({ resolution: 'skip' })),
    )
  })

  it('records provide-service against the matched record', async () => {
    resolveRow.mockResolvedValue({ ...EXACT_ROW, resolution: 'link' })
    const user = userEvent.setup()
    renderPage()
    await ready()

    const exact = await openTab(user, /Exact matches/)
    await user.click(within(exact).getByRole('button', { name: 'Decide' }))
    await user.click(await screen.findByRole('radio', { name: /Provide service/i }))
    await user.click(screen.getByRole('button', { name: /Save decision/i }))

    // Linking names the existing beneficiary — that is what raises the request-to-serve.
    await waitFor(() =>
      expect(resolveRow).toHaveBeenCalledWith(
        'ib1', 1,
        expect.objectContaining({ resolution: 'link', beneficiary_id: 'ben-existing' }),
      ),
    )
  })

  it('shows a recorded decision in Duplicate decisions and in history', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const decisions = await openTab(user, /Duplicate decisions/)
    expect(within(decisions).getByText('Ibrahim Danjuma')).toBeInTheDocument()
    // Undecided rows are not decisions.
    expect(within(decisions).queryByText('Musa Sani')).not.toBeInTheDocument()

    const history = await openTab(user, /Match history/)
    expect(within(history).getByText('Ibrahim Danjuma')).toBeInTheDocument()
    expect(within(history).getByText('Musa Sani')).toBeInTheDocument()
  })

  it('names the audit log as the record of a decision', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const decisions = await openTab(user, /Duplicate decisions/)
    expect(within(decisions).getByText(/in the audit log with who made it/i)).toBeInTheDocument()
  })

  /* ------------------------------------------------------------ pending reviews */

  it('lists only undecided rows in Pending reviews', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const pending = await openTab(user, /Pending reviews/)
    expect(within(pending).getByText('Aisha Bello')).toBeInTheDocument()
    expect(within(pending).getByText('Musa Sani')).toBeInTheDocument()
    expect(within(pending).queryByText('Ibrahim Danjuma')).not.toBeInTheDocument()
  })

  it('does not queue rows in a batch that can no longer be decided', async () => {
    // Once committed, the server refuses a resolution — so it is not pending work.
    listImports.mockResolvedValue(page([batch({ status: 'completed' })]))
    getImport.mockResolvedValue(batch({ status: 'completed' }))
    const user = userEvent.setup()
    renderPage()
    await ready()

    const pending = await openTab(user, /Pending reviews/)
    expect(within(pending).getByText('Nothing awaiting a decision')).toBeInTheDocument()

    // …but they remain visible as history.
    const history = await openTab(user, /Match history/)
    expect(within(history).getByText('Aisha Bello')).toBeInTheDocument()
  })

  it('offers no decision control at all in Match history', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const history = await openTab(user, /Match history/)
    expect(within(history).queryByRole('button', { name: 'Decide' })).not.toBeInTheDocument()
    expect(within(history).queryByRole('button', { name: 'Revisit decision' })).not.toBeInTheDocument()
  })

  /* ------------------------------------------------------------- permissions */

  it('refuses the module without beneficiary.view', async () => {
    auth.perms = []
    renderPage()

    expect(await screen.findByText(/do not have permission to view duplicate resolution/i)).toBeInTheDocument()
    expect(listImports).not.toHaveBeenCalled()
  })

  it('shows matches but no decision controls without beneficiary.create', async () => {
    auth.perms = ['beneficiary.view']
    const user = userEvent.setup()
    renderPage()
    await ready()

    const exact = await openTab(user, /Exact matches/)
    expect(within(exact).getByText('Aisha Bello')).toBeInTheDocument()
    await user.click(within(exact).getByRole('button', { name: 'Decide' }))

    expect(await screen.findByText(/do not have permission to resolve rows/i)).toBeInTheDocument()
    expect(screen.queryByRole('radio', { name: /Discard this row/i })).not.toBeInTheDocument()
  })

  it('hides the pre-serve search without the lookup permission', async () => {
    auth.perms = ['beneficiary.view', 'beneficiary.create']
    renderPage()
    await ready()

    expect(screen.queryByRole('tab', { name: /Search before serving/i })).not.toBeInTheDocument()

    auth.perms = OFFICER
    renderPage()
    expect(await screen.findAllByRole('tab', { name: /Search before serving/i })).not.toHaveLength(0)
  })
})
