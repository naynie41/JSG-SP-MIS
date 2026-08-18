import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { ImportBatchPage } from './ImportBatchPage'
import { formatWaitedFor } from '@/lib/utils/duration'
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
    activity_id: 'a-1',
    programme_id: 'p-1',
    draft_activity_name: null,
    draft_target_beneficiaries: null,
    target_mismatch: false,
    status: 'preview_ready',
    summary: { total_rows: 1, valid_rows: 1, invalid_rows: 0, committed_rows: 0, served_rows: 0, skipped_rows: 0 },
    error: null,
    processing_for_seconds: null,
    processing_stalled: false,
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
        resolved_at: null,
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
    await user.click(screen.getByRole('button', { name: /expand zainab umaru/i }))
    expect(await screen.findByText('Zainab Umar')).toBeInTheDocument()
    expect(screen.getByText('Health')).toBeInTheDocument()
    // Programme/benefit sections render only when there is something to
    // disclose — empty "populates in Phase 4" placeholders used to occupy half
    // the panel at the decision moment and leaked roadmap vocabulary.
    expect(screen.queryByText(/phase 4/i)).not.toBeInTheDocument()
  })

  it('resolves a flagged row as link / request-to-serve without creating a duplicate', async () => {
    get.mockResolvedValue(makeBatch())
    resolveRow.mockResolvedValue({ ...makeBatch().rows![0], resolution: 'link', resolved_beneficiary_id: 'ben-9' })
    const user = userEvent.setup()
    renderPage(<ImportBatchPage />)

    await user.click(await screen.findByRole('button', { name: /expand zainab umaru/i }))
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

    await user.click(await screen.findByRole('button', { name: /expand zainab umaru/i }))
    await user.click(screen.getByLabelText(/create new/i))
    await user.click(screen.getByRole('button', { name: /save decision/i }))

    expect(await screen.findByText(/justification is required/i)).toBeInTheDocument()
    expect(resolveRow).not.toHaveBeenCalled()
  })

  it('hides the same-person adjudication on exact matches but keeps discard/serve (§5.9)', async () => {
    get.mockResolvedValue(makeBatch('exact'))
    const user = userEvent.setup()
    renderPage(<ImportBatchPage />)

    await user.click(await screen.findByRole('button', { name: /expand zainab umaru/i }))

    // No "create new" adjudication control on an exact (definitive) match...
    expect(screen.queryByLabelText(/create new/i)).not.toBeInTheDocument()
    // ...but the discard / provide-service choices remain, plus the definitive note.
    expect(screen.getByLabelText(/provide service/i)).toBeInTheDocument()
    expect(screen.getByLabelText(/discard this row/i)).toBeInTheDocument()
    expect(
      screen.getByText(/a new record cannot be created for an exact identifier match/i),
    ).toBeInTheDocument()
  })

  it('shows the same-person adjudication on probable matches (§5.9)', async () => {
    get.mockResolvedValue(makeBatch('probable'))
    const user = userEvent.setup()
    renderPage(<ImportBatchPage />)

    await user.click(await screen.findByRole('button', { name: /expand zainab umaru/i }))

    // Adjudication offered for a probable (fuzzy) match, alongside discard/serve.
    expect(screen.getByLabelText(/create new/i)).toBeInTheDocument()
    expect(screen.getByLabelText(/provide service/i)).toBeInTheDocument()
    expect(screen.getByLabelText(/discard this row/i)).toBeInTheDocument()
  })

  it('confirms the batch only after the commit is confirmed in a dialog', async () => {
    get.mockResolvedValue(makeBatch())
    confirm.mockResolvedValue({ ...makeBatch(), status: 'completed' })
    const user = userEvent.setup()
    renderPage(<ImportBatchPage />)

    // Commit is irreversible and silently discards undecided flagged rows, so
    // the page-head button opens a dialog rather than writing immediately.
    await user.click(await screen.findByRole('button', { name: /confirm & commit/i }))
    expect(confirm).not.toHaveBeenCalled()

    const dialog = await screen.findByRole('dialog')
    expect(within(dialog).getByText(/cannot be undone/i)).toBeInTheDocument()

    await user.click(within(dialog).getByRole('button', { name: /confirm & commit/i }))
    await waitFor(() => expect(confirm).toHaveBeenCalledWith('batch-1'))
  })

  it('defaults to the rows that need review and can show all rows', async () => {
    get.mockResolvedValue(makeBatch())
    const user = userEvent.setup()
    renderPage(<ImportBatchPage />)

    // The flagged subset is the work; the officer should not have to find it.
    const reviewTab = await screen.findByRole('button', { name: /needs review/i })
    expect(reviewTab).toHaveAttribute('aria-pressed', 'true')

    await user.click(screen.getByRole('button', { name: /all rows/i }))
    expect(screen.getByRole('button', { name: /all rows/i })).toHaveAttribute('aria-pressed', 'true')
  })

  /**
   * Deciding a whole cohort at once.
   *
   * A re-uploaded cohort arrives as dozens of exact NIN matches, and one-at-a-time is
   * the wrong tool for a decision that carries no per-person judgement. These pin what
   * bulk may and may not do — it writes audited judgements about citizens' identities,
   * so it must not be able to do anything the per-row control forbids.
   */
  describe('deciding many rows at once', () => {
    /** A flagged row matching its own distinct existing record. */
    function flaggedRow(rowNumber: number, band: 'exact' | 'probable', beneficiaryId: string) {
      const base = makeBatch(band).rows![0]!
      return {
        ...base,
        row_number: rowNumber,
        match: {
          band,
          candidates: [
            {
              ...base.match.candidates[0]!,
              band,
              reveal: { ...reveal, id: beneficiaryId, full_name: `Existing ${rowNumber}` },
            },
          ],
        },
        preview: { ...base.preview, first_name: `Person${rowNumber}`, last_name: 'Test' },
      }
    }

    async function selectAll(user: ReturnType<typeof userEvent.setup>) {
      await user.click(await screen.findByRole('checkbox', { name: /select all/i }))
    }

    it('links every selected row to ITS OWN matched record', async () => {
      // The failure this prevents: one shared beneficiary id attaching a whole
      // selection to the wrong person.
      get.mockResolvedValue({
        ...makeBatch(),
        rows: [flaggedRow(1, 'exact', 'ben-A'), flaggedRow(2, 'exact', 'ben-B')],
      })
      resolveRow.mockResolvedValue({})
      const user = userEvent.setup()
      renderPage(<ImportBatchPage />)

      await selectAll(user)
      await user.click(screen.getByRole('button', { name: /provide service/i }))

      await waitFor(() => expect(resolveRow).toHaveBeenCalledTimes(2))
      expect(resolveRow).toHaveBeenCalledWith('batch-1', 1, expect.objectContaining({ resolution: 'link', beneficiary_id: 'ben-A' }))
      expect(resolveRow).toHaveBeenCalledWith('batch-1', 2, expect.objectContaining({ resolution: 'link', beneficiary_id: 'ben-B' }))
    })

    it('does not offer "create as new" when the selection contains an exact match', async () => {
      // §9: an exact match is definitive and is never adjudicated as a new person. The
      // server refuses it, so offering it in bulk would invite 45 identical failures.
      get.mockResolvedValue({ ...makeBatch(), rows: [flaggedRow(1, 'exact', 'ben-A'), flaggedRow(2, 'probable', 'ben-B')] })
      const user = userEvent.setup()
      renderPage(<ImportBatchPage />)

      await selectAll(user)

      expect(screen.queryByRole('button', { name: /create as new/i })).not.toBeInTheDocument()
      // ...while the decisions that ARE valid at every band remain available.
      expect(screen.getByRole('button', { name: /provide service/i })).toBeEnabled()
      expect(screen.getByRole('button', { name: /skip selected/i })).toBeEnabled()
    })

    it('offers "create as new" when every selected row is a probable match', async () => {
      get.mockResolvedValue({ ...makeBatch(), rows: [flaggedRow(1, 'probable', 'ben-A'), flaggedRow(2, 'probable', 'ben-B')] })
      const user = userEvent.setup()
      renderPage(<ImportBatchPage />)

      await selectAll(user)

      expect(screen.getByRole('button', { name: /create as new/i })).toBeInTheDocument()
    })

    it('cannot provide service for a row with no matched record', async () => {
      // Nothing to link TO. It must be reported and stay selected, never guessed at.
      const orphan = { ...flaggedRow(1, 'probable', 'ben-A'), match: { band: 'probable', candidates: [] } }
      get.mockResolvedValue({ ...makeBatch(), rows: [orphan] })
      const user = userEvent.setup()
      renderPage(<ImportBatchPage />)

      await selectAll(user)
      expect(screen.getByRole('button', { name: /provide service/i })).toBeDisabled()
      expect(resolveRow).not.toHaveBeenCalled()
    })
  })

  describe('a batch nothing is working on', () => {
    it('says it is processing while it genuinely is', async () => {
      get.mockResolvedValue({
        ...makeBatch(),
        status: 'pending',
        rows: [],
        processing_for_seconds: 3,
        processing_stalled: false,
      })
      renderPage(<ImportBatchPage />)

      expect(await screen.findByText(/Processing… this view refreshes automatically/)).toBeInTheDocument()
      expect(screen.queryByText(/background worker/i)).not.toBeInTheDocument()
    })

    it('stops claiming progress once it has waited too long', async () => {
      // The failure has no error to show — nothing failed, the job was never picked up.
      // Saying "Processing…" indefinitely is what wasted someone's afternoon.
      get.mockResolvedValue({
        ...makeBatch(),
        status: 'pending',
        rows: [],
        processing_for_seconds: 600,
        processing_stalled: true,
      })
      renderPage(<ImportBatchPage />)

      const alert = await screen.findByRole('alert')
      expect(alert).toHaveTextContent(/Still waiting after 10 minutes/)
      expect(alert).toHaveTextContent(/background worker/i)
      // Reassure, and point at who can fix it — the officer cannot.
      expect(alert).toHaveTextContent(/Nothing has been lost/)

      // The contradictory "Processing…" line is gone, not merely joined by a warning.
      expect(screen.queryByText(/this view refreshes automatically/)).not.toBeInTheDocument()
      expect(await screen.findByText('Not started yet')).toBeInTheDocument()
    })

    it('shows no worker warning for a batch that is not queued', async () => {
      // A settled batch is not waiting on anything; warning here would train people to
      // ignore the warning.
      get.mockResolvedValue({ ...makeBatch(), processing_for_seconds: null, processing_stalled: false })
      renderPage(<ImportBatchPage />)

      await screen.findByText('beneficiaries.csv')
      expect(screen.queryByText(/background worker/i)).not.toBeInTheDocument()
      expect(screen.queryByText(/this view refreshes automatically/)).not.toBeInTheDocument()
    })
  })
})

describe('formatWaitedFor', () => {
  it('reads like a status, not a stack trace', () => {
    expect(formatWaitedFor(45)).toBe('45 seconds')
    expect(formatWaitedFor(119)).toBe('119 seconds')
    expect(formatWaitedFor(600)).toBe('10 minutes')
    expect(formatWaitedFor(3600)).toBe('1 hour')
    expect(formatWaitedFor(5400)).toBe('1 hour 30 min')
    expect(formatWaitedFor(7200)).toBe('2 hours')
  })

  it('never renders a negative or missing duration', () => {
    // Clock skew between server and client must not produce "-3 seconds".
    expect(formatWaitedFor(null)).toBe('0 seconds')
    expect(formatWaitedFor(-5)).toBe('0 seconds')
  })
})
