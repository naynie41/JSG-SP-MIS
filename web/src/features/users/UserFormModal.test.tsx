import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import type { ReactNode } from 'react'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { UserFormModal } from './UserFormModal'
import { roleApi, userApi } from './api'
import { mdaApi } from '@/features/mdas/api'

vi.mock('./api', () => ({
  userApi: { list: vi.fn(), create: vi.fn(), update: vi.fn(), changeStatus: vi.fn(), forcePasswordReset: vi.fn(), resetMfa: vi.fn() },
  roleApi: { list: vi.fn() },
}))
vi.mock('@/features/mdas/api', () => ({ mdaApi: { list: vi.fn() } }))
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    hasPermission: () => true,
    hasAnyPermission: () => true,
    user: { permissions: ['user.create', 'user.edit', 'role.view', 'mda.view', 'cross-mda.view'] },
    status: 'authenticated',
  }),
}))

const MDA_ADMIN = { id: '11111111-1111-4111-8111-111111111111', key: 'mda_admin', name: 'MDA Admin', requires_mfa: false, requires_mda: true }
const EXECUTIVE = { id: '22222222-2222-4222-8222-222222222222', key: 'executive', name: 'Executive', requires_mfa: true, requires_mda: false }

const listRoles = roleApi.list as Mock
const listMdas = mdaApi.list as Mock
const createUser = userApi.create as Mock

function wrap(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>{ui}</ToastProvider>
    </QueryClientProvider>,
  )
}

/**
 * The MDA dropdown is driven by the ROLE (FR-UAM-02/03), not by the actor: these all
 * run as a System Administrator holding cross-mda.view, which under the previous
 * behaviour made the MDA optional for every role — including MDA Admin.
 */
describe('UserFormModal — MDA is chosen per role', () => {
  beforeEach(() => {
    listRoles.mockReset()
    listMdas.mockReset()
    createUser.mockReset()
    listRoles.mockResolvedValue([MDA_ADMIN, EXECUTIVE])
    listMdas.mockResolvedValue(
      Array.from({ length: 22 }, (_, i) => ({
        id: `mda-${i}`,
        name: i === 0 ? 'Ministry of Health' : `Agency ${i}`,
        type: 'agency',
        status: 'active',
      })),
    )
    createUser.mockResolvedValue({})
  })

  it('hides the MDA field until an MDA-scoped role is chosen', async () => {
    wrap(<UserFormModal open onClose={() => {}} />)

    // Wait for the role OPTIONS, not just the select: selectOptions fails if the
    // list has not resolved yet.
    await screen.findByRole('option', { name: 'MDA Admin' })
    expect(screen.queryByLabelText(/^MDA/i)).not.toBeInTheDocument()

    await userEvent.selectOptions(screen.getByLabelText(/Role/i), MDA_ADMIN.id)

    expect(await screen.findByLabelText(/^MDA/i)).toBeInTheDocument()
  })

  it('does not show the MDA field for a state-level role', async () => {
    wrap(<UserFormModal open onClose={() => {}} />)

    // Wait for the role OPTIONS, not just the select: selectOptions fails if the
    // list has not resolved yet.
    await screen.findByRole('option', { name: 'MDA Admin' })
    await userEvent.selectOptions(screen.getByLabelText(/Role/i), EXECUTIVE.id)

    await waitFor(() => expect(screen.queryByLabelText(/^MDA/i)).not.toBeInTheDocument())
  })

  it('offers the seeded MDAs and lets them be filtered', async () => {
    wrap(<UserFormModal open onClose={() => {}} />)

    // Wait for the role OPTIONS, not just the select: selectOptions fails if the
    // list has not resolved yet.
    await screen.findByRole('option', { name: 'MDA Admin' })
    await userEvent.selectOptions(screen.getByLabelText(/Role/i), MDA_ADMIN.id)

    const select = await screen.findByLabelText(/^MDA/i)
    // 22 MDAs plus the placeholder.
    expect(select.querySelectorAll('option')).toHaveLength(23)

    // 22 options is past the point a plain select is comfortable, so a filter appears.
    await userEvent.type(screen.getByLabelText(/Filter MDAs/i), 'Health')

    await waitFor(() => expect(select.querySelectorAll('option')).toHaveLength(1))
    expect(select.querySelector('option')?.textContent).toBe('Ministry of Health')
  })

  it('refuses to submit an MDA Admin with no MDA chosen', async () => {
    wrap(<UserFormModal open onClose={() => {}} />)

    // Wait for the role OPTIONS, not just the select: selectOptions fails if the
    // list has not resolved yet.
    await screen.findByRole('option', { name: 'MDA Admin' })
    await userEvent.type(screen.getByLabelText(/Full name/i), 'New Person')
    await userEvent.type(screen.getByLabelText(/Email/i), 'new.person@example.test')
    await userEvent.type(screen.getByLabelText(/Temporary password/i), 'Sup3rStr0ng!Pass')
    await userEvent.type(screen.getByLabelText(/Confirm password/i), 'Sup3rStr0ng!Pass')
    await userEvent.selectOptions(screen.getByLabelText(/Role/i), MDA_ADMIN.id)

    await userEvent.click(screen.getByRole('button', { name: /Create user/i }))

    expect(await screen.findByText('Choose the MDA this user belongs to')).toBeInTheDocument()
    expect(createUser).not.toHaveBeenCalled()
  })

  it('submits the chosen MDA for an MDA Admin', async () => {
    wrap(<UserFormModal open onClose={() => {}} />)

    // Wait for the role OPTIONS, not just the select: selectOptions fails if the
    // list has not resolved yet.
    await screen.findByRole('option', { name: 'MDA Admin' })
    await userEvent.type(screen.getByLabelText(/Full name/i), 'New Person')
    await userEvent.type(screen.getByLabelText(/Email/i), 'new.person@example.test')
    await userEvent.type(screen.getByLabelText(/Temporary password/i), 'Sup3rStr0ng!Pass')
    await userEvent.type(screen.getByLabelText(/Confirm password/i), 'Sup3rStr0ng!Pass')
    await userEvent.selectOptions(screen.getByLabelText(/Role/i), MDA_ADMIN.id)
    await userEvent.selectOptions(await screen.findByLabelText(/^MDA/i), 'mda-0')

    await userEvent.click(screen.getByRole('button', { name: /Create user/i }))

    await waitFor(() =>
      expect(createUser).toHaveBeenCalledWith(expect.objectContaining({ mda_id: 'mda-0', role_id: MDA_ADMIN.id })),
    )
  })

  it('drops a chosen MDA when the role changes to a state-level one', async () => {
    // Otherwise the form submits a pairing the server rejects, from a field that is
    // no longer on screen to explain the error.
    wrap(<UserFormModal open onClose={() => {}} />)

    // Wait for the role OPTIONS, not just the select: selectOptions fails if the
    // list has not resolved yet.
    await screen.findByRole('option', { name: 'MDA Admin' })
    await userEvent.type(screen.getByLabelText(/Full name/i), 'New Person')
    await userEvent.type(screen.getByLabelText(/Email/i), 'new.person@example.test')
    await userEvent.type(screen.getByLabelText(/Temporary password/i), 'Sup3rStr0ng!Pass')
    await userEvent.type(screen.getByLabelText(/Confirm password/i), 'Sup3rStr0ng!Pass')

    await userEvent.selectOptions(screen.getByLabelText(/Role/i), MDA_ADMIN.id)
    await userEvent.selectOptions(await screen.findByLabelText(/^MDA/i), 'mda-0')
    await userEvent.selectOptions(screen.getByLabelText(/Role/i), EXECUTIVE.id)

    await userEvent.click(screen.getByRole('button', { name: /Create user/i }))

    await waitFor(() =>
      expect(createUser).toHaveBeenCalledWith(expect.objectContaining({ role_id: EXECUTIVE.id, mda_id: undefined })),
    )
  })
})
