import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { AdjudicateQueuePage } from './AdjudicateQueuePage'
import { importApi } from './api'

vi.mock('./api', () => ({
  importApi: { get: vi.fn(), resolveRow: vi.fn() },
}))

vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: () => true, user: { mda: { id: 'mda-1', name: 'Health' } } }),
}))

const get = importApi.get as Mock
const resolveRow = importApi.resolveRow as Mock

function row(overrides: Record<string, unknown> = {}) {
  return {
    row_number: 1,
    original_record_id: null,
    is_valid: true,
    errors: [],
    beneficiary_id: null,
    resolution: null,
    resolution_note: null,
    resolved_beneficiary_id: null,
    resolved_at: null,
    match: {
      band: 'probable',
      candidates: [
        {
          type: 'registry',
          band: 'probable',
          score: 0.87,
          matched_fields: ['last_name'],
          stage: 'fuzzy',
          comparison: [
            { field: 'nin', verdict: 'exact', similarity: 1, weight: null, participated: true, deterministic: true },
            { field: 'last_name', verdict: 'near', similarity: 0.9, weight: 0.4, participated: true, deterministic: false },
            { field: 'date_of_birth', verdict: 'differs', similarity: 0.2, weight: 0.3, participated: true, deterministic: false },
            { field: 'phone', verdict: 'absent_existing', similarity: 0, weight: 0.3, participated: true, deterministic: false },
          ],
          reveal: {
            id: 'ben-9',
            full_name: 'Aminu Bala',
            owner_mda: { id: 'mda-2', name: 'Ministry of Health' },
            registration_source: 'kobo',
            registration_date: '2025-02-01',
            lga: 'dutse',
            ward: 'Ward 1',
            status: 'active',
            programmes: [],
            benefits: { summary: null, items: [] },
          },
        },
      ],
    },
    preview: {
      first_name: 'Aminu',
      last_name: 'Bello',
      nin: '12345678901',
      bvn: null,
      phone: '08030000000',
      date_of_birth: '1984-03-02',
      gender: 'male',
      lga: 'dutse',
      ward: 'Ward 1',
    },
    ...overrides,
  }
}

function batch(rows: unknown[]) {
  return {
    id: 'batch-1',
    owner_mda_id: 'mda-1',
    uploaded_by: 'u1',
    original_filename: 'kobo-export.xlsx',
    source: 'kobo',
    activity_id: 'act-1',
    draft_activity_name: null,
    draft_target_beneficiaries: null,
    target_mismatch: false,
    status: 'preview_ready',
    summary: {
      total_rows: rows.length, valid_rows: rows.length, invalid_rows: 0,
      committed_rows: 0, served_rows: 0, skipped_rows: 0,
    },
    error: null,
    matching_thresholds: { review: 0.72, auto_accept: 0.95 },
    rows,
    created_at: null,
    updated_at: null,
  }
}

function renderPage(initialEntry = '/imports/batch-1/adjudicate') {
  const client = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={client}>
      <ToastProvider>
        <MemoryRouter initialEntries={[initialEntry]}>
          <Routes>
            <Route path="/imports/:id/adjudicate" element={<AdjudicateQueuePage />} />
          </Routes>
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('AdjudicateQueuePage', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    resolveRow.mockResolvedValue({})
  })

  it('shows the incoming row values beside per-field verdicts', async () => {
    get.mockResolvedValue(batch([row()]))
    renderPage()

    // The officer's own row is shown in full — the whole point of the screen.
    expect(await screen.findByText('12345678901')).toBeInTheDocument()
    expect(screen.getByText('1984-03-02')).toBeInTheDocument()

    // The existing record is represented only by verdicts.
    expect(screen.getByText('Different')).toBeInTheDocument()
    expect(screen.getByText('Nearly the same')).toBeInTheDocument()
    expect(screen.getByText('Not on their record')).toBeInTheDocument()
  })

  it('never renders the existing record identifiers, only the reveal projection', async () => {
    get.mockResolvedValue(batch([row()]))
    renderPage()

    // The reveal name is allowed; NIN/DOB of the other MDA's record are not sent
    // at all, so nothing beyond the incoming column may show them.
    expect(await screen.findByText('Aminu Bala')).toBeInTheDocument()
    const ninCells = screen.getAllByText('12345678901')
    expect(ninCells).toHaveLength(1)
  })

  it('places the score against the configured thresholds instead of printing it', async () => {
    get.mockResolvedValue(batch([row()]))
    renderPage()

    expect(await screen.findByText(/above the review threshold/i)).toBeInTheDocument()
    expect(screen.getByText('review 0.72')).toBeInTheDocument()
    expect(screen.getByText('auto-accept 0.95')).toBeInTheDocument()
    // The raw composite is an audit-log detail, not a decision aid.
    expect(screen.queryByText(/0\.87/)).not.toBeInTheDocument()
  })

  it('calls an exact identifier match definitive rather than banding it', async () => {
    const r = row()
    r.match.candidates[0]!.stage = 'deterministic'
    r.match.band = 'exact'
    get.mockResolvedValue(batch([r]))
    renderPage()

    expect(await screen.findByText(/definitively the same person/i)).toBeInTheDocument()
    expect(screen.queryByText(/above the review threshold/i)).not.toBeInTheDocument()
  })

  it('reports progress and opens on the first undecided row', async () => {
    get.mockResolvedValue(
      batch([
        row({ row_number: 1, resolution: 'skip' }),
        row({ row_number: 2 }),
        row({ row_number: 3 }),
      ]),
    )
    renderPage()

    // Row 1 is decided, so the queue opens on row 2.
    expect(await screen.findByText(/2 of 3 · 1 decided/)).toBeInTheDocument()
    expect(screen.getByText('Row 2')).toBeInTheDocument()
  })

  it('moves through the queue with the arrow keys', async () => {
    get.mockResolvedValue(batch([row({ row_number: 1 }), row({ row_number: 2 })]))
    const user = userEvent.setup()
    renderPage()

    expect(await screen.findByText('Row 1')).toBeInTheDocument()
    await user.keyboard('{ArrowRight}')
    await waitFor(() => expect(screen.getByText('Row 2')).toBeInTheDocument())
    await user.keyboard('{ArrowLeft}')
    await waitFor(() => expect(screen.getByText('Row 1')).toBeInTheDocument())
  })

  it('says so plainly when there is nothing to adjudicate', async () => {
    get.mockResolvedValue(batch([row({ match: { band: 'none', candidates: [] } })]))
    renderPage()

    expect(await screen.findByText(/nothing to adjudicate/i)).toBeInTheDocument()
  })

  /**
   * The shape a real exact-NIN duplicate arrives in: `is_valid: false` (the row carries
   * duplicate errors), a deterministic stage, score 1, and no fuzzy weights. Every
   * flagged row in a re-uploaded cohort looks like this, so if it cannot render, the
   * whole adjudication queue is unreachable exactly when it is needed.
   */
  function exactDuplicateRow(rowNumber: number) {
    return row({
      row_number: rowNumber,
      is_valid: false,
      errors: [
        { field: 'nin', message: 'A beneficiary with this NIN is already registered.', group: 'duplicate' },
        { field: 'date_of_birth', message: 'The date of birth field is required.', group: 'dropped' },
      ],
      match: {
        band: 'exact',
        candidates: [
          {
            type: 'registry',
            band: 'exact',
            score: 1,
            matched_fields: ['nin'],
            stage: 'deterministic',
            comparison: [
              { field: 'nin', verdict: 'exact', similarity: 1, weight: null, participated: true, deterministic: true },
            ],
            reveal: {
              id: 'ben-1',
              full_name: 'Ladidi Ciroma',
              owner_mda: { id: 'mda-2', name: 'Ministry of Health' },
              registration_source: 'excel',
              registration_date: '2026-08-17',
              lga: 'auyo',
              ward: 'Kafur',
              status: 'active',
              programmes: [
                {
                  programme_id: 'p-1',
                  name: 'Health Insurance',
                  owner_mda: { id: 'mda-2', name: 'Ministry of Health' },
                  status: 'enrolled',
                },
              ],
              benefits: { summary: { count: 0, total_value: null, last_delivery_date: null, types: [] }, items: [] },
            },
          },
        ],
      },
      preview: {
        first_name: 'Ladidi Ciroma',
        last_name: 'Ladidi Ciroma',
        nin: '•••••••2619',
        bvn: null,
        phone: '07085046387',
        date_of_birth: null,
        gender: 'female',
        lga: 'auyo',
        ward: 'Kafur',
      },
    })
  }

  it('renders an exact-NIN duplicate row', async () => {
    get.mockResolvedValue(batch([exactDuplicateRow(5)]))
    renderPage()

    expect(await screen.findByText('Row 5')).toBeInTheDocument()
    // The existing record is disclosed so the decision can actually be made.
    expect(screen.getByText('Ladidi Ciroma')).toBeInTheDocument()
    expect(screen.getByText('Ministry of Health')).toBeInTheDocument()
  })

  it('states what the existing person already received, without calling it expenditure', async () => {
    // The reveal exists to answer "is this the same person, and are we about to serve
    // them twice" — so the delivery history has to render, not just be present.
    const withHistory = exactDuplicateRow(5)
    const candidate = (withHistory.match as { candidates: { reveal: Record<string, unknown> }[] }).candidates[0]!
    candidate.reveal.benefits = {
      summary: { count: 3, total_value: 4500000, last_delivery_date: '2026-06-30', types: ['cash'] },
      items: [],
    }

    get.mockResolvedValue(batch([withHistory]))
    renderPage()

    const line = await screen.findByText(/3 deliveries/)
    expect(line).toHaveTextContent('₦45,000.00 delivered')
    expect(line).toHaveTextContent('last 2026-06-30')
    // Delivery value is NOT treasury expenditure.
    expect(line).not.toHaveTextContent(/spent|disbursed|expenditure/i)
  })

  it('omits the monetary value when the viewing MDA may not see it', async () => {
    // null total_value means "not permitted to see", which is not zero — showing ₦0.00
    // would state something false about what this person received.
    const masked = exactDuplicateRow(5)
    const candidate = (masked.match as { candidates: { reveal: Record<string, unknown> }[] }).candidates[0]!
    candidate.reveal.benefits = {
      summary: { count: 2, total_value: null, last_delivery_date: '2026-06-30', types: [] },
      items: [],
    }

    get.mockResolvedValue(batch([masked]))
    renderPage()

    const line = await screen.findByText(/2 deliveries/)
    expect(line).not.toHaveTextContent('₦')
  })

  it('renders a queue of exact duplicates with no matching thresholds configured', async () => {
    // A deterministic match needs no thresholds, and a batch can legitimately have none.
    const b = batch([exactDuplicateRow(5), exactDuplicateRow(6)])
    get.mockResolvedValue({ ...b, matching_thresholds: null })
    renderPage()

    expect(await screen.findByText('Row 5')).toBeInTheDocument()
    expect(screen.getByText(/1 of 2/)).toBeInTheDocument()
  })
})
