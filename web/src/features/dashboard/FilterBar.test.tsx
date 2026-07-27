import { describe, expect, it, vi } from 'vitest'
import { render, screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { FilterBar } from './FilterBar'
import { filterParams } from './api'
import { EMPTY_FILTER } from './types'
import type { FilterOptions } from './types'

const options: FilterOptions = {
  programmes: [
    { id: 'p-a', name: 'cash_transfer' },
    { id: 'p-b', name: 'school_feeding' },
  ],
  mdas: [{ id: 'mda-1', name: 'Ministry of Health' }],
  lgas: ['dutse', 'hadejia'],
  wards: ['zango'],
  years: [2026, 2025],
}

describe('FilterBar', () => {
  it('renders scoped options for every filter dimension', () => {
    render(<FilterBar value={EMPTY_FILTER} options={options} onChange={() => {}} />)

    expect(screen.getByRole('combobox', { name: 'Year' })).toBeInTheDocument()
    expect(screen.getByRole('combobox', { name: 'Quarter' })).toBeInTheDocument()
    expect(screen.getByRole('combobox', { name: 'Month' })).toBeInTheDocument()
    // Programme options come from the scoped list (titleCased).
    expect(screen.getByRole('option', { name: 'Cash Transfer' })).toBeInTheDocument()
    expect(screen.getByRole('option', { name: 'Ministry of Health' })).toBeInTheDocument()
    expect(screen.getByRole('option', { name: '2026' })).toBeInTheDocument()
  })

  it('emits the updated filter when a dimension changes', async () => {
    const onChange = vi.fn()
    const user = userEvent.setup()
    render(<FilterBar value={EMPTY_FILTER} options={options} onChange={onChange} />)

    await user.selectOptions(screen.getByRole('combobox', { name: 'Programme' }), 'p-a')
    expect(onChange).toHaveBeenCalledWith({ ...EMPTY_FILTER, programme_id: 'p-a' })

    await user.selectOptions(screen.getByRole('combobox', { name: 'Year' }), '2026')
    expect(onChange).toHaveBeenCalledWith({ ...EMPTY_FILTER, year: 2026 }) // numeric coercion
  })

  it('shows a Clear control only when a filter is active, and clears to empty', async () => {
    const onChange = vi.fn()
    const user = userEvent.setup()
    const { rerender } = render(<FilterBar value={EMPTY_FILTER} options={options} onChange={onChange} />)
    expect(screen.queryByRole('button', { name: /clear/i })).toBeNull()

    rerender(<FilterBar value={{ ...EMPTY_FILTER, lga: 'dutse', year: 2026 }} options={options} onChange={onChange} live />)
    const clear = screen.getByRole('button', { name: /clear/i })
    expect(clear).toBeInTheDocument()
    await user.click(clear)
    expect(onChange).toHaveBeenCalledWith(EMPTY_FILTER)
  })
})

describe('filterParams', () => {
  it('drops empty values so an unfiltered request sends no params', () => {
    expect(filterParams(EMPTY_FILTER)).toEqual({})
    expect(filterParams(undefined)).toEqual({})
  })

  it('keeps only the set values (for the query string)', () => {
    expect(filterParams({ ...EMPTY_FILTER, year: 2026, programme_id: 'p-a', lga: 'dutse' })).toEqual({
      year: 2026,
      programme_id: 'p-a',
      lga: 'dutse',
    })
  })
})
