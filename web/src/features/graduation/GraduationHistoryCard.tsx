import { GraduationCap, Users } from 'lucide-react'
import { Card } from '@/components/Card/Card'
import { Badge } from '@/components/Badge/Badge'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { useGraduationEvents } from './hooks'
import type { GraduationEvent } from './types'
import styles from './graduation.module.css'

function when(iso: string | null): string {
  if (!iso) return '—'
  return new Date(iso).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' })
}

/**
 * The graduation record for a programme (FR-GRD-02).
 *
 * Graduation is a judgement about a person's circumstances, so the record exists to be
 * reviewed: who graduated, when, on which criteria, and who decided. It is a permanent
 * record — a graduation changes the enrolment's status and never removes the person or
 * their delivery history, so this list only ever grows.
 *
 * Scoped by the server to the MDA that ran the programme, which is why it can carry
 * names at all.
 */
export function GraduationHistoryCard({ programmeId }: { programmeId: string }) {
  const { hasPermission } = useAuth()
  const canView = hasPermission('graduation.view')
  const { data, isLoading } = useGraduationEvents(programmeId, canView)

  if (!canView) return null

  const events: GraduationEvent[] = data?.items ?? []

  return (
    <Card
      titleAs="h2"
      title="Graduation record"
      eyebrow="History"
    >
      <p className={styles.note}>
        A permanent record. Graduating changes the enrolment status and never removes the person
        or their delivery history.
      </p>

      {isLoading && <Spinner size={20} label="Loading graduation record" />}

      {!isLoading && events.length === 0 && (
        <p className={styles.note}>
          No one has graduated from this programme yet. Records appear here as officers record them.
        </p>
      )}

      {!isLoading && events.length > 0 && (
        <ul className={styles.historyList}>
          {events.map((event) => (
            <li key={event.id} className={styles.historyItem}>
              <span className={styles.historyIcon} aria-hidden="true">
                <Icon icon={event.subject?.type === 'household' ? Users : GraduationCap} size={16} />
              </span>

              <div className={styles.historyBody}>
                <p className={styles.historyName}>
                  {event.subject?.name ?? 'Record retained'}
                  <Badge variant="neutral">
                    {event.subject?.type === 'household' ? 'Household' : 'Individual'}
                  </Badge>
                </p>
                {event.reason && <p className={styles.historyReason}>{event.reason}</p>}
                <p className={styles.historyMeta}>
                  {when(event.graduated_at)}
                  {event.criteria_name ? ` · ${event.criteria_name}` : ''}
                  {event.decided_by_name ? ` · decided by ${event.decided_by_name}` : ''}
                </p>
              </div>
            </li>
          ))}
        </ul>
      )}
    </Card>
  )
}
