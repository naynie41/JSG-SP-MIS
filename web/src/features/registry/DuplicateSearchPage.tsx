import { useState } from 'react'
import { Search } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card } from '@/components/Card/Card'
import { TextField } from '@/components/Field/TextField'
import { SelectField } from '@/components/Field/SelectField'
import { ApiError } from '@/types/api'
import { useAuth } from '@/lib/auth/AuthProvider'
import { GENDER_OPTIONS, LGA_OPTIONS, MATCH_BAND_LABELS } from './constants'
import { useDuplicateSearch } from './hooks'
import { MatchRevealPanel } from './MatchRevealPanel'
import { RequestToServeButton } from './RequestToServeButton'
import type { SearchQuery } from './api'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

const EMPTY: SearchQuery = {
  nin: '', bvn: '', phone: '', first_name: '', last_name: '', date_of_birth: '', gender: '', lga: '', ward: '',
}

/**
 * Standalone duplicate / "serve many" search (PRD FR-DUP-04, FR-OWN-03). Runs the
 * same matching engine as import screening against partial identity details and
 * returns ranked, reveal-only candidates across MDAs; from any result the officer
 * can raise a request-to-serve without taking ownership.
 */
export function DuplicateSearchPage() {
  const { hasPermission, user } = useAuth()
  const canSearch = hasPermission('beneficiary-lookup.view')
  const canServe = hasPermission('beneficiary.create')
  const myMdaId = user?.mda?.id ?? null

  const search = useDuplicateSearch()
  const [query, setQuery] = useState<SearchQuery>(EMPTY)
  const [error, setError] = useState<string | null>(null)

  if (!canSearch) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to search the registry.</p>
      </Card>
    )
  }

  const set = (key: keyof SearchQuery) => (event: { target: { value: string } }) =>
    setQuery((q) => ({ ...q, [key]: event.target.value }))

  // A blocking-capable field is required so the search never scans the whole table.
  const canSubmit = [query.nin, query.bvn, query.phone, query.last_name].some((v) => (v ?? '').trim() !== '')

  async function runSearch(event: React.FormEvent) {
    event.preventDefault()
    setError(null)
    const trimmed = Object.fromEntries(
      Object.entries(query).map(([k, v]) => [k, (v ?? '').trim()]).filter(([, v]) => v !== ''),
    )
    try {
      await search.mutateAsync(trimmed)
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Search failed. Please try again.')
    }
  }

  const candidates = search.data ?? []

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">03 · Registry</span>
          <h1 className="t-h1">Duplicate search</h1>
          <p className={styles.note}>
            Find an existing beneficiary across all MDAs before registering. Reveal fields only — never the full profile.
          </p>
        </div>
      </div>

      <Card>
        <form onSubmit={runSearch} className={layout.form} noValidate>
          {error && (
            <p className={layout.alert} role="alert">
              {error}
            </p>
          )}
          <div className={layout.grid2}>
            <TextField label="NIN" value={query.nin} onChange={set('nin')} />
            <TextField label="BVN" value={query.bvn} onChange={set('bvn')} />
          </div>
          <div className={layout.grid2}>
            <TextField label="Phone" value={query.phone} onChange={set('phone')} />
            <TextField label="Date of birth" type="date" value={query.date_of_birth} onChange={set('date_of_birth')} />
          </div>
          <div className={layout.grid2}>
            <TextField label="First name" value={query.first_name} onChange={set('first_name')} />
            <TextField label="Last name" value={query.last_name} onChange={set('last_name')} />
          </div>
          <div className={layout.grid2}>
            <SelectField label="Gender" placeholder="Any" options={GENDER_OPTIONS} value={query.gender} onChange={set('gender')} />
            <SelectField label="LGA" placeholder="Any" options={LGA_OPTIONS} value={query.lga} onChange={set('lga')} />
          </div>
          <div>
            <Button type="submit" leftIcon={Search} loading={search.isPending} disabled={!canSubmit}>
              Search
            </Button>
            {!canSubmit && (
              <p className={styles.note} style={{ marginTop: 'var(--space-2)' }}>
                Enter at least a NIN, BVN, phone, or last name.
              </p>
            )}
          </div>
        </form>
      </Card>

      {search.isSuccess && (
        <div className={styles.stack} style={{ marginTop: 'var(--space-5)' }}>
          <span className="eyebrow">
            {candidates.length} {candidates.length === 1 ? 'candidate' : 'candidates'}
          </span>
          {candidates.length === 0 && (
            <Card>
              <p className={styles.note}>No matching beneficiary found. You can register this person as new.</p>
            </Card>
          )}
          {candidates.map((candidate, index) => {
            const owned = candidate.beneficiary.owner_mda?.id === myMdaId
            return (
              <Card key={candidate.beneficiary.id ?? index}>
                <div className={styles.reviewPanel}>
                  <div className={styles.stack}>
                    <div className={styles.candidateMeta}>
                      <Badge variant={statusVariant(`match.${candidate.band}`)} dot mono>
                        {MATCH_BAND_LABELS[candidate.band]}
                      </Badge>
                      <span className={styles.cellSub}>score {candidate.score.toFixed(2)}</span>
                      <div className={styles.matchedFields}>
                        {candidate.matched_fields.map((f) => (
                          <Badge key={f} variant="outline" mono>
                            {f}
                          </Badge>
                        ))}
                      </div>
                    </div>
                    <MatchRevealPanel reveal={candidate.beneficiary} />
                  </div>
                  <div className={styles.resolveBox}>
                    <span className="eyebrow">Coordinate</span>
                    {!canServe ? (
                      <p className={styles.note}>You do not have permission to request service.</p>
                    ) : candidate.beneficiary.id ? (
                      <RequestToServeButton
                        beneficiaryId={candidate.beneficiary.id}
                        disabledReason={owned ? 'Owned by your MDA' : undefined}
                      />
                    ) : null}
                  </div>
                </div>
              </Card>
            )
          })}
        </div>
      )}
    </div>
  )
}
