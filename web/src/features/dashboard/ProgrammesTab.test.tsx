import { beforeEach, describe, expect, it, vi } from 'vitest'
import { render, screen, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { ProgrammesTab } from './ProgrammesTab'
import { makeExecutivePayload } from './executiveTestData'

const perms = { keys: new Set<string>(['activity.view', 'cross-mda.view']) }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: (k: string) => perms.keys.has(k) }),
}))

describe('ProgrammesTab', () => {
  beforeEach(() => {
    perms.keys = new Set(['activity.view', 'cross-mda.view'])
  })

  it('renders a performance card per programme with status, MDA, budget and completion', () => {
    render(<ProgrammesTab data={makeExecutivePayload()} />)

    // Names appear across the comparison + financial tables + cards.
    expect(screen.getAllByText('Cash Transfer').length).toBeGreaterThan(0)
    expect(screen.getAllByText('School Feeding').length).toBeGreaterThan(0)

    // Card-level detail: status chip, implementing MDA, completion %.
    expect(screen.getAllByText('Active').length).toBeGreaterThan(0)
    expect(screen.getByText('Draft')).toBeInTheDocument() // conditional_grants
    expect(screen.getAllByText('Ministry of Humanitarian Affairs').length).toBeGreaterThan(0)
    expect(screen.getAllByText(/Reached \/ target/i).length).toBeGreaterThan(0)
  })

  it('renders the comparison table with target / reached / completion', () => {
    render(<ProgrammesTab data={makeExecutivePayload()} />)

    const comparison = screen.getByRole('table', { name: /target, reached and completion/i })
    const head = within(comparison).getAllByRole('columnheader').map((c) => c.textContent)
    expect(head).toEqual(expect.arrayContaining(['Programme', 'Target', 'Reached', 'Completion', 'Score']))

    // The rows carry the figures (skills_training: 1,400 reached of 2,000 target).
    const row = within(comparison).getByText('Skills Training').closest('tr')!
    expect(within(row).getByText('2,000')).toBeInTheDocument()
    expect(within(row).getByText('1,400')).toBeInTheDocument()
    expect(within(row).getByText('70%')).toBeInTheDocument()
  })

  it('shows the financial dashboard: budget vs actual and cost per beneficiary', () => {
    render(<ProgrammesTab data={makeExecutivePayload()} />)

    expect(screen.getByRole('heading', { name: 'Financials' })).toBeInTheDocument()
    expect(screen.getAllByText('Allocated').length).toBeGreaterThan(0) // figure label + table header
    expect(screen.getAllByText('Disbursed').length).toBeGreaterThan(0)
    expect(screen.getAllByText('Cost / beneficiary').length).toBeGreaterThan(0)
    expect(screen.getByText('73%')).toBeInTheDocument() // overall utilisation ribbon
  })

  it('renders the configurable traffic-light scoring legend and per-programme scores', () => {
    render(<ProgrammesTab data={makeExecutivePayload()} />)

    // Legend reflects the configured thresholds.
    expect(screen.getByText(/On track ≥ 80%/i)).toBeInTheDocument()
    expect(screen.getByText(/Lagging ≥ 50%/i)).toBeInTheDocument()

    // Scores render as labelled dots (never color alone): green/red/unrated present.
    expect(screen.getAllByText('On track').length).toBeGreaterThan(0) // cash_transfer (green)
    expect(screen.getAllByText('Off track').length).toBeGreaterThan(0) // school_feeding (red)
    expect(screen.getAllByText('Unrated').length).toBeGreaterThan(0) // conditional_grants
  })

  it('drills down to activity level when permitted', async () => {
    render(<ProgrammesTab data={makeExecutivePayload()} />)

    // Activities are hidden until the card is expanded.
    expect(screen.queryByText('Cash round Q1')).toBeNull()

    const toggle = screen.getByRole('button', { name: /View 2 activities/i })
    await userEvent.click(toggle)

    expect(screen.getByText('Cash round Q1')).toBeInTheDocument()
    expect(screen.getByText('Cash round Q2')).toBeInTheDocument()
    expect(toggle).toHaveAttribute('aria-expanded', 'true')
  })

  it('hides the activity drill-down when the viewer lacks activity.view', () => {
    perms.keys = new Set(['dashboard.view']) // no activity.view
    render(<ProgrammesTab data={makeExecutivePayload()} />)

    expect(screen.queryByRole('button', { name: /activit/i })).toBeNull()
  })

  it('is read-only — no mutating controls', () => {
    render(<ProgrammesTab data={makeExecutivePayload()} />)

    expect(screen.queryByRole('textbox')).toBeNull()
    expect(screen.queryByRole('button', { name: /edit|create|save|delete|add|new|remove|update/i })).toBeNull()
  })
})
