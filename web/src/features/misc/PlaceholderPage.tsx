import { Card } from '@/components/Card/Card'

export interface PlaceholderPageProps {
  eyebrow: string
  title: string
}

/**
 * Stand-in for admin/feature screens delivered in later steps. The route,
 * navigation entry, permission gating and layout are real; the table/forms land
 * with their feature.
 */
export function PlaceholderPage({ eyebrow, title }: PlaceholderPageProps) {
  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 'var(--space-5)' }}>
      <div style={{ display: 'flex', flexDirection: 'column', gap: 'var(--space-2)' }}>
        <span className="eyebrow">{eyebrow}</span>
        <h1 className="t-h1">{title}</h1>
      </div>
      <Card>
        <p className="t-muted">
          This screen composes the shared components (table, forms, dialogs) and is delivered with its
          feature step. The API endpoints and permissions are already in place.
        </p>
      </Card>
    </div>
  )
}
