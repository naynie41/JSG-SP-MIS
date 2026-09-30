import { useMemo, useState } from 'react'
import { CalendarRange, FileText } from 'lucide-react'
import { Button, Card, Icon, SelectField } from '@/components'
import type { SelectOption } from '@/components'
import { useMonthlyProjectReport } from '@/features/reports/hooks'
import { useWorkspaceIdentity } from './workspaceIdentity'
import styles from './mda.module.css'

/**
 * Generate the monthly project report (FR-RPT-12b).
 *
 * The month list starts at the last COMPLETE month and runs backwards. The month in
 * progress is deliberately absent rather than disabled: a part-month sits beside a full
 * one in the same report and makes every project look like it is collapsing, and a
 * greyed-out option invites the question "why not?" on every visit.
 *
 * There is no format control. The report is charts and a table with a traffic light per
 * project, and a CSV of that is a grid of numbers with none of the reading — the same
 * reasoning that fixes "People in the register" to PDF.
 */
export function MdaMonthlyReportCard() {
  const identity = useWorkspaceIdentity()
  const generate = useMonthlyProjectReport()

  const months = useMonthOptions()
  const [selected, setSelected] = useState(() => months[0]?.value ?? '')

  const submit = () => {
    const [year, month] = selected.split('-').map(Number)
    if (!year || !month) return
    generate.mutate({ year, month })
  }

  return (
    <div className={styles.section}>
      <Card
        titleAs="h3"
        title="Monthly project report"
        eyebrow="How your projects are doing"
      >
        <p className={styles.muted}>
          One PDF covering a single month: what each of your {identity.orgPossessive} projects delivered that
          month, how that compares with the month before, where each one stands against its target overall,
          and which ones need looking at.
        </p>

        <div className={styles.rowActions} style={{ justifyContent: 'flex-start', alignItems: 'flex-end' }}>
          <SelectField
            label="Month"
            value={selected}
            options={months}
            onChange={(event) => setSelected(event.target.value)}
          />
          <Button
            onClick={submit}
            loading={generate.isPending}
            disabled={selected === ''}
            leftIcon={FileText}
          >
            Generate report
          </Button>
        </div>

        <p className={styles.queueNote}>
          <Icon icon={CalendarRange} size={14} aria-hidden="true" />{' '}
          The report covers whole months only, so the current month is not listed until it ends. The PDF
          appears under History as soon as it is ready.
        </p>
      </Card>
    </div>
  )
}

/**
 * The last complete month, then the eleven before it.
 *
 * Computed from the browser clock purely to populate the list; the server validates the
 * month it is given and resolves its own default, so a wrong clock cannot produce a
 * report for a month that has not finished.
 */
function useMonthOptions(): SelectOption[] {
  return useMemo(() => {
    const out: SelectOption[] = []
    const cursor = new Date()
    cursor.setDate(1)
    cursor.setMonth(cursor.getMonth() - 1)

    for (let i = 0; i < 12; i++) {
      const year = cursor.getFullYear()
      const month = cursor.getMonth() + 1
      out.push({
        value: `${year}-${month}`,
        label: cursor.toLocaleDateString(undefined, { month: 'long', year: 'numeric' }),
      })
      cursor.setMonth(cursor.getMonth() - 1)
    }

    return out
  }, [])
}
