import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor } from '@testing-library/react'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { ProgrammeListPage } from './ProgrammeListPage'
import { programmeApi } from './api'

vi.mock('./api', () => ({
  programmeApi: { list: vi.fn(), catalog: vi.fn(), create: vi.fn(), update: vi.fn(), get: vi.fn(), archive: vi.fn(), budget: vi.fn() },
  activityApi: {},
  enrollmentApi: {},
}))

const perms = new Set<string>()
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: (p: string) => perms.has(p) }),
}))

const list = programmeApi.list as Mock

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

describe('ProgrammeListPage (catalog gating)', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    list.mockResolvedValue({ items: [], pagination: { page: 1, per_page: 25, total: 0, total_pages: 1 } })
  })

  it('shows no create/edit control to an MDA officer (view-only)', async () => {
    perms.clear()
    perms.add('programme.view') // an MDA officer/admin holds view but not create

    renderPage(<ProgrammeListPage />)

    await screen.findByRole('heading', { name: 'Programmes' })
    expect(screen.queryByRole('button', { name: /create programme/i })).toBeNull()
  })

  it('lets a catalog admin create programmes', async () => {
    perms.clear()
    perms.add('programme.view')
    perms.add('programme.create')

    renderPage(<ProgrammeListPage />)

    await waitFor(() => expect(screen.getByRole('button', { name: /create programme/i })).toBeInTheDocument())
  })
})
