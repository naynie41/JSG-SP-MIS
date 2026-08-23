import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { SegmentBuilderPanel } from './SegmentBuilderPanel'
import { reportsApi } from './api'

vi.mock('./api', () => ({
  reportsApi: {
    segmentDimensions: vi.fn(),
    segmentPreview: vi.fn(),
    exportSegment: vi.fn(),
  },
}))

const segmentDimensions = reportsApi.segmentDimensions as Mock
const segmentPreview = reportsApi.segmentPreview as Mock
const exportSegment = reportsApi.exportSegment as Mock

function catalogue(overrides: Record<string, unknown> = {}) {
  return {
    tier: 'rows',
    reveal_pii: false,
    cell_size_guard: false,
    minimum_cell_size: 5,
    dimensions: [
      {
        key: 'gender',
        label: 'Gender',
        kind: 'enum',
        canonical: true,
        options: [
          { value: 'female', label: 'Female' },
          { value: 'male', label: 'Male' },
        ],
      },
      { key: 'date_of_birth', label: 'Age', kind: 'age', canonical: true, unit: 'years' },
      { key: 'ward', label: 'Ward', kind: 'lookup', canonical: true },
      // A field the frontend has never heard of — it must still render.
      {
        key: 'disability_status',
        label: 'Disability status',
        kind: 'enum',
        canonical: true,
        options: [{ value: 'yes', label: 'Yes' }],
      },
    ],
    ...overrides,
  }
}

function previewResult(overrides: Record<string, unknown> = {}) {
  return {
    total: 3,
    total_suppressed: false,
    tier: 'rows',
    reveal_pii: false,
    cell_size_guard: false,
    minimum_cell_size: 5,
    columns: [
      { key: 'first_name', label: 'First name' },
      { key: 'nin', label: 'NIN' },
    ],
    rows: [{ first_name: 'Amina', nin: '•••••••0011' }],
    page: 1,
    page_size: 50,
    breakdown: null,
    ...overrides,
  }
}

function renderPanel() {
  const client = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={client}>
      <ToastProvider>
        <SegmentBuilderPanel />
      </ToastProvider>
    </QueryClientProvider>,
  )
}

/**
 * The segment builder, from the user's side (FR-RPT-03).
 *
 * The panel holds no opinion about which filters exist or what the caller may see —
 * both come from the server. These tests pin that: the UI renders a dimension it has
 * never been told about, and it shows an aggregate-tier user counts rather than rows.
 */
describe('SegmentBuilderPanel', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    segmentDimensions.mockResolvedValue(catalogue())
    segmentPreview.mockResolvedValue(previewResult())
    exportSegment.mockResolvedValue({ id: 'run-1' })
  })

  it('renders a filter the frontend has no knowledge of', async () => {
    // The auto-expose promise, seen from the client: a new segmentable schema field
    // arrives in the catalogue and becomes a control with no frontend change.
    renderPanel()

    // A fieldset+legend is a group; the label also appears as a breakdown option, so
    // the group is what proves a FILTER CONTROL was built for it.
    expect(await screen.findByRole('group', { name: 'Disability status' })).toBeInTheDocument()
    expect(screen.getByRole('checkbox', { name: /yes/i })).toBeInTheDocument()
  })

  it('composes a multi-dimension filter and sends it as AND across, OR within', async () => {
    renderPanel()

    await userEvent.click(await screen.findByRole('checkbox', { name: /female/i }))
    await userEvent.type(screen.getByLabelText('From'), '20')
    await userEvent.type(screen.getByLabelText('To'), '25')
    await userEvent.click(screen.getByRole('button', { name: /run segment/i }))

    expect(segmentPreview).toHaveBeenCalledWith({
      filters: {
        gender: { op: 'in', values: ['female'] },
        date_of_birth: { op: 'between', values: ['20', '25'] },
      },
      breakdown: null,
    })
  })

  it('shows the table for a rows tier', async () => {
    renderPanel()

    await userEvent.click(await screen.findByRole('button', { name: /run segment/i }))

    expect(await screen.findByText('Amina')).toBeInTheDocument()
    // Masked by the server; the client never receives the full identifier.
    expect(screen.getByText('•••••••0011')).toBeInTheDocument()
  })

  it('tells an aggregate-tier user why there is no table', async () => {
    segmentDimensions.mockResolvedValue(catalogue({ tier: 'aggregate', cell_size_guard: true }))
    segmentPreview.mockResolvedValue(previewResult({ tier: 'aggregate', columns: [], rows: [] }))
    renderPanel()

    await userEvent.click(await screen.findByRole('button', { name: /run segment/i }))

    expect(await screen.findByText(/aggregate reporting only/i)).toBeInTheDocument()
    expect(screen.getByText(/never the beneficiary registry/i)).toBeInTheDocument()
  })

  it('says a withheld group is withheld rather than showing it as zero', async () => {
    segmentDimensions.mockResolvedValue(catalogue({ tier: 'aggregate', cell_size_guard: true }))
    segmentPreview.mockResolvedValue(
      previewResult({
        tier: 'aggregate',
        columns: [],
        rows: [],
        breakdown: {
          dimension: 'gender',
          label: 'Gender',
          minimum: 5,
          suppressed_groups: 1,
          suppressed_total: 2,
          groups: [
            { key: 'female', label: 'Female', count: 6, suppressed: false },
            { key: 'male', label: 'Male', count: null, suppressed: true },
          ],
        },
      }),
    )
    renderPanel()

    await userEvent.click(await screen.findByRole('button', { name: /run segment/i }))
    await userEvent.click(await screen.findByRole('button', { name: /show chart/i }))

    expect(screen.getByText('< 5')).toBeInTheDocument()
    expect(screen.getByText(/1 group withheld/i)).toBeInTheDocument()
  })

  it('explains a suppressed total instead of showing nothing', async () => {
    segmentDimensions.mockResolvedValue(catalogue({ tier: 'aggregate', cell_size_guard: true }))
    segmentPreview.mockResolvedValue(
      previewResult({ tier: 'aggregate', total: null, total_suppressed: true, columns: [], rows: [] }),
    )
    renderPanel()

    await userEvent.click(await screen.findByRole('button', { name: /run segment/i }))

    expect(await screen.findByText(/the count is withheld/i)).toBeInTheDocument()
  })

  it('queues an export with the same definition it previewed', async () => {
    renderPanel()

    await userEvent.click(await screen.findByRole('checkbox', { name: /female/i }))
    await userEvent.click(screen.getByRole('button', { name: /^export$/i }))

    expect(exportSegment).toHaveBeenCalledWith(
      { filters: { gender: { op: 'in', values: ['female'] } }, breakdown: null },
      'csv',
    )
  })
})
