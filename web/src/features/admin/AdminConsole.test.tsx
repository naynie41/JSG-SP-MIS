import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { AdminLayout } from './AdminLayout'
import { AdminOverviewPage } from './AdminOverviewPage'
import { AdminAuditPage } from './AdminAuditPage'
import { AdminSettingsPage } from './AdminSettingsPage'
import { AdminIntegrationsPage } from './AdminIntegrationsPage'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { adminApi } from './api'
import type { AdminSummary } from './types'

vi.mock('./api', () => ({
  adminApi: {
    summary: vi.fn(),
    // The Audit section is reachable from a Quick Action, so its reads are stubbed too.
    auditLogs: vi.fn().mockResolvedValue({ items: [], pagination: { page: 1, per_page: 25, total: 0, total_pages: 1 } }),
    exportAuditLogs: vi.fn(),
    // Settings is the gear's destination.
    settings: vi.fn().mockResolvedValue({
      general: [{ label: 'Environment', value: 'testing', source: 'APP_ENV' }],
      security: { policy: [], mfa_roles: [] },
      registry: { identity_fields: [], non_identity_fields: [], locked: true, privacy: [], consent_purposes: [] },
      notifications: [],
    }),
    broadcast: vi.fn(),
  },
}))
vi.mock('@/features/access/api', () => ({
  accessApi: {
    matrix: vi.fn().mockResolvedValue({ permissions: [], roles: [] }),
    updateRolePermissions: vi.fn(),
  },
}))
// Integrations is the manual-sync quick action's destination.
vi.mock('@/features/sync/api', () => ({
  syncApi: { connectors: vi.fn().mockResolvedValue([]), runs: vi.fn().mockResolvedValue({ items: [] }), trigger: vi.fn() },
}))
vi.mock('@/features/registry/api', () => ({
  importApi: { list: vi.fn().mockResolvedValue({ items: [] }), get: vi.fn(), upload: vi.fn() },
}))

const authState = { roleKey: 'system_administrator' }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ user: { role: { key: authState.roleKey }, name: 'Admin' }, hasPermission: () => true }),
}))

const summaryFn = adminApi.summary as Mock

const summary: AdminSummary = {
  generated_at: new Date().toISOString(),
  kpis: {
    users_total: 48,
    users_active: 41,
    users_suspended: 5,
    users_deactivated: 2,
    users_without_mfa: 7,
    mdas_registered: 12,
    mdas_active: 11,
    development_partners: 4,
    programmes_catalog: 9,
    activities_active: 23,
    beneficiaries_registered: 15_402,
    households_registered: 4_120,
  },
  adoption_trend: [
    { month: '2026-06', new_users: 4, total_users: 44 },
    { month: '2026-07', new_users: 4, total_users: 48 },
  ],
  registry: {
    imports_total: 30,
    imports_completed: 26,
    imports_failed: 3,
    imports_in_progress: 1,
    rows_total: 1_000,
    rows_valid: 940,
    rows_invalid: 60,
    validation_rate: 0.94,
    duplicates_surfaced: 80,
    duplicates_resolved: 65,
    duplicates_pending: 15,
    duplicate_rate: 0.08,
  },
  alerts: [
    { id: 'mfa', severity: 'warning', title: '7 active accounts have no MFA', detail: 'Review these accounts.' },
    { id: 'mdas', severity: 'info', title: '1 inactive MDA', detail: 'Inactive MDAs cannot serve beneficiaries.' },
  ],
  recent_activity: [
    { id: 'a1', action: 'user.created', entity_type: 'User', actor: 'Ada Admin', actor_mda: null, at: new Date().toISOString() },
    { id: 'a2', action: 'mda.deactivated', entity_type: 'Mda', actor: 'Ada Admin', actor_mda: 'MDA A', at: new Date().toISOString() },
  ],
}

function renderAt(path = '/admin') {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
      <MemoryRouter initialEntries={[path]}>
        <Routes>
          <Route path="/admin" element={<AdminLayout />}>
            <Route index element={<AdminOverviewPage />} />
            <Route path="audit" element={<AdminAuditPage />} />
            <Route path="integrations" element={<AdminIntegrationsPage />} />
            <Route path="settings" element={<AdminSettingsPage />} />
          </Route>
          {/* Destinations the Quick Actions launch into. */}
          <Route path="/users" element={<div>Users module</div>} />
          <Route path="/mdas" element={<div>MDAs module</div>} />
          <Route path="/matching" element={<div>Matching module</div>} />
          <Route path="/imports" element={<div>Imports module</div>} />
        </Routes>
      </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('System Administrator console', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    authState.roleKey = 'system_administrator'
    summaryFn.mockResolvedValue(summary)
  })

  /* ------------------------------------------------------------------ gating */

  it('blocks every non-administrator role and never fetches', () => {
    for (const role of ['executive', 'sp_coordination', 'mda_admin', 'development_partner']) {
      authState.roleKey = role
      const { unmount } = renderAt()
      expect(screen.getByText(/available to System Administrators only/i)).toBeInTheDocument()
      expect(summaryFn).not.toHaveBeenCalled()
      unmount()
    }
  })

  it('opens for a System Administrator', async () => {
    renderAt()
    expect(await screen.findByRole('heading', { name: 'Governance overview' })).toBeInTheDocument()
  })

  /* -------------------------------------------------------------------- KPIs */

  it('renders governance KPIs accurately from the summary', async () => {
    renderAt()
    const band = await screen.findByRole('region', { name: 'Governance indicators' })

    expect(within(band).getByText('Total users')).toBeInTheDocument()
    expect(within(band).getByText('48')).toBeInTheDocument()
    expect(within(band).getByText('Active users')).toBeInTheDocument()
    expect(within(band).getAllByText('41').length).toBeGreaterThan(0)
    expect(within(band).getByText('Registered MDAs')).toBeInTheDocument()
    expect(within(band).getByText('12')).toBeInTheDocument()
    expect(within(band).getByText('Development partners')).toBeInTheDocument()
    expect(within(band).getByText('4')).toBeInTheDocument()
    expect(within(band).getByText('Programmes in catalog')).toBeInTheDocument()
    expect(within(band).getByText('9')).toBeInTheDocument()
    expect(within(band).getByText('Active activities')).toBeInTheDocument()
    expect(within(band).getByText('23')).toBeInTheDocument()
    expect(within(band).getByText('Registered beneficiaries')).toBeInTheDocument()
    expect(within(band).getByText('15,402')).toBeInTheDocument()
    expect(within(band).getByText('Registered households')).toBeInTheDocument()
    expect(within(band).getByText('4,120')).toBeInTheDocument()
  })

  it('shows adoption, the registry snapshot, alerts and recent activity', async () => {
    renderAt()
    await screen.findByRole('heading', { name: 'Governance overview' })

    // Adoption trend closes on the running total.
    const adoption = screen.getByRole('region', { name: 'User adoption' })
    expect(within(adoption).getByText('48')).toBeInTheDocument()

    // Registry snapshot.
    const registry = screen.getByRole('region', { name: 'Registry snapshot' })
    expect(within(registry).getByText('Failed imports')).toBeInTheDocument()
    expect(within(registry).getByText('94%')).toBeInTheDocument() // validation rate
    expect(within(registry).getByText('Duplicates surfaced')).toBeInTheDocument()

    // Alerts.
    const alerts = screen.getByRole('region', { name: 'Administrative alerts' })
    expect(within(alerts).getByText('7 active accounts have no MFA')).toBeInTheDocument()
    expect(within(alerts).getByText('1 inactive MDA')).toBeInTheDocument()

    // Recent activity — humanised action, actor, no audit payloads.
    const activity = screen.getByRole('region', { name: 'Recent administrative activity' })
    expect(within(activity).getByText('User created')).toBeInTheDocument()
    expect(within(activity).getByText('Mda deactivated')).toBeInTheDocument()
    expect(within(activity).getAllByText(/Ada Admin/).length).toBeGreaterThan(0)
  })

  it('carries no infrastructure / system-health widgets', async () => {
    renderAt()
    await screen.findByRole('heading', { name: 'Governance overview' })

    expect(screen.queryByText(/backup|queue depth|snapshot freshness|cpu|memory|disk|uptime/i)).toBeNull()
  })

  /* ---------------------------------------------------------- quick actions */

  it('launches existing flows — Create User routes to the users module', async () => {
    const user = userEvent.setup()
    renderAt()
    await screen.findByRole('heading', { name: 'Governance overview' })

    await user.click(screen.getByRole('button', { name: /Create User/i }))
    expect(await screen.findByText('Users module')).toBeInTheDocument()
  })

  it('routes each remaining action to its existing flow', async () => {
    const cases: [RegExp, string][] = [
      [/Register MDA/i, 'MDAs module'],
      [/Configure Matching Rules/i, 'Matching module'],
      [/Reprocess Failed Imports/i, 'Imports module'],
    ]
    for (const [name, destination] of cases) {
      const user = userEvent.setup()
      const { unmount } = renderAt()
      await screen.findByRole('heading', { name: 'Governance overview' })
      await user.click(screen.getByRole('button', { name }))
      expect(await screen.findByText(destination)).toBeInTheDocument()
      unmount()
    }
  })

  it('routes View Audit Logs to the console audit section', async () => {
    const user = userEvent.setup()
    renderAt()
    await screen.findByRole('heading', { name: 'Governance overview' })

    await user.click(screen.getByRole('button', { name: /View Audit Logs/i }))
    expect(await screen.findByRole('heading', { name: /Audit & Security/i })).toBeInTheDocument()
  })

  it('leaves no quick action without a destination', async () => {
    renderAt()
    await screen.findByRole('heading', { name: 'Governance overview' })

    // Every launcher now opens a real flow — nothing renders as a disabled promise.
    for (const button of screen.getAllByRole('button')) {
      if (button.textContent?.includes('Pending')) throw new Error(`still pending: ${button.textContent}`)
    }
    expect(screen.getByRole('button', { name: /Trigger Manual Synchronization/i })).toBeEnabled()
    expect(screen.getByRole('button', { name: /Broadcast System Notification/i })).toBeEnabled()
  })

  it('routes Trigger Manual Synchronization to the Integrations section', async () => {
    const user = userEvent.setup()
    renderAt()
    await screen.findByRole('heading', { name: 'Governance overview' })

    await user.click(screen.getByRole('button', { name: /Trigger Manual Synchronization/i }))
    expect(await screen.findByRole('heading', { name: 'Integrations' })).toBeInTheDocument()
  })

  it('routes Broadcast System Notification to Settings, where the notifier is wired', async () => {
    const user = userEvent.setup()
    renderAt()
    await screen.findByRole('heading', { name: 'Governance overview' })

    await user.click(screen.getByRole('button', { name: /Broadcast System Notification/i }))
    expect(await screen.findByRole('heading', { name: 'Settings' })).toBeInTheDocument()
  })

  /* --------------------------------------------------------------- settings */

  it('renders the Settings page on its own route (opened from the gear, not the rail)', async () => {
    renderAt('/admin/settings')
    expect(await screen.findByRole('heading', { name: 'Settings' })).toBeInTheDocument()
  })
})
