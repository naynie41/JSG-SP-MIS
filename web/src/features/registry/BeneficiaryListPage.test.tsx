import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { fireEvent, render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { MemoryRouter } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { BeneficiaryListPage } from './BeneficiaryListPage'
import { beneficiaryApi } from './api'
import type { Beneficiary } from './types'

vi.mock('./api', () => ({
  beneficiaryApi: {
    list: vi.fn(),
    get: vi.fn(),
    update: vi.fn(),
    remove: vi.fn(),
    lookup: vi.fn(),
  },
}))

let permissions = ['beneficiary.view', 'beneficiary.create', 'beneficiary.edit']
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    hasPermission: (p: string) => permissions.includes(p),
    hasAnyPermission: () => true,
    user: { mda: { id: 'm-1' } },
    status: 'authenticated',
  }),
}))

const list = beneficiaryApi.list as Mock
const update = beneficiaryApi.update as Mock

function makeBeneficiary(overrides: Partial<Beneficiary> = {}): Beneficiary {
  return {
    id: 'b-1',
    owner_mda_id: 'm-1',
    first_name: 'Amina',
    middle_name: null,
    last_name: 'Sadiq',
    full_name: 'Amina Sadiq',
    nin: null,
    bvn: null,
    phone: null,
    date_of_birth: '1990-01-01',
    gender: 'female',
    address: null,
    lga: 'dutse',
    ward: 'Ward 1',
    registration_source: 'csv',
    registration_date: '2026-07-01',
    import_batch_id: null,
    original_record_id: null,
    status: 'active',
    created_at: null,
    updated_at: null,
    ...overrides,
  }
}

function renderPage(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter>{ui}</MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('BeneficiaryListPage', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    permissions = ['beneficiary.view', 'beneficiary.create', 'beneficiary.edit']
  })

  it('renders owner rows with an actions menu', async () => {
    list.mockResolvedValue({ items: [makeBeneficiary()], pagination: { page: 1, per_page: 25, total: 1, total_pages: 1 } })

    renderPage(<BeneficiaryListPage />)

    expect(await screen.findByText('Amina Sadiq')).toBeInTheDocument()
    expect(screen.getByRole('button', { name: /actions for amina sadiq/i })).toBeInTheDocument()
  })

  it('offers no manual "register/create" action — ingestion is source-only', async () => {
    list.mockResolvedValue({ items: [], pagination: { page: 1, per_page: 25, total: 0, total_pages: 1 } })

    renderPage(<BeneficiaryListPage />)

    await screen.findByText(/records are added by importing a source/i)
    expect(screen.queryByRole('button', { name: /register individual/i })).not.toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /add beneficiary/i })).not.toBeInTheDocument()
    // The empty state instead points to the Import Center.
    expect(screen.getByRole('button', { name: /import center/i })).toBeInTheDocument()
  })

  it('hides edit actions for a beneficiary owned by another MDA', async () => {
    list.mockResolvedValue({
      items: [makeBeneficiary({ owner_mda_id: 'm-2', full_name: 'Other Mda' })],
      pagination: { page: 1, per_page: 25, total: 1, total_pages: 1 },
    })

    renderPage(<BeneficiaryListPage />)

    expect(await screen.findByText('Other Mda')).toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /actions for other mda/i })).not.toBeInTheDocument()
  })

  it('corrects an existing beneficiary through the edit modal (owner-only)', async () => {
    list.mockResolvedValue({ items: [makeBeneficiary()], pagination: { page: 1, per_page: 25, total: 1, total_pages: 1 } })
    update.mockResolvedValue(makeBeneficiary({ last_name: 'Ibrahim', full_name: 'Amina Ibrahim' }))

    const user = userEvent.setup()
    renderPage(<BeneficiaryListPage />)

    await screen.findByText('Amina Sadiq')
    await user.click(screen.getByRole('button', { name: /actions for amina sadiq/i }))
    await user.click(await screen.findByRole('menuitem', { name: /edit/i }))

    const dialog = await screen.findByRole('dialog')
    expect(within(dialog).getByText('Edit beneficiary')).toBeInTheDocument()
    const lastName = within(dialog).getByLabelText('Last name')
    await user.clear(lastName)
    await user.type(lastName, 'Ibrahim')

    fireEvent.submit(document.getElementById('beneficiary-edit-form')!)

    await waitFor(() =>
      expect(update).toHaveBeenCalledWith('b-1', expect.objectContaining({ last_name: 'Ibrahim' })),
    )
  })
})
