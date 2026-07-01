import { useState } from 'react'
import type { ReactNode } from 'react'
import { Plus } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Button } from '@/components/Button/Button'
import { Avatar } from '@/components/Avatar/Avatar'
import { Card, KpiPanel } from '@/components/Card/Card'
import { Tabs } from '@/components/Tabs/Tabs'
import { Breadcrumbs } from '@/components/Breadcrumbs/Breadcrumbs'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column, SortState } from '@/components/DataTable/DataTable'
import { Modal } from '@/components/Modal/Modal'
import { ConfirmDialog } from '@/components/Modal/ConfirmDialog'
import { useToast } from '@/components/Toast/ToastProvider'
import { TextField } from '@/components/Field/TextField'
import { TextareaField } from '@/components/Field/TextareaField'
import { SelectField } from '@/components/Field/SelectField'
import { Checkbox } from '@/components/Field/Checkbox'
import { RadioGroup } from '@/components/Field/RadioGroup'
import { Toggle } from '@/components/Field/Toggle'
import { FileField } from '@/components/Field/FileField'
import styles from './Styleguide.module.css'

function Section({ eyebrow, title, children }: { eyebrow: string; title: string; children: ReactNode }) {
  return (
    <section className={styles.section}>
      <div className={styles.sectionHead}>
        <span className="eyebrow">{eyebrow}</span>
        <h2 className="t-h2">{title}</h2>
      </div>
      {children}
    </section>
  )
}

const COLORS = [
  '--accent',
  '--accent-soft',
  '--surface-mint',
  '--surface-mint-2',
  '--forest',
  '--forest-2',
  '--bg',
  '--ink',
  '--success',
  '--warning',
  '--danger',
  '--info',
]

interface Beneficiary {
  id: string
  name: string
  nin: string
  mda: string
  status: string
  benefits: number
}

const SAMPLE_ROWS: Beneficiary[] = [
  { id: '1', name: 'Amina Bello', nin: '•••• •••• 214', mda: 'Women Affairs', status: 'active', benefits: 3 },
  { id: '2', name: 'Ibrahim Sani', nin: '•••• •••• 887', mda: 'Health', status: 'suspended', benefits: 1 },
  { id: '3', name: 'Halima Yusuf', nin: '•••• •••• 043', mda: 'Education', status: 'flagged', benefits: 0 },
]

export function StyleguidePage() {
  const toast = useToast()
  const [sort, setSort] = useState<SortState>({ key: 'name', direction: 'asc' })
  const [selected, setSelected] = useState<ReadonlySet<string>>(new Set())
  const [modalOpen, setModalOpen] = useState(false)
  const [confirmOpen, setConfirmOpen] = useState(false)

  const columns: Column<Beneficiary>[] = [
    { key: 'name', header: 'Name', sortable: true, render: (r) => r.name },
    { key: 'nin', header: 'NIN', render: (r) => <span className="mono">{r.nin}</span> },
    { key: 'mda', header: 'Owner MDA', render: (r) => r.mda },
    {
      key: 'status',
      header: 'Status',
      render: (r) => (
        <Badge variant={statusVariant(r.status)} dot>
          {r.status}
        </Badge>
      ),
    },
    { key: 'benefits', header: 'Benefits', align: 'right', sortable: true, render: (r) => r.benefits },
  ]

  function toggleRow(id: string) {
    setSelected((current) => {
      const next = new Set(current)
      if (next.has(id)) next.delete(id)
      else next.add(id)
      return next
    })
  }

  return (
    <div className={styles.page}>
      <div>
        <span className="eyebrow">System · Style guide</span>
        <h1 className="t-h1">SP-MIS design system</h1>
      </div>

      <Section eyebrow="Foundations" title="Color tokens">
        <div className={styles.swatches}>
          {COLORS.map((token) => (
            <div key={token} className={styles.swatch}>
              <div className={styles.swatchColor} style={{ backgroundColor: `var(${token})` }} />
              <div className={styles.swatchMeta}>{token}</div>
            </div>
          ))}
        </div>
      </Section>

      <Section eyebrow="Foundations" title="Typography">
        <div className={styles.stack} style={{ maxWidth: 'none' }}>
          <div className={styles.typeRow}>
            <span className={styles.typeTag}>Display</span>
            <span className="t-display">Register once</span>
          </div>
          <div className={styles.typeRow}>
            <span className={styles.typeTag}>H1</span>
            <span className="t-h1">Beneficiary registry</span>
          </div>
          <div className={styles.typeRow}>
            <span className={styles.typeTag}>H2</span>
            <span className="t-h2">Section title</span>
          </div>
          <div className={styles.typeRow}>
            <span className={styles.typeTag}>H3</span>
            <span className="t-h3">Card title</span>
          </div>
          <div className={styles.typeRow}>
            <span className={styles.typeTag}>Body</span>
            <span>Default body copy in Hanken Grotesk.</span>
          </div>
          <div className={styles.typeRow}>
            <span className={styles.typeTag}>Eyebrow</span>
            <span className="eyebrow">01 · Registry</span>
          </div>
        </div>
      </Section>

      <Section eyebrow="Components" title="Buttons">
        <div className={styles.row}>
          <Button variant="primary">Primary</Button>
          <Button variant="secondary">Secondary</Button>
          <Button variant="tertiary">Tertiary</Button>
          <Button variant="danger">Danger</Button>
          <Button variant="link" rightIcon={Plus}>
            Link action
          </Button>
        </div>
        <div className={styles.row}>
          <Button size="sm">Small</Button>
          <Button size="md">Medium</Button>
          <Button size="lg">Large</Button>
          <Button leftIcon={Plus}>With icon</Button>
          <Button loading>Loading</Button>
          <Button disabled>Disabled</Button>
        </div>
      </Section>

      <Section eyebrow="Components" title="Badges & status map">
        <div className={styles.row}>
          <Badge variant="accent">accent</Badge>
          <Badge variant="neutral">neutral</Badge>
          <Badge variant="dark">dark</Badge>
          <Badge variant="outline">outline</Badge>
          <Badge variant="success" dot>
            success
          </Badge>
          <Badge variant="warning" dot>
            warning
          </Badge>
          <Badge variant="danger" dot>
            danger
          </Badge>
          <Badge variant="info" dot>
            info
          </Badge>
          <Badge variant="dark" mono>
            EXACT
          </Badge>
        </div>
      </Section>

      <Section eyebrow="Components" title="Form fields">
        <div className={styles.grid}>
          <div className={styles.stack}>
            <TextField label="Full name" placeholder="e.g. Amina Bello" required />
            <TextField label="Email" type="email" helper="We never share this." />
            <TextField label="NIN" error="NIN must be 11 digits." defaultValue="123" />
            <TextField label="Disabled" disabled value="Read only" />
            <SelectField
              label="Owner MDA"
              placeholder="Select an MDA"
              options={[
                { value: 'health', label: 'Ministry of Health' },
                { value: 'women', label: 'Women Affairs' },
              ]}
            />
            <TextField label="Date of birth" type="date" />
          </div>
          <div className={styles.stack}>
            <TextareaField label="Notes" placeholder="Add context…" />
            <Checkbox label="Consent captured" defaultChecked />
            <RadioGroup
              label="Registration source"
              name="source"
              defaultValue="kobo"
              options={[
                { value: 'kobo', label: 'Kobo Collect' },
                { value: 'excel', label: 'Excel upload' },
                { value: 'api', label: 'REST API' },
              ]}
            />
            <Toggle label="Human review enabled" defaultChecked />
            <FileField label="Supporting document" />
          </div>
        </div>
      </Section>

      <Section eyebrow="Components" title="Data table">
        <DataTable
          caption="Sample beneficiaries"
          columns={columns}
          rows={SAMPLE_ROWS}
          getRowId={(r) => r.id}
          sort={sort}
          onSortChange={(key) =>
            setSort((s) => ({ key, direction: s.key === key && s.direction === 'asc' ? 'desc' : 'asc' }))
          }
          selectedIds={selected}
          onToggleRow={toggleRow}
          pagination={{ page: 1, pageCount: 3, onPageChange: () => undefined }}
        />
        <div className={styles.grid}>
          <DataTable caption="Loading" columns={columns} rows={[]} getRowId={(r) => r.id} loading />
          <DataTable
            caption="Empty"
            columns={columns}
            rows={[]}
            getRowId={(r) => r.id}
            emptyTitle="No beneficiaries yet"
            emptyAction={<Button size="sm" leftIcon={Plus}>Register beneficiary</Button>}
          />
        </div>
      </Section>

      <Section eyebrow="Components" title="Cards, KPI, tabs, breadcrumbs, avatar">
        <div className={styles.grid}>
          <Card eyebrow="Programme" title="Conditional Cash">
            <p className="t-muted">Card surface with a title and eyebrow.</p>
          </Card>
          <Card variant="mint" title="Mint panel">
            <p>Secondary surface.</p>
          </Card>
          <KpiPanel label="Beneficiaries" value="128,540" hint="+3.2% this month" />
        </div>
        <Breadcrumbs items={[{ label: 'SP-MIS', to: '/' }, { label: 'Registry', to: '/' }, { label: 'Amina Bello' }]} />
        <Tabs
          items={[
            { id: 'profile', label: 'Profile', content: <p className="t-muted">Profile tab content.</p> },
            { id: 'benefits', label: 'Benefits', content: <p className="t-muted">Benefits tab content.</p> },
            { id: 'audit', label: 'Audit', content: <p className="t-muted">Audit tab content.</p> },
          ]}
        />
        <div className={styles.row}>
          <Avatar name="Amina Bello" size="sm" />
          <Avatar name="Ibrahim Sani" size="md" />
          <Avatar name="Halima Yusuf" size="lg" />
        </div>
      </Section>

      <Section eyebrow="Components" title="Overlays & toasts">
        <div className={styles.row}>
          <Button variant="secondary" onClick={() => setModalOpen(true)}>
            Open modal
          </Button>
          <Button variant="danger" onClick={() => setConfirmOpen(true)}>
            Danger confirm
          </Button>
          <Button variant="tertiary" onClick={() => toast.success('Beneficiary registered', 'Amina Bello was added.')}>
            Success toast
          </Button>
          <Button variant="tertiary" onClick={() => toast.error('Import failed', '3 rows had errors.')}>
            Error toast
          </Button>
          <Button variant="tertiary" onClick={() => toast.info('Sync started')}>
            Info toast
          </Button>
        </div>

        <Modal
          open={modalOpen}
          onClose={() => setModalOpen(false)}
          title="Edit MDA"
          footer={
            <>
              <Button variant="tertiary" onClick={() => setModalOpen(false)}>
                Cancel
              </Button>
              <Button onClick={() => setModalOpen(false)}>Save changes</Button>
            </>
          }
        >
          <div className={styles.stack} style={{ maxWidth: 'none' }}>
            <TextField label="Name" defaultValue="Ministry of Health" />
            <TextField label="Contact email" type="email" defaultValue="health@example.test" />
          </div>
        </Modal>

        <ConfirmDialog
          open={confirmOpen}
          danger
          title="Deactivate MDA?"
          confirmLabel="Deactivate"
          onCancel={() => setConfirmOpen(false)}
          onConfirm={() => {
            setConfirmOpen(false)
            toast.warning('MDA deactivated')
          }}
        >
          This will deactivate <strong>Ministry of Health</strong> and hide its records from new
          assignments. This can be reversed by reactivating the MDA.
        </ConfirmDialog>
      </Section>
    </div>
  )
}
