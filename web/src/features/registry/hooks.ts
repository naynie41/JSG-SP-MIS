import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { useToast } from '@/components/Toast/ToastProvider'
import { beneficiaryApi, documentApi, householdApi, importApi, matchingApi, serviceRequestApi } from './api'
import type { BeneficiaryListParams, ResolveRowInput, SearchQuery } from './api'
import { RESOLUTION_LABELS } from './constants'
import type { ActivityInput } from '@/features/programmes/types'
import type { BeneficiaryInput, ConsentInput, HouseholdRole, MatchingConfigInput } from './types'

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

/**
 * Record or withdraw consent (NFR-PRV-01). Invalidates the beneficiary AND the
 * data-sharing oversight view, because withdrawing consent immediately suspends every
 * cross-MDA grant that depended on it — the oversight report would otherwise keep
 * showing those grants as effective.
 */
export function useRecordConsent() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ id, input }: { id: string; input: ConsentInput }) => beneficiaryApi.recordConsent(id, input),
    onSuccess: (beneficiary, { input }) => {
      qc.invalidateQueries({ queryKey: ['beneficiary', beneficiary.id] })
      qc.invalidateQueries({ queryKey: ['beneficiaries'] })
      qc.invalidateQueries({ queryKey: ['data-sharing'] })
      toast.success(
        input.status === 'granted' ? 'Consent recorded' : 'Consent withdrawn',
        input.status === 'granted'
          ? 'Cross-MDA sharing may now proceed where a grant exists.'
          : 'Any cross-MDA grant on this record is now suspended.',
      )
    },
  })
}

/**
 * Who holds cross-MDA read access to this record (FR-OWN-07). Owner MDA only, so the
 * caller passes `enabled` rather than the hook guessing — a non-owner must not fire a
 * request that will 403.
 */
export function useServiceGrants(beneficiaryId: string | undefined, enabled = true) {
  return useQuery({
    queryKey: ['beneficiary', beneficiaryId, 'service-grants'],
    queryFn: () => beneficiaryApi.serviceGrants(beneficiaryId!),
    enabled: enabled && Boolean(beneficiaryId),
  })
}

/**
 * Withdraw one cross-MDA grant.
 *
 * Invalidates the beneficiary too, not just the grant list: revocation changes who may
 * read the record, and a stale detail view would keep implying access that has ended.
 * `data-sharing` covers the platform-wide oversight report showing the same grant.
 */
export function useRevokeGrant(beneficiaryId: string) {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ grantId, reason }: { grantId: string; reason?: string }) =>
      beneficiaryApi.revokeGrant(grantId, reason),
    onSuccess: (result) => {
      qc.invalidateQueries({ queryKey: ['beneficiary', beneficiaryId, 'service-grants'] })
      qc.invalidateQueries({ queryKey: ['beneficiary', beneficiaryId] })
      qc.invalidateQueries({ queryKey: ['data-sharing'] })
      // The server is idempotent: a second revoke succeeds without changing anything,
      // and saying "revoked" again would misreport what happened.
      toast.success(
        result.revoked ? 'Access withdrawn' : 'Already withdrawn',
        result.revoked
          ? 'That MDA can no longer read this record. Deliveries already recorded are unaffected.'
          : 'This access had already been withdrawn; nothing changed.',
      )
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

/** The mapping screen's payload — detected columns, suggestions, samples, preview. */
export function useImportMapping(id: string | undefined, enabled = true) {
  return useQuery({
    queryKey: ['import', id, 'mapping'],
    queryFn: () => importApi.mapping(id!),
    enabled: enabled && Boolean(id),
  })
}

/**
 * Confirm the mapping and release the file for parsing.
 *
 * Invalidates the BATCH as well as the mapping: confirming moves it out of
 * `mapping_required`, and the page decides which step to show from that status.
 */
export function useConfirmMapping(batchId: string) {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ columnMap, saveTemplateAs }: { columnMap: Record<string, string | null>; saveTemplateAs?: string }) =>
      importApi.confirmMapping(batchId, columnMap, saveTemplateAs),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['import', batchId] })
      qc.invalidateQueries({ queryKey: ['imports'] })
      toast.success('Mapping confirmed', 'Validating and checking for duplicates…')
    },
  })
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
    mutationFn: ({
      file,
      programmeId,
      activityId,
      source,
    }: {
      file: File
      programmeId: string
      /** Optional: which of the MDA's activities delivered to these people, if known. */
      activityId?: string
      source?: string
    }) => importApi.upload(file, programmeId, activityId, source),
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

/**
 * Stage an activity-wizard preview (§10): upload an OPTIONAL beneficiary file for a
 * draft activity; dedup runs in preview before anything is saved.
 */
export function usePreviewActivityImport() {
  const qc = useQueryClient()
  return useMutation({
    // The draft is the activity payload — it carries the nested `locations` set, so it is
    // typed as the input itself rather than a scalar map.
    mutationFn: ({ draft, file, source }: { draft: ActivityInput; file: File; source?: string }) =>
      importApi.previewActivityImport(draft, file, source),
    onSuccess: () => qc.invalidateQueries({ queryKey: ['imports'] }),
  })
}

/**
 * Confirm an activity-wizard preview (§10): atomically create the activity and commit
 * the file under it. Distinct endpoint from the standalone confirm.
 */
export function useConfirmActivityImport() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (id: string) => importApi.confirmActivityImport(id),
    onSuccess: (result) => {
      qc.invalidateQueries({ queryKey: ['imports'] })
      qc.invalidateQueries({ queryKey: ['activities'] })
      qc.invalidateQueries({ queryKey: ['beneficiaries'] })
      toast.success('Activity created', `New beneficiaries recorded under "${result.activity.name}"; served duplicates raised Service Requests.`)
    },
  })
}

/**
 * Resolve a flagged preview row (FR-DUP-05). Refreshes the batch preview.
 *
 * `silent` suppresses the per-row toast for bulk runs, which report once at the
 * end instead of firing a toast per row. A single resolution is the product's
 * core audited decision, so on its own it always confirms.
 */
export function useResolveRow(batchId: string, options?: { silent?: boolean }) {
  const qc = useQueryClient()
  const toast = useToast()
  const silent = options?.silent ?? false
  return useMutation({
    mutationFn: ({ rowNumber, input }: { rowNumber: number; input: ResolveRowInput }) =>
      importApi.resolveRow(batchId, rowNumber, input),
    onSuccess: (_data, variables) => {
      qc.invalidateQueries({ queryKey: ['import', batchId] })
      if (silent) return
      toast.success(
        `Row ${variables.rowNumber} decided`,
        `${RESOLUTION_LABELS[variables.input.resolution]} — recorded in the audit log.`,
      )
    },
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

/** Request-to-serve items this MDA raised under an activity (§10 — activity detail). */
export function useActivityServiceRequests(activityId: string | undefined, status?: string, enabled = true) {
  return useQuery({
    queryKey: ['service-requests', 'activity', activityId, status],
    queryFn: () => serviceRequestApi.forActivity(activityId!, status),
    enabled: enabled && Boolean(activityId),
  })
}

/**
 * Owner accept/decline. Accept opens the requester's read-access grant; decline
 * blocks and requires a reason (surfaced to the requester in their outbox).
 */
/**
 * @param options.silent Suppress the per-decision toast. A bulk run reports once at the
 *   end; forty toasts would bury the summary and hide any failure among them.
 */
export function useDecideServiceRequest(options?: { silent?: boolean }) {
  const qc = useQueryClient()
  const toast = useToast()
  const silent = options?.silent ?? false
  return useMutation({
    mutationFn: ({ id, accept, reason }: { id: string; accept: boolean; reason?: string }) =>
      accept ? serviceRequestApi.accept(id, reason) : serviceRequestApi.decline(id, reason ?? ''),
    onSuccess: (request) => {
      qc.invalidateQueries({ queryKey: ['service-requests'] })
      // The MDA Overview's "pending request-to-serve approvals" counter counts exactly
      // the rows this decision just removed from the queue. Without this it keeps
      // showing the old number until its own interval elapses, and the console reports
      // work that is already done.
      qc.invalidateQueries({ queryKey: ['mda-action-required'] })
      if (silent) return
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
