import type { FieldValues, Path, UseFormSetError } from 'react-hook-form'
import { ApiError } from '@/types/api'

/**
 * Map a backend validation error envelope onto a react-hook-form. Field-level
 * messages bind to their inputs; anything else is returned as a general message
 * for an inline alert / toast.
 */
export function applyApiErrors<T extends FieldValues>(
  error: unknown,
  setError: UseFormSetError<T>,
  knownFields: readonly (keyof T)[],
): string | null {
  if (!(error instanceof ApiError)) {
    return 'Something went wrong. Please try again.'
  }

  const fieldErrors = error.fieldErrors()
  let boundAny = false

  for (const [field, message] of Object.entries(fieldErrors)) {
    if (knownFields.includes(field as keyof T)) {
      setError(field as Path<T>, { message })
      boundAny = true
    }
  }

  // If we bound field errors, no general message is needed.
  if (boundAny) return null
  return error.message
}
