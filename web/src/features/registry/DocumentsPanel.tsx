import { useState } from 'react'
import { Download, Trash2, Upload } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Modal } from '@/components/Modal/Modal'
import { SelectField } from '@/components/Field/SelectField'
import { FileField } from '@/components/Field/FileField'
import { Menu } from '@/components/Menu/Menu'
import type { MenuAction } from '@/components/Menu/Menu'
import { ConfirmDialog } from '@/components/Modal/ConfirmDialog'
import { ApiError } from '@/types/api'
import { documentApi } from './api'
import { DOCUMENT_TYPE_OPTIONS } from './constants'
import { useDeleteDocument, useDocuments, useUploadDocument } from './hooks'
import type { BeneficiaryDocument } from './types'
import formStyles from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

interface DocumentsPanelProps {
  beneficiaryId: string
  canManage: boolean
}

function formatSize(bytes: number): string {
  if (bytes < 1024) return `${bytes} B`
  if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(0)} KB`
  return `${(bytes / (1024 * 1024)).toFixed(1)} MB`
}

/** Supporting documents (FR-REG-07): validated upload, download, delete. */
export function DocumentsPanel({ beneficiaryId, canManage }: DocumentsPanelProps) {
  const { data: documents = [], isLoading } = useDocuments(beneficiaryId)
  const uploadDocument = useUploadDocument(beneficiaryId)
  const deleteDocument = useDeleteDocument(beneficiaryId)

  const [uploadOpen, setUploadOpen] = useState(false)
  const [documentType, setDocumentType] = useState('national_id')
  const [file, setFile] = useState<File | null>(null)
  const [uploadError, setUploadError] = useState<string | null>(null)
  const [toDelete, setToDelete] = useState<BeneficiaryDocument | null>(null)

  async function submitUpload() {
    if (!file) {
      setUploadError('Choose a file to upload.')
      return
    }
    setUploadError(null)
    try {
      await uploadDocument.mutateAsync({ file, documentType })
      setUploadOpen(false)
      setFile(null)
    } catch (error) {
      setUploadError(error instanceof ApiError ? error.message : 'Upload failed. Please try again.')
    }
  }

  const columns: Column<BeneficiaryDocument>[] = [
    {
      key: 'name',
      header: 'Document',
      render: (d) => (
        <div className={styles.cellStack}>
          <span>{d.original_filename}</span>
          <span className={styles.cellSub}>
            {DOCUMENT_TYPE_OPTIONS.find((o) => o.value === d.document_type)?.label ?? d.document_type}
          </span>
        </div>
      ),
    },
    { key: 'size', header: 'Size', render: (d) => <span className={styles.mono}>{formatSize(d.size_bytes)}</span> },
    { key: 'added', header: 'Added', render: (d) => <span className={styles.mono}>{d.created_at?.slice(0, 10) ?? '—'}</span> },
    {
      key: 'actions',
      header: '',
      align: 'right',
      render: (d) => {
        const actions: MenuAction[] = [
          { label: 'Download', icon: Download, onSelect: () => void documentApi.download(d) },
        ]
        if (canManage) {
          actions.push({ label: 'Delete', icon: Trash2, danger: true, onSelect: () => setToDelete(d) })
        }
        return <Menu label={`Actions for ${d.original_filename}`} actions={actions} />
      },
    },
  ]

  return (
    <div>
      {canManage && (
        <div className={formStyles.pageHead}>
          <p className={styles.note}>Attach supporting documents (PDF, JPG or PNG · max 5 MB).</p>
          <Button size="sm" leftIcon={Upload} onClick={() => setUploadOpen(true)}>
            Upload document
          </Button>
        </div>
      )}

      <DataTable
        caption="Documents"
        columns={columns}
        rows={documents}
        getRowId={(d) => d.id}
        loading={isLoading}
        emptyTitle="No documents attached"
      />

      <Modal
        open={uploadOpen}
        onClose={() => setUploadOpen(false)}
        title="Upload document"
        footer={
          <>
            <Button variant="tertiary" onClick={() => setUploadOpen(false)} disabled={uploadDocument.isPending}>
              Cancel
            </Button>
            <Button onClick={submitUpload} loading={uploadDocument.isPending}>
              Upload
            </Button>
          </>
        }
      >
        <div className={formStyles.form}>
          {uploadError && (
            <p className={formStyles.alert} role="alert">
              {uploadError}
            </p>
          )}
          <SelectField
            label="Document type"
            options={DOCUMENT_TYPE_OPTIONS}
            value={documentType}
            onChange={(e) => setDocumentType(e.target.value)}
          />
          <FileField
            label="File"
            required
            helper="PDF, JPG or PNG · max 5 MB. Files are stored privately and access-audited."
            onFilesSelected={(files) => setFile(files[0] ?? null)}
          />
        </div>
      </Modal>

      <ConfirmDialog
        open={toDelete !== null}
        danger
        title="Delete document?"
        confirmLabel="Delete"
        loading={deleteDocument.isPending}
        onCancel={() => setToDelete(null)}
        onConfirm={async () => {
          if (!toDelete) return
          await deleteDocument.mutateAsync(toDelete.id)
          setToDelete(null)
        }}
      >
        This will remove <strong>{toDelete?.original_filename}</strong>.
      </ConfirmDialog>
    </div>
  )
}
