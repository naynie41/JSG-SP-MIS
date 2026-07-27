import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { ExportMenu } from './ExportMenu'
import { dashboardApi } from './api'
import { EMPTY_FILTER } from './types'

vi.mock('./api', () => ({ dashboardApi: { export: vi.fn() } }))
const exportFn = dashboardApi.export as Mock

describe('ExportMenu', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    exportFn.mockResolvedValue(undefined)
  })

  it('offers PDF / Excel / CSV', async () => {
    render(<ExportMenu filter={EMPTY_FILTER} />)
    await userEvent.click(screen.getByRole('button', { name: /export/i }))

    expect(screen.getByRole('menuitem', { name: 'PDF' })).toBeInTheDocument()
    expect(screen.getByRole('menuitem', { name: 'Excel' })).toBeInTheDocument()
    expect(screen.getByRole('menuitem', { name: 'CSV' })).toBeInTheDocument()
  })

  it('exports the current filter in the chosen format', async () => {
    const filter = { ...EMPTY_FILTER, programme_id: 'p-a' }
    render(<ExportMenu filter={filter} />)

    await userEvent.click(screen.getByRole('button', { name: /export/i }))
    await userEvent.click(screen.getByRole('menuitem', { name: 'Excel' }))

    expect(exportFn).toHaveBeenCalledWith('xlsx', filter)
  })
})
