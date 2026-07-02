import axios from 'axios'
import type { AxiosRequestConfig } from 'axios'
import { ApiError } from '@/types/api'
import type { ApiErrorBody, ApiSuccess } from '@/types/api'
import { tokenStore } from './tokenStore'

const baseURL = import.meta.env.VITE_API_BASE_URL ?? 'http://localhost:8080/api/v1'

export const http = axios.create({
  baseURL,
  headers: { Accept: 'application/json' },
})

// Attach the stored bearer token unless the caller set one explicitly.
http.interceptors.request.use((config) => {
  const token = tokenStore.get()
  if (token && config.headers && !config.headers.Authorization) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

let onUnauthorized: (() => void) | null = null

/** Register a handler invoked when an authenticated request is rejected (401). */
export function setUnauthorizedHandler(handler: () => void): void {
  onUnauthorized = handler
}

// Normalise errors into ApiError and react to expired/invalid tokens.
http.interceptors.response.use(
  (response) => response,
  (error) => {
    const hadToken = Boolean(tokenStore.get())
    const status = error.response?.status ?? 0
    const body = error.response?.data as ApiErrorBody | undefined

    if (status === 401 && hadToken) {
      // The token we sent was rejected (expired/invalid) → drop the session.
      onUnauthorized?.()
    }

    const apiError =
      body && typeof body === 'object' && 'error' in body
        ? new ApiError(status, body.error.code, body.error.message, body.error.details)
        : new ApiError(status, 'NETWORK_ERROR', error.message ?? 'Network error')

    return Promise.reject(apiError)
  },
)

/** Perform a request and unwrap the success envelope's `data`. */
export async function apiRequest<T>(config: AxiosRequestConfig): Promise<T> {
  const response = await http.request<ApiSuccess<T>>(config)
  return response.data.data
}

export interface Paginated<T> {
  items: T[]
  pagination?: { page: number; per_page: number; total: number; total_pages: number }
}

/** Perform a request and return the collection `data` plus `meta.pagination`. */
export async function apiRequestList<T>(config: AxiosRequestConfig): Promise<Paginated<T>> {
  const response = await http.request<ApiSuccess<T[]>>(config)
  return { items: response.data.data, pagination: response.data.meta?.pagination }
}
