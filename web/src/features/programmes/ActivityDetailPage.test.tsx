import { describe, expect, it, vi } from 'vitest'
import { render, screen, within } from '@testing-library/react'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { ActivityDetailPage } from './ActivityDetailPage'

vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: (p: string) => p === 'activity.view' }),
}))

const activity = {
  id: 'a-1',
  programme_id: 'p-1',
  owner_mda_id: 'm-1',
  name: 'Q1 Cash Round',
  description: null,
  involves_beneficiaries: true,
  target_beneficiaries: 1200,
  lga: 'dutse',
  ward: 'Ward 3',
  location_description: null,
  schedule: null,
  starts_on: '2026-01-01',
  ends_on: '2026-03-31',
  budget_amount: 20_000_000,
  funding_source: 'State budget',
  status: 'active',
  created_by: null,
  created_at: null,
  updated_at: null,
}

vi.mock('./hooks', () => ({
  useActivity: () => ({ data: activity, isLoading: false }),
  useProgrammeCatalog: () => ({ data: { items: [{ id: 'p-1', name: 'Cash Transfer' }] } }),
}))

const serviceRequests = [
  { id: 'sr-1', beneficiary_id: 'ben-11112222', beneficiary_name: 'Zainab Umar', from_mda_id: 'm-1', to_mda_id: 'm-2', owner_mda: { id: 'm-2', name: 'MDA B' }, activity_id: 'a-1', status: 'pending', reason: null, decided_at: null, decision_reason: null, created_at: '2026-07-10T09:00:00Z' },
  { id: 'sr-2', beneficiary_id: 'ben-33334444', beneficiary_name: 'Musa Bello', from_mda_id: 'm-1', to_mda_id: 'm-3', owner_mda: { id: 'm-3', name: 'MDA C' }, activity_id: 'a-1', status: 'accepted', reason: null, decided_at: null, decision_reason: null, created_at: '2026-07-09T09:00:00Z' },
]

vi.mock('@/features/registry/hooks', () => ({
  useActivityServiceRequests: () => ({ data: serviceRequests, isLoading: false }),
}))

function renderPage() {
  return render(
    <MemoryRouter initialEntries={['/activities/a-1']}>
      <Routes>
        <Route path="/activities/:id" element={<ActivityDetailPage />} />
      </Routes>
    </MemoryRouter>,
  )
}

describe('ActivityDetailPage', () => {
  it('shows the activity and its request-to-serve items with status chips', () => {
    renderPage()

    expect(screen.getByRole('heading', { name: 'Q1 Cash Round' })).toBeInTheDocument()
    // Beneficiary involvement + target.
    expect(screen.getByText('Yes · target 1200')).toBeInTheDocument()

    // The pending service requests section, scoped to this activity.
    const section = screen.getByText('Pending service requests').closest('section') as HTMLElement
    expect(within(section).getByText('Zainab Umar')).toBeInTheDocument()
    expect(within(section).getByText('MDA B')).toBeInTheDocument()
    // Status chips per §5.9 (pending + accepted both listed).
    expect(within(section).getByText('pending')).toBeInTheDocument()
    expect(within(section).getByText('accepted')).toBeInTheDocument()
    // One is still awaiting owner approval.
    expect(within(section).getByText(/1 awaiting approval/i)).toBeInTheDocument()
  })
})
