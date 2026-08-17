import { MapPin, X } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Checkbox } from '@/components/Field/Checkbox'
import { SelectField } from '@/components/Field/SelectField'
import { Icon } from '@/components/Icon/Icon'
import { useLgas, useWards } from './hooks'
import type { LocationSetEntry } from './types'
import styles from './locationSet.module.css'

interface LocationSetFieldProps {
  value: LocationSetEntry[]
  onChange: (next: LocationSetEntry[]) => void
  /** Field-level errors keyed exactly as the API returns them (`locations.0.ward_ids.1`). */
  errors?: Record<string, string>
  disabled?: boolean
}

/**
 * The activity's declared location set: add several LGAs, and within each pick specific
 * wards or the whole LGA.
 *
 * Each chosen LGA is its own titled block with its OWN ward selector, fetched per LGA
 * (GEO.1 `/reference/wards?lga_id=`). Scoping the fetch rather than filtering one big
 * list client-side is what makes "wards of this LGA" true by construction — ward names
 * repeat across Jigawa, so a shared list filtered in the browser is one bug away from
 * offering a ward that belongs somewhere else.
 *
 * Removing an LGA removes its wards with it, because a ward without its LGA is not a
 * place this system can name.
 */
export function LocationSetField({ value, onChange, errors = {}, disabled }: LocationSetFieldProps) {
  const lgas = useLgas()
  const all = lgas.data?.lgas ?? []
  const chosen = new Set(value.map((entry) => entry.lga_id))
  const available = all.filter((lga) => !chosen.has(lga.id))

  function addLga(lgaId: string) {
    if (!lgaId || chosen.has(lgaId)) return
    onChange([...value, { lga_id: lgaId, ward_ids: [], whole_lga: false }])
  }

  function removeLga(lgaId: string) {
    onChange(value.filter((entry) => entry.lga_id !== lgaId))
  }

  function updateEntry(lgaId: string, patch: Partial<LocationSetEntry>) {
    onChange(value.map((entry) => (entry.lga_id === lgaId ? { ...entry, ...patch } : entry)))
  }

  return (
    <fieldset className={styles.fieldset} disabled={disabled}>
      <legend className={styles.legend}>Where does this activity run?</legend>
      <p className={styles.helper}>
        Add every LGA this activity covers, then choose its wards — or the whole LGA. This describes
        the plan; it does not restrict which beneficiaries can be uploaded.
      </p>

      {errors.locations && <p className={styles.error} role="alert">{errors.locations}</p>}

      <div className={styles.blocks}>
        {value.map((entry, index) => (
          <LgaBlock
            key={entry.lga_id}
            entry={entry}
            index={index}
            name={all.find((lga) => lga.id === entry.lga_id)?.name ?? 'Unknown LGA'}
            errors={errors}
            onRemove={() => removeLga(entry.lga_id)}
            onChange={(patch) => updateEntry(entry.lga_id, patch)}
          />
        ))}
      </div>

      {value.length === 0 && (
        <p className={styles.empty}>
          <Icon icon={MapPin} size={16} aria-hidden="true" /> No areas declared yet.
        </p>
      )}

      <SelectField
        label="Add an LGA"
        placeholder={lgas.isPending ? 'Loading LGAs…' : available.length ? 'Select an LGA to add' : 'All LGAs added'}
        options={available.map((lga) => ({ value: lga.id, label: lga.name }))}
        disabled={lgas.isPending || available.length === 0}
        value=""
        onChange={(event) => addLga(event.target.value)}
      />
    </fieldset>
  )
}

interface LgaBlockProps {
  entry: LocationSetEntry
  index: number
  name: string
  errors: Record<string, string>
  onRemove: () => void
  onChange: (patch: Partial<LocationSetEntry>) => void
}

/** One chosen LGA: its title, its own ward multi-select, and the whole-LGA option. */
function LgaBlock({ entry, index, name, errors, onRemove, onChange }: LgaBlockProps) {
  // Scoped to THIS LGA — the selector can only ever offer its own wards.
  const wards = useWards(entry.lga_id)
  const list = wards.data?.wards ?? []
  const selected = new Set(entry.ward_ids)
  const lgaError = errors[`locations.${index}.lga_id`] ?? errors[`locations.${index}.whole_lga`]

  // A ward the server rejected as belonging to ANOTHER LGA is, by definition, absent
  // from this LGA's ward list — so it has no checkbox to mark. Surfacing those messages
  // at block level is the only way the user ever sees them.
  const orphanWardErrors = entry.ward_ids
    .map((wardId, position) => (list.some((ward) => ward.id === wardId) ? null : errors[`locations.${index}.ward_ids.${position}`]))
    .filter((message): message is string => Boolean(message))

  function toggleWard(wardId: string, checked: boolean) {
    onChange({
      ward_ids: checked ? [...entry.ward_ids, wardId] : entry.ward_ids.filter((id) => id !== wardId),
    })
  }

  return (
    <section className={styles.block} aria-label={name}>
      <header className={styles.blockHeader}>
        <h4 className={styles.blockTitle}>
          <Icon icon={MapPin} size={16} aria-hidden="true" /> {name}
        </h4>
        <Button
          size="sm"
          variant="tertiary"
          leftIcon={X}
          onClick={onRemove}
          aria-label={`Remove ${name}`}
        >
          Remove
        </Button>
      </header>

      {lgaError && <p className={styles.error} role="alert">{lgaError}</p>}
      {orphanWardErrors.map((message, i) => (
        <p key={i} className={styles.error} role="alert">{message}</p>
      ))}

      <Checkbox
        label="Whole LGA (all wards)"
        checked={entry.whole_lga}
        // Choosing the whole LGA clears any ward picks: "everywhere in this LGA" and
        // "only these wards" are different claims, and the API rejects both at once.
        onChange={(event) => onChange({ whole_lga: event.target.checked, ward_ids: [] })}
      />

      {!entry.whole_lga && (
        <div className={styles.wards}>
          {wards.isPending && <p className={styles.muted}>Loading wards…</p>}

          {!wards.isPending && list.length === 0 && (
            <p className={styles.muted}>
              No ward data loaded for {name}. Choose “Whole LGA” to declare it.
            </p>
          )}

          {list.map((ward) => {
            const position = entry.ward_ids.indexOf(ward.id)
            const wardError = position >= 0 ? errors[`locations.${index}.ward_ids.${position}`] : undefined
            return (
              <div key={ward.id} className={wardError ? styles.wardInvalid : undefined}>
                <Checkbox
                  label={ward.name}
                  checked={selected.has(ward.id)}
                  onChange={(event) => toggleWard(ward.id, event.target.checked)}
                />
                {wardError && <span className={styles.error} role="alert">{wardError}</span>}
              </div>
            )
          })}
        </div>
      )}

      {!entry.whole_lga && entry.ward_ids.length === 0 && list.length > 0 && (
        <p className={styles.muted}>No wards selected — this LGA will be saved as whole-LGA coverage.</p>
      )}
    </section>
  )
}
