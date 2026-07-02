import { useState } from 'react'
import { Search } from 'lucide-react'
import { Modal } from '@/components/Modal/Modal'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { TextField } from '@/components/Field/TextField'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { ApiError } from '@/types/api'
import { beneficiaryApi } from './api'
import { REGISTRATION_SOURCE_LABELS, titleCase } from './constants'
import type { RevealMatch } from './types'
import formStyles from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

interface LookupModalProps {
  open: boolean
  onClose: () => void
}

/**
 * Cross-MDA lookup/serve path (PRD FR-OWN-03). Exact-identifier search that
 * returns ONLY the reveal fields — never the full profile — so an MDA can tell a
 * beneficiary already exists without gaining ownership or edit access. This is the
 * seam Phase 3's request-to-serve will build on.
 */
export function LookupModal({ open, onClose }: LookupModalProps) {
  const [nin, setNin] = useState('')
  const [bvn, setBvn] = useState('')
  const [phone, setPhone] = useState('')
  const [matches, setMatches] = useState<RevealMatch[] | null>(null)
  const [error, setError] = useState<string | null>(null)
  const [searching, setSearching] = useState(false)

  const canSearch = [nin, bvn, phone].some((v) => v.trim() !== '')

  async function runSearch() {
    setError(null)
    setSearching(true)
    try {
      const result = await beneficiaryApi.lookup({
        nin: nin.trim() || undefined,
        bvn: bvn.trim() || undefined,
        phone: phone.trim() || undefined,
      })
      setMatches(result)
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Lookup failed. Please try again.')
      setMatches(null)
    } finally {
      setSearching(false)
    }
  }

  const columns: Column<RevealMatch>[] = [
    { key: 'name', header: 'Name', render: (m) => m.full_name },
    { key: 'owner', header: 'Owner MDA', render: (m) => m.owner_mda?.name ?? '—' },
    {
      key: 'source',
      header: 'Source',
      render: (m) => REGISTRATION_SOURCE_LABELS[m.registration_source] ?? m.registration_source,
    },
    { key: 'lga', header: 'LGA / Ward', render: (m) => `${m.lga ? titleCase(m.lga) : '—'} · ${m.ward ?? '—'}` },
    { key: 'status', header: 'Status', render: (m) => <Badge variant={statusVariant(m.status)} dot>{m.status}</Badge> },
  ]

  return (
    <Modal
      open={open}
      onClose={onClose}
      title="Cross-MDA lookup"
      footer={
        <Button variant="tertiary" onClick={onClose}>
          Close
        </Button>
      }
    >
      <div className={styles.stack}>
        <p className={styles.note}>
          Search by an exact identifier to see whether a beneficiary already exists in another MDA.
          Only reveal fields are shown — never the full profile.
        </p>
        {error && (
          <p className={formStyles.alert} role="alert">
            {error}
          </p>
        )}
        <div className={formStyles.grid2}>
          <TextField label="NIN" value={nin} onChange={(e) => setNin(e.target.value)} />
          <TextField label="BVN" value={bvn} onChange={(e) => setBvn(e.target.value)} />
        </div>
        <TextField label="Phone" value={phone} onChange={(e) => setPhone(e.target.value)} />
        <div>
          <Button leftIcon={Search} onClick={runSearch} loading={searching} disabled={!canSearch}>
            Search
          </Button>
        </div>

        {matches !== null && (
          <DataTable
            caption="Lookup matches"
            columns={columns}
            rows={matches}
            getRowId={(m) => m.id}
            emptyTitle="No matching beneficiary found"
          />
        )}
      </div>
    </Modal>
  )
}
