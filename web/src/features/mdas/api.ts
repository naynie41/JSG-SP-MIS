import { apiRequest } from '@/lib/api/client'
import type { Mda, MdaInput } from './types'

export const mdaApi = {
  async list(): Promise<Mda[]> {
    const { mdas } = await apiRequest<{ mdas: Mda[] }>({ method: 'GET', url: '/mdas' })
    return mdas
  },
  create(input: MdaInput) {
    return apiRequest<Mda>({ method: 'POST', url: '/mdas', data: input })
  },
  update(id: string, input: Partial<MdaInput>) {
    return apiRequest<Mda>({ method: 'PATCH', url: `/mdas/${id}`, data: input })
  },
  deactivate(id: string) {
    return apiRequest<Mda>({ method: 'POST', url: `/mdas/${id}/deactivate` })
  },
  activate(id: string) {
    return apiRequest<Mda>({ method: 'POST', url: `/mdas/${id}/activate` })
  },
}
