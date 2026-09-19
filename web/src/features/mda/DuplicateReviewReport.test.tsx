import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { DuplicateReviewReport } from './DuplicateReviewReport'
import { reportsApi } from '@/features/reports/api'
import type { DuplicateReviewReport as Report } from '@/features/reports/types'

vi.mock('@/features/reports/api', () => ({
  reportsApi: { duplicateReview: vi.fn(), exportDuplicateReview: vi.fn() },
}))

const review = reportsApi.duplicateReview as Mock
const exportReview = reportsApi.exportDuplicateReview as Mock

function report(overrides: Partial<Report> = {}): Report {
  return {
    scope: { kind: 'mda', label: 'Ministry of Health' },
    filters: {},
    totals: { surfaced: 12, exact: 4, probable: 8, decided: 7, awaiting: 4, closed_undecided: 1 },
    decisions: { new: 2, link: 3, own: 1, skip: 1 },
    waiting: [
      { key: 'recent', label: 'Under 7 days', count: 2 },
      { key: 'weeks', label: '7 to 30 days', count: 1 },
      { key: 'old', label: 'Over 30 days', count: 1 },
    ],
    median_hours_to_decide: 30,
    batches: [
      {
        id: 'b1',
        file: 'dutse-march.xlsx',
        activity: 'Cash transfer, March',
        mda: 'Ministry of Health',
        source: 'excel',
        status: 'preview_ready',
        uploaded_at: '2026-09-01T09:00:00+01:00',
        matches: 9,
        exact: 3,
        probable: 6,
        decided: 5,
        awaiting: 4,
        closed_undecided: 0,
      },
    ],
    batches_total: 1,
    computed_at: '2026-09-14T09:00:00+01:00',
    ...overrides,
  }
}

function renderReport(canExport = true) {
  const client = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={client}>
      <ToastProvider>
        <MemoryRouter>
          <DuplicateReviewReport canExport={canExport} />
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

/**
 * The duplicate review report replaced a group-by/measures builder over import rows. It
 * reports the state of the match queue and offers nothing to configure beyond when the
 * matches were found and which band.
 */
describe('DuplicateReviewReport', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    review.mockResolvedValue(report())
    exportReview.mockResolvedValue({ id: 'run-1', status: 'pending' })
  })

  it('has no group-by or measure controls — only the narrowing that matters', async () => {
    renderReport()
    await screen.findByText('Review progress')

    expect(screen.queryByRole('checkbox')).not.toBeInTheDocument()
    expect(screen.queryByText(/group by/i)).not.toBeInTheDocument()
    expect(screen.queryByText(/measures/i)).not.toBeInTheDocument()
    expect(screen.getByLabelText('Found from')).toBeInTheDocument()
    expect(screen.getByLabelText('Match type')).toBeInTheDocument()
  })

  it('states where the queue stands', async () => {
    renderReport()

    const band = await screen.findByRole('region', { name: /queue at a glance/i })
    expect(within(band).getByText('Matches found').nextElementSibling).toHaveTextContent('12')
    expect(within(band).getByText('Awaiting a decision', { selector: 'dt' }).nextElementSibling).toHaveTextContent('4')
    expect(within(band).getByText('30 hours')).toBeInTheDocument()
    expect(within(band).getByText('4 exact and 8 probable matches')).toBeInTheDocument()
    expect(within(band).getByText(/in uploads that finished or failed/i)).toBeInTheDocument()
  })

  it('points to where the waiting matches are decided', async () => {
    renderReport()

    const link = await screen.findByRole('link', { name: /review matches/i })
    expect(link).toHaveAttribute('href', '/mda/duplicate-resolution')
  })

  it('shows how long matches have waited and what was decided', async () => {
    renderReport()

    const waited = (await screen.findByRole('heading', { name: 'How long matches have waited' })).closest('section')!
    expect(within(waited).getByText('Over 30 days')).toBeInTheDocument()

    const decisions = screen.getByRole('heading', { name: 'Decisions taken' }).closest('section')!
    expect(within(decisions).getByText('Linked to another MDA’s record').parentElement).toHaveTextContent('3')
  })

  it('lists the uploads the matches came from', async () => {
    renderReport()

    const table = await screen.findByRole('table')
    const row = within(table).getByText('dutse-march.xlsx').closest('tr')!
    expect(within(row).getByText('Cash transfer, March')).toBeInTheDocument()
    expect(within(row).getByText('Excel upload')).toBeInTheDocument()
  })

  it('narrows by band', async () => {
    const user = userEvent.setup()
    renderReport()
    await screen.findByText('Review progress')

    await user.selectOptions(screen.getByLabelText('Match type'), 'probable')

    await waitFor(() => expect(review).toHaveBeenLastCalledWith({ band: 'probable' }))
  })

  it('refuses an end date before the start date instead of asking the server', async () => {
    const user = userEvent.setup()
    renderReport()
    await screen.findByText('Review progress')

    await user.type(screen.getByLabelText('Found from'), '2026-09-10')
    await user.type(screen.getByLabelText('Found to'), '2026-09-01')

    expect(await screen.findByText('Must be on or after the start date')).toBeInTheDocument()
    expect(review).not.toHaveBeenCalledWith(expect.objectContaining({ date_to: '2026-09-01' }))
    expect(screen.getByRole('button', { name: 'Export' })).toBeDisabled()
  })

  it('exports the same narrowing in the chosen format', async () => {
    const user = userEvent.setup()
    renderReport()
    await screen.findByText('Review progress')

    await user.selectOptions(screen.getByLabelText('Match type'), 'exact')
    await user.selectOptions(screen.getByLabelText('Export as'), 'xlsx')
    await user.click(screen.getByRole('button', { name: 'Export' }))

    await waitFor(() => expect(exportReview).toHaveBeenCalledWith({ band: 'exact' }, 'xlsx'))
  })

  it('offers no export without the permission', async () => {
    renderReport(false)
    await screen.findByText('Review progress')

    expect(screen.queryByRole('button', { name: 'Export' })).not.toBeInTheDocument()
  })

  it('says plainly when there is nothing to review', async () => {
    review.mockResolvedValue(
      report({
        totals: { surfaced: 0, exact: 0, probable: 0, decided: 0, awaiting: 0, closed_undecided: 0 },
        batches: [],
        batches_total: 0,
      }),
    )
    renderReport()

    expect(await screen.findByText(/no matches found yet/i)).toBeInTheDocument()
  })
})
