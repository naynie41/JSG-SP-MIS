import { apiRequest } from '@/lib/api/client'
import type { CreateUserInput, ManagedUser, RoleOption, UpdateUserInput } from './types'

export type UserStatusAction = 'suspend' | 'deactivate' | 'activate'

export const userApi = {
  async list(): Promise<ManagedUser[]> {
    const { users } = await apiRequest<{ users: ManagedUser[] }>({ method: 'GET', url: '/users' })
    return users
  },
  create(input: CreateUserInput) {
    return apiRequest<ManagedUser>({ method: 'POST', url: '/users', data: input })
  },
  update(id: string, input: UpdateUserInput) {
    return apiRequest<ManagedUser>({ method: 'PATCH', url: `/users/${id}`, data: input })
  },
  changeStatus(id: string, action: UserStatusAction) {
    return apiRequest<ManagedUser>({ method: 'POST', url: `/users/${id}/${action}` })
  },
  forcePasswordReset(id: string) {
    return apiRequest<{ message: string }>({ method: 'POST', url: `/users/${id}/force-password-reset` })
  },
  resetMfa(id: string) {
    return apiRequest<{ message: string }>({ method: 'POST', url: `/users/${id}/reset-mfa` })
  },
}

export const roleApi = {
  async list(): Promise<RoleOption[]> {
    const { roles } = await apiRequest<{ roles: RoleOption[] }>({ method: 'GET', url: '/roles' })
    return roles
  },
}
