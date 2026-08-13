import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { ConsentPanel } from './ConsentPanel'
import { beneficiaryApi } from './api'
import type { Beneficiary, ConsentStatus } from './types'
import { ApiError } from '@/types/api'

vi.mock('./api', () => ({
  beneficiaryApi: { recordConsent: vi.fn(), list: vi.fn(), get: vi.fn(), update: vi.fn() },
}))

const auth = { mdaId: 'm-owner' }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { name: 'Amina', mda: { id: auth.mdaId, name: 'Ministry of Health' } },
    hasPermission: () => true,
  }),
}))

const recordConsent = beneficiaryApi.recordConsent as Mock

function beneficiary(status: ConsentStatus, at: string | null = null): Beneficiary {
  return {
    id: 'b-1',
    owner_mda_id: 'm-owner',
    owner_mda: { id: 'm-owner', name: 'Ministry of Health' },
    first_name: 'Aisha', middle_name: null, last_name: 'Bello', full_name: 'Aisha Bello',
    nin: null, bvn: null, phone: null, date_of_birth: null, gender: null,
    address: null, lga: 'dutse', ward: null, status: 'active',
    registration_source: 'excel', registration_date: '2026-01-05',
    sharing_consent: status,
    sharing_consent_at: at,
    import_batch_id: null, original_record_id: null,
    created_at: null, updated_at: null,
  } as Beneficiary
}

function renderPanel(record: Beneficiary) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <ConsentPanel beneficiary={record} />
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('Beneficiary consent', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    auth.mdaId = 'm-owner'
    recordConsent.mockResolvedValue(beneficiary('granted', '2026-08-10T09:00:00+01:00'))
  })

  /* ------------------------------------------------------------------ display */

  it('shows an absent decision as "not recorded", never as a refusal', () => {
    renderPanel(beneficiary('unknown'))

    // "No" would misrepresent the record: nobody has decided. The distinction matters
    // to whoever has to justify how the data was handled.
    expect(screen.getByText('Not recorded')).toBeInTheDocument()
    expect(screen.getByText(/Consent is never assumed/i)).toBeInTheDocument()
  })

  it('shows a granted decision with when it was recorded', () => {
    renderPanel(beneficiary('granted', '2026-08-01T10:30:00+01:00'))

    expect(screen.getByText('Granted')).toBeInTheDocument()
    expect(screen.getByText(/Last recorded/)).toBeInTheDocument()
    expect(screen.queryByText(/Last recorded —/)).not.toBeInTheDocument()
  })

  it('says a withdrawal suspends existing grants', () => {
    renderPanel(beneficiary('withdrawn', '2026-08-02T10:30:00+01:00'))

    expect(screen.getByText('Withdrawn')).toBeInTheDocument()
    expect(screen.getByText(/grant on this record is suspended/i)).toBeInTheDocument()
  })

  /* ------------------------------------------------------------- owner only */

  it('lets the owner MDA record consent, capturing the lawful basis', async () => {
    const user = userEvent.setup()
    renderPanel(beneficiary('unknown'))

    await user.click(screen.getByRole('button', { name: /record consent/i }))
    const dialog = await screen.findByRole('dialog')
    await user.type(within(dialog).getByLabelText(/how was consent obtained/i), 'Signed enrolment form')
    await user.click(within(dialog).getByRole('button', { name: /record consent/i }))

    await waitFor(() =>
      expect(recordConsent).toHaveBeenCalledWith('b-1', expect.objectContaining({
        status: 'granted',
        basis: 'Signed enrolment form',
      })),
    )
  })

  it('requires a basis for a grant but not for a withdrawal', async () => {
    const user = userEvent.setup()
    renderPanel(beneficiary('unknown'))

    await user.click(screen.getByRole('button', { name: /record consent/i }))
    const dialog = await screen.findByRole('dialog')
    await user.click(within(dialog).getByRole('button', { name: /record consent/i }))

    expect(await screen.findByRole('alert')).toHaveTextContent(/how consent was obtained/i)
    expect(recordConsent).not.toHaveBeenCalled()
  })

  it('withdraws without demanding a justification', async () => {
    recordConsent.mockResolvedValue(beneficiary('withdrawn'))
    const user = userEvent.setup()
    renderPanel(beneficiary('granted', '2026-08-01T10:30:00+01:00'))

    await user.click(screen.getByRole('button', { name: /withdraw consent/i }))
    const dialog = await screen.findByRole('dialog')
    // A person withdrawing consent does not have to explain themselves.
    expect(within(dialog).queryByLabelText(/how was consent obtained/i)).not.toBeInTheDocument()

    await user.click(within(dialog).getByRole('button', { name: /withdraw consent/i }))
    await waitFor(() => expect(recordConsent).toHaveBeenCalledWith('b-1', expect.objectContaining({ status: 'withdrawn' })))
  })

  it('offers only the action that changes the current state', () => {
    renderPanel(beneficiary('granted', '2026-08-01T10:30:00+01:00'))
    expect(screen.queryByRole('button', { name: /^record consent$/i })).not.toBeInTheDocument()
    expect(screen.getByRole('button', { name: /withdraw consent/i })).toBeInTheDocument()
  })

  it('is read-only for a non-owner and names who can change it', () => {
    auth.mdaId = 'm-other'
    renderPanel(beneficiary('granted', '2026-08-01T10:30:00+01:00'))

    // Consent belongs to the data controller — the owning MDA.
    expect(screen.queryByRole('button', { name: /record consent/i })).not.toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /withdraw consent/i })).not.toBeInTheDocument()
    expect(screen.getByText(/Only Ministry of Health can record/i)).toBeInTheDocument()
    // …but the status is still visible, because whether they may act depends on it.
    expect(screen.getByText('Granted')).toBeInTheDocument()
  })

  /* ------------------------------------------------------------------- errors */

  it('surfaces the server’s refusal rather than inventing one', async () => {
    recordConsent.mockRejectedValue(new ApiError(403, 'FORBIDDEN', 'Only the owner MDA may record consent.'))
    const user = userEvent.setup()
    renderPanel(beneficiary('unknown'))

    await user.click(screen.getByRole('button', { name: /record consent/i }))
    const dialog = await screen.findByRole('dialog')
    await user.type(within(dialog).getByLabelText(/how was consent obtained/i), 'Verbal at registration')
    await user.click(within(dialog).getByRole('button', { name: /record consent/i }))

    expect(await screen.findByRole('alert')).toHaveTextContent(/Only the owner MDA may record consent/i)
  })

  /* -------------------------------------------------------------------- audit */

  it('tells the user the change is kept and audited', () => {
    renderPanel(beneficiary('unknown'))

    expect(screen.getByText(/immutable history entry and written to the audit log/i)).toBeInTheDocument()
  })
})
