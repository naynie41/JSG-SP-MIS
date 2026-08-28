import { useState } from 'react'
import { Tabs } from '@/components/Tabs/Tabs'
import { useAuth } from '@/lib/auth/AuthProvider'
import { useReportDatasets } from '@/features/reports/hooks'
import { ReportsLoading } from '@/features/reports/ReportPanels'
import { ReportBuilderPanel } from '@/features/reports/ReportBuilderPanel'
import { ReportHistoryPanel } from '@/features/reports/ReportHistoryPanel'
import { ReportsDashboardPanel } from '@/features/reports/ReportsDashboardPanel'
import styles from './admin.module.css'

/**
 * Reports (console section 9) — the administrative report catalogue over the Phase 6
 * reporting engine.
 *
 * There is no reporting logic here, and no reporting UI of its own either: the builder,
 * schedule and run views are the shared panels in `@/features/reports/ReportPanels`,
 * which the MDA console also composes. This page's only job is to decide WHICH datasets
 * it shows — the administrative ones — and to say what they are for.
 *
 * Entitlement is never decided here. `/reports/adhoc/datasets` releases the
 * administrative datasets only to a governance scope, so this page renders whatever it
 * is entitled to and nothing more.
 */
export function AdminReportsPage() {
  const { hasPermission } = useAuth()
  const canExport = hasPermission('reporting.export')
  const { data, isLoading, error } = useReportDatasets()
  const [tab, setTab] = useState('dashboard')

  const adminDatasets = (data ?? []).filter((d) => d.admin)

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>Administration console</span>
        <h1 className={styles.pageTitle}>Reports</h1>
        <p className={styles.lead}>
          Administrative reporting over the platform's own records: users and access, organizations, the programme
          catalogue, duplicate review, the audit log and imports. Reports are generated, scheduled and exported by
          the shared reporting engine.
        </p>
      </header>

      {isLoading && <ReportsLoading label="Loading report datasets" />}
      {error && <p className={styles.muted}>Could not load the report catalogue. Please try again.</p>}

      {!isLoading && !error && (
        <Tabs
          activeId={tab}
          onChange={setTab}
          items={[
            {
              id: 'dashboard',
              label: 'Dashboard',
              content: <ReportsDashboardPanel />,
            },
            {
              // One builder. The subject picker inside it replaces what used to be a
              // separate "Report catalogue" tab: an officer chooses what they are
              // reporting on, and the right builder follows from that.
              id: 'build',
              label: 'Build a report',
              content: <ReportBuilderPanel datasets={adminDatasets} canExport={canExport} />,
            },
            {
              id: 'history',
              label: 'History',
              content: (
                <ReportHistoryPanel
                  canManage={canExport}
                  scheduleFootnote="A schedule delivers only to recipients whose own scope covers the report. An administrative report can never be delivered outside the administration console"
                  runsFootnote="Every export is generated and audited by the shared reporting engine. The console adds datasets, not a second pipeline"
                />
              ),
            },          ]}
        />
      )}
    </div>
  )
}
