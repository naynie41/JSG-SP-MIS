import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { AdminIntegrationsPage } from './AdminIntegrationsPage'
import { syncApi } from '@/features/sync/api'
import { importApi } from '@/features/registry/api'
import { ApiError } from '@/types/api'
import type { SyncConnector, SyncRun } from '@/features/sync/types'

vi.mock('@/features/sync/api', () => ({
  syncApi: {
    connectors: vi.fn(), runs: vi.fn(), trigger: vi.fn(),
    mapping: vi.fn(), confirmMapping: vi.fn(), setEnabled: vi.fn(),
  },
}))
vi.mock('@/features/registry/api', () => ({
  importApi: { list: vi.fn(), get: vi.fn(), upload: vi.fn() },
}))

const perms = { value: [] as string[] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { role: { key: 'system_administrator' }, name: 'Admin' },
    hasPermission: (p: string) => perms.value.includes(p),
  }),
}))

const connectors = syncApi.connectors as Mock
const runs = syncApi.runs as Mock
const trigger = syncApi.trigger as Mock
const mappingProposal = syncApi.mapping as Mock
const listImports = importApi.list as Mock

/** A connector whose standing mapping is approved and current (CLAUDE.md §11). */
const confirmedMapping: SyncConnector['mapping'] = {
  status: 'confirmed',
  confirmed_at: new Date().toISOString(),
  confirmed_by: 'Amina Bello',
  stale_at: null,
  stale_reason: null,
  can_enable: true,
}

const connectorRows: SyncConnector[] = [
  {
    id: 'c1', name: 'SOCU feed', source: 'socu', owner_mda_id: 'm1',
    owner_mda: { id: 'm1', name: 'MDA A' }, conflict_policy: 'flag_for_review',
    enabled: true, schedule: 'daily', last_run_at: new Date().toISOString(),
    mapping: confirmedMapping,
    activity: { id: 'a1', name: 'Q3 cash transfer', blocker: null },
  },
  {
    id: 'c2', name: 'Legacy registry', source: 'government_system', owner_mda_id: 'm2',
    owner_mda: { id: 'm2', name: 'MDA B' }, conflict_policy: 'skip',
    enabled: false, schedule: null, last_run_at: null,
    mapping: {
      status: 'never_configured',
      confirmed_at: null, confirmed_by: null, stale_at: null, stale_reason: null,
      can_enable: false,
    },
    // Unbound as well as unmapped: the listing must say which decision is missing.
    activity: { id: null, name: null, blocker: 'This connector has no target activity.' },
  },
]

const runRows: SyncRun[] = [
  {
    id: 'r1', connector_id: 'c1', trigger: 'manual', source: 'socu', owner_mda_id: 'm1',
    conflict_policy: 'flag_for_review', status: 'completed',
    summary: { fetched: 120, created: 100, updated: 15, skipped: 3, flagged: 2, rejected: 0, errors: 0 },
    error: null, started_at: new Date().toISOString(), finished_at: new Date().toISOString(),
    created_at: new Date().toISOString(),
  },
  {
    id: 'r2', connector_id: 'c1', trigger: 'scheduled', source: 'socu', owner_mda_id: 'm1',
    conflict_policy: 'flag_for_review', status: 'failed',
    summary: { fetched: 0, created: 0, updated: 0, skipped: 0, flagged: 0, rejected: 0, errors: 1 },
    error: 'Connection refused', started_at: new Date().toISOString(), finished_at: null,
    created_at: new Date().toISOString(),
  },
]

/** Phase 7 absent: the sync surface answers 404. */
const notBuilt = () => Promise.reject(new ApiError(404, 'NOT_FOUND', 'The requested resource was not found.'))

function renderPage() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter>
          <AdminIntegrationsPage />
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('Admin console — Integrations', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms.value = ['sync.view', 'sync.run', 'beneficiary.view']
    connectors.mockResolvedValue(connectorRows)
    runs.mockResolvedValue({ items: runRows, pagination: { page: 1, per_page: 20, total: 2, total_pages: 1 } })
    listImports.mockResolvedValue({ items: [], pagination: { page: 1, per_page: 25, total: 0, total_pages: 1 } })
  })

  /* ------------------------------------------------ WIRED: Phase 7 present */

  it('composes the sync engine when it answers — connected systems', async () => {
    renderPage()

    expect(await screen.findByText('SOCU feed')).toBeInTheDocument()
    expect(screen.getByText('Legacy registry')).toBeInTheDocument()
    expect(screen.getByText('enabled')).toBeInTheDocument()
    expect(screen.getByText('disabled')).toBeInTheDocument()
    expect(screen.getByText('Flag for review')).toBeInTheDocument()
    expect(connectors).toHaveBeenCalled()
  })

  it('shows synchronization status and history', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('SOCU feed')

    await user.click(screen.getByRole('tab', { name: /sync status & history/i }))

    expect(await screen.findByText('Synchronization history')).toBeInTheDocument()
    expect(screen.getByText('completed')).toBeInTheDocument()
    expect(screen.getByText('failed')).toBeInTheDocument()
    expect(screen.getByText('120')).toBeInTheDocument() // fetched
    expect(screen.getByText('Succeeded')).toBeInTheDocument()
    expect(runs).toHaveBeenCalled()
  })

  it('triggers manual synchronization through the existing endpoint', async () => {
    trigger.mockResolvedValue({ message: 'Sync started for SOCU feed.' })
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('SOCU feed')

    const buttons = screen.getAllByRole('button', { name: /sync now/i })
    await user.click(buttons[0]!)

    await waitFor(() => expect(trigger).toHaveBeenCalledWith('c1'))
  })

  /* ------------------------------- the connector's standing mapping (§11) */

  it('shows each connector’s mapping status, and who confirmed it', async () => {
    renderPage()
    await screen.findByText('SOCU feed')

    expect(screen.getByText('Confirmed')).toBeInTheDocument()
    expect(screen.getByText(/by Amina Bello/)).toBeInTheDocument()
    // Never-configured is shown distinctly — it needs a first mapping, not a review.
    expect(screen.getByText('Not configured')).toBeInTheDocument()
  })

  it('distinguishes a STALE mapping from one that was never configured', async () => {
    connectors.mockResolvedValue([
      {
        ...connectorRows[0]!,
        mapping: {
          status: 'stale',
          confirmed_at: new Date().toISOString(),
          confirmed_by: 'Amina Bello',
          stale_at: new Date().toISOString(),
          stale_reason: 'the source’s fields changed since the mapping was confirmed.',
          can_enable: false,
        },
      },
    ])
    renderPage()

    // A feed that WAS working and has stopped must not be filed under "not set up yet".
    expect(await screen.findByText('Needs review')).toBeInTheDocument()
    expect(screen.getByText(/fields changed/i)).toBeInTheDocument()
  })

  it('will not offer to enable a connector whose mapping is not confirmed', async () => {
    renderPage()
    await screen.findByText('Legacy registry')

    // c2 is disabled AND unmapped — enabling is refused server-side, so the control is
    // disabled rather than inviting a click that 422s.
    const enable = screen.getByRole('button', { name: /^enable$/i })
    expect(enable).toBeDisabled()
  })

  it('does not offer manual sync for a connector that is not mapped', async () => {
    renderPage()
    await screen.findByText('SOCU feed')

    // Only the confirmed connector can sync; the unmapped one would be held anyway.
    const syncButtons = screen.getAllByRole('button', { name: /sync now/i })
    expect(syncButtons[0]).toBeEnabled()
    expect(syncButtons[1]).toBeDisabled()
  })

  it('opens the mapping screen for a connector', async () => {
    mappingProposal.mockResolvedValue({
      detected_fields: ['surname'],
      suggestions: { last_name: { header: 'surname', confidence: 'high', reason: '' } },
      column_map: {},
      samples: {},
      normalized_preview: [],
      identity_fields: ['last_name'],
      unconfirmed_identity_fields: ['last_name'],
      source_signature: 's', confirmed_signature: null,
      signature_changed: false, mapping_confirmed_at: null,
    })
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Legacy registry')

    await user.click(screen.getByRole('button', { name: /map columns/i }))

    expect(await screen.findByRole('dialog')).toBeInTheDocument()
    expect(mappingProposal).toHaveBeenCalledWith('c2')
  })

  it('does not offer manual sync without sync.run', async () => {
    perms.value = ['sync.view', 'beneficiary.view'] // view only
    renderPage()
    await screen.findByText('SOCU feed')

    expect(screen.queryByRole('button', { name: /sync now/i })).not.toBeInTheDocument()
  })

  /* --------------------------------------------- STUBBED: Phase 7 absent */

  it('renders the pending state when the sync engine is not enabled — and fabricates nothing', async () => {
    connectors.mockImplementation(notBuilt)
    runs.mockImplementation(notBuilt)
    renderPage()

    expect(await screen.findByText(/Available when synchronization \(Phase 7\) is enabled/i)).toBeInTheDocument()

    // No fabricated connectors, counters or runs.
    expect(screen.queryByText('SOCU feed')).not.toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /sync now/i })).not.toBeInTheDocument()
    expect(screen.queryByText('Succeeded')).not.toBeInTheDocument()
    expect(screen.getByText(/never displays placeholder sync data/i)).toBeInTheDocument()
  })

  it('shows the pending state on the history tab too when sync is absent', async () => {
    connectors.mockImplementation(notBuilt)
    runs.mockImplementation(notBuilt)
    const user = userEvent.setup()
    renderPage()
    await screen.findByText(/Available when synchronization/i)

    await user.click(screen.getByRole('tab', { name: /sync status & history/i }))

    expect(await screen.findByText(/Available when synchronization/i)).toBeInTheDocument()
    expect(screen.queryByText('Synchronization history')).not.toBeInTheDocument()
  })

  it('treats a permission failure as an error, not as "not built"', async () => {
    // 403 must NOT be mistaken for an absent engine — that would hide a real problem.
    connectors.mockImplementation(() =>
      Promise.reject(new ApiError(403, 'FORBIDDEN', 'You do not have permission to perform this action.')),
    )
    renderPage()

    // A real error is retried by the hook (an absent engine is not), so allow for the backoff.
    expect(await screen.findByText(/Could not load connected systems/i, {}, { timeout: 8000 })).toBeInTheDocument()
    expect(screen.queryByText(/Available when synchronization/i)).not.toBeInTheDocument()
    expect(connectors).toHaveBeenCalledTimes(3) // initial + 2 retries
  })

  it('renders the pending state without sync.view, and never probes', async () => {
    perms.value = ['beneficiary.view']
    renderPage()

    expect(await screen.findByText(/Available when synchronization/i)).toBeInTheDocument()
    expect(connectors).not.toHaveBeenCalled()
  })

  /* ------------------------------------------------- import logs (Phase 2) */

  it('reuses the Phase 2 import history for import logs, read-only', async () => {
    perms.value = ['sync.view', 'beneficiary.view', 'beneficiary.create']
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('SOCU feed')

    await user.click(screen.getByRole('tab', { name: /import logs/i }))

    await waitFor(() => expect(listImports).toHaveBeenCalled())
    // Read-only: no upload panel even though the admin holds beneficiary.create.
    expect(screen.queryByText('Upload a file')).not.toBeInTheDocument()
    // Embedded: the host page owns the heading, so the import page's own header is gone.
    expect(screen.queryByRole('heading', { name: 'Bulk import' })).not.toBeInTheDocument()
    expect(screen.getByRole('heading', { name: 'Integrations' })).toBeInTheDocument()
  })
})
