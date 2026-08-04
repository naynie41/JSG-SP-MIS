import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { AdminAccessPage } from './AdminAccessPage'
import { adminApi } from './api'
import { userApi, roleApi } from '@/features/users/api'
import { accessApi } from '@/features/access/api'
import type { LoginActivity } from './types'

// The console must COMPOSE the Phase 1 modules — so we mock the Phase 1 API layer and
// assert the section drives it, rather than mocking the console's own endpoints.
vi.mock('@/features/users/api', () => ({
  userApi: { list: vi.fn(), create: vi.fn(), update: vi.fn(), changeStatus: vi.fn(), forcePasswordReset: vi.fn(), resetMfa: vi.fn() },
  roleApi: { list: vi.fn() },
}))
vi.mock('@/features/access/api', () => ({
  accessApi: { roles: vi.fn(), permissions: vi.fn(), matrix: vi.fn(), grants: vi.fn(), createGrant: vi.fn(), revokeGrant: vi.fn() },
}))
vi.mock('./api', () => ({ adminApi: { summary: vi.fn(), loginActivity: vi.fn() } }))

const perms = { value: [] as string[] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { role: { key: 'system_administrator' }, name: 'Admin' },
    hasPermission: (p: string) => perms.value.includes(p),
  }),
}))

const listUsers = userApi.list as Mock
const listRoles = roleApi.list as Mock
const accessRoles = accessApi.roles as Mock
const accessPermissions = accessApi.permissions as Mock
const accessMatrix = accessApi.matrix as Mock
const loginActivity = adminApi.loginActivity as Mock

const managedUsers = [
  {
    id: 'u1', name: 'Ada Officer', email: 'ada@example.test', status: 'active',
    is_locked: true, locked_until: new Date(Date.now() + 6e5).toISOString(),
    mfa_enabled: false, mfa_required: true, last_login_at: null,
    mda: { id: 'm1', name: 'MDA A', type: 'ministry' }, role: { key: 'mda_officer', name: 'MDA Officer' }, permissions: [],
  },
  {
    id: 'u2', name: 'Bola Admin', email: 'bola@example.test', status: 'suspended',
    is_locked: false, locked_until: null, mfa_enabled: true, mfa_required: true, last_login_at: null,
    mda: null, role: { key: 'system_administrator', name: 'System Administrator' }, permissions: [],
  },
]

const activity: LoginActivity = {
  window_days: 30,
  summary: { logins: 42, failed_logins: 3, lockouts: 1, mfa_resets: 2 },
  entries: [
    { id: 'e1', action: 'auth.login', outcome: 'success', actor: 'Ada Officer', actor_email: 'ada@example.test', actor_mda: 'MDA A', ip_address: '10.0.0.1', at: new Date().toISOString() },
    { id: 'e2', action: 'auth.login_failed', outcome: 'failure', actor: 'Ada Officer', actor_email: 'ada@example.test', actor_mda: 'MDA A', ip_address: '10.0.0.2', at: new Date().toISOString() },
    { id: 'e3', action: 'auth.account_locked', outcome: 'security', actor: 'Ada Officer', actor_email: null, actor_mda: null, ip_address: '10.0.0.3', at: new Date().toISOString() },
  ],
}

function renderPage() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter>
          <AdminAccessPage />
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('Admin console — User & Access (composes Phase 1)', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms.value = ['user.view', 'user.create', 'user.edit', 'role.view', 'permission.view']
    listUsers.mockResolvedValue(managedUsers)
    listRoles.mockResolvedValue([{ id: 'r1', key: 'mda_officer', name: 'MDA Officer', requires_mfa: true }])
    accessRoles.mockResolvedValue([{ id: 'r1', key: 'mda_officer', name: 'MDA Officer', description: null, requires_mfa: true, permissions: ['user.view'] }])
    accessPermissions.mockResolvedValue({ user: [{ key: 'user.view', action: 'view', description: null }] })
    accessMatrix.mockResolvedValue({ roles: [], permissions: [] })
    loginActivity.mockResolvedValue(activity)
  })

  /* ------------------------------------------------------------- composition */

  it('drives the EXISTING Phase 1 user endpoint — no parallel user store', async () => {
    renderPage()

    expect(await screen.findByText('Ada Officer')).toBeInTheDocument()
    // The section fetched users through the Phase 1 api layer, not a console endpoint.
    expect(listUsers).toHaveBeenCalled()
    expect(adminApi.summary).not.toHaveBeenCalled()
  })

  it('exposes the full Phase 1 user administration surface', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Ada Officer')

    // Create comes straight from the existing page.
    expect(screen.getAllByRole('button', { name: /create user/i }).length).toBeGreaterThan(0)

    // The per-user administrative actions live in that page's row menu.
    await user.click(screen.getByRole('button', { name: /actions for Ada Officer/i }))
    expect(await screen.findByText('Suspend')).toBeInTheDocument()
    expect(screen.getByText('Deactivate')).toBeInTheDocument()
    expect(screen.getByText('Force password reset')).toBeInTheDocument()
    // Ada has not enrolled MFA, so there is nothing to reset for her.
    expect(screen.queryByText('Reset MFA')).not.toBeInTheDocument()
    await user.keyboard('{Escape}')

    // Bola HAS MFA enrolled — the reset action is offered there.
    await user.click(screen.getByRole('button', { name: /actions for Bola Admin/i }))
    expect(await screen.findByText('Reset MFA')).toBeInTheDocument()
  })

  it('surfaces account status including runtime lockout, and MFA compliance', async () => {
    renderPage()
    await screen.findByText('Ada Officer')

    // Ada is `active` but currently LOCKED — both states are shown.
    expect(screen.getByText('active')).toBeInTheDocument()
    expect(screen.getByText('locked')).toBeInTheDocument()
    expect(screen.getByText('suspended')).toBeInTheDocument()

    // MFA: Ada's role requires it but she has not enrolled → a compliance gap.
    expect(screen.getByText('Required')).toBeInTheDocument()
    expect(screen.getByText('On')).toBeInTheDocument()
  })

  it('composes Roles and Permissions from the existing access module', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Ada Officer')

    await user.click(screen.getByRole('tab', { name: 'Roles' }))
    expect(await screen.findByRole('heading', { name: 'Roles' })).toBeInTheDocument()
    expect(accessRoles).toHaveBeenCalled()
    // The role's MFA requirement IS the enforcement policy (no per-user toggle exists).
    expect(screen.getByText('Required')).toBeInTheDocument()

    await user.click(screen.getByRole('tab', { name: 'Permissions' }))
    expect(accessPermissions).toHaveBeenCalled()
  })

  /* ---------------------------------------------------------- login activity */

  it('shows login activity projected from the audit log', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Ada Officer')

    await user.click(screen.getByRole('tab', { name: 'Login activity' }))

    expect(await screen.findByText('Auth login')).toBeInTheDocument()
    expect(screen.getByText('Auth login failed')).toBeInTheDocument()
    expect(screen.getByText('Auth account locked')).toBeInTheDocument()

    // Window summary + the envelope fields.
    expect(screen.getByText('Successful logins')).toBeInTheDocument()
    expect(screen.getByText('42')).toBeInTheDocument()
    expect(screen.getByText('10.0.0.1')).toBeInTheDocument()

    // Outcomes are badged so failures/security events read at a glance.
    expect(screen.getByText('failure')).toBeInTheDocument()
    expect(screen.getByText('security')).toBeInTheDocument()
  })

  /* -------------------------------------------------------- permission gating */

  it('inherits Phase 1 permission gating — a view-only admin gets no write actions', async () => {
    perms.value = ['user.view', 'role.view'] // no user.create / user.edit
    renderPage()
    await screen.findByText('Ada Officer')

    expect(screen.queryByRole('button', { name: /create user/i })).not.toBeInTheDocument()
    // With no edit permission the row action menu is not offered at all.
    expect(screen.queryByRole('button', { name: /actions for/i })).not.toBeInTheDocument()
  })

  it('blocks the users view entirely without user.view', async () => {
    perms.value = []
    renderPage()

    expect(await screen.findByText(/do not have permission/i)).toBeInTheDocument()
    expect(listUsers).not.toHaveBeenCalled()
  })

  it('disables the Permissions tab without permission.view', async () => {
    perms.value = ['user.view']
    renderPage()
    await screen.findByText(/do not have permission|Ada Officer/i)

    expect(screen.getByRole('tab', { name: 'Permissions' })).toBeDisabled()
  })
})
