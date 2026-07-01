import { useNavigate } from 'react-router-dom'
import { Button } from '@/components/Button/Button'

export function NotFoundPage() {
  const navigate = useNavigate()
  return (
    <div style={{ minHeight: '60vh', display: 'grid', placeItems: 'center', textAlign: 'center' }}>
      <div style={{ display: 'flex', flexDirection: 'column', gap: 'var(--space-3)', alignItems: 'center' }}>
        <span className="eyebrow">404</span>
        <h1 className="t-h1">Page not found</h1>
        <p className="t-muted">The page you were looking for doesn’t exist.</p>
        <Button onClick={() => navigate('/')}>Back to dashboard</Button>
      </div>
    </div>
  )
}
