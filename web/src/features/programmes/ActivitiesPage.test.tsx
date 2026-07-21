import { describe, expect, it, vi } from 'vitest'
import { render, screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { MemoryRouter } from 'react-router-dom'
import { ActivitiesPage } from './ActivitiesPage'

const navigate = vi.fn()
vi.mock('react-router-dom', async (importOriginal) => ({
  ...(await importOriginal<typeof import('react-router-dom')>()),
  useNavigate: () => navigate,
}))

const permissions = new Set<string>()
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: (p: string) => permissions.has(p) }),
}))

const activityRow = { id: 'a-1', programme_id: 'p-1', owner_mda_id: 'm-1', involves_beneficiaries: false, name: 'Q1 Round', description: null, target_beneficiaries: null, lga: 'dutse', ward: null, location_description: null, schedule: null, starts_on: null, ends_on: null, budget_amount: 1_000_000, funding_source: null, status: 'active', created_by: null, created_at: null, updated_at: null }

vi.mock('./hooks', () => ({
  useAllActivities: () => ({ data: { items: [activityRow] }, isLoading: false }),
  useProgrammeCatalog: () => ({ data: { items: [{ id: 'p-1', name: 'Cash Transfer' }] } }),
  useArchiveActivity: () => ({ mutate: vi.fn() }),
}))

describe('ActivitiesPage', () => {
  it('offers a View action on every activity row — even for a view-only user', async () => {
    permissions.clear()
    permissions.add('activity.view') // no activity.create

    render(<MemoryRouter><ActivitiesPage /></MemoryRouter>)

    // No management affordances for a view-only user…
    expect(screen.queryByRole('button', { name: /new activity/i })).toBeNull()
    expect(screen.queryByRole('button', { name: /actions for/i })).toBeNull()

    // …but a View action is present on the row and opens the detail page.
    await userEvent.click(screen.getByRole('button', { name: /view/i }))
    expect(navigate).toHaveBeenCalledWith('/activities/a-1')
  })
})
