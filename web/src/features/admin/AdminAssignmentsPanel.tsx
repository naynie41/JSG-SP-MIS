import { useMemo, useState } from 'react'
import { AlertTriangle } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Checkbox } from '@/components/Field/Checkbox'
import { SearchableSelectField } from '@/components/Field/SearchableSelectField'
import { TextField } from '@/components/Field/TextField'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { useMdas } from '@/features/mdas/hooks'
import { useMdaAssignments, useProgrammes, useSaveMdaAssignments } from '@/features/programmes/hooks'
import type { AssignmentSource, Programme } from '@/features/programmes/types'
import { ApiError } from '@/types/api'
import styles from './admin.module.css'

/** What each provenance means, in the words an administrator needs before unticking. */
const SOURCE_LABEL: Record<AssignmentSource, string> = {
  inventory: 'From inventory',
  derived: 'Already running it',
  mda: 'Their own programme',
  assignment: 'Assigned here',
}

const SOURCE_HINT: Record<AssignmentSource, string> = {
  inventory: 'Came from the MDA programme inventory the catalogue was built from.',
  derived: 'Recorded because this organisation already had activities under it.',
  mda: 'A programme they created themselves, cleared by the System Administrator.',
  assignment: 'Chosen by a catalogue administrator.',
}

/**
 * Programme assignment — which catalogue programmes an organisation offers
 * (§10, revised 2026-11-05).
 *
 * The state catalogue runs to 115 programmes and no MDA delivers more than nineteen
 * of them. Until this existed, every MDA saw all 115 in every programme dropdown, so
 * an officer at Health scrolled past the agriculture inventory to find a health
 * programme. What is ticked here is what that organisation can select, anywhere.
 *
 * Administrator-push only. There is no request queue and no self-service: an MDA that
 * wants a programme it has not been assigned either asks an administrator, or creates
 * one of its own and submits it for approval — which is a separate, pre-existing flow
 * this screen does not touch.
 */
export function AdminAssignmentsPanel() {
  const { hasPermission } = useAuth()
  const canAssign = hasPermission('programme.edit')

  const [mdaId, setMdaId] = useState('')
  const [search, setSearch] = useState('')
  // Null until the administrator touches something: the saved list IS the state
  // until then, so a refetch while they are reading does not look like an edit.
  const [draft, setDraft] = useState<Set<string> | null>(null)
  const [blocking, setBlocking] = useState<string[]>([])

  const mdas = useMdas(canAssign)
  // The whole catalogue, which an administrator sees unnarrowed by definition.
  const catalogue = useProgrammes({ per_page: 100 }, canAssign)
  const assignments = useMdaAssignments(mdaId || undefined, canAssign)
  const save = useSaveMdaAssignments()

  const assigned = useMemo(
    () => new Set((assignments.data?.programmes ?? []).map((p) => p.id)),
    [assignments.data],
  )
  const selected = draft ?? assigned

  const sourceById = useMemo(() => {
    const map = new Map<string, AssignmentSource>()
    for (const p of assignments.data?.programmes ?? []) {
      if (p.assignment) map.set(p.id, p.assignment.source)
    }
    return map
  }, [assignments.data])

  const activityCountById = useMemo(() => {
    const map = new Map<string, number>()
    for (const p of assignments.data?.programmes ?? []) map.set(p.id, p.activities_count ?? 0)
    return map
  }, [assignments.data])

  // Memoised off the query result, not off a fresh `?? []` literal: a new array every
  // render would make the sort below re-run on every keystroke anywhere on the page.
  const programmes = useMemo(() => catalogue.data?.items ?? [], [catalogue.data])
  const term = search.trim().toLowerCase()
  const visible = useMemo(() => {
    const rows = term === '' ? programmes : programmes.filter((p) => p.name.toLowerCase().includes(term))
    // Assigned first, then alphabetical: the administrator is auditing what this
    // organisation offers, so the answer should not be scattered through 115 rows.
    return [...rows].sort((a, b) => {
      const mine = Number(selected.has(b.id)) - Number(selected.has(a.id))
      return mine !== 0 ? mine : a.name.localeCompare(b.name)
    })
  }, [programmes, term, selected])

  const added = [...selected].filter((id) => !assigned.has(id))
  const removed = [...assigned].filter((id) => !selected.has(id))
  const dirty = added.length > 0 || removed.length > 0

  if (!canAssign) {
    return <p className={styles.muted}>You do not have permission to assign programmes.</p>
  }

  function toggle(programme: Programme, on: boolean) {
    setBlocking([])
    const next = new Set(selected)
    if (on) next.add(programme.id)
    else next.delete(programme.id)
    setDraft(next)
  }

  function chooseMda(id: string) {
    setMdaId(id)
    // The draft belonged to the previous organisation. Carrying it over would apply
    // one MDA's catalogue to another on the next save.
    setDraft(null)
    setBlocking([])
    setSearch('')
  }

  function submit() {
    if (!mdaId) return
    setBlocking([])
    save.mutate(
      { mdaId, programmeIds: [...selected] },
      {
        onSuccess: () => setDraft(null),
        onError: (error) => {
          // 409: the organisation still runs something being unticked. The response
          // names those activities, and they are the only part anyone can act on, so
          // they go on the page rather than into a toast that says "failed".
          if (error instanceof ApiError && error.code === 'PROGRAMME_ASSIGNMENT_IN_USE') {
            setBlocking(error.details.map((d) => d.message))
          }
        },
      },
    )
  }

  const organisation = mdas.data?.find((m) => m.id === mdaId)

  return (
    <div className={styles.stack}>
      <div className={styles.sectionHead}>
        <h2 className={styles.sectionTitle}>What each organisation offers</h2>
        <p className={styles.sectionSub}>
          Tick the catalogue programmes this organisation delivers. That is what its officers can choose when creating
          an activity, running an import or recording delivery — the rest of the catalogue stays invisible to them.
        </p>
      </div>

      <div className={styles.filterBar}>
        <SearchableSelectField
          label="Organisation"
          value={mdaId}
          placeholder="Select an organisation"
          onChange={(e) => chooseMda(e.target.value)}
          options={(mdas.data ?? []).map((m) => ({
            value: m.id,
            label: m.is_government === false ? `${m.name} (partner)` : m.name,
          }))}
          helper="Partner organisations are never narrowed — they read the whole catalogue."
        />
        {mdaId !== '' && (
          <TextField
            label="Find a programme"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder={`Search ${programmes.length} catalogue programmes`}
          />
        )}
      </div>

      {mdaId === '' ? (
        <p className={styles.muted}>Choose an organisation to see and change what it offers.</p>
      ) : assignments.isLoading || catalogue.isLoading ? (
        <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
          <Spinner size={24} label="Loading assignments" />
        </div>
      ) : (
        <>
          <div className={styles.assignSummary}>
            <p className={styles.assignCount}>
              <strong>{selected.size}</strong> of {programmes.length} assigned
              {organisation ? ` to ${organisation.name}` : ''}
            </p>
            {selected.size === 0 && (
              // Confirmed with the owner: an organisation with nothing assigned offers
              // nothing and cannot create work. Saying so is the difference between a
              // deliberate state and a screen that looks broken.
              <p className={styles.assignWarn}>
                <Icon icon={AlertTriangle} size={14} /> With nothing assigned, this organisation cannot create an
                activity or run an import at all.
              </p>
            )}
            {dirty && (
              <p className={styles.assignDirty}>
                {added.length > 0 && `${added.length} to assign`}
                {added.length > 0 && removed.length > 0 && ' · '}
                {removed.length > 0 && `${removed.length} to remove`}
                {' — not saved yet'}
              </p>
            )}
          </div>

          {blocking.length > 0 && (
            <div className={styles.alert} role="alert">
              <Icon icon={AlertTriangle} size={16} className={styles.alertIcon} />
              <div className={styles.alertBody}>
                <p className={styles.alertTitle}>Nothing was changed — these are still running</p>
                <ul className={styles.plainList}>
                  {blocking.map((message) => (
                    <li key={message} className={styles.alertDetail}>
                      {message}
                    </li>
                  ))}
                </ul>
                <p className={styles.alertDetail}>
                  Complete or archive them first. Removing the assignment would leave them under a programme this
                  organisation can no longer see.
                </p>
              </div>
            </div>
          )}

          <ul className={styles.assignList}>
            {visible.map((programme) => {
              const on = selected.has(programme.id)
              const source = sourceById.get(programme.id)
              const running = activityCountById.get(programme.id) ?? 0

              return (
                <li key={programme.id} className={styles.assignRow} data-on={on || undefined}>
                  <Checkbox
                    label={programme.name}
                    checked={on}
                    onChange={(e) => toggle(programme, e.target.checked)}
                  />
                  <div className={styles.assignMeta}>
                    {programme.is_central === false && <Badge variant="info">Their own</Badge>}
                    {on && source && (
                      <span className={styles.assignSource} title={SOURCE_HINT[source]}>
                        {SOURCE_LABEL[source]}
                      </span>
                    )}
                    {running > 0 && (
                      <span className={styles.assignRunning}>
                        {running} {running === 1 ? 'activity' : 'activities'}
                      </span>
                    )}
                  </div>
                </li>
              )
            })}
          </ul>

          {visible.length === 0 && <p className={styles.muted}>No programme matches “{search}”.</p>}

          <div className={styles.actionBar}>
            <Button onClick={submit} disabled={!dirty || save.isPending}>
              {save.isPending ? 'Saving…' : 'Save assignments'}
            </Button>
            {dirty && (
              <Button variant="tertiary" onClick={() => setDraft(null)} disabled={save.isPending}>
                Discard changes
              </Button>
            )}
          </div>
        </>
      )}

      <p className={styles.footnote}>
        An assignment is not ownership. One catalogue programme may be assigned to several organisations, each running
        it through its own activities. Removing an assignment removes permission to start new work — never the
        programme, and never the history under it
      </p>
    </div>
  )
}
