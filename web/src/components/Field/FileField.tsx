import { useId, useRef, useState } from 'react'
import { FileUp, Paperclip } from 'lucide-react'
import { cn } from '@/lib/utils/cn'
import { Icon } from '@/components/Icon/Icon'
import { FieldShell, fieldMessageId } from './FieldShell'
import styles from './file.module.css'

export interface FileFieldProps {
  label: string
  helper?: string
  error?: string
  required?: boolean
  accept?: string
  multiple?: boolean
  disabled?: boolean
  onFilesSelected?: (files: File[]) => void
}

/**
 * File upload dropzone (DESIGN-SYSTEM.md §5.2). Copy states the SECURITY.md file
 * rules (allowed types/size). Inline errors render from the API envelope.
 */
export function FileField({
  label,
  helper = 'PDF, JPG or PNG · max 25 MB. Files are scanned before storage.',
  error,
  required,
  accept = '.pdf,.jpg,.jpeg,.png',
  multiple = false,
  disabled,
  onFilesSelected,
}: FileFieldProps) {
  const id = useId()
  const inputRef = useRef<HTMLInputElement>(null)
  const [dragging, setDragging] = useState(false)
  const [files, setFiles] = useState<File[]>([])

  function handleFiles(fileList: FileList | null) {
    if (!fileList) return
    const selected = Array.from(fileList)
    setFiles(selected)
    onFilesSelected?.(selected)
  }

  return (
    <FieldShell id={id} label={label} required={required} helper={helper} error={error}>
      <input
        ref={inputRef}
        id={id}
        type="file"
        className={styles.hiddenInput}
        accept={accept}
        multiple={multiple}
        disabled={disabled}
        aria-invalid={error ? true : undefined}
        aria-describedby={helper || error ? fieldMessageId(id) : undefined}
        onChange={(event) => handleFiles(event.target.files)}
      />
      <div
        className={cn(styles.dropzone, dragging && styles.dragging, error && styles.invalid)}
        role="button"
        tabIndex={0}
        onClick={() => inputRef.current?.click()}
        onKeyDown={(event) => {
          if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault()
            inputRef.current?.click()
          }
        }}
        onDragOver={(event) => {
          event.preventDefault()
          setDragging(true)
        }}
        onDragLeave={() => setDragging(false)}
        onDrop={(event) => {
          event.preventDefault()
          setDragging(false)
          handleFiles(event.dataTransfer.files)
        }}
      >
        <Icon icon={FileUp} size={24} />
        <span className={styles.prompt}>Drag &amp; drop or click to browse</span>
        <span className={styles.hint}>{accept.replaceAll('.', '').toUpperCase()}</span>
      </div>
      {files.length > 0 && (
        <ul className={styles.files}>
          {files.map((file) => (
            <li key={file.name} className={styles.fileRow}>
              <Icon icon={Paperclip} size={14} />
              <span>{file.name}</span>
            </li>
          ))}
        </ul>
      )}
    </FieldShell>
  )
}
