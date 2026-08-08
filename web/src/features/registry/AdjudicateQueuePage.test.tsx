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
})
