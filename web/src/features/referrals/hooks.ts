import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { useToast } from '@/components/Toast/ToastProvider'
import { referralApi } from './api'
import type { ReferralActionInput, ReferralListParams } from './api'
import type { ReferralAction, ReferralInput } from './types'

export function useReferrals(params: ReferralListParams, enabled = true) {
  return useQuery({ queryKey: ['referrals', params], queryFn: () => referralApi.list(params), enabled })
}

export function useReferral(id: string | undefined, enabled = true) {
  return useQuery({
    queryKey: ['referral', id],
    queryFn: () => referralApi.get(id!),
    enabled: enabled && Boolean(id),
  })
}

export function useCreateReferral() {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: (input: ReferralInput) => referralApi.create(input),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['referrals'] })
      toast.success('Referral raised', 'The receiving MDA has been notified.')
    },
  })
}

const ACTION_TOAST: Record<ReferralAction, string> = {
  accept: 'Referral accepted',
  reject: 'Referral rejected',
  'request-info': 'More information requested',
  'respond-info': 'Response sent',
  start: 'Marked in progress',
  complete: 'Referral completed',
  close: 'Referral closed',
}

/** Drive any lifecycle transition; refreshes the detail + both list views. */
export function useReferralAction(id: string) {
  const qc = useQueryClient()
  const toast = useToast()
  return useMutation({
    mutationFn: ({ action, input }: { action: ReferralAction; input?: ReferralActionInput }) =>
      referralApi.act(id, action, input),
    onSuccess: (_data, { action }) => {
      qc.invalidateQueries({ queryKey: ['referral', id] })
      qc.invalidateQueries({ queryKey: ['referrals'] })
      toast.success(ACTION_TOAST[action])
    },
  })
}
