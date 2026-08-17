/**
 * Appends a value to FormData, handling nested arrays/objects and booleans.
 *
 * `FormData.append` stringifies whatever it is given, so a nested array arrives at the
 * server as the literal "[object Object]" and a boolean as "true" — which Laravel's
 * `boolean` rule rejects (it accepts true/false/1/0/"1"/"0", not "true"). Both failures
 * look identical from the UI: a 422 on a field the form cannot point at.
 *
 * So nested values are expanded into the bracket notation PHP parses back into an array
 * (`locations[0][ward_ids][1]`), and booleans are sent as "1"/"0".
 *
 * `null`, `undefined` and `''` are omitted — an absent optional field must not arrive as
 * the string "null".
 */
export function appendFormValue(form: FormData, key: string, value: unknown): void {
  if (value === null || value === undefined || value === '') return

  if (value instanceof File || value instanceof Blob) {
    form.append(key, value)
    return
  }

  if (typeof value === 'boolean') {
    form.append(key, value ? '1' : '0')
    return
  }

  if (Array.isArray(value)) {
    value.forEach((item, index) => appendFormValue(form, `${key}[${index}]`, item))
    return
  }

  if (typeof value === 'object') {
    for (const [childKey, childValue] of Object.entries(value as Record<string, unknown>)) {
      appendFormValue(form, `${key}[${childKey}]`, childValue)
    }
    return
  }

  form.append(key, String(value))
}

/**
 * Appends every entry of a plain object, nested values included.
 *
 * Takes `object` rather than `Record<string, unknown>` so a typed payload interface (an
 * `ActivityInput`, say) can be passed without casting away its type.
 */
export function appendFormObject(form: FormData, values: object): void {
  for (const [key, value] of Object.entries(values)) {
    appendFormValue(form, key, value)
  }
}
