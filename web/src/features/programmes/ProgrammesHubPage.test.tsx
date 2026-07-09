import { describe, expect, it, vi } from 'vitest'
import { render, screen } from '@testing-library/react'
import { MemoryRouter } from 'react-router-dom'
import { ProgrammesHubPage } from './ProgrammesHubPage'

const perms = new Set<string>()
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: (p: string) => perms.has(p) }),
}))

const renderHub = () =>
  render(
    <MemoryRouter>
      <ProgrammesHubPage />
    </MemoryRouter>,
  )

describe('ProgrammesHubPage', () => {
  it('offers a create action and cards to a programme manager', () => {
    perms.clear()
    perms.add('programme.view')
    perms.add('programme.create')
    perms.add('benefit.view')

    renderHub()

    expect(screen.getByRole('button', { name: 'New programme' })).toBeInTheDocument()
    expect(screen.getByRole('link', { name: /Programmes/ })).toHaveAttribute('href', '/programmes/list')
    expect(screen.getByRole('link', { name: /Benefit ledger/ })).toBeInTheDocument()
  })

  it('hides the create action from a view-only user', () => {
    perms.clear()
    perms.add('benefit.view')

    renderHub()

    expect(screen.queryByRole('button', { name: 'New programme' })).toBeNull()
    expect(screen.getByRole('link', { name: /Benefit ledger/ })).toBeInTheDocument()
  })
})
