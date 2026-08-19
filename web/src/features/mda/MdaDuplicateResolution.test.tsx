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
    user: { name: 'Amina', role: { key: 'mda_admin', name: 'MDA Admin' }, mda: { id: 'm1', name: 'Ministry of Health' } },
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

  /**
   * One tab per KIND of decision, not per stage of one.
   *
   * "Pending reviews", "Duplicate decisions" and "Match history" were three more tabs
   * over the same rows: history was exactly Exact + Possible combined, and a single row
   * could appear in three tabs at once. Decision state is a filter inside a band now, so
   * the tabs answer "what am I being asked?" and the filter answers "has it been
   * answered yet?".
   */
  it('offers one tab per kind of match, and no tab that merely re-slices them', async () => {
    renderPage()
    await ready()

    expect(screen.getByRole('heading', { name: 'Duplicate Resolution' })).toBeInTheDocument()
    for (const name of [/Exact matches/, /Possible matches/]) {
      expect(screen.getByRole('tab', { name })).toBeInTheDocument()
    }
    for (const gone of [/Pending reviews/, /Duplicate decisions/, /Match history/]) {
      expect(screen.queryByRole('tab', { name: gone })).not.toBeInTheDocument()
    }
  })

  it('counts the work outstanding on a tab, not the total', async () => {
    // A tab reading "(45)" when all 45 are decided sends someone looking for nothing.
    renderPage()
    await ready()

    const exactTab = screen.getByRole('tab', { name: /Exact matches/ })
    expect(exactTab).toHaveTextContent('Exact matches (1)')
  })

  it('reads matches from the existing import endpoints, not a new one', async () => {
    renderPage()
    await ready()

    await waitFor(() => {
      expect(listImports).toHaveBeenCalled()
      expect(getImport).toHaveBeenCalledWith('ib1')
    })
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

  /* ----------------------------------------------- the decision-state filter */

  /** Switch the decision-state filter inside the currently open band tab. */
  async function setState(user: ReturnType<typeof userEvent.setup>, name: RegExp) {
    await user.click(screen.getByRole('button', { name }))
  }

  it('opens on the work — undecided matches, not a mixed list', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const possible = await openTab(user, /Possible matches/)
    // Musa is undecided; Ibrahim was already decided, so he is not in the way.
    expect(within(possible).getByText('Musa Sani')).toBeInTheDocument()
    expect(within(possible).queryByText('Ibrahim Danjuma')).not.toBeInTheDocument()
    expect(screen.getByRole('button', { name: /Awaiting decision/ })).toHaveAttribute('aria-pressed', 'true')
  })

  it('shows a recorded decision under Decided', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const possible = await openTab(user, /Possible matches/)
    await setState(user, /^Decided$/)

    expect(within(possible).getByText('Ibrahim Danjuma')).toBeInTheDocument()
    // An undecided row is not a decision.
    expect(within(possible).queryByText('Musa Sani')).not.toBeInTheDocument()
  })

  it('shows decided and undecided together under All', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const possible = await openTab(user, /Possible matches/)
    await setState(user, /^All/)

    expect(within(possible).getByText('Musa Sani')).toBeInTheDocument()
    expect(within(possible).getByText('Ibrahim Danjuma')).toBeInTheDocument()
  })

  it('never lists a row the engine did not flag, under any filter', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const exact = await openTab(user, /Exact matches/)
    await setState(user, /^All/)
    // Halima matched nothing, so she is not a duplicate question at all.
    expect(within(exact).queryByText('Halima Yusuf')).not.toBeInTheDocument()
  })

  it('does not count rows in a batch that can no longer be decided as awaiting', async () => {
    // Once committed, the server refuses a resolution — so it is not outstanding work.
    listImports.mockResolvedValue(page([batch({ status: 'completed' })]))
    getImport.mockResolvedValue(batch({ status: 'completed' }))
    const user = userEvent.setup()
    renderPage()
    await ready()

    const exact = await openTab(user, /Exact matches/)
    expect(within(exact).getByText('No exact matches awaiting a decision')).toBeInTheDocument()

    // …but they remain visible, which is what the All filter is for.
    await setState(user, /^All/)
    expect(within(exact).getByText('Aisha Bello')).toBeInTheDocument()
  })

  /**
   * The whole journey, through the tabs that remain: open on the work, decide a row,
   * watch it leave the queue, and find it again where decisions live.
   *
   * Worth pinning as one test rather than four: the value of the filter is that these
   * steps compose, and a decided row that stayed in "Awaiting" — or vanished entirely —
   * would pass every individual assertion above.
   */
  it('carries a match from awaiting, through a decision, to decided', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const exact = await openTab(user, /Exact matches/)

    // 1. It starts as outstanding work, and the tab counts it.
    expect(within(exact).getByText('Aisha Bello')).toBeInTheDocument()
    expect(screen.getByRole('tab', { name: /Exact matches/ })).toHaveTextContent('(1)')

    // 2. Decide it. The decision invalidates the batch query, so what the refetch
    //    returns has to be staged BEFORE saving — otherwise the list refreshes from the
    //    old mock and the assertion below measures nothing.
    resolveRow.mockResolvedValue({ ...EXACT_ROW, resolution: 'skip' })
    getImport.mockResolvedValue(
      batch({ rows: [{ ...EXACT_ROW, resolution: 'skip', resolved_at: '2026-08-18T09:00:00Z' }, PROBABLE_ROW] }),
    )

    await user.click(within(exact).getByRole('button', { name: 'Decide' }))
    await user.click(await screen.findByRole('radio', { name: /Discard this row/i }))
    await user.click(screen.getByRole('button', { name: /Save decision/i }))
    await waitFor(() => expect(resolveRow).toHaveBeenCalled())

    // 3. It leaves the queue — the work is done, so it stops being work.
    await waitFor(() =>
      expect(within(screen.getByRole('tabpanel')).getByText('No exact matches awaiting a decision')).toBeInTheDocument(),
    )

    // 4. ...and it is findable where decisions live, not lost. Scoped to the table:
    //    the open decision panel names her too, and matching that would prove nothing.
    await setState(user, /^Decided$/)
    const decidedTable = await screen.findByRole('table', { name: 'Exact identifier matches' })
    expect(within(decidedTable).getByText('Aisha Bello')).toBeInTheDocument()
  })

  /* -------------------------------------------------------- bulk decisions */

  /** Tick the select-all box in the currently open band tab. */
  async function selectAllRows(user: ReturnType<typeof userEvent.setup>, caption: string) {
    const table = await screen.findByRole('table', { name: caption })
    const [selectAll] = within(table).getAllByRole('checkbox')
    await user.click(selectAll!)
  }

  it('decides many exact matches at once', async () => {
    // The case this exists for: a re-uploaded cohort where every row is the same person
    // as an existing record, and the only question is serve-or-discard.
    resolveRow.mockResolvedValue({ ...EXACT_ROW, resolution: 'skip' })
    const user = userEvent.setup()
    renderPage()
    await ready()

    await openTab(user, /Exact matches/)
    await selectAllRows(user, 'Exact identifier matches')
    expect(await screen.findByText('1 selected')).toBeInTheDocument()

    await user.click(screen.getByRole('button', { name: /^Discard$/ }))

    await waitFor(() =>
      expect(resolveRow).toHaveBeenCalledWith('ib1', 1, expect.objectContaining({ resolution: 'skip' })),
    )
  })

  it('links each exact match to its own matched record', async () => {
    // There is no single "the" beneficiary across a selection — each row carries its own
    // candidate, and the server rejects an id that is not a candidate for that row.
    resolveRow.mockResolvedValue({ ...EXACT_ROW, resolution: 'link' })
    const user = userEvent.setup()
    renderPage()
    await ready()

    await openTab(user, /Exact matches/)
    await selectAllRows(user, 'Exact identifier matches')
    await user.click(screen.getByRole('button', { name: /Provide service/ }))

    await waitFor(() =>
      expect(resolveRow).toHaveBeenCalledWith(
        'ib1',
        1,
        expect.objectContaining({ resolution: 'link', beneficiary_id: 'ben-existing' }),
      ),
    )
  })

  it('offers no bulk same-person judgement on probable matches', async () => {
    // FR-DUP-09 / CLAUDE.md §9: asserting sameness across many people without anyone
    // looking is the auto-merge the rule forbids. Discard creates nothing, so it stays.
    const user = userEvent.setup()
    renderPage()
    await ready()

    await openTab(user, /Possible matches/)
    await selectAllRows(user, 'Probable matches awaiting judgement')

    expect(await screen.findByText('1 selected')).toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /Provide service/ })).not.toBeInTheDocument()
    expect(screen.getByRole('button', { name: /^Discard$/ })).toBeInTheDocument()
    // ...and it says why, where the missing action would have been.
    expect(screen.getByText(/decide those individually/i)).toBeInTheDocument()
  })

  it('offers bulk only over the awaiting queue', async () => {
    // Selecting across decided rows invites re-deciding something already settled.
    const user = userEvent.setup()
    renderPage()
    await ready()

    await openTab(user, /Possible matches/)
    await setState(user, /^Decided$/)

    const table = await screen.findByRole('table', { name: 'Probable matches awaiting judgement' })
    expect(within(table).queryAllByRole('checkbox')).toHaveLength(0)
  })

  it('keeps a failed row selected so it can be retried', async () => {
    resolveRow.mockRejectedValue(new Error('boom'))
    const user = userEvent.setup()
    renderPage()
    await ready()

    await openTab(user, /Exact matches/)
    await selectAllRows(user, 'Exact identifier matches')
    await user.click(screen.getByRole('button', { name: /^Discard$/ }))

    await waitFor(() => expect(screen.getByText('1 selected')).toBeInTheDocument())
  })

  it('says which filter is hiding the rest when a view is empty', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const exact = await openTab(user, /Exact matches/)
    await setState(user, /^Decided$/)

    // "No exact matches" alone would read as "there are none", which is false.
    expect(within(exact).getByText('No exact matches decided yet')).toBeInTheDocument()
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
