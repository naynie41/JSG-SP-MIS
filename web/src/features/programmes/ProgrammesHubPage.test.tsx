import { describe, expect, it, vi } from 'vitest'
import { render, screen } from '@testing-library/react'
import { MemoryRouter } from 'react-router-dom'
import { ProgrammesHubPage } from './ProgrammesHubPage'

const perms = new Set<string>()
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: (p: string) => perms.has(p) }),
}))

// The gated metric band reads the scoped dashboard metrics.
const dashboardData: { data: unknown } = { data: undefined }
vi.mock('@/features/dashboard/hooks', () => ({
  useDashboard: () => dashboardData,
}))

const renderHub = () =>
  render(
    <MemoryRouter>
      <ProgrammesHubPage />
    </MemoryRouter>,
  )

describe('ProgrammesHubPage', () => {
  it('offers operational cards but no programme create/edit control to an MDA', () => {
    perms.clear()
    perms.add('programme.view')
    perms.add('activity.view')
    perms.add('benefit.view')

    renderHub()

    // Browse the catalog (read-only) + run it through activities.
    expect(screen.getByRole('link', { name: /Programme catalog/ })).toHaveAttribute('href', '/programmes/list')
    expect(screen.getByRole('link', { name: /Activities/ })).toHaveAttribute('href', '/activities')
    expect(screen.getByRole('link', { name: /Benefit ledger/ })).toBeInTheDocument()
    // Catalog creation lives in Administration — never on the MDA hub.
    expect(screen.queryByRole('button', { name: /new programme/i })).toBeNull()
    expect(screen.queryByRole('link', { name: /new programme/i })).toBeNull()
  })

  it('shows only the areas the user can access', () => {
    perms.clear()
    perms.add('benefit.view')

    renderHub()

    expect(screen.getByRole('link', { name: /Benefit ledger/ })).toBeInTheDocument()
    expect(screen.queryByRole('link', { name: /Activities/ })).toBeNull()
  })

  it('attaches a scoped metric band only for users with dashboard access', () => {
    dashboardData.data = { scope: { label: 'MDA A' }, metrics: { programmes: { active: 4, total: 9 }, benefits: { disbursed: { benefit_count: 12, total_value: 500 }, budget: { utilization_rate: 0.4 } } } }

    // Without dashboard.view: launcher only, no metrics.
    perms.clear()
    perms.add('programme.view')
    renderHub()
    expect(screen.queryByText('Active programmes')).toBeNull()

    // With dashboard.view: the metric band shows scoped figures.
    perms.add('dashboard.view')
    renderHub()
    expect(screen.getByText('Active programmes')).toBeInTheDocument()
    expect(screen.getByText('4')).toBeInTheDocument()
  })
})
