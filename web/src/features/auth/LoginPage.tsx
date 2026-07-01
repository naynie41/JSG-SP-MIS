import { useState } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import { useLocation, useNavigate } from 'react-router-dom'
import { ArrowRight, ShieldCheck } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { TextField } from '@/components/Field/TextField'
import { authApi } from '@/lib/api/authApi'
import type { MfaEnrollment } from '@/lib/api/authApi'
import { useAuth } from '@/lib/auth/AuthProvider'
import { ApiError } from '@/types/api'
import { isMfaRequired, isMfaSetupRequired, isTokenResponse } from '@/types/auth'
import styles from './Login.module.css'

const credentialsSchema = z.object({
  email: z.string().min(1, 'Email is required').email('Enter a valid email address'),
  password: z.string().min(1, 'Password is required'),
})
type Credentials = z.infer<typeof credentialsSchema>

type Step = 'credentials' | 'challenge' | 'setup'

export function LoginPage() {
  const { completeSession } = useAuth()
  const navigate = useNavigate()
  const location = useLocation()
  const redirectTo = (location.state as { from?: string } | null)?.from ?? '/'

  const [step, setStep] = useState<Step>('credentials')
  const [mfaToken, setMfaToken] = useState('')
  const [enrollment, setEnrollment] = useState<MfaEnrollment | null>(null)
  const [code, setCode] = useState('')
  const [useRecovery, setUseRecovery] = useState(false)
  const [formError, setFormError] = useState<string | null>(null)
  const [mfaBusy, setMfaBusy] = useState(false)

  const {
    register,
    handleSubmit,
    setError,
    formState: { errors, isSubmitting },
  } = useForm<Credentials>({ resolver: zodResolver(credentialsSchema) })

  function finish(response: Parameters<typeof completeSession>[0]) {
    completeSession(response)
    navigate(redirectTo, { replace: true })
  }

  function handleApiError(error: unknown) {
    if (error instanceof ApiError) {
      const fieldErrors = error.fieldErrors()
      if (Object.keys(fieldErrors).length > 0) {
        for (const [field, message] of Object.entries(fieldErrors)) {
          if (field === 'email' || field === 'password') {
            setError(field, { message })
          }
        }
      }
      setFormError(error.details.length ? null : error.message)
    } else {
      setFormError('Something went wrong. Please try again.')
    }
  }

  const onSubmitCredentials = handleSubmit(async ({ email, password }) => {
    setFormError(null)
    try {
      const response = await authApi.login(email, password)
      if (isTokenResponse(response)) {
        finish(response)
      } else if (isMfaRequired(response)) {
        setMfaToken(response.mfa_token)
        setStep('challenge')
      } else if (isMfaSetupRequired(response)) {
        setMfaToken(response.mfa_token)
        const details = await authApi.mfaEnroll(response.mfa_token)
        setEnrollment(details)
        setStep('setup')
      }
    } catch (error) {
      handleApiError(error)
    }
  })

  async function onSubmitChallenge(event: React.FormEvent) {
    event.preventDefault()
    setFormError(null)
    setMfaBusy(true)
    try {
      const response = await authApi.mfaChallenge(mfaToken, code.trim())
      finish(response)
    } catch (error) {
      handleApiError(error)
    } finally {
      setMfaBusy(false)
    }
  }

  async function onSubmitSetup(event: React.FormEvent) {
    event.preventDefault()
    setFormError(null)
    setMfaBusy(true)
    try {
      const response = await authApi.mfaVerify(mfaToken, code.trim())
      finish(response)
    } catch (error) {
      handleApiError(error)
    } finally {
      setMfaBusy(false)
    }
  }

  return (
    <div className={styles.page}>
      <aside className={styles.brand}>
        <div className={styles.brandTop}>
          <span className={styles.brandMark} aria-hidden="true">
            SP
          </span>
          <span className={styles.brandWord}>SP-MIS</span>
        </div>
        <h1 className={styles.headline}>
          Coordinated social protection for <span className={styles.accentText}>Jigawa State</span>.
        </h1>
        <p className={styles.brandFoot}>
          One secure platform for MDAs to register, coordinate, and track social-protection
          beneficiaries — without duplication, without losing ownership.
        </p>
      </aside>

      <main className={styles.formSide}>
        {step === 'credentials' && (
          <form className={styles.form} onSubmit={onSubmitCredentials} noValidate>
            <div className={styles.heading}>
              <span className="eyebrow">01 · Sign in</span>
              <h2 className={styles.title}>Welcome back</h2>
              <p className={styles.subtitle}>Sign in to your SP-MIS account.</p>
            </div>

            {formError && (
              <p className={styles.alert} role="alert">
                {formError}
              </p>
            )}

            <div className={styles.fields}>
              <TextField
                label="Email"
                type="email"
                autoComplete="username"
                autoFocus
                error={errors.email?.message}
                {...register('email')}
              />
              <TextField
                label="Password"
                type="password"
                autoComplete="current-password"
                error={errors.password?.message}
                {...register('password')}
              />
            </div>

            <Button type="submit" size="lg" fullWidth loading={isSubmitting} rightIcon={ArrowRight}>
              Sign in
            </Button>
          </form>
        )}

        {step === 'challenge' && (
          <form className={styles.form} onSubmit={onSubmitChallenge} noValidate>
            <div className={styles.heading}>
              <span className="eyebrow">02 · Verification</span>
              <h2 className={styles.title}>Two-factor check</h2>
              <p className={styles.subtitle}>
                {useRecovery
                  ? 'Enter one of your saved recovery codes.'
                  : 'Enter the 6-digit code from your authenticator app.'}
              </p>
            </div>

            {formError && (
              <p className={styles.alert} role="alert">
                {formError}
              </p>
            )}

            <TextField
              label={useRecovery ? 'Recovery code' : 'Authentication code'}
              inputMode={useRecovery ? 'text' : 'numeric'}
              autoComplete="one-time-code"
              autoFocus
              value={code}
              onChange={(event) => setCode(event.target.value)}
            />

            <Button type="submit" size="lg" fullWidth loading={mfaBusy} rightIcon={ShieldCheck}>
              Verify &amp; continue
            </Button>

            <div className={styles.altAction}>
              <Button
                type="button"
                variant="link"
                onClick={() => {
                  setUseRecovery((value) => !value)
                  setCode('')
                  setFormError(null)
                }}
              >
                {useRecovery ? 'Use authenticator code instead' : 'Use a recovery code'}
              </Button>
            </div>
          </form>
        )}

        {step === 'setup' && enrollment && (
          <form className={styles.form} onSubmit={onSubmitSetup} noValidate>
            <div className={styles.heading}>
              <span className="eyebrow">02 · Set up MFA</span>
              <h2 className={styles.title}>Secure your account</h2>
              <p className={styles.subtitle}>
                Your role requires two-factor authentication. Add this key to your authenticator app,
                then enter the code it shows.
              </p>
            </div>

            {formError && (
              <p className={styles.alert} role="alert">
                {formError}
              </p>
            )}

            <div className={styles.secretBox}>
              <span className={styles.secretLabel}>Setup key</span>
              <span className={styles.secretValue}>{enrollment.secret}</span>
            </div>

            <div className={styles.secretBox}>
              <span className={styles.secretLabel}>Recovery codes — save these now</span>
              <div className={styles.recoveryList}>
                {enrollment.recovery_codes.map((rc) => (
                  <span key={rc}>{rc}</span>
                ))}
              </div>
            </div>

            <TextField
              label="Authentication code"
              inputMode="numeric"
              autoComplete="one-time-code"
              autoFocus
              value={code}
              onChange={(event) => setCode(event.target.value)}
            />

            <Button type="submit" size="lg" fullWidth loading={mfaBusy} rightIcon={ShieldCheck}>
              Enable &amp; continue
            </Button>
          </form>
        )}
      </main>
    </div>
  )
}
