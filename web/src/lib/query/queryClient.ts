import { QueryClient } from '@tanstack/react-query'
import { ApiError } from '@/types/api'

/** Shared TanStack Query client (CONVENTIONS.md §5: server-state library). */
export const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      staleTime: 30_000,
      retry: (failureCount, error) => {
        // Don't retry auth/permission failures; retry transient errors once.
        if (error instanceof ApiError && [401, 403, 404, 422].includes(error.status)) {
          return false
        }
        return failureCount < 1
      },
    },
  },
})
