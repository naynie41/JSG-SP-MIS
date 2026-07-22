import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { GraduationModal } from './GraduationModal'
import { graduationApi } from './api'
import type { EnrollmentGraduation } from './types'

vi.mock('./api', () => ({
  graduationApi: {
    progress: vi.fn(),
    graduate: vi.fn(),
  },
}))

let permissions = ['graduation.view', 'graduation.edit']
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: (p: string) => permissions.includes(p) }),
}))

const progressApi = graduationApi.progress as Mock
const graduateApi = graduationApi.graduate as Mock

const inProgress: EnrollmentGraduation = {
  enrollment_id: 'e-1',
  status: 'enrolled',
  progress: {
    criteria_id: 'c-1',
    logic: 'all',
    eligible: false,
    progress: 0.5,
    already_graduated: false,
    rules: [
      { type: 'benefits_received', label: 'Benefits received', current: 1, threshold: 2, met: false, progress: 0.5 },
      { type: 'months_enrolled', label: 'Months enrolled', current: 6, threshold: 3, met: true, progress: 1 },
    ],
  },
  history: [],
}

function renderModal(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>{ui}</ToastProvider>
    </QueryClientProvider>,
  )
}

describe('GraduationModal', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    permissions = ['graduation.view', 'graduation.edit']
  })

  it('shows progress against each criterion and records a graduation', async () => {
    progressApi.mockResolvedValue(inProgress)
    graduateApi.mockResolvedValue({ id: 'g-1', enrollment_id: 'e-1', reason: 'Sustained income', graduated_at: '2026-07-21T00:00:00Z' })
    const onClose = vi.fn()
    const user = userEvent.setup()

    renderModal(<GraduationModal open onClose={onClose} enrollmentId="e-1" label="#abcdef12" />)

    // Per-rule progress is surfaced.
    expect(await screen.findByText('Benefits received')).toBeInTheDocument()
    expect(screen.getByText('1 / 2')).toBeInTheDocument()
    expect(screen.getByText('6 / 3 months')).toBeInTheDocument()

    await user.type(screen.getByLabelText(/reason/i), 'Sustained income')
    await user.click(screen.getByRole('button', { name: /record graduation/i }))

    await waitFor(() => expect(graduateApi).toHaveBeenCalledWith('e-1', 'Sustained income'))
    await waitFor(() => expect(onClose).toHaveBeenCalled())
  })

  it('disables recording when the enrolment already graduated', async () => {
    progressApi.mockResolvedValue({
      ...inProgress,
      status: 'graduated',
      progress: { ...inProgress.progress, already_graduated: true, eligible: true },
    })
    renderModal(<GraduationModal open onClose={() => {}} enrollmentId="e-1" label="#abcdef12" />)

    expect(await screen.findByText(/already graduated/i)).toBeInTheDocument()
    expect(screen.getByRole('button', { name: /record graduation/i })).toBeDisabled()
  })

  it('hides the record action for a view-only user', async () => {
    permissions = ['graduation.view']
    progressApi.mockResolvedValue(inProgress)
    renderModal(<GraduationModal open onClose={() => {}} enrollmentId="e-1" label="#abcdef12" />)

    expect(await screen.findByText('Benefits received')).toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /record graduation/i })).not.toBeInTheDocument()
  })
})
