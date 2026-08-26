import { AlertTriangle } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import styles from './mda.module.css'

/**
 * The console's shared loading and failure states.
 *
 * These existed in four vocabularies and, on six of the eight pages, not at all — so a
 * failed request rendered as an EMPTY RESULT. On the Overview that painted the green
 * "nothing overdue or unresolved in your MDA" panel; on the duplicate queue it read
 * "No exact matches awaiting a decision". A network error that looks like a cleared
 * queue is the most damaging thing this console can do: it tells an officer their work
 * is done when nobody knows whether it is (PRODUCT.md principle 5).
 */
export function MdaLoading({ label }: { label: string }) {
  return (
    <div className={styles.pageLoading}>
      <Spinner size={26} label={label} />
    </div>
  )
}

interface MdaLoadErrorProps {
  /** What could not be loaded, in the officer's words — "your programmes", not "GET /programmes". */
  subject: string
  onRetry?: () => void
}

export function MdaLoadError({ subject, onRetry }: MdaLoadErrorProps) {
  return (
    <Card>
      <div className={styles.loadError} role="alert">
        <Icon icon={AlertTriangle} size={18} />
        <div>
          <p className={styles.loadErrorTitle}>Could not load {subject}.</p>
          <p className={styles.loadErrorNote}>
            This is a connection or server problem, not an empty result — the figures on this
            page are not showing what your MDA has.
          </p>
        </div>
        {onRetry && (
          <Button size="sm" variant="secondary" onClick={onRetry}>
            Try again
          </Button>
        )}
      </div>
    </Card>
  )
}

/** Permission refusals, so every guard reads the same and names the next step. */
export function MdaForbidden({ what }: { what: string }) {
  return (
    <Card>
      <p className={styles.forbidden}>You do not have permission to view {what}.</p>
      <p className={styles.forbiddenNote}>
        Your MDA administrator or a System Administrator can grant it.
      </p>
    </Card>
  )
}
