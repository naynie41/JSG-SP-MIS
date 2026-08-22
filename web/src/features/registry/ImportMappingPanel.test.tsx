import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { ImportMappingPanel } from './ImportMappingPanel'
import { importApi } from './api'
import type { ImportMappingProposal } from './types'

/**
 * The column-mapping step (CLAUDE.md §11).
 *
 * The guard is the point of this screen: NIN, BVN, name and phone must be answered on
 * every import, and a saved template must not answer them on the officer's behalf. A
 * wrong identity mapping does not fail loudly — it merges two different citizens — so
 * the tests below care most about what the UI REFUSES to do.
 */

vi.mock('./api', () => ({
  importApi: { mapping: vi.fn(), confirmMapping: vi.fn() },
}))

const mapping = importApi.mapping as Mock
const confirmMapping = importApi.confirmMapping as Mock

function makeProposal(overrides: Partial<ImportMappingProposal> = {}): ImportMappingProposal {
  return {
    detected_headers: ['surname', 'given_name', 'national_id', 'mobile', 'date_of_birth', 'sex', 'lga', 'ward'],
    suggestions: {
      first_name: { header: 'given_name', confidence: 'high', reason: 'Header matches “given_name”.' },
      last_name: { header: 'surname', confidence: 'high', reason: 'Header matches “surname”.' },
      nin: { header: 'national_id', confidence: 'low', reason: '“national_id” often means something else.' },
      bvn: { header: null, confidence: 'none', reason: 'No column resembled this field.' },
      phone: { header: 'mobile', confidence: 'high', reason: 'Header matches “mobile”.' },
      date_of_birth: { header: 'date_of_birth', confidence: 'high', reason: 'Header matches “date_of_birth”.' },
      lga: { header: 'lga', confidence: 'high', reason: 'Header matches “lga”.' },
    },
    column_map: {},
    samples: {
      national_id: ['22200000011', '22200000012'],
      mobile: ['0803 123 4567'],
      surname: ['Okoye'],
    },
    normalized_preview: [],
    template: null,
    prefilled_from: null,
    source: 'csv',
    requires_source_record_id: false,
    identity_fields: ['first_name', 'last_name', 'nin', 'bvn', 'phone'],
    unconfirmed_identity_fields: ['first_name', 'last_name', 'nin', 'bvn', 'phone'],
    unknown_headers: [],
    mapping_confirmed_at: null,
    ...overrides,
  }
}

function renderPanel() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <ImportMappingPanel batchId="ib-1" />
      </ToastProvider>
    </QueryClientProvider>,
  )
}

const continueButton = () => screen.getByRole('button', { name: /confirm mapping/i })

/** Answer every identity field, as an officer would. */
async function answerIdentityFields(user: ReturnType<typeof userEvent.setup>) {
  await user.selectOptions(screen.getByLabelText(/^first name/i), 'given_name')
  await user.selectOptions(screen.getByLabelText(/^last name/i), 'surname')
  await user.selectOptions(screen.getByLabelText(/^nin/i), 'national_id')
  await user.selectOptions(screen.getByLabelText(/^bvn/i), '__absent__')
  await user.selectOptions(screen.getByLabelText(/^phone/i), 'mobile')
}

describe('ImportMappingPanel', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    mapping.mockResolvedValue(makeProposal())
    confirmMapping.mockResolvedValue({ id: 'ib-1', status: 'preview_ready' })
  })

  /* ------------------------------------------------------- suggestions are advice */

  it('shows the suggestion and its confidence without selecting it for an identity field', async () => {
    renderPanel()

    // Suggested, and visibly uncertain — "national_id" is used for several identifiers.
    expect(await screen.findByText(/Uncertain: national_id/)).toBeInTheDocument()
    // But NOT pre-selected: a machine guess must not satisfy the confirmation.
    expect(screen.getByLabelText(/^nin/i)).toHaveValue('')
  })

  it('pre-selects a confident suggestion for a NON-identity field', async () => {
    renderPanel()

    // Nothing irreversible rides on these, so accepting the suggestion saves work.
    await waitFor(() => expect(screen.getByLabelText(/date of birth/i)).toHaveValue('date_of_birth'))
  })

  it('shows real values from the file so the confirmation can be evaluated', async () => {
    renderPanel()
    await user_waitForLoad()

    // Whether `national_id` holds NINs is guesswork from the header and obvious from
    // the values — without these the guard is a click-through.
    expect(screen.getByText(/22200000011, 22200000012/)).toBeInTheDocument()
  })

  /* --------------------------------------------------------------- the guard */

  it('cannot continue until every identity field is answered', async () => {
    renderPanel()
    await user_waitForLoad()

    expect(continueButton()).toBeDisabled()
    expect(screen.getByText(/confirm .*NIN.*to continue/i)).toBeInTheDocument()
  })

  it('flags each unanswered identity field and clears the flag once answered', async () => {
    const user = userEvent.setup()
    renderPanel()
    await user_waitForLoad()

    expect(screen.getAllByText(/confirmation required/i).length).toBe(5)

    await user.selectOptions(screen.getByLabelText(/^nin/i), 'national_id')

    expect(screen.getAllByText(/confirmation required/i).length).toBe(4)
    expect(screen.getAllByText(/^confirmed$/i).length).toBe(1)
  })

  it('enables continue once all five are answered, and sends the map', async () => {
    const user = userEvent.setup()
    renderPanel()
    await user_waitForLoad()

    await answerIdentityFields(user)
    expect(continueButton()).toBeEnabled()

    await user.click(continueButton())

    await waitFor(() =>
      expect(confirmMapping).toHaveBeenCalledWith(
        'ib-1',
        expect.objectContaining({
          first_name: 'given_name',
          last_name: 'surname',
          nin: 'national_id',
          bvn: null, // "not present" is an ANSWER, sent explicitly as null
          phone: 'mobile',
        }),
        undefined,
      ),
    )
  })

  it('treats "not present" as an answer, not as a blank', async () => {
    const user = userEvent.setup()
    renderPanel()
    await user_waitForLoad()

    await user.selectOptions(screen.getByLabelText(/^bvn/i), '__absent__')

    // The file genuinely has no BVN column; saying so is answering.
    const bvnRow = screen.getByLabelText(/^bvn/i).closest('div')!.parentElement!
    expect(within(bvnRow).getByText(/^confirmed$/i)).toBeInTheDocument()
  })

  /* ------------------------------------------------------- SOCU provenance */

  /**
   * A SOCU-mined batch must carry each row's SOCU id.
   *
   * The batch flag says these people came from SOCU; `original_record_id` says WHICH
   * SOCU record each one is. Without it nobody can trace a beneficiary back to the
   * register, and a re-mine has no key to recognise itself by.
   */
  it('will not continue a SOCU import until the SOCU record id is mapped', async () => {
    const user = userEvent.setup()
    mapping.mockResolvedValue(
      makeProposal({
        source: 'socu',
        requires_source_record_id: true,
        detected_headers: ['socu_id', 'surname', 'given_name', 'national_id', 'mobile'],
        suggestions: {
          ...makeProposal().suggestions,
          original_record_id: { header: 'socu_id', confidence: 'high', reason: 'Header matches “socu_id”.' },
        },
      }),
    )
    renderPanel()
    await user_waitForLoad()

    await answerIdentityFields(user)

    // Identity fields all answered, and it is STILL blocked — on the SOCU id.
    expect(continueButton()).toBeDisabled()
    expect(screen.getByText(/Confirm Source record ID to continue/i)).toBeInTheDocument()

    await user.selectOptions(screen.getByLabelText(/source record id/i), 'socu_id')

    expect(continueButton()).toBeEnabled()
  })

  it('sends the SOCU id mapping with the rest', async () => {
    const user = userEvent.setup()
    mapping.mockResolvedValue(
      makeProposal({
        source: 'socu',
        requires_source_record_id: true,
        detected_headers: ['socu_id', 'surname', 'given_name', 'national_id', 'mobile'],
        suggestions: {
          ...makeProposal().suggestions,
          original_record_id: { header: 'socu_id', confidence: 'high', reason: 'Header matches “socu_id”.' },
        },
      }),
    )
    renderPanel()
    await user_waitForLoad()

    await answerIdentityFields(user)
    await user.selectOptions(screen.getByLabelText(/source record id/i), 'socu_id')
    await user.click(continueButton())

    await waitFor(() =>
      expect(confirmMapping).toHaveBeenCalledWith(
        'ib-1',
        expect.objectContaining({ original_record_id: 'socu_id' }),
        undefined,
      ),
    )
  })

  it('does not demand a source record id for the MDA’s own file', async () => {
    // A self-collected file has no external register to point at; demanding an id
    // would make officers invent them.
    const user = userEvent.setup()
    renderPanel()
    await user_waitForLoad()

    await answerIdentityFields(user)

    expect(continueButton()).toBeEnabled()
  })

  /* -------------------------------------------------------------- templates */

  it('says when a saved mapping pre-filled the form, and still requires confirmation', async () => {
    mapping.mockResolvedValue(
      makeProposal({
        template: { id: 't-1', name: 'Health monthly returns' },
        prefilled_from: { type: 'template', name: 'Health monthly returns' },
        column_map: { first_name: 'given_name', last_name: 'surname', date_of_birth: 'date_of_birth' },
        unconfirmed_identity_fields: ['nin', 'bvn', 'phone'],
      }),
    )
    renderPanel()

    expect(await screen.findByText(/saved mapping “Health monthly returns”/)).toBeInTheDocument()
    expect(screen.getByText(/pre-filled but never pre-confirmed/i)).toBeInTheDocument()

    // Pre-filled fields ARE applied…
    expect(screen.getByLabelText(/^first name/i)).toHaveValue('given_name')
    // …but the identity fields the template did not answer still block the step.
    expect(continueButton()).toBeDisabled()
  })

  it('says when a familiar layout was recognised from an earlier import', async () => {
    // The ordinary case: the same export uploaded again, with no template ever saved.
    // Naming the earlier file and who confirmed it is what makes this a review rather
    // than a formality — otherwise the mapping simply appears, from nowhere.
    mapping.mockResolvedValue(
      makeProposal({
        template: null,
        prefilled_from: {
          type: 'previous_import',
          name: 'MoH CMPND Beneficiaries.xlsx',
          confirmed_by: 'Health MDA Admin',
          confirmed_at: '2026-08-17T09:12:00Z',
        },
        column_map: { first_name: 'given_name', last_name: 'surname' },
        unconfirmed_identity_fields: ['nin', 'bvn', 'phone'],
      }),
    )
    renderPanel()

    expect(await screen.findByText(/same columns as “MoH CMPND Beneficiaries.xlsx”/)).toBeInTheDocument()
    expect(screen.getByText(/confirmed by Health MDA Admin on 2026-08-17/)).toBeInTheDocument()

    // Recognised, pre-filled — and still blocked until the identity fields are answered.
    expect(screen.getByLabelText(/^first name/i)).toHaveValue('given_name')
    expect(continueButton()).toBeDisabled()
  })

  it('omits the attribution when the earlier import has none', async () => {
    mapping.mockResolvedValue(
      makeProposal({
        prefilled_from: { type: 'previous_import', name: 'january.csv', confirmed_by: null, confirmed_at: null },
      }),
    )
    renderPanel()

    // No dangling empty parentheses when there is nobody to credit.
    const note = await screen.findByText(/same columns as “january.csv”/)
    expect(note).toHaveTextContent(/pre-filled\./)
    expect(note.textContent).not.toMatch(/\(\s*\)/)
  })

  it('shows no pre-fill notice for a file shape seen for the first time', async () => {
    mapping.mockResolvedValue(makeProposal({ template: null, prefilled_from: null }))
    renderPanel()

    await user_waitForLoad()
    expect(screen.queryByText(/pre-filled/i)).not.toBeInTheDocument()
    expect(screen.queryByText(/same columns as/i)).not.toBeInTheDocument()
  })

  it('warns when the saved mapping names a column this file does not have', async () => {
    mapping.mockResolvedValue(makeProposal({ unknown_headers: ['old_nin_column'] }))
    renderPanel()

    // A changed export silently mapping a field to nothing is indistinguishable from a
    // source that omitted it — so it is called out.
    expect(await screen.findByText(/old_nin_column/)).toBeInTheDocument()
    expect(screen.getByText(/not in this file/i)).toBeInTheDocument()
  })

  it('passes a template name through when one is given', async () => {
    const user = userEvent.setup()
    renderPanel()
    await user_waitForLoad()

    await answerIdentityFields(user)
    await user.type(screen.getByLabelText(/save this mapping/i), 'Health monthly returns')
    await user.click(continueButton())

    await waitFor(() =>
      expect(confirmMapping).toHaveBeenCalledWith('ib-1', expect.anything(), 'Health monthly returns'),
    )
  })

  /* ---------------------------------------------------- normalize/validate preview */

  it('shows the original value beside the value duplicate checking will compare', async () => {
    mapping.mockResolvedValue(
      makeProposal({
        normalized_preview: [
          { field: 'phone', header: 'mobile', original: '0803 123 4567', normalized: '08031234567' },
          { field: 'date_of_birth', header: 'date_of_birth', original: '12/03/1995', normalized: '1995-03-12' },
        ],
      }),
    )
    renderPanel()

    const table = await screen.findByRole('table')
    // This is where a wrong mapping shows itself — a date read as the wrong month, or a
    // "NIN" that does not reduce to digits.
    expect(within(table).getByText('0803 123 4567')).toBeInTheDocument()
    expect(within(table).getByText('08031234567')).toBeInTheDocument()
    expect(within(table).getByText('12/03/1995')).toBeInTheDocument()
    expect(within(table).getByText('1995-03-12')).toBeInTheDocument()
  })

  it('explains the identity-reject rule alongside the preview', async () => {
    mapping.mockResolvedValue(
      makeProposal({
        normalized_preview: [{ field: 'nin', header: 'national_id', original: '222 000 000 11', normalized: '22200000011' }],
      }),
    )
    renderPanel()

    // FR-REG-05, stated where it becomes relevant rather than in a manual.
    expect(await screen.findByText(/rejected whole and never saved/i)).toBeInTheDocument()
  })

  it('marks a value that will not normalize', async () => {
    mapping.mockResolvedValue(
      makeProposal({
        normalized_preview: [{ field: 'nin', header: 'national_id', original: 'A/1234', normalized: null }],
      }),
    )
    renderPanel()

    expect(await screen.findByText(/not usable/i)).toBeInTheDocument()
  })

  /* --------------------------------------------------------------- failures */

  it('surfaces a rejected confirmation instead of appearing to succeed', async () => {
    confirmMapping.mockRejectedValue(new Error('boom'))
    const user = userEvent.setup()
    renderPanel()
    await user_waitForLoad()

    await answerIdentityFields(user)
    await user.click(continueButton())

    expect(await screen.findByRole('alert')).toHaveTextContent(/could not confirm/i)
  })
})

/** The panel renders a loading card first; anchor on the identity heading. */
async function user_waitForLoad() {
  await screen.findByRole('heading', { name: /identity fields/i })
}
