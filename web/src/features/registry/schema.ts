import { z } from 'zod'
import type { BeneficiaryInput } from './types'

const elevenDigits = (label: string) =>
  z
    .string()
    .trim()
    .refine((v) => v === '' || /^\d{11}$/.test(v.replace(/\D/g, '')), `${label} must be exactly 11 digits`)

function isValidPastDob(value: string): boolean {
  if (!value) return false
  const date = new Date(value)
  if (Number.isNaN(date.getTime())) return false
  return date < new Date() && date > new Date('1900-01-01')
}

/**
 * Mirrors the backend's `BeneficiaryRules::forRegistration()`.
 *
 * This is the CORRECTION schema — it validates owner-only edits of an already-imported
 * record (`EditBeneficiaryModal` → `PATCH /beneficiaries/{id}`). There is no create form:
 * ingestion is source-only (CLAUDE.md §8), so nothing on the client ever builds a new
 * beneficiary. It still mirrors the registration ruleset because a correction has to
 * satisfy exactly the same field rules an imported row did.
 */
export const beneficiarySchema = z.object({
  first_name: z.string().trim().min(1, 'First name is required').max(255),
  middle_name: z.string().trim().max(255),
  last_name: z.string().trim().min(1, 'Last name is required').max(255),
  nin: elevenDigits('NIN'),
  bvn: elevenDigits('BVN'),
  phone: z.string().trim().max(20),
  date_of_birth: z.string().min(1, 'Date of birth is required').refine(isValidPastDob, 'Enter a valid date of birth in the past'),
  gender: z.enum(['male', 'female', 'other']),
  address: z.string().trim().max(500),
  lga: z.string().min(1, 'LGA is required'),
  ward: z.string().trim().min(1, 'Ward is required').max(120),
})

export type BeneficiaryFormValues = z.infer<typeof beneficiarySchema>

const optional = (value: string) => {
  const trimmed = value.trim()
  return trimmed === '' ? undefined : trimmed
}

const digits = (value: string) => {
  const clean = value.replace(/\D/g, '')
  return clean === '' ? undefined : clean
}

export function toBeneficiaryPayload(values: BeneficiaryFormValues): BeneficiaryInput {
  return {
    first_name: values.first_name.trim(),
    middle_name: optional(values.middle_name),
    last_name: values.last_name.trim(),
    nin: digits(values.nin),
    bvn: digits(values.bvn),
    phone: digits(values.phone),
    date_of_birth: values.date_of_birth,
    gender: values.gender,
    address: optional(values.address),
    lga: values.lga,
    ward: values.ward.trim(),
  }
}
