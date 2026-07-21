import { describe, expect, it, vi } from 'vitest'
import { render, screen, within } from '@testing-library/react'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { ActivityDetailPage } from './ActivityDetailPage'

vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: (p: string) => p === 'activity.view' }),
}))

// The View Activity page is powered by the single GET /activities/{id} detail payload.
const activity = {
  id: 'a-1',
  programme_id: 'p-1',
  owner_mda_id: 'm-1',
  involves_beneficiaries: true,
  name: 'Q1 Cash Round',
  description: null,
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
  programme: { id: 'p-1', name: 'Cash Transfer', type: 'individual', benefit_category: null, status: 'active', objective: null, eligibility: [], enforce_eligibility: false, created_by: null, created_at: null, updated_at: null },
  counts: { target: 1200, actual: 2, pending_service_requests: 1 },
  beneficiaries: [
    { enrollment_id: 'e-1', beneficiary_id: 'b-1', full_name: 'Ada Okoye', nin: '•••••••8901', bvn: '•••••••8902', lga: 'dutse', ward: 'Ward 3', beneficiary_status: 'active', enrollment_status: 'enrolled', enrolled_on: '2026-01-05' },
    { enrollment_id: 'e-2', beneficiary_id: 'b-2', full_name: 'Bala Sule', nin: null, bvn: null, lga: 'dutse', ward: 'Ward 2', beneficiary_status: 'active', enrollment_status: 'enrolled', enrolled_on: '2026-01-06' },
  ],
  import_summary: { batches: 1, total_rows: 3, valid_rows: 2, invalid_rows: 1, rejected_rows: 1, dropped_field_rows: 0, committed_rows: 1, served_rows: 1, skipped_rows: 0 },
  service_requests: [
    { id: 'sr-1', beneficiary_id: 'ben-11112222', beneficiary_name: 'Zainab Umar', from_mda_id: 'm-1', to_mda_id: 'm-2', owner_mda: { id: 'm-2', name: 'MDA B' }, activity_id: 'a-1', status: 'pending', reason: null, decided_at: null, decision_reason: null, created_at: '2026-07-10T09:00:00Z' },
    { id: 'sr-2', beneficiary_id: 'ben-33334444', beneficiary_name: 'Musa Bello', from_mda_id: 'm-1', to_mda_id: 'm-3', owner_mda: { id: 'm-3', name: 'MDA C' }, activity_id: 'a-1', status: 'accepted', reason: null, decided_at: null, decision_reason: null, created_at: '2026-07-09T09:00:00Z' },
  ],
}

let activityData: unknown = activity
vi.mock('./hooks', () => ({
  useActivity: () => ({ data: activityData, isLoading: false }),
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
  it('shows the full activity picture: counts, beneficiaries (masked), import summary, and requests', () => {
    activityData = activity
    renderPage()

    expect(screen.getByRole('heading', { name: 'Q1 Cash Round' })).toBeInTheDocument()
    expect(screen.getAllByText('Cash Transfer').length).toBeGreaterThan(0) // header badge + details
    // Target vs actual.
    expect(screen.getByText('Yes · target 1200 · actual 2')).toBeInTheDocument()

    // Beneficiaries/interventions under the activity, with NIN masked.
    const beneficiaries = screen.getByText('Beneficiaries recorded under this activity').closest('table') as HTMLElement
    expect(within(beneficiaries).getByText('Ada Okoye')).toBeInTheDocument()
    expect(within(beneficiaries).getByText('•••••••8901')).toBeInTheDocument()
    expect(within(beneficiaries).queryByText('12345678901')).not.toBeInTheDocument()

    // Import/validation summary.
    expect(screen.getByText('Valid rows')).toBeInTheDocument()

    // Pending service requests, scoped to this activity.
    const section = screen.getByText('Pending service requests').closest('section') as HTMLElement
    expect(within(section).getByText('Zainab Umar')).toBeInTheDocument()
    expect(within(section).getByText('pending')).toBeInTheDocument()
    expect(within(section).getByText('accepted')).toBeInTheDocument()
    expect(within(section).getByText(/1 awaiting approval/i)).toBeInTheDocument()
  })

  it('shows no beneficiary sections for an activity that does not involve beneficiaries', () => {
    activityData = { ...activity, involves_beneficiaries: false, beneficiaries: [], import_summary: null, service_requests: [], counts: { target: null, actual: 0, pending_service_requests: 0 } }

    renderPage()
    expect(screen.getByText('No')).toBeInTheDocument()
    expect(screen.queryByText('Beneficiaries recorded under this activity')).not.toBeInTheDocument()
    expect(screen.queryByText('Pending service requests')).not.toBeInTheDocument()
  })
})
