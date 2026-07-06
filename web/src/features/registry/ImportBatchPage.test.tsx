import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { ImportBatchPage } from './ImportBatchPage'
import { importApi } from './api'
import type { ImportBatch, MatchReveal } from './types'

vi.mock('./api', () => ({
  importApi: { list: vi.fn(), get: vi.fn(), upload: vi.fn(), confirm: vi.fn(), resolveRow: vi.fn() },
}))

vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    hasPermission: () => true,
    hasAnyPermission: () => true,
    user: { mda: { id: 'm-1' } },
    status: 'authenticated',
  }),
}))

const get = importApi.get as Mock
const confirm = importApi.confirm as Mock
const resolveRow = importApi.resolveRow as Mock

const reveal: MatchReveal = {
  id: 'ben-9',
  full_name: 'Zainab Umar',
  owner_mda: { id: 'm-2', name: 'Health' },
  registration_source: 'kobo',
  registration_date: '2025-01-01',
  lga: 'dutse',
  ward: 'Ward 1',
  status: 'active',
  programmes: [],
  benefits: { summary: null, items: [] },
}

function makeBatch(band: 'exact' | 'probable' = 'exact'): ImportBatch {
  return {
    id: 'batch-1',
    owner_mda_id: 'm-1',
    uploaded_by: 'u-1',
    original_filename: 'beneficiaries.csv',
    source: 'csv',
    status: 'preview_ready',
    summary: { total_rows: 1, valid_rows: 1, invalid_rows: 0, committed_rows: 0, served_rows: 0, skipped_rows: 0 },
    error: null,
    rows: [
      {
        row_number: 1,
        original_record_id: 'EXT-1',
        is_valid: true,
        errors: [],
        beneficiary_id: null,
        resolution: null,
        resolution_note: null,
        resolved_beneficiary_id: null,
        match: {
          band,
          candidates: [{ type: 'registry', band, score: band === 'exact' ? 1 : 0.82, matched_fields: band === 'exact' ? ['nin'] : ['last_name'], reveal }],
        },
        preview: { first_name: 'Zainab', last_name: 'Umaru', nin: null, bvn: null, phone: null, date_of_birth: '1990-01-01', gender: 'female', lga: 'dutse', ward: 'Ward 1' },
      },
    ],
    created_at: null,
    updated_at: null,
  }
}

function renderPage(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter initialEntries={['/imports/batch-1']}>
          <Routes>
            <Route path="/imports/:id" element={ui} />
          </Routes>
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('ImportBatchPage — duplicate resolution', () => {
  beforeEach(() => vi.clearAllMocks())

  it('shows a match badge and reveals the existing record on expand', async () => {
    get.mockResolvedValue(makeBatch())
    const user = userEvent.setup()
    renderPage(<ImportBatchPage />)

    // Match badge in the row.
    expect((await screen.findAllByText('Exact')).length).toBeGreaterThan(0)

    // Expand the flagged row → the reveal panel discloses the existing record.
    await user.click(screen.getByRole('button', { name: /expand row 1/i }))
    expect(await screen.findByText('Zainab Umar')).toBeInTheDocument()
    expect(screen.getByText('Health')).toBeInTheDocument()
    // Phase-4 sections present but empty (programmes + benefits).
    expect(screen.getAllByText(/populates in Phase 4/i)).toHaveLength(2)
  })

  it('resolves a flagged row as link / request-to-serve without creating a duplicate', async () => {
    get.mockResolvedValue(makeBatch())
    resolveRow.mockResolvedValue({ ...makeBatch().rows![0], resolution: 'link', resolved_beneficiary_id: 'ben-9' })
    const user = userEvent.setup()
    renderPage(<ImportBatchPage />)

    await user.click(await screen.findByRole('button', { name: /expand row 1/i }))
    // Link is the default decision when a registry candidate exists.
    await user.click(screen.getByRole('button', { name: /save decision/i }))

    await waitFor(() =>
      expect(resolveRow).toHaveBeenCalledWith('batch-1', 1, { resolution: 'link', note: undefined, beneficiary_id: 'ben-9' }),
    )
  })

  it('requires a justification to adjudicate a probable row as new', async () => {
    get.mockResolvedValue(makeBatch('probable'))
    const user = userEvent.setup()
    renderPage(<ImportBatchPage />)

    await user.click(await screen.findByRole('button', { name: /expand row 1/i }))
    await user.click(screen.getByLabelText(/create new/i))
    await user.click(screen.getByRole('button', { name: /save decision/i }))

    expect(await screen.findByText(/justification is required/i)).toBeInTheDocument()
    expect(resolveRow).not.toHaveBeenCalled()
  })

  it('hides the same-person adjudication on exact matches but keeps discard/serve (§5.9)', async () => {
    get.mockResolvedValue(makeBatch('exact'))
    const user = userEvent.setup()
    renderPage(<ImportBatchPage />)

    await user.click(await screen.findByRole('button', { name: /expand row 1/i }))

    // No "create new" adjudication control on an exact (definitive) match...
    expect(screen.queryByLabelText(/create new/i)).not.toBeInTheDocument()
    // ...but the discard / provide-service choices remain, plus the definitive note.
    expect(screen.getByLabelText(/provide service/i)).toBeInTheDocument()
    expect(screen.getByLabelText(/discard this row/i)).toBeInTheDocument()
    expect(screen.getByText(/exact match — definitively the same person/i)).toBeInTheDocument()
  })

  it('shows the same-person adjudication on probable matches (§5.9)', async () => {
    get.mockResolvedValue(makeBatch('probable'))
    const user = userEvent.setup()
    renderPage(<ImportBatchPage />)

    await user.click(await screen.findByRole('button', { name: /expand row 1/i }))

    // Adjudication offered for a probable (fuzzy) match, alongside discard/serve.
    expect(screen.getByLabelText(/create new/i)).toBeInTheDocument()
    expect(screen.getByLabelText(/provide service/i)).toBeInTheDocument()
    expect(screen.getByLabelText(/discard this row/i)).toBeInTheDocument()
  })

  it('confirms the batch', async () => {
    get.mockResolvedValue(makeBatch())
    confirm.mockResolvedValue({ ...makeBatch(), status: 'completed' })
    const user = userEvent.setup()
    renderPage(<ImportBatchPage />)

    await user.click(await screen.findByRole('button', { name: /confirm & commit/i }))
    await waitFor(() => expect(confirm).toHaveBeenCalledWith('batch-1'))
  })
})
