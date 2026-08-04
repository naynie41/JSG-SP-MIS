import { apiRequest } from '@/lib/api/client'
import type {
  AccessGrant,
  AccessRole,
  CreateGrantInput,
  MatrixRole,
  PermissionMatrix,
  PermissionModules,
} from './types'

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
  /**
   * Replace a role's permission set. Writes the same `role_permission` pivot the
   * matrix reads, so the change is in force on the next request. The server rejects
   * a locked role or a never-grantable permission with 422 PERMISSION_NOT_GRANTABLE.
   */
  updateRolePermissions(roleId: string, permissions: string[]): Promise<{ role: MatrixRole }> {
    return apiRequest<{ role: MatrixRole }>({
      method: 'PUT',
      url: `/roles/${roleId}/permissions`,
      data: { permissions },
    })
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
