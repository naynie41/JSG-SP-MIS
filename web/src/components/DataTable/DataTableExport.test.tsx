import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { DataTableExport } from './DataTableExport'
import { exportListFile } from '@/lib/api/exportList'

vi.mock('@/lib/api/exportList', () => ({ exportListFile: vi.fn() }))

const allowed = new Set<string>()
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: (p: string) => allowed.has(p) }),
}))

const runExport = exportListFile as Mock

function renderControl(ui: ReactNode) {
  return render(<ToastProvider>{ui}</ToastProvider>)
}

const control = (
  <DataTableExport
    endpoint="/beneficiaries/export"
    permission="beneficiary.export"
    params={{ search: 'ada', 'filter[lga]': 'dutse' }}
  />
)

describe('DataTableExport', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    allowed.clear()
  })

  it('is hidden for users without the export permission', () => {
    allowed.add('beneficiary.view') // not beneficiary.export

    renderControl(control)

    expect(screen.queryByRole('button', { name: /export/i })).not.toBeInTheDocument()
  })

  it('exports the current filters to CSV and confirms the download', async () => {
    allowed.add('beneficiary.export')
    runExport.mockResolvedValue({ queued: false })

    const user = userEvent.setup()
    renderControl(control)

    await user.click(screen.getByRole('button', { name: /export/i }))
    await user.click(await screen.findByRole('menuitem', { name: /csv/i }))

    expect(runExport).toHaveBeenCalledWith('/beneficiaries/export', { search: 'ada', 'filter[lga]': 'dutse' }, 'csv')
    expect(await screen.findByText('Export started')).toBeInTheDocument()
  })

  it('exports to Excel when that format is chosen', async () => {
    allowed.add('beneficiary.export')
    runExport.mockResolvedValue({ queued: false })

    const user = userEvent.setup()
    renderControl(control)

    await user.click(screen.getByRole('button', { name: /export/i }))
    await user.click(await screen.findByRole('menuitem', { name: /excel/i }))

    expect(runExport).toHaveBeenCalledWith('/beneficiaries/export', { search: 'ada', 'filter[lga]': 'dutse' }, 'excel')
  })

  it('tells the user a large export was queued for later notification', async () => {
    allowed.add('beneficiary.export')
    runExport.mockResolvedValue({ queued: true })

    const user = userEvent.setup()
    renderControl(control)

    await user.click(screen.getByRole('button', { name: /export/i }))
    await user.click(await screen.findByRole('menuitem', { name: /csv/i }))

    expect(await screen.findByText('Export queued')).toBeInTheDocument()
  })

  it('surfaces a failure without crashing the grid', async () => {
    allowed.add('beneficiary.export')
    runExport.mockRejectedValue(new Error('network'))

    const user = userEvent.setup()
    renderControl(control)

    await user.click(screen.getByRole('button', { name: /export/i }))
    await user.click(await screen.findByRole('menuitem', { name: /csv/i }))

    expect(await screen.findByText('Export failed')).toBeInTheDocument()
  })
})
