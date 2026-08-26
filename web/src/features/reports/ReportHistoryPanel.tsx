import type { ReactNode } from 'react'
import { RunsPanel, SchedulesPanel } from './ReportPanels'
import styles from './reports.module.css'

interface ReportHistoryPanelProps {
  canManage: boolean
  scheduleFootnote: string
  runsFootnote?: string
  /** Console-specific extras, e.g. the MDA's beneficiary registry export. */
  children?: ReactNode
}

/**
 * Everything about reports that have already run, or are set to run (FR-RPT-04).
 *
 * Recent exports, schedules and the registry export were three sibling tabs answering
 * one question — "what has this console produced, and what will it produce" — which
 * made the tab bar longer than the work behind it. As sections of one page they read in
 * the order they happen: what is queued, then what came out.
 */
export function ReportHistoryPanel({
  canManage,
  scheduleFootnote,
  runsFootnote,
  children,
}: ReportHistoryPanelProps) {
  return (
    <div className={styles.historySections}>
      <section>
        <h3 className={styles.historySectionTitle}>Scheduled reports</h3>
        <SchedulesPanel canManage={canManage} footnote={scheduleFootnote} />
      </section>

      <section>
        <h3 className={styles.historySectionTitle}>Recent exports</h3>
        <RunsPanel footnote={runsFootnote} />
      </section>

      {children}
    </div>
  )
}
