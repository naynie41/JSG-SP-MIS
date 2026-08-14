import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { ConnectorMappingModal } from './ConnectorMappingModal'
import { syncApi } from './api'
import type { ConnectorMappingProposal, SyncConnector } from './types'

/**
 * A sync connector's STANDING mapping confirmation (CLAUDE.md §11).
 *
 * A connector runs unattended, so the identity question is asked once here rather than
 * per sync. That makes this screen carry MORE weight than the file-upload one: a wrong
 * identity mapping merges citizens on every run instead of once. So the tests care most
 * about what it refuses to do — and about the stale case, which is what stops "confirm
 * once" from becoming "confirm never".
 */

vi.mock('./api', () => ({
  syncApi: {
    connectors: vi.fn(), runs: vi.fn(), trigger: vi.fn(),
    mapping: vi.fn(), confirmMapping: vi.fn(), setEnabled: vi.fn(),
  },
}))

const mapping = syncApi.mapping as Mock
const confirmMapping = syncApi.confirmMapping as Mock

const CONNECTOR = {
  id: 'c1',
  name: 'SOCU feed',
  source: 'socu',
  mapping: { status: 'never_configured', confirmed_at: null, stale_at: null, stale_reason: null, can_enable: false },
} as SyncConnector

function makeProposal(overrides: Partial<ConnectorMappingProposal> = {}): ConnectorMappingProposal {
  return {
    detected_fields: ['surname', 'given_name', 'national_id', 'msisdn', 'dob'],
    suggestions: {
      first_name: { header: 'given_name', confidence: 'high', reason: 'Header matches “given_name”.' },
      last_name: { header: 'surname', confidence: 'high', reason: 'Header matches “surname”.' },
      nin: { header: 'national_id', confidence: 'low', reason: '“national_id” often means something else.' },
      bvn: { header: null, confidence: 'none', reason: 'No column resembled this field.' },
      phone: { header: 'msisdn', confidence: 'high', reason: 'Header matches “msisdn”.' },
      date_of_birth: { header: 'dob', confidence: 'high', reason: 'Header matches “dob”.' },
    },
    column_map: {},
    samples: { national_id: ['22200000011'], msisdn: ['08031234567'] },
    normalized_preview: [],
    identity_fields: ['first_name', 'last_name', 'nin', 'bvn', 'phone'],
    unconfirmed_identity_fields: ['first_name', 'last_name', 'nin', 'bvn', 'phone'],
    source_signature: 'sig-1',
    confirmed_signature: null,
    signature_changed: false,
    mapping_confirmed_at: null,
    ...overrides,
  }
}

function renderModal(connector: SyncConnector = CONNECTOR) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <ConnectorMappingModal connector={connector} onClose={vi.fn()} />
      </ToastProvider>
    </QueryClientProvider>,
  )
}

const confirmButton = () => screen.getByRole('button', { name: /confirm mapping/i })

async function answerIdentityFields(user: ReturnType<typeof userEvent.setup>) {
  await user.selectOptions(screen.getByLabelText(/^first name/i), 'given_name')
  await user.selectOptions(screen.getByLabelText(/^last name/i), 'surname')
  await user.selectOptions(screen.getByLabelText(/^nin/i), 'national_id')
  await user.selectOptions(screen.getByLabelText(/^bvn/i), '__absent__')
  await user.selectOptions(screen.getByLabelText(/^phone/i), 'msisdn')
}

const loaded = () => screen.findByRole('heading', { name: /identity fields/i })

describe('ConnectorMappingModal', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    mapping.mockResolvedValue(makeProposal())
    confirmMapping.mockResolvedValue({ message: 'ok' })
  })

  /* --------------------------------------------------------------- the guard */

  it('cannot confirm until every identity field is answered', async () => {
    renderModal()
    await loaded()

    expect(confirmButton()).toBeDisabled()
    expect(screen.getByText(/confirm .*NIN.*to continue/i)).toBeInTheDocument()
  })

  it('does not pre-select a suggestion for an identity field', async () => {
    renderModal()
    await loaded()

    // Shown, and visibly uncertain…
    expect(screen.getByText(/Uncertain: national_id/)).toBeInTheDocument()
    // …but never chosen on the administrator's behalf. A standing approval given by a
    // machine guess would be worse here than on a file: it applies to every future run.
    expect(screen.getByLabelText(/^nin/i)).toHaveValue('')
  })

  it('pre-selects a confident suggestion for a non-identity field', async () => {
    renderModal()
    await waitFor(() => expect(screen.getByLabelText(/date of birth/i)).toHaveValue('dob'))
  })

  it('shows sampled values from the source so the answer can be evaluated', async () => {
    renderModal()
    await loaded()

    expect(screen.getByText(/22200000011/)).toBeInTheDocument()
  })

  it('sends the map once every identity field is answered', async () => {
    const user = userEvent.setup()
    renderModal()
    await loaded()

    await answerIdentityFields(user)
    expect(confirmButton()).toBeEnabled()
    await user.click(confirmButton())

    await waitFor(() =>
      expect(confirmMapping).toHaveBeenCalledWith(
        'c1',
        expect.objectContaining({
          first_name: 'given_name',
          last_name: 'surname',
          nin: 'national_id',
          bvn: null, // "not present" is an ANSWER, sent explicitly
          phone: 'msisdn',
        }),
      ),
    )
  })

  /* ------------------------------------------------------- the stale case */

  it('explains that syncing is on hold when the source schema changed', async () => {
    mapping.mockResolvedValue(
      makeProposal({
        signature_changed: true,
        confirmed_signature: 'sig-old',
        column_map: { first_name: 'given_name', last_name: 'surname' },
        unconfirmed_identity_fields: ['nin', 'bvn', 'phone'],
      }),
    )
    renderModal()

    // The administrator needs to know WHY it stopped and what could now be misread.
    expect(await screen.findByText(/fields have changed/i)).toBeInTheDocument()
    expect(screen.getByText(/on hold/i)).toBeInTheDocument()
  })

  it('still requires the identity fields on a stale connector', async () => {
    mapping.mockResolvedValue(
      makeProposal({
        signature_changed: true,
        column_map: { first_name: 'given_name', last_name: 'surname' },
        unconfirmed_identity_fields: ['nin', 'bvn', 'phone'],
      }),
    )
    renderModal()
    await loaded()

    // A previously-confirmed mapping does not carry its identity answers through a
    // schema change — that is exactly what re-confirmation is for.
    expect(confirmButton()).toBeDisabled()
    expect(screen.getByLabelText(/^first name/i)).toHaveValue('given_name')
  })

  /* ------------------------------------------------------------ standing scope */

  it('says the confirmation stands for later syncs', async () => {
    renderModal()
    await loaded()

    // The one place the per-import rule is relaxed; the screen should say so rather
    // than leaving an administrator to infer it.
    expect(screen.getByText(/confirmed once here and stands/i)).toBeInTheDocument()
  })

  it('shows the original beside the compared value', async () => {
    mapping.mockResolvedValue(
      makeProposal({
        normalized_preview: [
          { field: 'phone', header: 'msisdn', original: '+234 803 123 4567', normalized: '08031234567' },
        ],
      }),
    )
    renderModal()

    const table = await screen.findByRole('table')
    expect(within(table).getByText('+234 803 123 4567')).toBeInTheDocument()
    expect(within(table).getByText('08031234567')).toBeInTheDocument()
  })

  /* ------------------------------------------------------------- failures */

  it('says the mapping cannot be confirmed when the source will not respond', async () => {
    mapping.mockRejectedValue(new Error('unreachable'))
    renderModal()

    expect(await screen.findByRole('alert')).toHaveTextContent(/could not read a sample/i)
  })

  it('surfaces a rejected confirmation', async () => {
    confirmMapping.mockRejectedValue(new Error('boom'))
    const user = userEvent.setup()
    renderModal()
    await loaded()

    await answerIdentityFields(user)
    await user.click(confirmButton())

    expect(await screen.findByRole('alert')).toHaveTextContent(/could not confirm/i)
  })
})
