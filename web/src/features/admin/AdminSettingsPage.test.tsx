import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { AdminSettingsPage } from './AdminSettingsPage'
import { adminApi } from './api'
import { accessApi } from '@/features/access/api'
import type { AdminSettings } from './types'
import type { PermissionMatrix } from '@/features/access/types'

vi.mock('./api', () => ({
  adminApi: { settings: vi.fn(), broadcast: vi.fn(), broadcastAudience: vi.fn() },
}))
vi.mock('@/features/access/api', () => ({
  accessApi: { matrix: vi.fn(), updateRolePermissions: vi.fn() },
}))

const perms = { value: [] as string[] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { role: { key: 'system_administrator' }, name: 'Admin' },
    hasPermission: (p: string) => perms.value.includes(p),
  }),
}))

const settings = adminApi.settings as Mock
const broadcast = adminApi.broadcast as Mock
const matrix = accessApi.matrix as Mock
const updateRolePermissions = accessApi.updateRolePermissions as Mock

const SETTINGS: AdminSettings = {
  general: [
    { label: 'Application name', value: 'SP-MIS', source: 'APP_NAME' },
    { label: 'Environment', value: 'production', source: 'APP_ENV' },
  ],
  security: {
    policy: [
      { label: 'MFA enforced', value: 'Enabled', source: 'MFA_ENFORCE' },
      { label: 'Lockout after (failed attempts)', value: '5', source: 'AUTH_LOCKOUT_MAX_ATTEMPTS' },
    ],
    mfa_roles: [
      { key: 'system_administrator', name: 'System Administrator', requires_mfa: true },
      { key: 'mda_officer', name: 'MDA Officer', requires_mfa: false },
    ],
  },
  registry: {
    identity_fields: ['first_name', 'nin', 'bvn'],
    non_identity_fields: ['gender', 'lga'],
    locked: true,
    privacy: [{ label: 'Retention enforcement', value: 'Disabled', source: 'PRIVACY_RETENTION_ENABLED' }],
    consent_purposes: [{ key: 'cross_mda_sharing', label: 'Cross-MDA data sharing', gate: 'sharing' }],
  },
  notifications: [
    { key: 'in_app', available: true },
    { key: 'email', available: true },
    { key: 'sms', available: false },
  ],
}

const MATRIX: PermissionMatrix = {
  permissions: [
    { key: 'beneficiary.view', module: 'beneficiary', action: 'view', action_label: 'View', description: 'View beneficiaries', role_grantable: true, sensitive: false },
    { key: 'beneficiary.export', module: 'beneficiary', action: 'export', action_label: 'Export', description: 'Export beneficiaries', role_grantable: true, sensitive: true },
    { key: 'export.reveal_pii', module: 'export', action: 'reveal_pii', action_label: 'Reveal PII', description: 'Unmask NIN/BVN', role_grantable: false, sensitive: true },
  ],
  roles: [
    { id: 'r-officer', key: 'mda_officer', name: 'MDA Officer', editable: true, permissions: ['beneficiary.view'] },
    { id: 'r-admin', key: 'system_administrator', name: 'System Administrator', editable: false, permissions: ['beneficiary.view', 'beneficiary.export', 'export.reveal_pii'] },
  ],
}

function renderPage() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter>
          <AdminSettingsPage />
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('Admin console — Settings', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms.value = ['role.edit', 'permission.view']
    settings.mockResolvedValue(SETTINGS)
    matrix.mockResolvedValue(MATRIX)
  })

  /* ------------------------------------------------- effective configuration */

  it('reports effective configuration with the key that sets each value', async () => {
    renderPage()

    expect(await screen.findByText('Application name')).toBeInTheDocument()
    expect(screen.getByText('SP-MIS')).toBeInTheDocument()
    expect(screen.getByText('APP_NAME')).toBeInTheDocument()
    expect(screen.getByText('APP_ENV')).toBeInTheDocument()
  })

  it('offers no control to change deployment configuration', async () => {
    renderPage()
    await screen.findByText('Application name')

    // Read-only projection: no inputs, no save. The console keeps no settings store.
    expect(screen.queryByRole('textbox')).not.toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /save/i })).not.toBeInTheDocument()
  })

  it('shows the security policy and per-role MFA requirement', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Application name')

    await user.click(screen.getByRole('tab', { name: /user & security/i }))

    expect(await screen.findByText('MFA enforced')).toBeInTheDocument()
    expect(screen.getByText('AUTH_LOCKOUT_MAX_ATTEMPTS')).toBeInTheDocument()
    expect(screen.getByText('MFA required')).toBeInTheDocument()
    expect(screen.getByText('MFA optional')).toBeInTheDocument()
  })

  it('presents registry identity validation as locked, not editable', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Application name')

    await user.click(screen.getByRole('tab', { name: /registry/i }))

    expect(await screen.findByText('nin')).toBeInTheDocument()
    expect(screen.getByText(/locked decision/i)).toBeInTheDocument()
    expect(screen.queryByRole('checkbox')).not.toBeInTheDocument()
  })

  it('reports channel availability from the notifier, including stubs', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Application name')

    await user.click(screen.getByRole('tab', { name: /notifications/i }))

    expect(await screen.findByText('In-app inbox')).toBeInTheDocument()
    expect(screen.getByText('SMS')).toBeInTheDocument()
    expect(screen.getByText('not configured')).toBeInTheDocument()
  })

  /* -------------------------------------------------------------- broadcast */

  it('sends a broadcast through the notification endpoint', async () => {
    broadcast.mockResolvedValue({ recipient_count: 12, message: 'Broadcast sent to 12 recipients.' })
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Application name')

    await user.click(screen.getByRole('tab', { name: /notifications/i }))
    await user.type(await screen.findByLabelText('Subject'), 'Scheduled maintenance')
    await user.type(screen.getByLabelText('Message'), 'Saturday from 02:00.')
    await user.click(screen.getByRole('button', { name: /send broadcast/i }))

    await waitFor(() =>
      expect(broadcast).toHaveBeenCalledWith({ subject: 'Scheduled maintenance', body: 'Saturday from 02:00.' }),
    )
  })

  it('will not send a broadcast without a subject', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Application name')

    await user.click(screen.getByRole('tab', { name: /notifications/i }))

    expect(await screen.findByRole('button', { name: /send broadcast/i })).toBeDisabled()
    expect(broadcast).not.toHaveBeenCalled()
  })

  /* ------------------------------------------------- permission matrix editor */

  it('edits a role and saves through the RBAC endpoint', async () => {
    updateRolePermissions.mockResolvedValue({ role: { ...MATRIX.roles[0]!, permissions: ['beneficiary.view', 'beneficiary.export'] } })
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Application name')

    await user.click(screen.getByRole('tab', { name: /permission matrix/i }))

    const exportBox = await screen.findByRole('checkbox', { name: 'Export' })
    expect(screen.getByRole('checkbox', { name: 'View' })).toBeChecked()
    expect(exportBox).not.toBeChecked()

    await user.click(exportBox)
    await user.click(screen.getByRole('button', { name: /save permissions/i }))

    await waitFor(() =>
      expect(updateRolePermissions).toHaveBeenCalledWith('r-officer', ['beneficiary.view', 'beneficiary.export']),
    )
  })

  it('never offers export.reveal_pii as a role grant', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Application name')
    await user.click(screen.getByRole('tab', { name: /permission matrix/i }))

    const reveal = await screen.findByRole('checkbox', { name: 'Reveal PII' })
    expect(reveal).toBeDisabled()
    expect(screen.getByText(/never granted to a role/i)).toBeInTheDocument()
  })

  it('locks the System Administrator role', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Application name')
    await user.click(screen.getByRole('tab', { name: /permission matrix/i }))

    await user.selectOptions(await screen.findByLabelText('Role'), 'system_administrator')

    expect(await screen.findByText(/holds every permission implicitly/i)).toBeInTheDocument()
    expect(screen.getByRole('checkbox', { name: 'View' })).toBeDisabled()
    expect(screen.getByRole('button', { name: /save permissions/i })).toBeDisabled()
  })

  it('flags a sensitive grant as needing DPO sign-off', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Application name')
    await user.click(screen.getByRole('tab', { name: /permission matrix/i }))

    // The badge (exact text) — the footnote also mentions DPO sign-off in prose.
    expect(screen.queryByText('DPO sign-off')).not.toBeInTheDocument()

    await user.click(await screen.findByRole('checkbox', { name: 'Export' }))

    expect(screen.getByText('DPO sign-off')).toBeInTheDocument()
  })

  it('keeps save disabled until something actually changes', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Application name')
    await user.click(screen.getByRole('tab', { name: /permission matrix/i }))

    const save = await screen.findByRole('button', { name: /save permissions/i })
    expect(save).toBeDisabled()

    await user.click(screen.getByRole('checkbox', { name: 'Export' }))
    expect(save).toBeEnabled()

    await user.click(screen.getByRole('button', { name: /discard changes/i }))
    expect(save).toBeDisabled()
  })

  it('withholds the editor without the role edit permission', async () => {
    perms.value = ['permission.view']
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Application name')

    await user.click(screen.getByRole('tab', { name: /permission matrix/i }))

    expect(await screen.findByText(/needs the role edit permission/i)).toBeInTheDocument()
    expect(matrix).not.toHaveBeenCalled()
  })
})
