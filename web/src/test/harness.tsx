import type { ReactNode } from 'react'
import { render } from '@testing-library/react'
import { MemoryRouter } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { AuthProvider } from '@/lib/auth/AuthProvider'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import type { AuthUser } from '@/types/auth'

/** Render a tree inside all app providers + a MemoryRouter at `route`. */
export function renderWithProviders(ui: ReactNode, route = '/') {
  const queryClient = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={queryClient}>
      <AuthProvider>
        <ToastProvider>
          <MemoryRouter initialEntries={[route]}>{ui}</MemoryRouter>
        </ToastProvider>
      </AuthProvider>
    </QueryClientProvider>,
  )
}

export function makeUser(overrides: Partial<AuthUser> = {}): AuthUser {
  return {
    id: 'u-1',
    name: 'Amina Bello',
    email: 'amina@example.test',
    status: 'active',
    mfa_enabled: false,
    last_login_at: null,
    mda: { id: 'm-1', name: 'Women Affairs', type: 'ministry' },
    role: { key: 'mda_officer', name: 'MDA Officer' },
    permissions: ['mda.view', 'user.view'],
    ...overrides,
  }
}
