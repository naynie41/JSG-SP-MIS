import { useState } from 'react'
import { Plus, Trash2 } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { Card } from '@/components/Card/Card'
import { TextField } from '@/components/Field/TextField'
import { SelectField } from '@/components/Field/SelectField'
import { RadioGroup } from '@/components/Field/RadioGroup'
import { Icon } from '@/components/Icon/Icon'
import { ConfirmDialog } from '@/components/Modal/ConfirmDialog'
import { Spinner } from '@/components/Spinner/Spinner'
import { ApiError } from '@/types/api'
import { useAuth } from '@/lib/auth/AuthProvider'
import { COMPARATOR_OPTIONS, EXACT_BEHAVIOUR_OPTIONS, MATCH_FIELD_OPTIONS } from './constants'
import { useMatchingConfig, usePublishMatchingConfig } from './hooks'
import type { ExactMatchBehaviour, FuzzyFieldRule, MatchingConfig, MatchingConfigInput } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

const fieldLabel = (value: string) => MATCH_FIELD_OPTIONS.find((o) => o.value === value)?.label ?? value

export function MatchingConfigPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('matching.view')
  const canEdit = hasPermission('matching.edit')
  const { data: config, isLoading } = useMatchingConfig(canView)

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view the matching configuration.</p>
      </Card>
    )
  }

  if (isLoading || !config) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={24} label="Loading configuration" />
      </div>
    )
  }

  // Re-seed the editor whenever a new version becomes active.
  return <ConfigEditor key={config.id} config={config} canEdit={canEdit} />
}

function ConfigEditor({ config, canEdit }: { config: MatchingConfig; canEdit: boolean }) {
  const publish = usePublishMatchingConfig()
  const [rules, setRules] = useState<string[][]>(config.deterministic_rules)
  const [fuzzy, setFuzzy] = useState<FuzzyFieldRule[]>(config.fuzzy_fields)
  const [review, setReview] = useState(config.review_threshold)
  const [autoAccept, setAutoAccept] = useState<number | ''>(config.auto_accept_threshold ?? '')
  const [behaviour, setBehaviour] = useState<ExactMatchBehaviour>(config.exact_match_behaviour)
  const [description, setDescription] = useState(config.description ?? '')
  const [error, setError] = useState<string | null>(null)
  const [confirmOpen, setConfirmOpen] = useState(false)

  const totalWeight = fuzzy.reduce((sum, f) => sum + (Number.isFinite(f.weight) ? f.weight : 0), 0)

  function validate(): string | null {
    if (fuzzy.length === 0) return 'Add at least one fuzzy field.'
    if (fuzzy.some((f) => !(f.weight > 0))) return 'Every fuzzy field needs a weight greater than 0.'
    if (rules.some((r) => r.length === 0)) return 'Remove empty deterministic rules or add a field to them.'
    if (review < 0 || review > 1) return 'Review threshold must be between 0 and 1.'
    if (autoAccept !== '' && (autoAccept < 0 || autoAccept > 1)) return 'Auto-accept threshold must be between 0 and 1.'
    if (autoAccept !== '' && autoAccept < review) return 'Auto-accept threshold must be at least the review threshold.'
    return null
  }

  function openConfirm() {
    const message = validate()
    setError(message)
    if (!message) setConfirmOpen(true)
  }

  async function doPublish() {
    setError(null)
    const input: MatchingConfigInput = {
      deterministic_rules: rules,
      fuzzy_fields: fuzzy,
      review_threshold: review,
      auto_accept_threshold: autoAccept === '' ? null : autoAccept,
      exact_match_behaviour: behaviour,
      description: description.trim() || null,
    }
    try {
      await publish.mutateAsync(input)
      setConfirmOpen(false)
    } catch (err) {
      setConfirmOpen(false)
      setError(err instanceof ApiError ? err.message : 'Could not publish the configuration.')
    }
  }

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">03 · Registry</span>
          <h1 className="t-h1">Matching rules</h1>
          <p className={styles.note}>
            Duplicate-verification configuration. Publishing creates a new immutable version and is audited.
          </p>
        </div>
        <div className={styles.rowActions}>
          <Badge variant="dark" mono>
            v{config.version}
          </Badge>
          <Badge variant="success" dot>
            Active
          </Badge>
        </div>
      </div>

      {error && (
        <p className={layout.alert} role="alert" style={{ marginBottom: 'var(--space-4)' }}>
          {error}
        </p>
      )}

      <div className={styles.stack}>
        <Card title="Deterministic rules" eyebrow="Decisive key sets → exact match">
          <div className={styles.stack}>
            {rules.length === 0 && <p className={styles.note}>No deterministic rules. Matches rely on fuzzy scoring only.</p>}
            {rules.map((rule, ruleIndex) => {
              const available = MATCH_FIELD_OPTIONS.filter((o) => !rule.includes(o.value))
              return (
                <div key={ruleIndex} className={styles.candidate}>
                  <div className={styles.chipRow}>
                    <span className="eyebrow">Rule {ruleIndex + 1}</span>
                    {rule.map((f) => (
                      <Badge key={f} variant="neutral" mono>
                        {fieldLabel(f)}
                        {canEdit && (
                          <button
                            type="button"
                            aria-label={`Remove ${fieldLabel(f)} from rule ${ruleIndex + 1}`}
                            className={styles.chipRemove}
                            onClick={() => setRules(rules.map((r, i) => (i === ruleIndex ? r.filter((x) => x !== f) : r)))}
                          >
                            ×
                          </button>
                        )}
                      </Badge>
                    ))}
                    {rule.length === 0 && <span className={styles.cellSub}>empty</span>}
                  </div>
                  {canEdit && (
                    <div className={styles.chipRow}>
                      <SelectField
                        label="Add field"
                        hideLabel
                        placeholder="Add field…"
                        options={available}
                        value=""
                        onChange={(e) => {
                          const value = e.target.value
                          if (value) setRules(rules.map((r, i) => (i === ruleIndex ? [...r, value] : r)))
                        }}
                      />
                      <Button
                        size="sm"
                        variant="tertiary"
                        leftIcon={Trash2}
                        onClick={() => setRules(rules.filter((_, i) => i !== ruleIndex))}
                      >
                        Remove rule
                      </Button>
                    </div>
                  )}
                </div>
              )
            })}
            {canEdit && (
              <div>
                <Button size="sm" variant="tertiary" leftIcon={Plus} onClick={() => setRules([...rules, ['nin']])}>
                  Add rule
                </Button>
              </div>
            )}
          </div>
        </Card>

        <Card title="Fuzzy fields" eyebrow="Weighted comparators">
          <div className={styles.stack}>
            {fuzzy.map((rule, i) => (
              <div key={i} className={styles.ruleRow}>
                <SelectField
                  label="Field"
                  options={MATCH_FIELD_OPTIONS}
                  value={rule.field}
                  disabled={!canEdit}
                  onChange={(e) => setFuzzy(fuzzy.map((r, j) => (j === i ? { ...r, field: e.target.value } : r)))}
                />
                <SelectField
                  label="Comparator"
                  options={COMPARATOR_OPTIONS}
                  value={rule.comparator}
                  disabled={!canEdit}
                  onChange={(e) =>
                    setFuzzy(fuzzy.map((r, j) => (j === i ? { ...r, comparator: e.target.value as FuzzyFieldRule['comparator'] } : r)))
                  }
                />
                <TextField
                  label="Weight"
                  type="number"
                  min={0}
                  step={0.05}
                  value={String(rule.weight)}
                  disabled={!canEdit}
                  helper={totalWeight > 0 ? `${Math.round((rule.weight / totalWeight) * 100)}% share` : undefined}
                  onChange={(e) => setFuzzy(fuzzy.map((r, j) => (j === i ? { ...r, weight: Number(e.target.value) } : r)))}
                />
                {canEdit && (
                  <Button
                    size="sm"
                    variant="tertiary"
                    aria-label={`Remove ${fieldLabel(rule.field)}`}
                    onClick={() => setFuzzy(fuzzy.filter((_, j) => j !== i))}
                  >
                    <Icon icon={Trash2} size={16} />
                  </Button>
                )}
              </div>
            ))}
            {canEdit && (
              <div>
                <Button
                  size="sm"
                  variant="tertiary"
                  leftIcon={Plus}
                  onClick={() => setFuzzy([...fuzzy, { field: 'last_name', comparator: 'phonetic', weight: 0.1 }])}
                >
                  Add field
                </Button>
              </div>
            )}
          </div>
        </Card>

        <Card title="Thresholds & behaviour" eyebrow="Bands and exact-match handling">
          <div className={layout.form}>
            <div className={layout.grid2}>
              <TextField
                label="Review threshold"
                type="number"
                min={0}
                max={1}
                step={0.01}
                required
                disabled={!canEdit}
                helper="Score at or above this is a probable match."
                value={String(review)}
                onChange={(e) => setReview(Number(e.target.value))}
              />
              <TextField
                label="Auto-accept threshold"
                type="number"
                min={0}
                max={1}
                step={0.01}
                disabled={!canEdit}
                helper="Optional. Must be ≥ review threshold."
                value={autoAccept === '' ? '' : String(autoAccept)}
                onChange={(e) => setAutoAccept(e.target.value === '' ? '' : Number(e.target.value))}
              />
            </div>
            <RadioGroup
              label="Exact-match behaviour"
              name="exact-behaviour"
              options={EXACT_BEHAVIOUR_OPTIONS}
              value={behaviour}
              onChange={(value) => setBehaviour(value as ExactMatchBehaviour)}
            />
            <TextField
              label="Description"
              disabled={!canEdit}
              value={description}
              onChange={(e) => setDescription(e.target.value)}
            />
          </div>
        </Card>

        {canEdit && (
          <div className={styles.rowActions}>
            <Button onClick={openConfirm} loading={publish.isPending}>
              Publish new version
            </Button>
          </div>
        )}
      </div>

      <ConfirmDialog
        open={confirmOpen}
        title="Publish matching rules"
        confirmLabel="Publish"
        loading={publish.isPending}
        onCancel={() => setConfirmOpen(false)}
        onConfirm={doPublish}
      >
        <p>
          This publishes a new active version of the duplicate-matching rules. The change is audited and takes effect on
          the next preview re-run. Continue?
        </p>
      </ConfirmDialog>
    </div>
  )
}
