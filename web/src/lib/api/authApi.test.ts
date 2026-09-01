import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { authApi } from './authApi'
import { apiRequest } from './client'

vi.mock('./client', () => ({ apiRequest: vi.fn() }))

const request = apiRequest as Mock

/*
 * Guards the SPA -> API request CONTRACT.
 *
 * These exist because a real bug shipped through both suites untouched: the API's
 * PasswordRules carries Laravel's `confirmed` rule, so /auth/password requires
 * `password_confirmation`, and the SPA never sent it. Every password change failed
 * in production with a bare "The request is invalid."
 *
 * Neither suite could catch it. The component tests mock authApi, so they never see
 * the payload; the API tests hand-write their own payload, so they never see what
 * the client actually sends. Nothing compared the two. That is what this file is
 * for — asserting the SHAPE the server requires, not just that a call happened.
 */
describe('authApi request contract', () => {
  beforeEach(() => {
    request.mockReset()
    request.mockResolvedValue({})
  })

  it('sends password_confirmation on a password change', async () => {
    await authApi.changePassword('OldPass123!x', 'NewPass456!y')

    const [config] = request.mock.calls[0]

    expect(config.url).toBe('/auth/password')
    expect(config.method).toBe('POST')
    expect(config.data).toEqual({
      current_password: 'OldPass123!x',
      password: 'NewPass456!y',
      // Without this the server's `confirmed` rule rejects the whole request.
      password_confirmation: 'NewPass456!y',
    })
  })

  it('confirms with the NEW password, never the current one', async () => {
    await authApi.changePassword('OldPass123!x', 'NewPass456!y')

    const [config] = request.mock.calls[0]

    // Confirming with the wrong value would fail `confirmed` just as surely as
    // omitting the field, and would be far harder to spot.
    expect(config.data.password_confirmation).toBe(config.data.password)
    expect(config.data.password_confirmation).not.toBe(config.data.current_password)
  })
})
