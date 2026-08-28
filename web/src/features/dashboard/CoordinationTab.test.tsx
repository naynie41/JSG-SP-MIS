import { describe, expect, it } from 'vitest'
import { render, screen, within } from '@testing-library/react'
import { CoordinationTab } from './CoordinationTab'
import { makeExecutivePayload } from './executiveTestData'

describe('CoordinationTab', () => {
  it('renders the agencies panel (active agencies, activities, joint programmes)', () => {
    render(<CoordinationTab data={makeExecutivePayload()} />)

    expect(screen.getByText('Active agencies')).toBeInTheDocument()
    expect(screen.getByText('6')).toBeInTheDocument() // active_mdas
    expect(screen.getByText('Active activities')).toBeInTheDocument()

    // Joint programmes KPI (scoped so the bare "2" doesn't collide with other counts).
    const jointKpi = screen.getByText('Joint programmes').closest('div')!
    expect(within(jointKpi).getByText('2')).toBeInTheDocument()
  })

  it('renders cross-agency collaboration: joint beneficiaries, referral + approval rates', () => {
    render(<CoordinationTab data={makeExecutivePayload()} />)

    expect(screen.getByText('Joint beneficiaries')).toBeInTheDocument()
    expect(screen.getByText('900')).toBeInTheDocument() // cross_mda_beneficiaries

    expect(screen.getByText('Referral completion')).toBeInTheDocument()
    expect(screen.getByText('75%')).toBeInTheDocument() // referral completion_rate

    expect(screen.getByText('Request-to-serve approval')).toBeInTheDocument()
    expect(screen.getByText('83%')).toBeInTheDocument() // approval_rate 0.833
    expect(screen.getByText(/avg turnaround 19h/i)).toBeInTheDocument() // 18.5h → 19h
  })

  it('shows per-partner contributions scoped to funded programmes', () => {
    render(<CoordinationTab data={makeExecutivePayload()} />)

    const table = screen.getByRole('table', { name: /funding and beneficiaries by partner/i })
    expect(within(table).getByText('World Bank')).toBeInTheDocument()
    expect(within(table).getByText('UNICEF')).toBeInTheDocument()
    expect(within(table).getByText('Dangote Foundation')).toBeInTheDocument()

    // World Bank row carries its scoped funding + beneficiaries.
    const row = within(table).getByText('World Bank').closest('tr')!
    expect(within(row).getByText('1,600')).toBeInTheDocument() // beneficiaries_served
    expect(within(row).getByText('₦400,000.00')).toBeInTheDocument() // funding_allocated (kobo)
  })

  it('shows data-sharing / API-sync health with a derived status', () => {
    render(<CoordinationTab data={makeExecutivePayload()} />)

    expect(screen.getByText(/Registry sync: Degraded/i)).toBeInTheDocument() // 3 failed runs
    expect(screen.getByText('Agencies integrated')).toBeInTheDocument()
    expect(screen.getByText('4')).toBeInTheDocument() // connectors
    expect(screen.getByText('512')).toBeInTheDocument() // api_registrations

    // Integrated source chips (labelled).
    expect(screen.getByText('SOCU')).toBeInTheDocument()
    expect(screen.getByText('Government system')).toBeInTheDocument()
    expect(screen.getByText('API')).toBeInTheDocument()
  })

  it('notes the meetings module as a future or external slot but does NOT build it', () => {
    render(<CoordinationTab data={makeExecutivePayload()} />)

    // Only a note is present — no attendance/action-item panels or controls.
    expect(screen.getByText(/not part of SP-MIS/i)).toBeInTheDocument()
    expect(screen.getByText(/future or external slot/i)).toBeInTheDocument()
    expect(screen.queryByText(/attendance/i)?.closest('table')).toBeFalsy()
    expect(screen.queryByRole('table', { name: /attendance|action item|minutes/i })).toBeNull()
  })

  it('is a pure read-only display — no interactive controls', () => {
    render(<CoordinationTab data={makeExecutivePayload()} />)

    expect(screen.queryAllByRole('button')).toHaveLength(0)
    expect(screen.queryByRole('textbox')).toBeNull()
  })

  it('shows an empty state when coordination is unavailable (e.g. partner scope)', () => {
    const payload = makeExecutivePayload()
    payload.metrics.coordination = null
    render(<CoordinationTab data={payload} />)

    expect(screen.getByText(/Coordination metrics are unavailable/i)).toBeInTheDocument()
  })
})
