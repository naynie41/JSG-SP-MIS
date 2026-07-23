import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { RolesPage } from './RolesPage'
import { accessApi } from './api'
import type { AccessRole } from './types'

vi.mock('./api', () => ({ accessApi: { roles: vi.fn() } }))

let perms = new Set<string>(['role.view'])
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: (p: string) => perms.has(p) }),
}))

const rolesFn = accessApi.roles as Mock

const roles: AccessRole[] = [
  { id: 'r-1', key: 'system_administrator', name: 'System Administrator', description: 'All access', requires_mfa: true, permissions: ['user.view', 'user.create', 'mda.view'] },
  { id: 'r-2', key: 'mda_officer', name: 'MDA Officer', description: null, requires_mfa: false, permissions: ['beneficiary.view'] },
]

function renderPage(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(<QueryClientProvider client={qc}>{ui}</QueryClientProvider>)
}

describe('RolesPage', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms = new Set(['role.view'])
  })

  it('lists roles with MFA flag + permission count and opens a role to see its bundle', async () => {
    rolesFn.mockResolvedValue(roles)
    const user = userEvent.setup()

    renderPage(<RolesPage />)

    expect(await screen.findByText('System Administrator')).toBeInTheDocument()
    expect(screen.getByText('MDA Officer')).toBeInTheDocument()
    expect(screen.getByText('Required')).toBeInTheDocument() // sysadmin MFA

    // Open the sysadmin permission bundle — grouped by module.
    await user.click(screen.getAllByRole('button', { name: /view permissions/i })[0])
    expect(await screen.findByRole('heading', { name: 'System Administrator · permissions' })).toBeInTheDocument()
    expect(screen.getByText('user.create')).toBeInTheDocument()
    expect(screen.getByText('mda.view')).toBeInTheDocument()
  })

  it('blocks users without role.view', () => {
    perms = new Set()
    renderPage(<RolesPage />)
    expect(screen.getByText(/do not have permission to view roles/i)).toBeInTheDocument()
    expect(rolesFn).not.toHaveBeenCalled()
  })
})
