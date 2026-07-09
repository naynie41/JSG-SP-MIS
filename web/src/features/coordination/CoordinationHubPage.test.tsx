import { describe, expect, it, vi } from 'vitest'
import { render, screen } from '@testing-library/react'
import { MemoryRouter } from 'react-router-dom'
import { CoordinationHubPage } from './CoordinationHubPage'

const perms = new Set<string>()
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: (p: string) => perms.has(p) }),
}))

const renderHub = () =>
  render(
    <MemoryRouter>
      <CoordinationHubPage />
    </MemoryRouter>,
  )

describe('CoordinationHubPage', () => {
  it('shows only the coordination tools the user can access', () => {
    perms.clear()
    perms.add('referral.view')

    renderHub()

    expect(screen.getByRole('link', { name: /Referrals/ })).toHaveAttribute('href', '/referrals')
    expect(screen.queryByRole('link', { name: /Grievance desk/ })).toBeNull()
  })
})
