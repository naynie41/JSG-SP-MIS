import { beforeEach, describe, expect, it, vi } from 'vitest'
import { render, screen, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
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
  // A multi-LGA set: specific wards in one LGA, the whole of another.
  locations: [
    {
      lga_id: 'lga-1',
      lga_code: 'dutse',
      lga_name: 'Dutse',
      whole_lga: false,
      wards: [
        { ward_id: 'w-1', ward_code: 'limawa', ward_name: 'Limawa' },
        { ward_id: 'w-2', ward_code: 'madobi', ward_name: 'Madobi' },
      ],
    },
    { lga_id: 'lga-2', lga_code: 'kiyawa', lga_name: 'Kiyawa', whole_lga: true, wards: [] },
  ],
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
  // `activityData` is module-level state the mocked hook reads, so without a reset a
  // test that narrows it (no beneficiaries, no locations) silently changes what every
  // test after it renders. Ordering-dependent tests are the kind that fail for reasons
  // that have nothing to do with the change under review.
  beforeEach(() => {
    activityData = activity
  })

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

  it('shows the declared coverage broken down LGA by LGA', () => {
    activityData = activity
    renderPage()

    const details = screen.getByText('Areas covered').closest('dl') as HTMLElement

    // Named wards under the LGA they belong to...
    expect(within(details).getByText('Dutse')).toBeInTheDocument()
    expect(within(details).getByText(/Limawa, Madobi/)).toBeInTheDocument()

    // ...and a whole-LGA declaration said in words, not as an empty ward list.
    expect(within(details).getByText('Kiyawa')).toBeInTheDocument()
    expect(within(details).getByText('whole LGA')).toBeInTheDocument()
  })

  it('shows an em dash when no areas are declared', () => {
    activityData = { ...activity, locations: [] }
    renderPage()

    const details = screen.getByText('Areas covered').closest('dl') as HTMLElement
    expect(within(details).getAllByText('—').length).toBeGreaterThan(0)
  })

  it('shows no beneficiary sections for an activity that does not involve beneficiaries', () => {
    activityData = { ...activity, involves_beneficiaries: false, beneficiaries: [], import_summary: null, service_requests: [], counts: { target: null, actual: 0, pending_service_requests: 0 } }

    renderPage()
    expect(screen.getByText('No')).toBeInTheDocument()
    expect(screen.queryByText('Beneficiaries recorded under this activity')).not.toBeInTheDocument()
    expect(screen.queryByText('Pending service requests')).not.toBeInTheDocument()
  })

  describe('the beneficiary list', () => {
    /** Row names in the order they are rendered. */
    function renderedNames(): string[] {
      const table = screen.getByText('Beneficiaries recorded under this activity').closest('table') as HTMLElement
      return within(table)
        .getAllByRole('row')
        .slice(1) // skip the header row
        .map((row) => within(row).getAllByRole('cell')[0]?.textContent ?? '')
    }

    it('can be folded away, keeping the count visible', async () => {
      const user = userEvent.setup()
      renderPage()

      expect(screen.getByText('Beneficiaries recorded under this activity')).toBeInTheDocument()

      const toggle = screen.getByRole('button', { name: /beneficiaries/i })
      expect(toggle).toHaveAttribute('aria-expanded', 'true')
      await user.click(toggle)

      // Body gone, header and count still there — a folded section must still say
      // what it contains, or it reads as missing rather than collapsed.
      expect(toggle).toHaveAttribute('aria-expanded', 'false')
      expect(screen.queryByText('Beneficiaries recorded under this activity')).not.toBeInTheDocument()
      expect(screen.getByText('2 enrolled')).toBeInTheDocument()

      await user.click(toggle)
      expect(screen.getByText('Beneficiaries recorded under this activity')).toBeInTheDocument()
    })

    it('starts open — a list that vanishes on load reads as missing data', () => {
      renderPage()
      expect(screen.getByRole('button', { name: /beneficiaries/i })).toHaveAttribute('aria-expanded', 'true')
    })

    it('sorts by name A–Z by default', () => {
      renderPage()
      expect(renderedNames()).toEqual(['Ada Okoye', 'Bala Sule'])
    })

    it('reverses the order without changing the field', async () => {
      const user = userEvent.setup()
      renderPage()

      await user.click(screen.getByRole('button', { name: /reverse the order/i }))

      expect(renderedNames()).toEqual(['Bala Sule', 'Ada Okoye'])
      expect(screen.getByLabelText('Sort by')).toHaveValue('name')
    })

    it('sorts by another field from the sort row', async () => {
      const user = userEvent.setup()
      renderPage()

      // Both are in Dutse, so ward decides: Ward 2 before Ward 3.
      await user.selectOptions(screen.getByLabelText('Sort by'), 'location')

      expect(renderedNames()).toEqual(['Bala Sule', 'Ada Okoye'])
    })

    it('does not offer sorting by the masked NIN', () => {
      // Ordering by "•••••••8901" would sort the list by the mask, not by anything real.
      renderPage()
      const options = within(screen.getByLabelText('Sort by')).getAllByRole('option').map((o) => o.textContent)
      expect(options).not.toContain('NIN')
      expect(options).toEqual(['Name', 'LGA / Ward', 'Status', 'Date enrolled'])
    })

    it('keeps the sort row and the column headers in agreement', async () => {
      // Two controls over one ordering: if they held separate state the table could
      // show one order while the row claimed another.
      const user = userEvent.setup()
      renderPage()

      await user.click(screen.getByRole('button', { name: /^enrolled$/i }))

      expect(screen.getByLabelText('Sort by')).toHaveValue('enrolled')
      expect(renderedNames()).toEqual(['Ada Okoye', 'Bala Sule'])
    })
  })
})
