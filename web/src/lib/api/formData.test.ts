import { describe, expect, it } from 'vitest'
import { appendFormObject, appendFormValue } from './formData'

/** FormData entries as "key=value" pairs, for readable assertions. */
function entries(form: FormData): string[] {
  return [...form.entries()].map(([key, value]) => `${key}=${String(value)}`)
}

describe('appendFormValue', () => {
  it('expands a nested location set into bracket notation', () => {
    // The regression: a plain append sends "[object Object]" and the server 422s on a
    // field the form cannot point at.
    const form = new FormData()
    appendFormValue(form, 'locations', [
      { lga_id: 'lga-1', ward_ids: ['w-1', 'w-2'] },
      { lga_id: 'lga-2', whole_lga: true },
    ])

    expect(entries(form)).toEqual([
      'locations[0][lga_id]=lga-1',
      'locations[0][ward_ids][0]=w-1',
      'locations[0][ward_ids][1]=w-2',
      'locations[1][lga_id]=lga-2',
      'locations[1][whole_lga]=1',
    ])
    expect(entries(form).join()).not.toContain('[object Object]')
  })

  it('sends booleans as 1/0, not "true"/"false"', () => {
    // Laravel's `boolean` rule accepts "1"/"0" but not "true"/"false".
    const form = new FormData()
    appendFormValue(form, 'yes', true)
    appendFormValue(form, 'no', false)

    expect(entries(form)).toEqual(['yes=1', 'no=0'])
  })

  it('omits null, undefined and empty string', () => {
    // An absent optional field must not arrive as the string "null".
    const form = new FormData()
    appendFormObject(form, { a: null, b: undefined, c: '', d: 'kept', e: 0 })

    expect(entries(form)).toEqual(['d=kept', 'e=0'])
  })

  it('keeps a File as a File rather than stringifying it', () => {
    const form = new FormData()
    const file = new File(['x'], 'people.csv', { type: 'text/csv' })
    appendFormValue(form, 'file', file)

    expect(form.get('file')).toBeInstanceOf(File)
  })

  it('passes scalars through unchanged', () => {
    const form = new FormData()
    appendFormObject(form, { name: 'Q1 Round', target_beneficiaries: 250 })

    expect(entries(form)).toEqual(['name=Q1 Round', 'target_beneficiaries=250'])
  })
})
