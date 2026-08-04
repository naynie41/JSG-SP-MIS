import { useEffect, useMemo, useState } from 'react'
import { Lock, Save, Send } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { Checkbox } from '@/components/Field/Checkbox'
import { SelectField } from '@/components/Field/SelectField'
import { TextField } from '@/components/Field/TextField'
import { TextareaField } from '@/components/Field/TextareaField'
import { Spinner } from '@/components/Spinner/Spinner'
import { Tabs } from '@/components/Tabs/Tabs'
import { useAuth } from '@/lib/auth/AuthProvider'
import { usePermissionMatrix, useUpdateRolePermissions } from '@/features/access/hooks'
import type { MatrixPermission, MatrixRole } from '@/features/access/types'
import { useAdminSettings, useBroadcast } from './hooks'
import type { SettingRow } from './types'
import styles from './admin.module.css'

function Loading({ label }: { label: string }) {
  return (
    <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
      <Spinner size={24} label={label} />
    </div>
  )
}

/** A read-only list of effective values, each naming the key that sets it. */
function ConfigList({ rows }: { rows: SettingRow[] }) {
  return (
    <dl className={styles.configList}>
      {rows.map((row) => (
        <div key={row.label} className={styles.configRow}>
          <dt className={styles.configLabel}>{row.label}</dt>
          <dd className={styles.configValue}>
            {row.value}
            <span className={styles.configSource}>{row.source}</span>
          </dd>
        </div>
      ))}
    </dl>
  )
}

const READ_ONLY_NOTE =
  'Read-only. These are the values in force for this deployment — change them where they are set, then redeploy. The console keeps no settings of its own, so it can never disagree with the running configuration.'

/* --------------------------------------------------------------- general */

function GeneralPanel({ rows }: { rows: SettingRow[] }) {
  return (
    <div className={styles.page}>
      <Card title="Deployment" eyebrow="Effective configuration">
        <ConfigList rows={rows} />
        <p className={styles.footnote}>{READ_ONLY_NOTE}</p>
      </Card>
    </div>
  )
}

/* ------------------------------------------------------- user & security */

function SecurityPanel({
  policy,
  mfaRoles,
}: {
  policy: SettingRow[]
  mfaRoles: { key: string; name: string; requires_mfa: boolean }[]
}) {
  return (
    <div className={styles.page}>
      <Card title="Authentication & session policy" eyebrow="Effective configuration">
        <ConfigList rows={policy} />
        <p className={styles.footnote}>{READ_ONLY_NOTE}</p>
      </Card>

      <Card title="Multi-factor authentication by role" eyebrow="Phase 1 role property">
        <ul className={styles.plainList}>
          {mfaRoles.map((role) => (
            <li key={role.key} className={styles.plainRow}>
              <span>{role.name}</span>
              <Badge variant={role.requires_mfa ? 'success' : 'neutral'} dot>
                {role.requires_mfa ? 'MFA required' : 'MFA optional'}
              </Badge>
            </li>
          ))}
        </ul>
        <p className={styles.footnote}>
          Individual accounts are administered in User &amp; Access — this is the role-level requirement
        </p>
      </Card>
    </div>
  )
}

/* -------------------------------------------------------------- registry */

function RegistryPanel({
  identityFields,
  nonIdentityFields,
  privacy,
  purposes,
}: {
  identityFields: string[]
  nonIdentityFields: string[]
  privacy: SettingRow[]
  purposes: { key: string; label: string; gate: string }[]
}) {
  return (
    <div className={styles.page}>
      <Card title="Identity validation" eyebrow="Locked — not administrator-editable">
        <p className={styles.groupLabel}>Identity fields</p>
        <div className={styles.choiceRow}>
          {identityFields.map((field) => (
            <Badge key={field} variant="neutral" mono>
              {field}
            </Badge>
          ))}
        </div>
        <p className={styles.groupLabel}>Other profile fields</p>
        <div className={styles.choiceRow}>
          {nonIdentityFields.map((field) => (
            <Badge key={field} variant="neutral" mono>
              {field}
            </Badge>
          ))}
        </div>
        <p className={styles.footnote}>
          Which fields identify a person is a locked decision — it governs matching and masking everywhere, so it is
          reported here, never offered as a control
        </p>
      </Card>

      <Card title="Privacy & retention" eyebrow="Effective configuration">
        <ConfigList rows={privacy} />
        <p className={styles.groupLabel}>Consent purposes</p>
        <ul className={styles.plainList}>
          {purposes.map((purpose) => (
            <li key={purpose.key} className={styles.plainRow}>
              <span>{purpose.label}</span>
              <Badge variant="neutral" mono>
                {purpose.gate}
              </Badge>
            </li>
          ))}
        </ul>
        <p className={styles.footnote}>
          Retention and consent are DPO-owned decisions set in configuration, not console toggles
        </p>
      </Card>
    </div>
  )
}

/* --------------------------------------------------------- notifications */

const CHANNEL_LABELS: Record<string, string> = {
  in_app: 'In-app inbox',
  email: 'Email',
  sms: 'SMS',
  whatsapp: 'WhatsApp',
}

function NotificationsPanel({
  channels,
  canBroadcast,
}: {
  channels: { key: string; available: boolean }[]
  canBroadcast: boolean
}) {
  const [subject, setSubject] = useState('')
  const [body, setBody] = useState('')
  const broadcast = useBroadcast()

  return (
    <div className={styles.page}>
      <Card title="Delivery channels" eyebrow="Registered with the notifier">
        <ul className={styles.plainList}>
          {channels.map((channel) => (
            <li key={channel.key} className={styles.plainRow}>
              <span>{CHANNEL_LABELS[channel.key] ?? channel.key}</span>
              <Badge variant={channel.available ? 'success' : 'neutral'} dot>
                {channel.available ? 'available' : 'not configured'}
              </Badge>
            </li>
          ))}
        </ul>
        <p className={styles.footnote}>
          A channel reports its own availability — an unconfigured provider is skipped at send time rather than
          failing silently
        </p>
      </Card>

      {canBroadcast && (
        <Card title="Broadcast a system notification" eyebrow="Reaches every active user">
          <TextField
            label="Subject"
            value={subject}
            placeholder="e.g. Scheduled maintenance on Saturday"
            onChange={(e) => setSubject(e.target.value)}
          />
          <TextareaField
            label="Message"
            value={body}
            rows={4}
            helper="Delivered to the in-app inbox for every active user, and by email where their preferences allow."
            onChange={(e) => setBody(e.target.value)}
          />
          <div className={styles.filterActions}>
            <Button
              leftIcon={Send}
              disabled={subject.trim() === ''}
              loading={broadcast.isPending}
              onClick={() =>
                broadcast.mutate(
                  { subject: subject.trim(), body: body.trim() || undefined },
                  { onSuccess: () => { setSubject(''); setBody('') } },
                )
              }
            >
              Send broadcast
            </Button>
          </div>
        </Card>
      )}
    </div>
  )
}

/* ----------------------------------------------------- permission matrix */

/**
 * The role × module × action editor. It edits the SAME `role_permission` pivot that
 * authorization reads, so a saved change is in force immediately — there is no second
 * permission store.
 *
 * The two locks come from the server with the matrix, not from rules restated here:
 * the System Administrator column is not editable (it holds everything implicitly),
 * and a permission marked `role_grantable: false` — `export.reveal_pii` — can never be
 * bundled into a role. The server rejects either attempt regardless of the UI.
 */
function MatrixEditor() {
  const { data, isLoading, error } = usePermissionMatrix()
  const update = useUpdateRolePermissions()
  const [roleKey, setRoleKey] = useState<string>('')
  const [selected, setSelected] = useState<Set<string>>(new Set())

  const role: MatrixRole | undefined = useMemo(
    () => data?.roles.find((r) => r.key === roleKey) ?? data?.roles.find((r) => r.editable),
    [data, roleKey],
  )

  // Re-seed the working set whenever the selected role (or the server's copy) changes.
  useEffect(() => {
    if (role) setSelected(new Set(role.permissions))
  }, [role])

  if (isLoading) return <Loading label="Loading the permission matrix" />
  if (error || !data) return <p className={styles.muted}>Could not load the permission matrix. Please try again.</p>

  const byModule = new Map<string, MatrixPermission[]>()
  for (const permission of data.permissions) {
    const list = byModule.get(permission.module) ?? []
    list.push(permission)
    byModule.set(permission.module, list)
  }

  const editable = role?.editable ?? false
  const dirty =
    role !== undefined &&
    (selected.size !== role.permissions.length || role.permissions.some((k) => !selected.has(k)))

  function toggle(key: string) {
    setSelected((current) => {
      const next = new Set(current)
      if (next.has(key)) next.delete(key)
      else next.add(key)
      return next
    })
  }

  return (
    <div className={styles.page}>
      <Card title="Permission matrix" eyebrow="Role × module × action">
        <div className={styles.filterBar}>
          <SelectField
            label="Role"
            value={role?.key ?? ''}
            onChange={(e) => setRoleKey(e.target.value)}
            options={data.roles.map((r) => ({
              value: r.key,
              label: r.editable ? r.name : `${r.name} (locked)`,
            }))}
          />
        </div>

        {!editable && (
          <p className={styles.policyNote}>
            <span className={styles.policyLabel}>Locked</span>
            <span className={styles.policyText}>
              The System Administrator role holds every permission implicitly. It cannot be edited — otherwise an
              administrator could remove their own ability to administer.
            </span>
          </p>
        )}

        {[...byModule.entries()].map(([module, permissions]) => (
          <div key={module}>
            <p className={styles.groupLabel}>{module}</p>
            <div className={styles.choiceRow}>
              {permissions.map((permission) => (
                <span key={permission.key} className={styles.permCell}>
                  <Checkbox
                    label={permission.action_label}
                    title={permission.description ?? permission.key}
                    checked={selected.has(permission.key)}
                    disabled={!editable || !permission.role_grantable}
                    onChange={() => toggle(permission.key)}
                  />
                  {!permission.role_grantable && (
                    <Badge variant="warning" dot>
                      never granted to a role
                    </Badge>
                  )}
                  {permission.role_grantable && permission.sensitive && selected.has(permission.key) && (
                    <Badge variant="warning" dot>
                      DPO sign-off
                    </Badge>
                  )}
                </span>
              ))}
            </div>
          </div>
        ))}

        <div className={styles.filterActions}>
          <Button
            leftIcon={editable ? Save : Lock}
            disabled={!editable || !dirty}
            loading={update.isPending}
            onClick={() => role && update.mutate({ roleId: role.id, permissions: [...selected] })}
          >
            Save permissions
          </Button>
          {editable && dirty && (
            <Button variant="tertiary" onClick={() => role && setSelected(new Set(role.permissions))}>
              Discard changes
            </Button>
          )}
        </div>

        <p className={styles.footnote}>
          Saving writes the same role → permission mapping authorization reads, and is audited as
          role.permissions_updated. Granting an export permission is subject to DPO sign-off under NDPA/NDPR
        </p>
      </Card>
    </div>
  )
}

/* ------------------------------------------------------------------ page */

/**
 * Console Settings — reached from the gear affordance, deliberately NOT a navigation
 * link (the nav is the nine governance sections).
 *
 * Four of the five panels are read-only projections of configuration that already
 * exists (deployment, security policy, registry rules, notification channels); the two
 * things an administrator genuinely changes here are the PERMISSION MATRIX, which
 * writes the existing RBAC pivot, and a system BROADCAST, which goes out through the
 * Phase 5 notifier. Nothing on this page stores settings of its own.
 */
export function AdminSettingsPage() {
  const { hasPermission } = useAuth()
  const canEditRoles = hasPermission('role.edit')
  const { data, isLoading, error } = useAdminSettings()

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>Administration console</span>
        <h1 className={styles.pageTitle}>Settings</h1>
        <p className={styles.lead}>
          The configuration in force for this deployment, and the two controls the console owns: who holds which
          permissions, and system-wide announcements.
        </p>
      </header>

      {isLoading && <Loading label="Loading settings" />}
      {error && <p className={styles.muted}>Could not load settings. Please try again.</p>}

      {!isLoading && !error && data && (
        <Tabs
          items={[
            { id: 'general', label: 'General', content: <GeneralPanel rows={data.general} /> },
            {
              id: 'security',
              label: 'User & security',
              content: <SecurityPanel policy={data.security.policy} mfaRoles={data.security.mfa_roles} />,
            },
            {
              id: 'registry',
              label: 'Registry',
              content: (
                <RegistryPanel
                  identityFields={data.registry.identity_fields}
                  nonIdentityFields={data.registry.non_identity_fields}
                  privacy={data.registry.privacy}
                  purposes={data.registry.consent_purposes}
                />
              ),
            },
            {
              id: 'notifications',
              label: 'Notifications',
              content: <NotificationsPanel channels={data.notifications} canBroadcast />,
            },
            {
              id: 'permissions',
              label: 'Permission matrix',
              content: canEditRoles ? (
                <MatrixEditor />
              ) : (
                <p className={styles.muted}>Editing the permission matrix needs the role edit permission.</p>
              ),
            },
          ]}
        />
      )}
    </div>
  )
}
