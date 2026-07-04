import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { useToast } from '@/components/Toast/ToastProvider'
import { benefitApi, benefitImportApi, flagApi } from './api'
import type { AggregateParams } from './api'
import type { BenefitInput } from './types'

export function useRecordBenefit() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (input: BenefitInput) => benefitApi.record(input),
    onSuccess: (benefit) => {
      qc.invalidateQueries({ queryKey: ['benefits'] })
      qc.invalidateQueries({ queryKey: ['ledger', benefit.beneficiary_id] })
      qc.invalidateQueries({ queryKey: ['programme-budget'] })
      toast.success('Benefit recorded', benefit.status === 'verified' ? 'Delivery verified.' : 'Delivery recorded.')
    },
  })
}

export function useVerifyBenefit() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ id, input }: { id: string; input: { verification_method: string; verification_reference?: string } }) =>
      benefitApi.verify(id, input),
    onSuccess: (benefit) => {
      qc.invalidateQueries({ queryKey: ['benefits'] })
      qc.invalidateQueries({ queryKey: ['ledger', benefit.beneficiary_id] })
      toast.success('Delivery verified')
    },
  })
}

export function useBeneficiaryLedger(beneficiaryId: string | undefined, enabled = true) {
  return useQuery({
    queryKey: ['ledger', beneficiaryId],
    queryFn: () => benefitApi.ledger(beneficiaryId!),
    enabled: enabled && Boolean(beneficiaryId),
  })
}

export function useBenefits(params: { page?: number; programme_id?: string; status?: string; benefit_type?: string }, enabled = true) {
  return useQuery({ queryKey: ['benefits', params], queryFn: () => benefitApi.list(params), enabled })
}

export function useBenefitAggregate(params: AggregateParams, enabled = true) {
  return useQuery({ queryKey: ['benefit-aggregate', params], queryFn: () => benefitApi.aggregate(params), enabled })
}

/* --------------------------------------------------------- bulk delivery import */

export function useBenefitImport(id: string | undefined, enabled = true) {
  return useQuery({
    queryKey: ['benefit-import', id],
    queryFn: () => benefitImportApi.get(id!),
    enabled: enabled && Boolean(id),
    refetchInterval: (query) => {
      const status = query.state.data?.status
      return status === 'pending' || status === 'processing' || status === 'committing' ? 1500 : false
    },
  })
}

export function useUploadBenefitImport() {
  const toast = useToast()
  return useMutation({
    mutationFn: ({ activityId, file }: { activityId: string; file: File }) => benefitImportApi.upload(activityId, file),
    onSuccess: () => toast.success('Delivery list uploaded', 'Validating…'),
  })
}

export function useConfirmBenefitImport() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (id: string) => benefitImportApi.confirm(id),
    onSuccess: (batch) => {
      qc.invalidateQueries({ queryKey: ['benefit-import', batch.id] })
      qc.invalidateQueries({ queryKey: ['benefits'] })
      toast.success('Deliveries committed', 'Valid rows are recorded to the ledger.')
    },
  })
}

export function useBenefitFlags(params: { status?: string; beneficiary_id?: string } = {}, enabled = true) {
  return useQuery({ queryKey: ['benefit-flags', params], queryFn: () => flagApi.list(params), enabled })
}

export function useReviewFlag() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ id, status, review_note }: { id: string; status: 'confirmed' | 'dismissed'; review_note?: string }) =>
      flagApi.review(id, { status, review_note }),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['benefit-flags'] })
      toast.success('Flag reviewed')
    },
  })
}
