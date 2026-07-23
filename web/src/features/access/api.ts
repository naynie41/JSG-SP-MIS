import { apiRequest } from '@/lib/api/client'
import type { AccessGrant, AccessRole, CreateGrantInput, PermissionMatrix, PermissionModules } from './types'

export const accessApi = {
  async roles(): Promise<AccessRole[]> {
    const { roles } = await apiRequest<{ roles: AccessRole[] }>({ method: 'GET', url: '/roles' })
    return roles
  },
  async permissions(): Promise<PermissionModules> {
    const { modules } = await apiRequest<{ modules: PermissionModules }>({ method: 'GET', url: '/permissions' })
    return modules
  },
  matrix(): Promise<PermissionMatrix> {
    return apiRequest<PermissionMatrix>({ method: 'GET', url: '/access/matrix' })
  },
  async grants(): Promise<AccessGrant[]> {
    const { grants } = await apiRequest<{ grants: AccessGrant[] }>({ method: 'GET', url: '/mda-access-grants' })
    return grants
  },
  createGrant(input: CreateGrantInput): Promise<AccessGrant> {
    return apiRequest<AccessGrant>({ method: 'POST', url: '/mda-access-grants', data: input })
  },
  revokeGrant(id: string): Promise<{ message: string }> {
    return apiRequest<{ message: string }>({ method: 'DELETE', url: `/mda-access-grants/${id}` })
  },
}
