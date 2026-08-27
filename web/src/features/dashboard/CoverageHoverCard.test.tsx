import { render, screen } from '@testing-library/react'
import { describe, expect, it } from 'vitest'
import { CoverageHoverCard } from './CoverageHoverCard'
import type { CoverageFeatureProperties } from '@/features/gis/types'

const area = (overrides: Partial<CoverageFeatureProperties> = {}): CoverageFeatureProperties => ({
  code: 'dutse',
  name: 'Dutse',
  level: 'lga',
  beneficiary_count: 1240,
  benefit_count: 3100,
  benefit_value: 45_000_000, // kobo → ₦450,000
  funding_allocated: 90_000_000,
  households: 310,
  served: 980,
  active_programmes: 3,
  active_activities: 7,
  mdas: ['Ministry of Health'],
  band: 'green',
  ...overrides,
})

describe('CoverageHoverCard', () => {
  it('names the area and the figures a viewer points at it to see', () => {
    render(<CoverageHoverCard area={area()} areaWord="LGA" />)

    expect(screen.getByText('Dutse')).toBeInTheDocument()
    expect(screen.getByText('1,240')).toBeInTheDocument() // beneficiaries
    expect(screen.getByText('310')).toBeInTheDocument() // households
    expect(screen.getByText('980')).toBeInTheDocument() // net-unique served
    expect(screen.getByText('3,100')).toBeInTheDocument() // deliveries
    expect(screen.getByText('₦450,000')).toBeInTheDocument() // kobo → naira
    expect(screen.getByText('Ministry of Health')).toBeInTheDocument()
  })

  it('never calls delivered value spending', () => {
    // The ledger records the VALUE OF BENEFITS DELIVERED. SP-MIS does not move money,
    // so a card that says "spent" turns a delivery record into a treasury claim.
    const { container } = render(<CoverageHoverCard area={area()} areaWord="LGA" />)
    const text = (container.textContent ?? '').toLowerCase()

    expect(text).toContain('delivered value')
    for (const word of ['spent', 'spend', 'disbursed', 'expenditure']) {
      expect(text).not.toContain(word)
    }
  })

  it('shows no coverage percentage, because none is held', () => {
    // A percentage needs a population denominator the system does not have. Under a
    // pointer is exactly where an invented ratio would read as authoritative.
    const { container } = render(<CoverageHoverCard area={area()} areaWord="LGA" />)

    expect(container.textContent).not.toMatch(/\d%/)
  })

  it('says nothing is recorded rather than showing a wall of zeroes', () => {
    // "Nothing recorded" and "we measured zero" are different claims.
    render(
      <CoverageHoverCard
        area={area({ beneficiary_count: 0, served: 0, benefit_count: 0, benefit_value: 0, households: 0, mdas: [], band: 'grey' })}
        areaWord="LGA"
      />,
    )

    expect(screen.getByText(/No beneficiaries or deliveries recorded/)).toBeInTheDocument()
    expect(screen.queryByText('Delivered value')).not.toBeInTheDocument()
  })

  it('states the band so the card agrees with the colour under the pointer', () => {
    render(<CoverageHoverCard area={area({ band: 'red' })} areaWord="LGA" />)
    expect(screen.getByText('Low')).toBeInTheDocument()
  })

  it('leads with the figure the map is coloured by when it has one', () => {
    // The investment map recolours by a chosen metric; without this the card would not
    // explain the shade the viewer is actually looking at.
    render(<CoverageHoverCard area={area()} areaWord="LGA" lead={{ label: 'Funding allocated', value: '₦900,000' }} />)

    expect(screen.getByText('Funding allocated')).toBeInTheDocument()
    expect(screen.getByText('₦900,000')).toBeInTheDocument()
  })

  it('summarises a long MDA list instead of overflowing the card', () => {
    render(<CoverageHoverCard area={area({ mdas: ['Health', 'Education', 'Women Affairs', 'Agriculture'] })} areaWord="LGA" />)

    expect(screen.getByText(/Health, Education \+2/)).toBeInTheDocument()
    expect(screen.queryByText(/Agriculture/)).not.toBeInTheDocument()
  })

  it('asks for a pointer before one arrives, naming the level', () => {
    const { rerender } = render(<CoverageHoverCard area={null} areaWord="LGA" />)
    expect(screen.getByText('Point at an LGA to see its coverage.')).toBeInTheDocument()

    rerender(<CoverageHoverCard area={null} areaWord="Ward" />)
    expect(screen.getByText('Point at a Ward to see its coverage.')).toBeInTheDocument()
  })
})
