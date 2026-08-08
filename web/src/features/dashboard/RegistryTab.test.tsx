import { describe, expect, it } from 'vitest'
import { render, screen } from '@testing-library/react'
import { RegistryTab } from './RegistryTab'
import { makeExecutivePayload } from './executiveTestData'

describe('RegistryTab', () => {
  it('renders the registry KPIs', () => {
    render(<RegistryTab data={makeExecutivePayload()} />)

    expect(screen.getByText('Total individuals')).toBeInTheDocument()
    expect(screen.getAllByText('9,100').length).toBeGreaterThan(0) // individuals
    expect(screen.getByText('Total households')).toBeInTheDocument()
    expect(screen.getByText('5,200')).toBeInTheDocument()
    expect(screen.getByText('8,600')).toBeInTheDocument() // verified
    expect(screen.getByText('480')).toBeInTheDocument() // pending
    expect(screen.getByText('37')).toBeInTheDocument() // duplicates detected
    // "New this period" also appears as the latest point of the registrations
    // trend, which moved here from the Overview.
    expect(screen.getAllByText('640').length).toBeGreaterThan(0)
  })

  it('carries the registry composition and trend relocated from the Overview', () => {
    render(<RegistryTab data={makeExecutivePayload()} />)

    expect(screen.getByText('Female share')).toBeInTheDocument()
    expect(screen.getByText('New registrations')).toBeInTheDocument()
    // Page identity for screen-reader heading navigation.
    expect(screen.getByRole('heading', { level: 1, name: /registry & data quality/i })).toBeInTheDocument()
  })

  it('renders the data-quality panel with derived rates', () => {
    render(<RegistryTab data={makeExecutivePayload()} />)

    expect(screen.getByText('Verification rate')).toBeInTheDocument()
    expect(screen.getByText('95%')).toBeInTheDocument() // 8,600 / 9,100

    expect(screen.getByText('Data completeness')).toBeInTheDocument()
    expect(screen.getByText('76%')).toBeInTheDocument()

    expect(screen.getByText('Duplicate rate')).toBeInTheDocument()

    expect(screen.getByText('Missing NIN')).toBeInTheDocument()
    expect(screen.getByText('18%')).toBeInTheDocument() // 1 - 0.82

    expect(screen.getByText('Missing phone')).toBeInTheDocument()
    expect(screen.getByText('30%')).toBeInTheDocument() // 1 - 0.70
  })

  it('breaks households down by the fields we HAVE (gender, age band, household size)', () => {
    render(<RegistryTab data={makeExecutivePayload()} />)

    // Gender split.
    expect(screen.getByText('Gender')).toBeInTheDocument()
    expect(screen.getByText('Female')).toBeInTheDocument()
    expect(screen.getByText('Male')).toBeInTheDocument()

    // Age band.
    expect(screen.getByText('Age band')).toBeInTheDocument()
    expect(screen.getByText('Children')).toBeInTheDocument()
    expect(screen.getByText('Elderly')).toBeInTheDocument()

    // Household size (with average).
    expect(screen.getByText('Household size')).toBeInTheDocument()
    expect(screen.getByText('avg 4.3')).toBeInTheDocument()
    expect(screen.getByText('2–3')).toBeInTheDocument()
    expect(screen.getByText('7+')).toBeInTheDocument()
  })

  it('does NOT render any panel for the omitted (uncaptured) fields', () => {
    render(<RegistryTab data={makeExecutivePayload()} />)

    expect(screen.queryByText(/povert|disabilit|\bPWD\b|\bIDP\b|occupation|vulnerab/i)).toBeNull()
  })

  it('is a pure read-only display — no interactive controls', () => {
    render(<RegistryTab data={makeExecutivePayload()} />)

    expect(screen.queryAllByRole('button')).toHaveLength(0)
    expect(screen.queryByRole('textbox')).toBeNull()
  })

  it('degrades gracefully when registry metrics are absent (older snapshot)', () => {
    const payload = makeExecutivePayload()
    const m = payload.metrics
    m.population = undefined
    m.demographics = undefined
    m.registry_quality = undefined
    m.household_size = undefined

    expect(() => render(<RegistryTab data={payload} />)).not.toThrow()
  })
})
