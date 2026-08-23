import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { ResolveRowControls } from './ResolveRowControls'
import { importApi } from './api'
import type { ImportRow, MatchCandidate } from './types'

vi.mock('./api', () => ({
  importApi: { resolveRow: vi.fn() },
}))

const resolveRow = importApi.resolveRow as Mock

function candidate(overrides: Partial<MatchCandidate> = {}): MatchCandidate {
  return {
    type: 'registry',
    band: 'exact',
    score: 1,
    matched_fields: ['nin'],
    stage: 'deterministic',
    comparison: [],
    owned_by_you: false,
    reveal: {
      id: 'ben-other',
      full_name: 'Sadiq Umar',
      owner_mda: { id: 'mda-2', name: 'Ministry of Education' },
      registration_source: 'excel',
      registration_date: '2025-01-01',
      lga: 'ringim',
      ward: 'Ward 1',
      status: 'active',
      programmes: [],
      benefits: { summary: null, items: [] },
    },
    ...overrides,
  } as MatchCandidate
}

/** A candidate on a record the importing MDA already owns. */
function ownCandidate(): MatchCandidate {
  return candidate({
    owned_by_you: true,
    reveal: {
      id: 'ben-mine',
      full_name: 'Zainab Aliyu',
      owner_mda: { id: 'mda-1', name: 'Ministry of Health' },
      registration_source: 'excel',
      registration_date: '2025-01-01',
      lga: 'dutse',
      ward: 'Ward 1',
      status: 'active',
      programmes: [],
      benefits: { summary: null, items: [] },
    },
  } as Partial<MatchCandidate>)
}

function row(candidates: MatchCandidate[]): ImportRow {
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
    match: { band: 'exact', candidates },
    preview: {
      first_name: 'Zainab',
      last_name: 'Aliyu',
      nin: '•••••••0011',
      bvn: null,
      phone: '08030000001',
      date_of_birth: '1990-01-01',
      gender: 'female',
      lga: 'dutse',
      ward: 'Ward 1',
    },
  }
}

function renderControls(candidates: MatchCandidate[]) {
  const client = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={client}>
      <ToastProvider>
        <ResolveRowControls batchId="batch-1" row={row(candidates)} canResolve />
      </ToastProvider>
    </QueryClientProvider>,
  )
}

/**
 * The self-owned re-upload outcome, from the officer's side (FR-DUP-05, FR-OWN-06).
 *
 * The two outcomes must never read as one choice. A cross-MDA match needs another
 * MDA's approval before anything reaches the person; a match on your own record needs
 * nobody's. Presenting both as "link to existing" would hide that difference at the
 * exact moment it decides what happens.
 */
describe('ResolveRowControls — already in your registry', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    resolveRow.mockResolvedValue({})
  })

  it('offers a new intervention, not a request to serve, for a record you own', async () => {
    renderControls([ownCandidate()])

    expect(screen.getByRole('radio', { name: /already in your registry/i })).toBeInTheDocument()
    // Request-to-serve is present but unusable: there is no other MDA to ask.
    expect(screen.getByRole('radio', { name: /request to serve/i })).toBeDisabled()
    expect(screen.getByText(/no second record is created/i)).toBeInTheDocument()
  })

  it('defaults to the own-record outcome and posts it with the existing record', async () => {
    renderControls([ownCandidate()])

    await userEvent.click(screen.getByRole('button', { name: /save decision/i }))

    expect(resolveRow).toHaveBeenCalledWith('batch-1', 1, {
      resolution: 'own',
      note: undefined,
      beneficiary_id: 'ben-mine',
    })
  })

  it('still routes a cross-MDA match to request-to-serve', async () => {
    renderControls([candidate()])

    expect(screen.queryByRole('radio', { name: /already in your registry/i })).not.toBeInTheDocument()
    expect(screen.getByRole('radio', { name: /request to serve/i })).not.toBeDisabled()

    await userEvent.click(screen.getByRole('button', { name: /save decision/i }))

    expect(resolveRow).toHaveBeenCalledWith('batch-1', 1, {
      resolution: 'link',
      note: undefined,
      beneficiary_id: 'ben-other',
    })
  })

  it('switches the target list when the outcome changes, never posting the other list’s record', async () => {
    // A row can match both: your own record AND another MDA's. Carrying a selection
    // across would post a beneficiary the server rejects for that resolution.
    renderControls([ownCandidate(), candidate()])

    await userEvent.click(screen.getByRole('radio', { name: /request to serve/i }))
    await userEvent.click(screen.getByRole('button', { name: /save decision/i }))

    expect(resolveRow).toHaveBeenCalledWith('batch-1', 1, {
      resolution: 'link',
      note: undefined,
      beneficiary_id: 'ben-other',
    })
  })
})
