import { useState } from 'react'
import { Link } from 'react-router-dom'
import { BadgeInfo, Check, Save, X } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { Card } from '@/components/Card/Card'
import { TextField } from '@/components/Field/TextField'
import { SelectField } from '@/components/Field/SelectField'
import { TextareaField } from '@/components/Field/TextareaField'
import { Icon } from '@/components/Icon/Icon'
import { ApiError } from '@/types/api'
import { useAuth } from '@/lib/auth/AuthProvider'
import { nairaToKobo } from '@/lib/utils/money'
import { useBeneficiaries } from '@/features/registry/hooks'
import { titleCase } from '@/features/registry/constants'
import type { Beneficiary } from '@/features/registry/types'
import { useProgrammes } from '@/features/programmes/hooks'
import { useActivities } from '@/features/programmes/hooks'
import { BENEFIT_TYPE_OPTIONS, VERIFICATION_METHOD_OPTIONS } from './constants'
import { useRecordBenefit } from './hooks'
import type { Benefit } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from '@/features/programmes/programmes.module.css'

export function RecordBenefitPage() {
  const { hasPermission } = useAuth()
  const canRecord = hasPermission('benefit.create')

  const [beneficiary, setBeneficiary] = useState<Beneficiary | null>(null)
  const [query, setQuery] = useState('')
  const [programmeId, setProgrammeId] = useState('')
  const [activityId, setActivityId] = useState('')
  const [benefitType, setBenefitType] = useState('cash')
  const [quantity, setQuantity] = useState('')
  const [unit, setUnit] = useState('')
  const [monetary, setMonetary] = useState('')
  const [funding, setFunding] = useState('')
  const [deliveryDate, setDeliveryDate] = useState(new Date().toISOString().slice(0, 10))
  const [notes, setNotes] = useState('')
  const [method, setMethod] = useState('none')
  const [reference, setReference] = useState('')
  const [error, setError] = useState<string | null>(null)
  const [saved, setSaved] = useState<Benefit | null>(null)

  const searchResults = useBeneficiaries({ page: 1, search: query }, canRecord && query.trim().length > 0 && !beneficiary)
  const programmes = useProgrammes({ status: 'active' }, canRecord)
  const activities = useActivities(programmeId || undefined, Boolean(programmeId))
  const record = useRecordBenefit()

  if (!canRecord) {
    return <Card><p className={layout.forbidden}>You do not have permission to record benefits.</p></Card>
  }

  async function submit() {
    setError(null)
    setSaved(null)
    if (!beneficiary || !programmeId) {
      setError('Select a beneficiary and a programme.')
      return
    }
    if (method === 'signature' && reference.trim() === '') {
      setError('A signature reference is required for signature verification.')
      return
    }
    try {
      const benefit = await record.mutateAsync({
        beneficiary_id: beneficiary.id,
        programme_id: programmeId,
        activity_id: activityId || undefined,
        benefit_type: benefitType,
        quantity: quantity ? Number(quantity) : undefined,
        unit: unit || undefined,
        monetary_value: nairaToKobo(monetary),
        funding_source: funding || undefined,
        delivery_date: deliveryDate,
        verification_method: method as Benefit['verification_method'],
        verification_reference: reference || undefined,
        notes: notes || undefined,
      })
      setSaved(benefit)
      // Reset the delivery-specific fields, keep the beneficiary for follow-ups.
      setQuantity('')
      setMonetary('')
      setNotes('')
      setReference('')
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not record the benefit.')
    }
  }

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">04 · Benefits</span>
          <h1 className="t-h1">Record a benefit delivery</h1>
        </div>
      </div>

      <div className={styles.notice} style={{ marginBottom: 'var(--space-5)' }}>
        <Icon icon={BadgeInfo} size={18} />
        <span>This records a benefit <strong>delivery</strong> — SP-MIS does not move money. The monetary value is captured as data only.</span>
      </div>

      <div className={styles.stack}>
        {error && (
          <p className={layout.alert} role="alert">
            {error}
          </p>
        )}

        {saved && (
          <Card variant="mint">
            <p className={styles.note}>
              <Badge variant={saved.status === 'verified' ? 'success' : 'info'} dot>{saved.status}</Badge>{' '}
              Delivery recorded for {beneficiary?.full_name}.{' '}
              {beneficiary && <Link to={`/beneficiaries/${beneficiary.id}`}>View ledger →</Link>}
            </p>
          </Card>
        )}

        {/* Step 1 — beneficiary */}
        <Card title="Beneficiary" eyebrow="Step 1">
          {beneficiary ? (
            <div className={styles.rowActions} style={{ justifyContent: 'space-between' }}>
              <div>
                <strong>{beneficiary.full_name}</strong>{' '}
                <span className={styles.note}>{beneficiary.lga ? titleCase(beneficiary.lga) : '—'}</span>
              </div>
              <Button size="sm" variant="tertiary" leftIcon={X} onClick={() => setBeneficiary(null)}>Change</Button>
            </div>
          ) : (
            <div className={styles.stack}>
              <TextField label="Search" hideLabel placeholder="Search beneficiaries by name" value={query} onChange={(e) => setQuery(e.target.value)} />
              {(searchResults.data?.items ?? []).slice(0, 6).map((b) => (
                <div key={b.id} className={styles.rowActions} style={{ justifyContent: 'space-between' }}>
                  <span>{b.full_name} · <span className={styles.note}>{b.lga ? titleCase(b.lga) : '—'}</span></span>
                  <Button size="sm" variant="tertiary" leftIcon={Check} onClick={() => { setBeneficiary(b); setQuery('') }}>Select</Button>
                </div>
              ))}
            </div>
          )}
        </Card>

        {/* Step 2 — programme/activity */}
        <Card title="Programme & activity" eyebrow="Step 2">
          <div className={layout.grid2}>
            <SelectField
              label="Programme"
              required
              placeholder="Select a programme"
              options={(programmes.data?.items ?? []).map((p) => ({ value: p.id, label: p.name }))}
              value={programmeId}
              onChange={(e) => { setProgrammeId(e.target.value); setActivityId('') }}
            />
            <SelectField
              label="Activity (optional)"
              placeholder="Programme-level"
              options={(activities.data?.items ?? []).map((a) => ({ value: a.id, label: a.name }))}
              value={activityId}
              onChange={(e) => setActivityId(e.target.value)}
              disabled={!programmeId}
            />
          </div>
          <p className={styles.note} style={{ marginTop: 'var(--space-2)' }}>The beneficiary must be enrolled in the programme.</p>
        </Card>

        {/* Step 3 — delivery */}
        <Card title="Delivery" eyebrow="Step 3">
          <div className={layout.form}>
            <div className={layout.grid2}>
              <SelectField label="Benefit type" required options={BENEFIT_TYPE_OPTIONS} value={benefitType} onChange={(e) => setBenefitType(e.target.value)} />
              <TextField label="Delivery date" type="date" required value={deliveryDate} onChange={(e) => setDeliveryDate(e.target.value)} />
            </div>
            <div className={layout.grid2}>
              <TextField label="Quantity" type="number" min={0} value={quantity} onChange={(e) => setQuantity(e.target.value)} />
              <TextField label="Unit" placeholder="e.g. bags" value={unit} onChange={(e) => setUnit(e.target.value)} />
            </div>
            <div className={layout.grid2}>
              <TextField label="Monetary value (₦)" type="number" min={0} step="0.01" helper="Data only — not disbursed." value={monetary} onChange={(e) => setMonetary(e.target.value)} />
              <TextField label="Funding source" value={funding} onChange={(e) => setFunding(e.target.value)} />
            </div>
            <TextareaField label="Notes" rows={2} value={notes} onChange={(e) => setNotes(e.target.value)} />
          </div>
        </Card>

        {/* Step 4 — verification */}
        <Card title="Verification" eyebrow="Step 4">
          <div className={layout.grid2}>
            <SelectField label="Method" options={VERIFICATION_METHOD_OPTIONS} value={method} onChange={(e) => setMethod(e.target.value)} />
            {(method === 'signature' || method === 'field_confirmation') && (
              <TextField
                label={method === 'signature' ? 'Signature reference' : 'Confirmation note'}
                required={method === 'signature'}
                value={reference}
                onChange={(e) => setReference(e.target.value)}
              />
            )}
          </div>
          <p className={styles.note} style={{ marginTop: 'var(--space-2)' }}>OTP and biometric verification are not yet available.</p>
        </Card>

        <div>
          <Button leftIcon={Save} onClick={submit} loading={record.isPending} disabled={!beneficiary || !programmeId}>
            Record delivery
          </Button>
        </div>
      </div>
    </div>
  )
}
