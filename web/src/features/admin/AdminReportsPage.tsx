import { useState } from 'react'
import { Tabs } from '@/components/Tabs/Tabs'
import { useAuth } from '@/lib/auth/AuthProvider'
import { useReportDatasets } from '@/features/reports/hooks'
import {
  BuilderPanel,
  CataloguePanel,
  ReportsLoading,
  RunsPanel,
  SchedulesPanel,
} from '@/features/reports/ReportPanels'
import { SegmentBuilderPanel } from '@/features/reports/SegmentBuilderPanel'
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
  const [tab, setTab] = useState('catalogue')
  const [seedDataset, setSeedDataset] = useState<string | undefined>(undefined)

  const datasets = data ?? []
  const adminDatasets = datasets.filter((d) => d.admin)

  function buildFrom(key: string) {
    setSeedDataset(key)
    setTab('builder')
  }

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>Administration console</span>
        <h1 className={styles.pageTitle}>Reports</h1>
        <p className={styles.lead}>
          Administrative reporting over the platform's own records — users and access, organizations, the programme
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
              id: 'catalogue',
              label: 'Report catalogue',
              content: (
                <CataloguePanel
                  datasets={adminDatasets}
                  onBuild={buildFrom}
                  footnote="Every report is an aggregate — counts and sums over administrative attributes, never a personal record"
                />
              ),
            },
            {
              id: 'builder',
              label: 'Build & export',
              content: canExport ? (
                <BuilderPanel datasets={adminDatasets} initialDataset={seedDataset} />
              ) : (
                <p className={styles.muted}>
                  Generating and exporting reports needs the reporting export permission.
                </p>
              ),
            },
            {
              // The registry segment builder. Distinct from "Build & export" above: that
              // aggregates a whitelisted dataset, this filters the PEOPLE and — for a
              // role entitled to them — lists them. The server decides which of those
              // two a given caller actually gets.
              id: 'segments',
              label: 'Segment builder',
              content: <SegmentBuilderPanel />,
            },
            {
              id: 'schedules',
              label: 'Scheduled reports',
              content: (
                <SchedulesPanel
                  canManage={canExport}
                  footnote="A schedule delivers only to recipients whose own scope covers the report — an administrative report can never be delivered outside the administration console"
                />
              ),
            },
            {
              id: 'runs',
              label: 'Recent exports',
              content: (
                <RunsPanel footnote="Every export is generated and audited by the shared reporting engine — the console adds datasets, not a second pipeline" />
              ),
            },
          ]}
        />
      )}
    </div>
  )
}
