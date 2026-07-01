/**
 * API envelope types mirroring the backend contract (CONVENTIONS.md §4).
 * JSON keys are snake_case.
 *   success: { "data": ..., "meta"?: ... }
 *   error:   { "error": { "code", "message", "details": [{ "field", "message" }] } }
 */

export interface ApiSuccess<T> {
  data: T
  meta?: {
    pagination?: {
      page: number
      per_page: number
      total: number
      total_pages: number
    }
  }
}

export interface ApiFieldError {
  field: string
  message: string
}

export interface ApiErrorBody {
  error: {
    code: string
    message: string
    details: ApiFieldError[]
  }
}

/** Normalised client-side error thrown by the API client. */
export class ApiError extends Error {
  readonly code: string
  readonly status: number
  readonly details: ApiFieldError[]

  constructor(status: number, code: string, message: string, details: ApiFieldError[] = []) {
    super(message)
    this.name = 'ApiError'
    this.status = status
    this.code = code
    this.details = details
  }

  /** Map field errors to a { field: message } record for form binding. */
  fieldErrors(): Record<string, string> {
    return this.details.reduce<Record<string, string>>((acc, detail) => {
      acc[detail.field] = detail.message
      return acc
    }, {})
  }
}
