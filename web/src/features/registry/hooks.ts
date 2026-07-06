import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { useToast } from '@/components/Toast/ToastProvider'
import { beneficiaryApi, documentApi, householdApi, importApi, matchingApi, serviceRequestApi } from './api'
import type { BeneficiaryListParams, ResolveRowInput, SearchQuery } from './api'
import type { BeneficiaryInput, HouseholdRole, MatchingConfigInput } from './types'

/* ----------------------------------------------------------------- beneficiaries */

export function useBeneficiaries(params: BeneficiaryListParams, enabled = true) {
  return useQuery({
    queryKey: ['beneficiaries', params],
    queryFn: () => beneficiaryApi.list(params),
    enabled,
  })
}

export function useBeneficiary(id: string | undefined, enabled = true) {
  return useQuery({
    queryKey: ['beneficiary', id],
    queryFn: () => beneficiaryApi.get(id!),
    enabled: enabled && Boolean(id),
  })
}

export function useUpdateBeneficiary() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ id, input }: { id: string; input: Partial<BeneficiaryInput> }) => beneficiaryApi.update(id, input),
    onSuccess: (beneficiary) => {
      qc.invalidateQueries({ queryKey: ['beneficiaries'] })
      qc.invalidateQueries({ queryKey: ['beneficiary', beneficiary.id] })
      toast.success('Beneficiary updated', beneficiary.full_name)
    },
  })
}

export function useDeleteBeneficiary() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (id: string) => beneficiaryApi.remove(id),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['beneficiaries'] })
      toast.success('Beneficiary deleted')
    },
    onError: () => toast.error('Delete failed', 'Please try again.'),
  })
}

/* --------------------------------------------------------------------- households */

export function useHouseholds(page: number, enabled = true) {
  return useQuery({ queryKey: ['households', page], queryFn: () => householdApi.list(page), enabled })
}

export function useHousehold(id: string | undefined, enabled = true) {
  return useQuery({
    queryKey: ['household', id],
    queryFn: () => householdApi.get(id!),
    enabled: enabled && Boolean(id),
  })
}

export function useDeleteHousehold() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (id: string) => householdApi.remove(id),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['households'] })
      toast.success('Household deleted')
    },
    onError: () => toast.error('Delete failed', 'Please try again.'),
  })
}

function useHouseholdRefresh(householdId: string) {
  const qc = useQueryClient()
  return () => {
    qc.invalidateQueries({ queryKey: ['household', householdId] })
    qc.invalidateQueries({ queryKey: ['households'] })
  }
}

export function useAddMember(householdId: string) {
  const refresh = useHouseholdRefresh(householdId)
  const toast = useToast()
  return useMutation({
    mutationFn: (input: { beneficiary_id: string; role_in_household: HouseholdRole }) =>
      householdApi.addMember(householdId, input),
    onSuccess: () => {
      refresh()
      toast.success('Member added')
    },
  })
}

export function useMoveMember(householdId: string) {
  const refresh = useHouseholdRefresh(householdId)
  const toast = useToast()
  return useMutation({
    mutationFn: (input: { beneficiary_id: string; role_in_household?: HouseholdRole }) =>
      householdApi.moveMember(householdId, input),
    onSuccess: () => {
      refresh()
      toast.success('Member moved', 'Previous membership closed; history retained.')
    },
  })
}

export function useRemoveMember(householdId: string) {
  const refresh = useHouseholdRefresh(householdId)
  const toast = useToast()
  return useMutation({
    mutationFn: (beneficiaryId: string) => householdApi.removeMember(householdId, beneficiaryId),
    onSuccess: () => {
      refresh()
      toast.success('Member removed')
    },
    onError: () => toast.error('Remove failed', 'Please try again.'),
  })
}

export function useDesignateHead(householdId: string) {
  const refresh = useHouseholdRefresh(householdId)
  const toast = useToast()
  return useMutation({
    mutationFn: (beneficiaryId: string) => householdApi.designateHead(householdId, beneficiaryId),
    onSuccess: () => {
      refresh()
      toast.success('Head designated')
    },
  })
}

/* ------------------------------------------------------------------------ imports */

export function useImports(page: number, enabled = true) {
  return useQuery({ queryKey: ['imports', page], queryFn: () => importApi.list(page), enabled })
}

export function useImportBatch(id: string | undefined, enabled = true) {
  return useQuery({
    queryKey: ['import', id],
    queryFn: () => importApi.get(id!),
    enabled: enabled && Boolean(id),
    // Poll while the worker is still parsing/committing.
    refetchInterval: (query) => {
      const status = query.state.data?.status
      return status === 'pending' || status === 'processing' || status === 'committing' ? 1500 : false
    },
  })
}

export function useUploadImport() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ file, source }: { file: File; source?: string }) => importApi.upload(file, source),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['imports'] })
      toast.success('File uploaded', 'Parsing and validating…')
    },
  })
}

export function useConfirmImport() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (id: string) => importApi.confirm(id),
    onSuccess: (batch) => {
      qc.invalidateQueries({ queryKey: ['import', batch.id] })
      qc.invalidateQueries({ queryKey: ['imports'] })
      qc.invalidateQueries({ queryKey: ['beneficiaries'] })
      toast.success('Import confirmed', 'Only new rows are committed; linked rows raise a Service Request.')
    },
  })
}

/** Resolve a flagged preview row (FR-DUP-05). Refreshes the batch preview. */
export function useResolveRow(batchId: string) {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: ({ rowNumber, input }: { rowNumber: number; input: ResolveRowInput }) =>
      importApi.resolveRow(batchId, rowNumber, input),
    onSuccess: () => qc.invalidateQueries({ queryKey: ['import', batchId] }),
  })
}

/* ------------------------------------------------------------ duplicate search */

export function useDuplicateSearch() {
  return useMutation({
    mutationFn: (query: SearchQuery) => beneficiaryApi.search(query),
  })
}

/* ----------------------------------------------------------- service requests */

/** Requests routed TO my MDA (owner) — the approval inbox. */
export function useServiceInbox(enabled = true) {
  return useQuery({ queryKey: ['service-requests', 'inbox'], queryFn: () => serviceRequestApi.inbox(), enabled })
}

/** Requests raised BY my MDA — the requester's outbox (status chips). */
export function useServiceOutbox(enabled = true) {
  return useQuery({ queryKey: ['service-requests', 'outbox'], queryFn: () => serviceRequestApi.outbox(), enabled })
}

export function useRaiseServiceRequest() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (input: { beneficiary_id: string; reason?: string }) => serviceRequestApi.raise(input),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['service-requests'] })
      toast.success('Service Request sent', 'Routed to the owning MDA for approval.')
    },
  })
}

/**
 * Owner accept/decline. Accept opens the requester's read-access grant; decline
 * blocks and requires a reason (surfaced to the requester in their outbox).
 */
export function useDecideServiceRequest() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ id, accept, reason }: { id: string; accept: boolean; reason?: string }) =>
      accept ? serviceRequestApi.accept(id, reason) : serviceRequestApi.decline(id, reason ?? ''),
    onSuccess: (request) => {
      qc.invalidateQueries({ queryKey: ['service-requests'] })
      toast.success(request.status === 'accepted' ? 'Service Request accepted' : 'Service Request declined')
    },
  })
}

/* --------------------------------------------------------- matching config */

export function useMatchingConfig(enabled = true) {
  return useQuery({ queryKey: ['matching-config'], queryFn: () => matchingApi.getConfig(), enabled })
}

export function useMatchingVersions(enabled = true) {
  return useQuery({ queryKey: ['matching-versions'], queryFn: () => matchingApi.versions(), enabled })
}

export function usePublishMatchingConfig() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (input: MatchingConfigInput) => matchingApi.publish(input),
    onSuccess: (config) => {
      qc.invalidateQueries({ queryKey: ['matching-config'] })
      qc.invalidateQueries({ queryKey: ['matching-versions'] })
      toast.success('Matching rules published', `Version ${config.version} is now active.`)
    },
  })
}

/* ---------------------------------------------------------------------- documents */

export function useDocuments(beneficiaryId: string | undefined, enabled = true) {
  return useQuery({
    queryKey: ['documents', beneficiaryId],
    queryFn: () => documentApi.list(beneficiaryId!),
    enabled: enabled && Boolean(beneficiaryId),
  })
}

export function useUploadDocument(beneficiaryId: string) {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ file, documentType }: { file: File; documentType: string }) =>
      documentApi.upload(beneficiaryId, file, documentType),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['documents', beneficiaryId] })
      toast.success('Document uploaded')
    },
  })
}

export function useDeleteDocument(beneficiaryId: string) {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (documentId: string) => documentApi.remove(beneficiaryId, documentId),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['documents', beneficiaryId] })
      toast.success('Document deleted')
    },
    onError: () => toast.error('Delete failed', 'Please try again.'),
  })
}
