import { describe, expect, it, vi } from 'vitest'
import { render, screen } from '@testing-library/react'
import { MemoryRouter } from 'react-router-dom'
import { RegistryHubPage } from './RegistryHubPage'

const perms = new Set<string>()
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: (p: string) => perms.has(p) }),
}))

const renderHub = () =>
  render(
    <MemoryRouter>
      <RegistryHubPage />
    </MemoryRouter>,
  )

describe('RegistryHubPage', () => {
  it('surfaces only the tools the user can access as cards', () => {
    perms.clear()
    perms.add('beneficiary.view')
    perms.add('household.view')

    renderHub()

    expect(screen.getByRole('link', { name: /Beneficiaries/ })).toHaveAttribute('href', '/beneficiaries')
    expect(screen.getByRole('link', { name: /Households/ })).toBeInTheDocument()
    // No matching.view → the matching-rules card is not offered.
    expect(screen.queryByRole('link', { name: /Matching rules/ })).toBeNull()
  })

  it('shows an empty note when nothing is permitted', () => {
    perms.clear()

    renderHub()

    expect(screen.getByText(/don’t have access/i)).toBeInTheDocument()
  })
})
