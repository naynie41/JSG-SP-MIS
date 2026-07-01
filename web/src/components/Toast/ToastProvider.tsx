import { createContext, useCallback, useContext, useMemo, useRef, useState } from 'react'
import type { ReactNode } from 'react'
import { AlertTriangle, CheckCircle2, Info, X, XCircle } from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { cn } from '@/lib/utils/cn'
import { Icon } from '@/components/Icon/Icon'
import styles from './Toast.module.css'

export type ToastVariant = 'success' | 'warning' | 'danger' | 'info'

export interface ToastOptions {
  title: string
  message?: string
  variant?: ToastVariant
  /** ms before auto-dismiss; default 5000. */
  duration?: number
}

interface ToastRecord extends Required<Omit<ToastOptions, 'message'>> {
  id: number
  message?: string
}

interface ToastContextValue {
  toast: (options: ToastOptions) => void
  success: (title: string, message?: string) => void
  error: (title: string, message?: string) => void
  info: (title: string, message?: string) => void
  warning: (title: string, message?: string) => void
}

const ToastContext = createContext<ToastContextValue | null>(null)

const ICONS: Record<ToastVariant, LucideIcon> = {
  success: CheckCircle2,
  warning: AlertTriangle,
  danger: XCircle,
  info: Info,
}

export function ToastProvider({ children }: { children: ReactNode }) {
  const [toasts, setToasts] = useState<ToastRecord[]>([])
  const nextId = useRef(0)

  const dismiss = useCallback((id: number) => {
    setToasts((current) => current.filter((toast) => toast.id !== id))
  }, [])

  const toast = useCallback(
    ({ title, message, variant = 'info', duration = 5000 }: ToastOptions) => {
      const id = nextId.current++
      setToasts((current) => [...current, { id, title, message, variant, duration }])
      if (duration > 0) {
        window.setTimeout(() => dismiss(id), duration)
      }
    },
    [dismiss],
  )

  const value = useMemo<ToastContextValue>(
    () => ({
      toast,
      success: (title, message) => toast({ title, message, variant: 'success' }),
      error: (title, message) => toast({ title, message, variant: 'danger' }),
      info: (title, message) => toast({ title, message, variant: 'info' }),
      warning: (title, message) => toast({ title, message, variant: 'warning' }),
    }),
    [toast],
  )

  return (
    <ToastContext.Provider value={value}>
      {children}
      <div className={styles.region} aria-live="polite" aria-atomic="false">
        {toasts.map((item) => (
          <div key={item.id} className={cn(styles.toast, styles[item.variant])} role="status">
            <span className={styles.icon}>
              <Icon icon={ICONS[item.variant]} size={18} />
            </span>
            <div className={styles.content}>
              <div className={styles.title}>{item.title}</div>
              {item.message && <div className={styles.message}>{item.message}</div>}
            </div>
            <button
              type="button"
              className={styles.dismiss}
              onClick={() => dismiss(item.id)}
              aria-label="Dismiss notification"
            >
              <Icon icon={X} size={16} />
            </button>
          </div>
        ))}
      </div>
    </ToastContext.Provider>
  )
}

// eslint-disable-next-line react-refresh/only-export-components
export function useToast(): ToastContextValue {
  const context = useContext(ToastContext)
  if (!context) {
    throw new Error('useToast must be used within a ToastProvider')
  }
  return context
}
