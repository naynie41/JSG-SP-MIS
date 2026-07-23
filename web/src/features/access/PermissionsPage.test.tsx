import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { PermissionsPage } from './PermissionsPage'
import { accessApi } from './api'

vi.mock('./api', () => ({ accessApi: { permissions: vi.fn(), matrix: vi.fn() } }))

let perms = new Set<string>(['permission.view'])
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: (p: string) => perms.has(p) }),
}))

const permissionsFn = accessApi.permissions as Mock
const matrixFn = accessApi.matrix as Mock

function renderPage(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(<QueryClientProvider client={qc}>{ui}</QueryClientProvider>)
}

describe('PermissionsPage', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms = new Set(['permission.view'])
    permissionsFn.mockResolvedValue({
      beneficiary: [{ key: 'beneficiary.view', action: 'view', description: 'View beneficiaries' }],
      mda: [{ key: 'mda.view', action: 'view', description: 'View MDAs' }],
    })
    matrixFn.mockResolvedValue({
      permissions: ['beneficiary.view', 'mda.view'],
      roles: [
        { key: 'system_administrator', name: 'System Administrator', permissions: ['beneficiary.view', 'mda.view'] },
        { key: 'mda_officer', name: 'MDA Officer', permissions: ['beneficiary.view'] },
      ],
    })
  })

  it('shows the catalogue by module and the role matrix on tab switch', async () => {
    const user = userEvent.setup()
    renderPage(<PermissionsPage />)

    // Catalogue tab (default): module titles + permission keys + descriptions.
    expect(await screen.findByText('beneficiary.view')).toBeInTheDocument()
    expect(screen.getByText('View beneficiaries')).toBeInTheDocument()

    // Switch to the matrix tab → role columns + permission rows.
    await user.click(screen.getByRole('tab', { name: 'Role matrix' }))
    expect(await screen.findByRole('columnheader', { name: 'System Administrator' })).toBeInTheDocument()
    expect(screen.getByRole('columnheader', { name: 'MDA Officer' })).toBeInTheDocument()
  })

  it('blocks users without permission.view', () => {
    perms = new Set()
    renderPage(<PermissionsPage />)
    expect(screen.getByText(/do not have permission to view permissions/i)).toBeInTheDocument()
    expect(permissionsFn).not.toHaveBeenCalled()
  })
})
