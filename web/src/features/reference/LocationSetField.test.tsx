import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { useState } from 'react'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { LocationSetField } from './LocationSetField'
import { referenceApi } from './api'
import type { LocationSetEntry } from './types'

vi.mock('./api', () => ({
  referenceApi: { lgas: vi.fn(), wards: vi.fn() },
}))

const lgas = referenceApi.lgas as Mock
const wards = referenceApi.wards as Mock

const LGAS = {
  lgas: [
    { id: 'lga-dutse', code: 'dutse', name: 'Dutse', state: 'Jigawa', ward_count: 2 },
    { id: 'lga-kiyawa', code: 'kiyawa', name: 'Kiyawa', state: 'Jigawa', ward_count: 2 },
  ],
}

// Deliberately overlapping ward NAMES across the two LGAs — that is the real Jigawa
// situation, and the reason a ward selector must be scoped rather than filtered.
const WARDS: Record<string, { wards: Array<{ id: string; lga_id: string; code: string; name: string }> }> = {
  'lga-dutse': {
    wards: [
      { id: 'w-dutse-limawa', lga_id: 'lga-dutse', code: 'limawa', name: 'Limawa' },
      { id: 'w-dutse-sabon', lga_id: 'lga-dutse', code: 'sabon_gari', name: 'Sabon Gari' },
    ],
  },
  'lga-kiyawa': {
    wards: [
      { id: 'w-kiyawa-kwanda', lga_id: 'lga-kiyawa', code: 'kwanda', name: 'Kwanda' },
      { id: 'w-kiyawa-sabon', lga_id: 'lga-kiyawa', code: 'sabon_gari', name: 'Sabon Gari' },
    ],
  },
}

/** Wraps the controlled field in local state, the way the activity form uses it. */
function Harness({ initial = [], errors }: { initial?: LocationSetEntry[]; errors?: Record<string, string> }) {
  const [value, setValue] = useState<LocationSetEntry[]>(initial)
  return (
    <>
      <LocationSetField value={value} onChange={setValue} errors={errors} />
      <pre data-testid="state">{JSON.stringify(value)}</pre>
    </>
  )
}

function renderField(ui: React.ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(<QueryClientProvider client={qc}>{ui}</QueryClientProvider>)
}

function state(): LocationSetEntry[] {
  return JSON.parse(screen.getByTestId('state').textContent || '[]')
}

async function addLga(user: ReturnType<typeof userEvent.setup>, id: string) {
  const name = LGAS.lgas.find((lga) => lga.id === id)!.name
  const add = await screen.findByLabelText('Add an LGA')
  // Wait for the option being selected — an already-added LGA is removed from the list,
  // so waiting for a fixed name would pass only on the first call.
  await waitFor(() => expect(within(add).getByRole('option', { name })).toBeInTheDocument())
  await user.selectOptions(add, id)
}

describe('LocationSetField', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    lgas.mockResolvedValue(LGAS)
    wards.mockImplementation((lgaId: string) => Promise.resolve(WARDS[lgaId] ?? { wards: [] }))
  })

  it('adds several LGAs, each as its own titled block', async () => {
    const user = userEvent.setup()
    renderField(<Harness />)

    await addLga(user, 'lga-dutse')
    await addLga(user, 'lga-kiyawa')

    expect(await screen.findByRole('region', { name: 'Dutse' })).toBeInTheDocument()
    expect(screen.getByRole('region', { name: 'Kiyawa' })).toBeInTheDocument()
    expect(state()).toHaveLength(2)
  })

  it('scopes each ward selector to its own LGA', async () => {
    const user = userEvent.setup()
    renderField(<Harness />)

    await addLga(user, 'lga-dutse')
    await addLga(user, 'lga-kiyawa')

    const dutse = await screen.findByRole('region', { name: 'Dutse' })
    const kiyawa = screen.getByRole('region', { name: 'Kiyawa' })

    // Each block offers ONLY its own wards, fetched with its own lga_id.
    await waitFor(() => expect(within(dutse).getByLabelText('Limawa')).toBeInTheDocument())
    expect(within(dutse).queryByLabelText('Kwanda')).not.toBeInTheDocument()

    await waitFor(() => expect(within(kiyawa).getByLabelText('Kwanda')).toBeInTheDocument())
    expect(within(kiyawa).queryByLabelText('Limawa')).not.toBeInTheDocument()

    expect(wards).toHaveBeenCalledWith('lga-dutse')
    expect(wards).toHaveBeenCalledWith('lga-kiyawa')
  })

  it('keeps identically-named wards in different LGAs distinct', async () => {
    // "Sabon Gari" exists in both. Ticking it in Kiyawa must record KIYAWA's ward id —
    // the failure this guards would silently file the activity in the wrong LGA.
    const user = userEvent.setup()
    renderField(<Harness />)

    await addLga(user, 'lga-dutse')
    await addLga(user, 'lga-kiyawa')

    const kiyawa = await screen.findByRole('region', { name: 'Kiyawa' })
    await waitFor(() => expect(within(kiyawa).getByLabelText('Sabon Gari')).toBeInTheDocument())
    await user.click(within(kiyawa).getByLabelText('Sabon Gari'))

    const kiyawaEntry = state().find((entry) => entry.lga_id === 'lga-kiyawa')
    expect(kiyawaEntry?.ward_ids).toEqual(['w-kiyawa-sabon'])
    expect(state().find((entry) => entry.lga_id === 'lga-dutse')?.ward_ids).toEqual([])
  })

  it('selects several wards within one LGA', async () => {
    const user = userEvent.setup()
    renderField(<Harness />)

    await addLga(user, 'lga-dutse')
    const dutse = await screen.findByRole('region', { name: 'Dutse' })

    await waitFor(() => expect(within(dutse).getByLabelText('Limawa')).toBeInTheDocument())
    await user.click(within(dutse).getByLabelText('Limawa'))
    await user.click(within(dutse).getByLabelText('Sabon Gari'))

    expect(state()[0].ward_ids).toEqual(['w-dutse-limawa', 'w-dutse-sabon'])
  })

  it('offers a whole-LGA option that clears and hides the ward picks', async () => {
    const user = userEvent.setup()
    renderField(<Harness />)

    await addLga(user, 'lga-dutse')
    const dutse = await screen.findByRole('region', { name: 'Dutse' })

    await waitFor(() => expect(within(dutse).getByLabelText('Limawa')).toBeInTheDocument())
    await user.click(within(dutse).getByLabelText('Limawa'))
    await user.click(within(dutse).getByLabelText('Whole LGA (all wards)'))

    expect(state()[0]).toMatchObject({ whole_lga: true, ward_ids: [] })
    // "Everywhere in Dutse" and "only Limawa" are different claims — the API rejects
    // both together, so the form cannot offer both at once.
    expect(within(dutse).queryByLabelText('Limawa')).not.toBeInTheDocument()
  })

  it('removes an LGA and its wards together', async () => {
    const user = userEvent.setup()
    renderField(<Harness />)

    await addLga(user, 'lga-dutse')
    await addLga(user, 'lga-kiyawa')

    const dutse = await screen.findByRole('region', { name: 'Dutse' })
    await waitFor(() => expect(within(dutse).getByLabelText('Limawa')).toBeInTheDocument())
    await user.click(within(dutse).getByLabelText('Limawa'))

    await user.click(screen.getByRole('button', { name: 'Remove Dutse' }))

    expect(state()).toHaveLength(1)
    expect(state()[0].lga_id).toBe('lga-kiyawa')
    expect(screen.queryByRole('region', { name: 'Dutse' })).not.toBeInTheDocument()
  })

  it('does not offer an LGA that is already added', async () => {
    const user = userEvent.setup()
    renderField(<Harness />)

    await addLga(user, 'lga-dutse')

    const add = screen.getByLabelText('Add an LGA')
    await waitFor(() => expect(within(add).queryByRole('option', { name: 'Dutse' })).not.toBeInTheDocument())
    expect(within(add).getByRole('option', { name: 'Kiyawa' })).toBeInTheDocument()
  })

  it('marks the exact ward the server rejected', async () => {
    // The server reports positional paths; the user has to see WHICH chip was wrong.
    renderField(
      <Harness
        initial={[{ lga_id: 'lga-dutse', ward_ids: ['w-dutse-limawa', 'w-kiyawa-kwanda'], whole_lga: false }]}
        errors={{ 'locations.0.ward_ids.1': 'That ward does not belong to the selected LGA.' }}
      />,
    )

    expect(await screen.findByText('That ward does not belong to the selected LGA.')).toBeInTheDocument()
  })

  it('tells the user when an LGA has no ward data loaded', async () => {
    wards.mockResolvedValue({ wards: [] })
    const user = userEvent.setup()
    renderField(<Harness />)

    await addLga(user, 'lga-dutse')

    // Reference data is maintainer-supplied and may not include wards yet — the user
    // needs to know that is why the list is empty, not assume the LGA has no wards.
    expect(await screen.findByText(/No ward data loaded for Dutse/)).toBeInTheDocument()
  })

  it('renders an existing set for editing', async () => {
    renderField(
      <Harness initial={[{ lga_id: 'lga-kiyawa', ward_ids: ['w-kiyawa-kwanda'], whole_lga: false }]} />,
    )

    const kiyawa = await screen.findByRole('region', { name: 'Kiyawa' })
    await waitFor(() => expect(within(kiyawa).getByLabelText('Kwanda')).toBeChecked())
    expect(within(kiyawa).getByLabelText('Sabon Gari')).not.toBeChecked()
  })
})
