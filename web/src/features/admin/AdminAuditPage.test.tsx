import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { AdminAuditPage } from './AdminAuditPage'
import { adminApi } from './api'
import type { AuditEntry } from './types'

vi.mock('./api', () => ({ adminApi: { auditLogs: vi.fn(), exportAuditLogs: vi.fn() } }))

const perms = { value: [] as string[] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { role: { key: 'system_administrator' }, name: 'Admin' },
    hasPermission: (p: string) => perms.value.includes(p),
  }),
}))

const auditLogs = adminApi.auditLogs as Mock
const exportAuditLogs = adminApi.exportAuditLogs as Mock

const entries: AuditEntry[] = [
  {
    id: 'a1', action: 'auth.login_failed', category: 'security', entity_type: 'User', entity_id: 'u1',
    actor: 'Ada Officer', actor_id: 'u1', actor_mda: 'MDA A', ip_address: '10.0.0.1',
    correlation_id: 'c1', chain_position: 42, changed_fields: [], at: new Date().toISOString(),
  },
  {
    id: 'a2', action: 'service_request.accepted', category: 'service_request', entity_type: 'ServiceRequest', entity_id: 's1',
    actor: 'Bola Admin', actor_id: 'u2', actor_mda: 'MDA B', ip_address: '10.0.0.2',
    correlation_id: 'c2', chain_position: 43, changed_fields: ['status'], at: new Date().toISOString(),
  },
  {
    id: 'a3', action: 'user.updated', category: 'activity', entity_type: 'User', entity_id: 'u3',
    actor: 'System', actor_id: null, actor_mda: null, ip_address: null,
    correlation_id: null, chain_position: 44, changed_fields: ['email', 'password', 'status'], at: new Date().toISOString(),
  },
]

function page(items: AuditEntry[] = entries) {
  return { items, pagination: { page: 1, per_page: 25, total: items.length, total_pages: 1 } }
}

function renderPage() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <MemoryRouter>
        <AdminAuditPage />
      </MemoryRouter>
    </QueryClientProvider>,
  )
}

describe('Admin console — Audit & Security (read-only over the immutable log)', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms.value = ['dashboard.view', 'reporting.export']
    auditLogs.mockResolvedValue(page())
  })

  /* ---------------------------------------------------------------- rendering */

  it('lists audit events with actor, category, entity, IP and chain position', async () => {
    renderPage()

    expect(await screen.findByText('Auth login failed')).toBeInTheDocument()
    expect(screen.getByText('Security')).toBeInTheDocument()
    expect(screen.getByText(/Ada Officer/)).toBeInTheDocument()
    expect(screen.getByText('10.0.0.1')).toBeInTheDocument()
    expect(screen.getByText('42')).toBeInTheDocument()
    // Request-to-serve decisions are visible in the same review surface.
    expect(screen.getByText('Service request accepted')).toBeInTheDocument()
  })

  it('shows changed FIELD NAMES only — never recorded values', async () => {
    const { container } = renderPage()
    await screen.findByText('Auth login failed')

    // The names of what changed are shown...
    expect(screen.getByText('email, password, status')).toBeInTheDocument()

    // ...and the surface exposes no value payloads at all.
    const text = container.textContent ?? ''
    expect(text).not.toMatch(/@example|hunter2|S3cret/)
    expect(screen.getByText(/recorded values never leave the server/i)).toBeInTheDocument()
  })

  /* ------------------------------------------------------------------ filters */

  it('filters by category', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Auth login failed')

    await user.selectOptions(screen.getByLabelText('Category'), 'service_request')

    await waitFor(() =>
      expect(auditLogs).toHaveBeenLastCalledWith(expect.objectContaining({ category: 'service_request' })),
    )
  })

  it('filters by date range', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Auth login failed')

    await user.type(screen.getByLabelText('From'), '2026-01-01')

    await waitFor(() => expect(auditLogs).toHaveBeenLastCalledWith(expect.objectContaining({ from: '2026-01-01' })))
  })

  it('searches over the action/entity only', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Auth login failed')

    await user.type(screen.getByLabelText('Search action or entity'), 'auth')
    await user.click(screen.getByRole('button', { name: /^search$/i }))

    await waitFor(() => expect(auditLogs).toHaveBeenLastCalledWith(expect.objectContaining({ q: 'auth' })))
  })

  /* ------------------------------------------------------------------- export */

  it('exports the CURRENT filtered view through the export engine', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Auth login failed')

    await user.selectOptions(screen.getByLabelText('Category'), 'security')
    await user.click(screen.getByRole('button', { name: /export csv/i }))

    await waitFor(() =>
      expect(exportAuditLogs).toHaveBeenCalledWith('csv', expect.objectContaining({ category: 'security' })),
    )
  })

  it('hides export without reporting.export', async () => {
    perms.value = ['dashboard.view'] // no export permission
    renderPage()
    await screen.findByText('Auth login failed')

    expect(screen.queryByRole('button', { name: /export csv/i })).not.toBeInTheDocument()
  })

  /* --------------------------------------------------------- read-only + gating */

  it('is read-only — no mutating controls over the log', async () => {
    renderPage()
    await screen.findByText('Auth login failed')

    expect(
      screen.queryByRole('button', { name: /delete|edit|save|remove|purge|clear|create/i }),
    ).not.toBeInTheDocument()
  })

  it('blocks the section without permission and never fetches', () => {
    perms.value = []
    renderPage()

    expect(screen.getByText(/do not have permission/i)).toBeInTheDocument()
    expect(auditLogs).not.toHaveBeenCalled()
  })
})
