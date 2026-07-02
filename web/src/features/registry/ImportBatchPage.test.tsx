import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { ImportBatchPage } from './ImportBatchPage'
import { importApi } from './api'
import type { ImportBatch } from './types'

vi.mock('./api', () => ({
  importApi: { list: vi.fn(), get: vi.fn(), upload: vi.fn(), confirm: vi.fn() },
}))

vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    hasPermission: () => true,
    hasAnyPermission: () => true,
    user: { mda: { id: 'm-1' } },
    status: 'authenticated',
  }),
}))

const get = importApi.get as Mock
const confirm = importApi.confirm as Mock

function makeBatch(): ImportBatch {
  return {
    id: 'batch-1',
    owner_mda_id: 'm-1',
    uploaded_by: 'u-1',
    original_filename: 'beneficiaries.csv',
    source: 'csv',
    status: 'preview_ready',
    summary: { total_rows: 2, valid_rows: 1, invalid_rows: 1, committed_rows: 0 },
    error: null,
    rows: [
      {
        row_number: 1,
        original_record_id: 'EXT-1',
        is_valid: true,
        errors: [],
        beneficiary_id: null,
        preview: { first_name: 'Amina', last_name: 'Sadiq', nin: null, bvn: null, phone: null, date_of_birth: '1990-01-01', gender: 'female', lga: 'dutse', ward: 'Ward 1' },
      },
      {
        row_number: 2,
        original_record_id: 'EXT-2',
        is_valid: false,
        errors: [{ field: 'last_name', message: 'Last name is required' }],
        beneficiary_id: null,
        preview: { first_name: 'Bad', last_name: null, nin: null, bvn: null, phone: null, date_of_birth: null, gender: null, lga: null, ward: null },
      },
    ],
    created_at: null,
    updated_at: null,
  }
}

function renderPage(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter initialEntries={['/imports/batch-1']}>
          <Routes>
            <Route path="/imports/:id" element={ui} />
          </Routes>
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('ImportBatchPage', () => {
  beforeEach(() => vi.clearAllMocks())

  it('shows the preview summary and row-level errors', async () => {
    get.mockResolvedValue(makeBatch())

    renderPage(<ImportBatchPage />)

    // Summary + per-row validation.
    expect((await screen.findAllByText('Valid')).length).toBeGreaterThan(0)
    expect(screen.getByText('Error')).toBeInTheDocument()
    expect(screen.getByText(/Last name is required/)).toBeInTheDocument()
  })

  it('confirms the batch, committing only valid rows', async () => {
    get.mockResolvedValue(makeBatch())
    confirm.mockResolvedValue({ ...makeBatch(), status: 'completed', summary: { total_rows: 2, valid_rows: 1, invalid_rows: 1, committed_rows: 1 } })

    const user = userEvent.setup()
    renderPage(<ImportBatchPage />)

    const confirmButton = await screen.findByRole('button', { name: /confirm & commit 1 valid/i })
    await user.click(confirmButton)

    await waitFor(() => expect(confirm).toHaveBeenCalledWith('batch-1'))
  })
})
