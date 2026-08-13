import { useState } from 'react'
import { describe, expect, it, vi } from 'vitest'
import { render, screen, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { Modal } from './Modal'

/**
 * The dialog's focus trap must survive the parent re-rendering.
 *
 * Callers pass `onClose` as an inline arrow, so its identity changes on every parent
 * render. When the focus/Esc effect depended on it, typing a single character into a
 * controlled field inside the dialog re-rendered the parent, tore the effect down —
 * running its `previouslyFocused?.focus()` cleanup, which pulls focus OUT of the field
 * being typed into — and set it up again. Only the first keystroke landed.
 *
 * It went unnoticed because the other modal suites set values with `fireEvent.change`
 * (one shot); it only bites on per-character typing, which is what a real user does.
 */
function Host({ onClose = () => {} }: { onClose?: () => void }) {
  const [open, setOpen] = useState(true)
  const [value, setValue] = useState('')

  return (
    <Modal
      open={open}
      // Inline arrow with a fresh identity every render — the realistic caller shape.
      onClose={() => {
        setOpen(false)
        onClose()
      }}
      title="Record something"
      footer={<button type="button" onClick={() => onClose()}>Save</button>}
    >
      <label htmlFor="reason">Reason</label>
      <input id="reason" value={value} onChange={(event) => setValue(event.target.value)} />
    </Modal>
  )
}

describe('Modal', () => {
  it('keeps every keystroke in a controlled field inside the dialog', async () => {
    const user = userEvent.setup()
    render(<Host />)

    const dialog = await screen.findByRole('dialog')
    const field = within(dialog).getByLabelText('Reason')
    await user.type(field, 'Signed enrolment form')

    // Before the fix this was 'S'.
    expect(field).toHaveValue('Signed enrolment form')
  })

  it('still runs a footer action after the parent has re-rendered', async () => {
    const onClose = vi.fn()
    const user = userEvent.setup()
    render(<Host onClose={onClose} />)

    const dialog = await screen.findByRole('dialog')
    await user.type(within(dialog).getByLabelText('Reason'), 'abc')
    await user.click(within(dialog).getByRole('button', { name: 'Save' }))

    // The re-render must not detach the node the user is about to click.
    expect(onClose).toHaveBeenCalledTimes(1)
  })

  it('closes on Escape using the CURRENT handler, not a stale one', async () => {
    const onClose = vi.fn()
    const user = userEvent.setup()
    render(<Host onClose={onClose} />)

    // The handler is read through a ref, so holding the effect steady across renders
    // must not pin an outdated closure.
    await user.type(await within(await screen.findByRole('dialog')).findByLabelText('Reason'), 'x')
    await user.keyboard('{Escape}')

    expect(onClose).toHaveBeenCalledTimes(1)
    expect(screen.queryByRole('dialog')).not.toBeInTheDocument()
  })

  it('renders nothing when closed', () => {
    render(
      <Modal open={false} onClose={() => {}} title="Hidden">
        <p>body</p>
      </Modal>,
    )

    expect(screen.queryByRole('dialog')).not.toBeInTheDocument()
    expect(screen.queryByText('body')).not.toBeInTheDocument()
  })
})
