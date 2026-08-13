import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { GraduationCriteriaCard } from './GraduationCriteriaCard'
import { graduationApi } from './api'
import type { GraduationCriteria } from './types'

vi.mock('./api', () => ({
  graduationApi: {
    criteriaForProgramme: vi.fn(),
    createCriteria: vi.fn(),
    updateCriteria: vi.fn(),
    removeCriteria: vi.fn(),
    progress: vi.fn(),
    graduate: vi.fn(),
    history: vi.fn(),
  },
}))

const perms = { value: ['graduation.view', 'graduation.edit'] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: (p: string) => perms.value.includes(p), user: { mda: { id: 'm1' } } }),
}))

const listCriteria = graduationApi.criteriaForProgramme as Mock
const createCriteria = graduationApi.createCriteria as Mock
const updateCriteria = graduationApi.updateCriteria as Mock

/** An MDA's active criteria set: 3 benefits AND 6 months enrolled. */
const ACTIVE: GraduationCriteria = {
  id: 'crit-1',
  programme_id: 'p1',
  owner_mda_id: 'm1',
  name: 'Cash transfer exit',
  logic: 'all',
  is_active: true,
  rules: [
    { type: 'benefits_received', label: 'Benefits received', threshold: 3, automatic: true },
    { type: 'months_enrolled', label: 'Months enrolled', threshold: 6, automatic: true },
  ],
  created_at: null,
  updated_at: null,
}

function renderCard() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <GraduationCriteriaCard programmeId="p1" />
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('Graduation criteria configuration', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms.value = ['graduation.view', 'graduation.edit']
    listCriteria.mockResolvedValue({ criteria: [ACTIVE] })
    createCriteria.mockResolvedValue(ACTIVE)
    updateCriteria.mockResolvedValue(ACTIVE)
  })

  /* -------------------------------------------------------------- reading it */

  it('shows the active criteria set as configuration, not hard-coded rules', async () => {
    renderCard()

    expect(await screen.findByText('Cash transfer exit')).toBeInTheDocument()
    // The thresholds come from the server's config, so they must be rendered from it.
    expect(screen.getByText(/Benefits received: 3/)).toBeInTheDocument()
    expect(screen.getByText(/Months enrolled: 6 months/)).toBeInTheDocument()
    expect(screen.getByText(/ALL of these are met/)).toBeInTheDocument()
  })

  it('says plainly when a programme has no criteria yet', async () => {
    listCriteria.mockResolvedValue({ criteria: [] })
    renderCard()

    expect(await screen.findByText(/No graduation criteria defined/)).toBeInTheDocument()
    expect(screen.getByRole('button', { name: /define criteria/i })).toBeInTheDocument()
  })

  it('refuses the card without graduation.view and fetches nothing', async () => {
    perms.value = []
    renderCard()

    expect(await screen.findByText(/do not have permission to view graduation criteria/i)).toBeInTheDocument()
    expect(listCriteria).not.toHaveBeenCalled()
  })

  it('is read-only without graduation.edit', async () => {
    perms.value = ['graduation.view']
    renderCard()

    await screen.findByText('Cash transfer exit')
    expect(screen.queryByRole('button', { name: /configure/i })).not.toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /define criteria/i })).not.toBeInTheDocument()
  })

  /* ------------------------------------------------------------- editing it */

  it('seeds the editor with the EXISTING criteria when configuring', async () => {
    const user = userEvent.setup()
    renderCard()
    await screen.findByText('Cash transfer exit')

    await user.click(screen.getByRole('button', { name: /configure/i }))
    const dialog = await screen.findByRole('dialog')

    // Opening "Configure" on a defined set must show THAT set. If the editor seeded
    // itself once on mount it would show the blank defaults here, and saving would
    // silently replace the MDA's real criteria with them.
    expect(within(dialog).getByLabelText(/criteria name/i)).toHaveValue('Cash transfer exit')
    const thresholds = within(dialog).getAllByLabelText(/count|months|threshold/i)
    expect(thresholds[0]).toHaveValue(3)
    expect(thresholds[1]).toHaveValue(6)
  })

  it('saves an edited threshold against the existing set', async () => {
    const user = userEvent.setup()
    renderCard()
    await screen.findByText('Cash transfer exit')

    await user.click(screen.getByRole('button', { name: /configure/i }))
    const dialog = await screen.findByRole('dialog')
    const count = within(dialog).getAllByLabelText(/count|months|threshold/i)[0]!
    await user.clear(count)
    await user.type(count, '5')
    await user.click(within(dialog).getByRole('button', { name: /save criteria/i }))

    await waitFor(() =>
      expect(updateCriteria).toHaveBeenCalledWith(
        'crit-1',
        expect.objectContaining({
          name: 'Cash transfer exit',
          logic: 'all',
          rules: expect.arrayContaining([{ type: 'benefits_received', threshold: 5 }]),
        }),
      ),
    )
  })

  it('defines a new set when none exists', async () => {
    listCriteria.mockResolvedValue({ criteria: [] })
    const user = userEvent.setup()
    renderCard()
    await screen.findByText(/No graduation criteria defined/)

    await user.click(screen.getByRole('button', { name: /define criteria/i }))
    const dialog = await screen.findByRole('dialog')
    await user.type(within(dialog).getAllByLabelText(/count|months|threshold/i)[0]!, '4')
    await user.click(within(dialog).getByRole('button', { name: /save criteria/i }))

    await waitFor(() =>
      expect(createCriteria).toHaveBeenCalledWith('p1', expect.objectContaining({
        is_active: true,
        rules: [{ type: 'benefits_received', threshold: 4 }],
      })),
    )
  })

  it('lets the officer choose ANY instead of ALL', async () => {
    const user = userEvent.setup()
    renderCard()
    await screen.findByText('Cash transfer exit')

    await user.click(screen.getByRole('button', { name: /configure/i }))
    const dialog = await screen.findByRole('dialog')
    await user.selectOptions(within(dialog).getByLabelText(/graduates when/i), 'any')
    await user.click(within(dialog).getByRole('button', { name: /save criteria/i }))

    await waitFor(() =>
      expect(updateCriteria).toHaveBeenCalledWith('crit-1', expect.objectContaining({ logic: 'any' })),
    )
  })

  it('adds and removes rules, but never leaves the set empty', async () => {
    const user = userEvent.setup()
    renderCard()
    await screen.findByText('Cash transfer exit')

    await user.click(screen.getByRole('button', { name: /configure/i }))
    const dialog = await screen.findByRole('dialog')

    const countRows = () => within(dialog).getAllByLabelText(/count|months|threshold/i).length
    const before = countRows()

    await user.click(within(dialog).getByRole('button', { name: /add (a )?(rule|criterion)/i }))
    expect(countRows()).toBe(before + 1)

    // Remove down to one and confirm the last one cannot be removed — a criteria set
    // with no rules would silently graduate everybody.
    const remove = () => within(dialog).getAllByRole('button', { name: /remove/i })
    while (remove().length > 1) {
      await user.click(remove()[0]!)
    }
    expect(countRows()).toBe(1)
    await user.click(remove()[0]!)
    expect(countRows()).toBe(1)
  })

  it('will not save an unnamed criteria set', async () => {
    const user = userEvent.setup()
    renderCard()
    await screen.findByText('Cash transfer exit')

    await user.click(screen.getByRole('button', { name: /configure/i }))
    const dialog = await screen.findByRole('dialog')
    await user.clear(within(dialog).getByLabelText(/criteria name/i))

    expect(within(dialog).getByRole('button', { name: /save criteria/i })).toBeDisabled()
  })
})
