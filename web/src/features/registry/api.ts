import { apiRequest, apiRequestList, http } from '@/lib/api/client'
import type { Paginated } from '@/lib/api/client'
import type {
  Beneficiary,
  BeneficiaryDocument,
  BeneficiaryInput,
  Household,
  HouseholdInput,
  HouseholdMembership,
  HouseholdRole,
  ImportBatch,
  ImportRow,
  ImportRowResolution,
  MatchingConfig,
  MatchingConfigInput,
  RevealMatch,
  SearchCandidate,
  ServiceRequest,
} from './types'

/** Partial identity details fed to the duplicate-search engine (FR-DUP-04). */
export interface SearchQuery {
  nin?: string
  bvn?: string
  phone?: string
  first_name?: string
  middle_name?: string
  last_name?: string
  date_of_birth?: string
  gender?: string
  lga?: string
  ward?: string
}

export interface ResolveRowInput {
  resolution: ImportRowResolution
  note?: string
  beneficiary_id?: string
}

export interface BeneficiaryListParams {
  page?: number
  perPage?: number
  search?: string
  lga?: string
  ward?: string
  status?: string
}

/** Save a fetched Blob to the user's device (authenticated download). */
function triggerBlobDownload(blob: Blob, filename: string): void {
  const url = URL.createObjectURL(blob)
  const anchor = window.document.createElement('a')
  anchor.href = url
  anchor.download = filename
  window.document.body.appendChild(anchor)
  anchor.click()
  anchor.remove()
  URL.revokeObjectURL(url)
}

export const beneficiaryApi = {
  list(params: BeneficiaryListParams = {}): Promise<Paginated<Beneficiary>> {
    return apiRequestList<Beneficiary>({
      method: 'GET',
      url: '/beneficiaries',
      params: {
        page: params.page,
        per_page: params.perPage,
        search: params.search || undefined,
        'filter[lga]': params.lga || undefined,
        'filter[ward]': params.ward || undefined,
        'filter[status]': params.status || undefined,
      },
    })
  },
  get(id: string): Promise<Beneficiary> {
    return apiRequest<Beneficiary>({ method: 'GET', url: `/beneficiaries/${id}` })
  },
  /**
   * Export the current filtered list (FR-REG-04). Sends the SAME filter/search params
   * as {@link list}. Small exports stream back and download immediately; large ones
   * are queued server-side (HTTP 202) and the user is notified when ready.
   */
  async export(params: BeneficiaryListParams, format: 'csv' | 'excel'): Promise<{ queued: boolean }> {
    const response = await http.get('/beneficiaries/export', {
      params: {
        format,
        search: params.search || undefined,
        'filter[lga]': params.lga || undefined,
        'filter[ward]': params.ward || undefined,
        'filter[status]': params.status || undefined,
      },
      responseType: 'blob',
    })

    // Over the size threshold → queued; the JSON envelope arrives as a blob.
    if (response.status === 202) {
      return { queued: true }
    }

    const disposition = String(response.headers['content-disposition'] ?? '')
    const named = /filename="?([^";]+)"?/.exec(disposition)?.[1]
    triggerBlobDownload(response.data as Blob, named ?? `beneficiaries.${format === 'excel' ? 'xlsx' : 'csv'}`)
    return { queued: false }
  },
  // No create(): ingestion is source-only (bulk import + REST intake). Only
  // owner-only correction of existing records is exposed here.
  update(id: string, input: Partial<BeneficiaryInput>): Promise<Beneficiary> {
    return apiRequest<Beneficiary>({ method: 'PATCH', url: `/beneficiaries/${id}`, data: input })
  },
  remove(id: string): Promise<{ message: string }> {
    return apiRequest<{ message: string }>({ method: 'DELETE', url: `/beneficiaries/${id}` })
  },
  async lookup(params: { nin?: string; bvn?: string; phone?: string }): Promise<RevealMatch[]> {
    const { matches } = await apiRequest<{ matches: RevealMatch[] }>({
      method: 'GET',
      url: '/beneficiaries/lookup',
      params,
    })
    return matches
  },
  /** Fuzzy "serve many" search (FR-DUP-04): ranked reveal-only candidates. */
  async search(query: SearchQuery): Promise<SearchCandidate[]> {
    const { candidates } = await apiRequest<{ candidates: SearchCandidate[] }>({
      method: 'GET',
      url: '/beneficiaries/search',
      params: query,
    })
    return candidates
  },
}

/**
 * Service Request workflow (§12, FR-OWN-06/07) — a non-owner MDA asks the owner to
 * serve a beneficiary; the owner accepts (opening a read-access grant) or declines.
 * Never transfers ownership. Inbox = routed to me (to decide); outbox = raised by me.
 */
export const serviceRequestApi = {
  async inbox(): Promise<ServiceRequest[]> {
    const { service_requests } = await apiRequest<{ service_requests: ServiceRequest[] }>({
      method: 'GET',
      url: '/service-requests/inbox',
    })
    return service_requests
  },
  async outbox(): Promise<ServiceRequest[]> {
    const { service_requests } = await apiRequest<{ service_requests: ServiceRequest[] }>({
      method: 'GET',
      url: '/service-requests/outbox',
    })
    return service_requests
  },
  /** Requests-to-serve this MDA raised under a given activity (§10 — activity detail). */
  async forActivity(activityId: string, status?: string): Promise<ServiceRequest[]> {
    const { service_requests } = await apiRequest<{ service_requests: ServiceRequest[] }>({
      method: 'GET',
      url: '/service-requests/outbox',
      params: { activity_id: activityId, status },
    })
    return service_requests
  },
  raise(input: { beneficiary_id: string; reason?: string }): Promise<ServiceRequest> {
    return apiRequest<ServiceRequest>({ method: 'POST', url: '/service-requests', data: input })
  },
  accept(id: string, reason?: string): Promise<ServiceRequest> {
    return apiRequest<ServiceRequest>({ method: 'POST', url: `/service-requests/${id}/accept`, data: { reason } })
  },
  decline(id: string, reason: string): Promise<ServiceRequest> {
    return apiRequest<ServiceRequest>({ method: 'POST', url: `/service-requests/${id}/decline`, data: { reason } })
  },
}

/** Duplicate-matching configuration admin (FR-DUP-02/03). */
export const matchingApi = {
  getConfig(): Promise<MatchingConfig> {
    return apiRequest<MatchingConfig>({ method: 'GET', url: '/matching/config' })
  },
  async versions(): Promise<MatchingConfig[]> {
    const { versions } = await apiRequest<{ versions: MatchingConfig[] }>({
      method: 'GET',
      url: '/matching/config/versions',
    })
    return versions
  },
  publish(input: MatchingConfigInput): Promise<MatchingConfig> {
    return apiRequest<MatchingConfig>({ method: 'PUT', url: '/matching/config', data: input })
  },
}

export const householdApi = {
  list(page?: number): Promise<Paginated<Household>> {
    return apiRequestList<Household>({ method: 'GET', url: '/households', params: { page } })
  },
  get(id: string): Promise<Household> {
    return apiRequest<Household>({ method: 'GET', url: `/households/${id}` })
  },
  // No create(): households are formed by source ingestion, not manual entry.
  update(id: string, input: Partial<HouseholdInput>): Promise<Household> {
    return apiRequest<Household>({ method: 'PATCH', url: `/households/${id}`, data: input })
  },
  remove(id: string): Promise<{ message: string }> {
    return apiRequest<{ message: string }>({ method: 'DELETE', url: `/households/${id}` })
  },
  addMember(householdId: string, input: { beneficiary_id: string; role_in_household: HouseholdRole }): Promise<HouseholdMembership> {
    return apiRequest<HouseholdMembership>({ method: 'POST', url: `/households/${householdId}/members`, data: input })
  },
  moveMember(householdId: string, input: { beneficiary_id: string; role_in_household?: HouseholdRole }): Promise<HouseholdMembership> {
    return apiRequest<HouseholdMembership>({ method: 'POST', url: `/households/${householdId}/members/move`, data: input })
  },
  removeMember(householdId: string, beneficiaryId: string): Promise<{ message: string }> {
    return apiRequest<{ message: string }>({ method: 'DELETE', url: `/households/${householdId}/members/${beneficiaryId}` })
  },
  designateHead(householdId: string, beneficiaryId: string): Promise<Household> {
    return apiRequest<Household>({ method: 'POST', url: `/households/${householdId}/head`, data: { beneficiary_id: beneficiaryId } })
  },
}

export const importApi = {
  list(page?: number): Promise<Paginated<ImportBatch>> {
    return apiRequestList<ImportBatch>({ method: 'GET', url: '/beneficiaries/imports', params: { page } })
  },
  get(id: string): Promise<ImportBatch> {
    return apiRequest<ImportBatch>({ method: 'GET', url: `/beneficiaries/imports/${id}` })
  },
  upload(file: File, source?: string): Promise<ImportBatch> {
    const form = new FormData()
    form.append('file', file)
    if (source) form.append('source', source)
    return apiRequest<ImportBatch>({ method: 'POST', url: '/beneficiaries/imports', data: form })
  },
  confirm(id: string): Promise<ImportBatch> {
    return apiRequest<ImportBatch>({ method: 'POST', url: `/beneficiaries/imports/${id}/confirm` })
  },
  /**
   * Activity-wizard OPTIONAL inline upload (§10): stage an UNBOUND preview batch for a
   * draft activity + file. Reuses the standard preview + row-resolve endpoints; confirm
   * via {@link confirmActivityImport}.
   */
  previewActivityImport(draft: Record<string, string | number | null | undefined>, file: File, source?: string): Promise<ImportBatch> {
    const form = new FormData()
    form.append('file', file)
    if (source) form.append('source', source)
    for (const [key, value] of Object.entries(draft)) {
      if (value !== null && value !== undefined && value !== '') form.append(key, String(value))
    }
    return apiRequest<ImportBatch>({ method: 'POST', url: '/activity-imports', data: form })
  },
  /** Atomically create the activity and commit the previewed file under it. */
  confirmActivityImport(id: string): Promise<{ activity: { id: string; name: string }; import: { batch: ImportBatch } }> {
    return apiRequest({ method: 'POST', url: `/activity-imports/${id}/confirm` })
  },
  /** Resolve a flagged preview row: new (with justification) / link-serve / skip (FR-DUP-05). */
  resolveRow(batchId: string, rowNumber: number, input: ResolveRowInput): Promise<ImportRow> {
    return apiRequest<ImportRow>({
      method: 'POST',
      url: `/beneficiaries/imports/${batchId}/rows/${rowNumber}/resolve`,
      data: input,
    })
  },
}

export const documentApi = {
  async list(beneficiaryId: string): Promise<BeneficiaryDocument[]> {
    const { documents } = await apiRequest<{ documents: BeneficiaryDocument[] }>({
      method: 'GET',
      url: `/beneficiaries/${beneficiaryId}/documents`,
    })
    return documents
  },
  upload(beneficiaryId: string, file: File, documentType: string): Promise<BeneficiaryDocument> {
    const form = new FormData()
    form.append('file', file)
    form.append('document_type', documentType)
    return apiRequest<BeneficiaryDocument>({ method: 'POST', url: `/beneficiaries/${beneficiaryId}/documents`, data: form })
  },
  remove(beneficiaryId: string, documentId: string): Promise<{ message: string }> {
    return apiRequest<{ message: string }>({ method: 'DELETE', url: `/beneficiaries/${beneficiaryId}/documents/${documentId}` })
  },
  /** Fetch the file (authenticated) and trigger a browser download. */
  async download(document: BeneficiaryDocument): Promise<void> {
    const response = await http.get(`/beneficiaries/${document.beneficiary_id}/documents/${document.id}/download`, {
      responseType: 'blob',
    })
    const url = URL.createObjectURL(response.data as Blob)
    const anchor = window.document.createElement('a')
    anchor.href = url
    anchor.download = document.original_filename
    window.document.body.appendChild(anchor)
    anchor.click()
    anchor.remove()
    URL.revokeObjectURL(url)
  },
}
