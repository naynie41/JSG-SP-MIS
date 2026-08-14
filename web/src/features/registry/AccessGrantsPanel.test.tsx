import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { AccessGrantsPanel } from './AccessGrantsPanel'
import { beneficiaryApi } from './api'
import type { Beneficiary, ServiceGrant } from './types'

/**
 * The owner's view of who else can read a beneficiary, and the control to end it
 * (FR-OWN-07).
 *
 * Two things are load-bearing here. The gating: a serving MDA must not see this list at
 * all, because it would learn which OTHER MDAs hold access to a record it does not own.
 * And the copy: "revoke" reads like an undo, so the confirm step has to say what it does
 * not do — past deliveries stand and ownership does not move — or an officer will expect
 * it to claw back work already recorded.
 */

vi.mock('./api', () => ({
  beneficiaryApi: { serviceGrants: vi.fn(), revokeGrant: vi.fn() },
}))

const auth = { mdaId: 'm-owner', perms: ['beneficiary.view', 'beneficiary.approve'] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { mda: { id: auth.mdaId } },
    hasPermission: (p: string) => auth.perms.includes(p),
  }),
}))

const listGrants = beneficiaryApi.serviceGrants as Mock
const revokeGrant = beneficiaryApi.revokeGrant as Mock

const BENEFICIARY = { id: 'b-1', owner_mda_id: 'm-owner', full_name: 'Amina Sadiq' } as Beneficiary

function makeGrant(overrides: Partial<ServiceGrant> = {}): ServiceGrant {
  return {
    id: 'g-1',
    mda: { id: 'm-server', name: 'Ministry of Education' },
    service_request_id: 'sr-1',
    granted_at: '2026-08-01T09:00:00Z',
    active: true,
    revoked_at: null,
    revoked_by: null,
    revocation_reason: null,
    ...overrides,
  }
}

function renderPanel() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <AccessGrantsPanel beneficiary={BENEFICIARY} />
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('AccessGrantsPanel', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    auth.mdaId = 'm-owner'
    auth.perms = ['beneficiary.view', 'beneficiary.approve']
    listGrants.mockResolvedValue([makeGrant()])
    revokeGrant.mockResolvedValue({ id: 'g-1', revoked: true, message: 'Cross-MDA read access revoked.' })
  })

  /* --------------------------------------------------------------------- listing */

  it('shows which MDA holds access, when it was granted, and via which request', async () => {
    renderPanel()

    expect(await screen.findByText('Ministry of Education')).toBeInTheDocument()
    expect(screen.getByText('Active')).toBeInTheDocument()
    // The originating Service Request — the authority an auditor follows back to the
    // decision that opened this access.
    expect(screen.getByText('sr-1')).toBeInTheDocument()
    // The granted date, asserted on the row itself rather than by text lookup — the
    // panel's intro prose also contains the word "granted".
    // Matched on the year rather than a formatted date: `toLocaleString` orders parts by
    // the runtime's locale, so pinning the format would make this fail on a CI box in a
    // different region for no real reason.
    const row = screen.getByRole('listitem')
    expect(row.textContent).toMatch(/Granted[^]*2026/)
  })

  it('says plainly when nobody else has access', async () => {
    listGrants.mockResolvedValue([])
    renderPanel()

    expect(await screen.findByText(/no other mda has been granted access/i)).toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /revoke access/i })).not.toBeInTheDocument()
  })

  it('keeps a revoked grant listed, with who withdrew it and why', async () => {
    listGrants.mockResolvedValue([
      makeGrant({
        active: false,
        revoked_at: '2026-08-10T10:00:00Z',
        revoked_by: 'Amina Bello',
        revocation_reason: 'Service episode ended',
      }),
    ])
    renderPanel()

    // History, not just current state: who COULD read this is the owner's business too.
    expect(await screen.findByText('Withdrawn')).toBeInTheDocument()
    expect(screen.getByText(/by Amina Bello/i)).toBeInTheDocument()
    expect(screen.getByText(/Service episode ended/i)).toBeInTheDocument()
    // Nothing left to revoke on a withdrawn grant.
    expect(screen.queryByRole('button', { name: /revoke access/i })).not.toBeInTheDocument()
    expect(screen.getByText(/no mda currently holds access/i)).toBeInTheDocument()
  })

  /* ---------------------------------------------------------------------- gating */

  it('renders nothing for a serving MDA that is not the owner', async () => {
    // The grantee must not learn which OTHER MDAs hold access to a record it does not own.
    auth.mdaId = 'm-server'
    renderPanel()

    // No panel at all — not an empty one, and not a permission notice that would itself
    // confirm other MDAs hold access.
    await waitFor(() => expect(screen.queryByText('Cross-MDA access')).not.toBeInTheDocument())
    expect(screen.queryByText('Ministry of Education')).not.toBeInTheDocument()
    // And it never asks: the request would 403, and the absence is decided client-side.
    expect(listGrants).not.toHaveBeenCalled()
  })

  it('shows the list read-only to an oversight role that cannot revoke', async () => {
    auth.mdaId = 'm-other'
    auth.perms = ['beneficiary.view', 'cross-mda.view']
    renderPanel()

    expect(await screen.findByText('Ministry of Education')).toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /revoke access/i })).not.toBeInTheDocument()
  })

  it('hides the revoke control from an owner lacking the approval permission', async () => {
    auth.perms = ['beneficiary.view']
    renderPanel()

    expect(await screen.findByText('Ministry of Education')).toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /revoke access/i })).not.toBeInTheDocument()
  })

  it('lets a System Administrator revoke as an override', async () => {
    auth.mdaId = 'm-other'
    auth.perms = ['beneficiary.view', 'cross-mda.view', 'mda-access.edit']
    renderPanel()

    expect(await screen.findByRole('button', { name: /revoke access/i })).toBeInTheDocument()
  })

  /* ------------------------------------------------------------- the revoke flow */

  it('requires a confirm step and does not revoke on the first click', async () => {
    const user = userEvent.setup()
    renderPanel()

    await user.click(await screen.findByRole('button', { name: /revoke access/i }))

    expect(await screen.findByRole('dialog')).toBeInTheDocument()
    // Opening the dialog is not the action — withdrawing access is not a one-click move.
    expect(revokeGrant).not.toHaveBeenCalled()
  })

  it('states what revocation does NOT do before confirming', async () => {
    const user = userEvent.setup()
    renderPanel()
    await user.click(await screen.findByRole('button', { name: /revoke access/i }))

    const dialog = await screen.findByRole('dialog')
    expect(within(dialog).getByText(/already recorded stay on the ledger/i)).toBeInTheDocument()
    expect(within(dialog).getByText(/ownership does not change/i)).toBeInTheDocument()
  })

  it('cancelling leaves the grant untouched', async () => {
    const user = userEvent.setup()
    renderPanel()
    await user.click(await screen.findByRole('button', { name: /revoke access/i }))
    await user.click(within(await screen.findByRole('dialog')).getByRole('button', { name: 'Cancel' }))

    await waitFor(() => expect(screen.queryByRole('dialog')).not.toBeInTheDocument())
    expect(revokeGrant).not.toHaveBeenCalled()
  })

  it('revokes with the optional reason on confirm', async () => {
    const user = userEvent.setup()
    renderPanel()
    await user.click(await screen.findByRole('button', { name: /revoke access/i }))

    const dialog = await screen.findByRole('dialog')
    await user.type(within(dialog).getByLabelText(/reason/i), 'Service episode ended')
    await user.click(within(dialog).getByRole('button', { name: /withdraw access/i }))

    await waitFor(() => expect(revokeGrant).toHaveBeenCalledWith('g-1', 'Service episode ended'))
  })

  it('revokes without a reason when none is given', async () => {
    const user = userEvent.setup()
    renderPanel()
    await user.click(await screen.findByRole('button', { name: /revoke access/i }))
    await user.click(within(await screen.findByRole('dialog')).getByRole('button', { name: /withdraw access/i }))

    // The reason is optional — an empty box must send undefined, not an empty string.
    await waitFor(() => expect(revokeGrant).toHaveBeenCalledWith('g-1', undefined))
  })

  it('surfaces a failed revoke and keeps the dialog open', async () => {
    revokeGrant.mockRejectedValue(new Error('boom'))
    const user = userEvent.setup()
    renderPanel()
    await user.click(await screen.findByRole('button', { name: /revoke access/i }))
    await user.click(within(await screen.findByRole('dialog')).getByRole('button', { name: /withdraw access/i }))

    expect(await screen.findByRole('alert')).toHaveTextContent(/could not withdraw/i)
    expect(screen.getByRole('dialog')).toBeInTheDocument()
  })

  it('does not carry a typed reason from one grant to another', async () => {
    listGrants.mockResolvedValue([
      makeGrant(),
      makeGrant({ id: 'g-2', mda: { id: 'm-third', name: 'Ministry of Works' } }),
    ])
    const user = userEvent.setup()
    renderPanel()

    const buttons = await screen.findAllByRole('button', { name: /revoke access/i })
    await user.click(buttons[0])
    await user.type(within(await screen.findByRole('dialog')).getByLabelText(/reason/i), 'For the first MDA')
    await user.click(within(screen.getByRole('dialog')).getByRole('button', { name: 'Cancel' }))
    await waitFor(() => expect(screen.queryByRole('dialog')).not.toBeInTheDocument())

    await user.click((await screen.findAllByRole('button', { name: /revoke access/i }))[1])

    // A reason typed for one MDA must never be submitted against another.
    const second = await screen.findByRole('dialog')
    expect(within(second).getByLabelText(/reason/i)).toHaveValue('')
    // The dialog is addressing the SECOND grant — its heading names that MDA.
    expect(within(second).getByRole('heading', { name: /Ministry of Works/ })).toBeInTheDocument()
  })
})
