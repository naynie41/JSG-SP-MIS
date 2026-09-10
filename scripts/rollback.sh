#!/usr/bin/env bash
#
# rollback.sh — Undo a release. Code by default; the database only if you say so.
#
# Rolling back CODE and rolling back DATA are different acts with different risk,
# and conflating them is how a bad deploy becomes a bad day:
#
#   * Code rollback is cheap and reversible. Migrations here are additive, so the
#     previous image runs happily against the newer schema — it simply ignores the
#     columns it does not know about. This is almost always all you need.
#   * A database restore is DESTRUCTIVE. It discards every row written since the
#     dump: registrations, benefit ledger entries, grievances, audit trail. On a
#     system holding beneficiary data that is real harm, so it is opt-in, needs a
#     typed confirmation, and takes its own safety dump first.
#
# USAGE (from the compose directory, as the deploy user):
#   ./rollback.sh                        # code → the previously deployed tag
#   ./rollback.sh v1.1.1                 # code → an explicit tag
#   ./rollback.sh --list                 # what can be rolled back to
#   ./rollback.sh v1.1.1 --database local-backups/pre-v1.2.0-20260902-125638.sql.gz
#
# NOTE ON `backup:restore`: that command restores an ENCRYPTED OFFSITE artifact and
# needs the S3 credentials. While AWS_* is unset it cannot be used, so this script
# works from the plain pg_dump files in ./local-backups. Once offsite backups are
# configured, prefer `php artisan backup:restore` — it verifies the artifact and
# restores documents too, neither of which a bare SQL dump does.
#
set -uo pipefail

COMPOSE_FILE="${COMPOSE_FILE:-docker-compose.prod.yml}"
ENV_FILE="${ENV_FILE:-.env}"
BACKUP_DIR="${BACKUP_DIR:-local-backups}"
DC="docker compose -f ${COMPOSE_FILE}"
HEALTH_TIMEOUT="${HEALTH_TIMEOUT:-300}"

log()  { printf '\n\033[1;32m==>\033[0m %s\n' "$*"; }
warn() { printf '\n\033[1;33m[!]\033[0m %s\n'  "$*"; }
die()  { printf '\n\033[1;31m[x]\033[0m %s\n'  "$*" >&2; exit 1; }

[[ -f "${COMPOSE_FILE}" ]] || die "${COMPOSE_FILE} not found — run from the compose directory."
[[ -f "${ENV_FILE}" ]]     || die "${ENV_FILE} not found."

CURRENT_TAG="$(grep -E '^IMAGE_TAG=' "${ENV_FILE}" | head -1 | cut -d= -f2- | tr -d '"'"'")"
PREVIOUS_TAG="$(cat .last-deployed-tag 2>/dev/null | tr -d '[:space:]')"
GHCR_OWNER="$(grep -E '^GHCR_OWNER=' "${ENV_FILE}" | head -1 | cut -d= -f2- | tr -d '"'"'")"

# ---------------------------------------------------------------------- --list
if [[ "${1:-}" == "--list" ]]; then
  printf '\n\033[1mCurrently deployed\033[0m\n  %s\n' "${CURRENT_TAG:-<unset>}"
  printf '\n\033[1mPrevious tag (from .last-deployed-tag)\033[0m\n  %s\n' "${PREVIOUS_TAG:-<none recorded>}"
  printf '\n\033[1mDatabase dumps in %s/\033[0m\n' "${BACKUP_DIR}"
  if [[ -d "${BACKUP_DIR}" ]]; then
    find "${BACKUP_DIR}" -maxdepth 1 -name '*.sql.gz' -printf '  %TY-%Tm-%Td %TH:%TM  %10s  %p\n' 2>/dev/null | sort -r
  else
    printf '  (no %s directory)\n' "${BACKUP_DIR}"
  fi
  printf '\nA dump restores the DATA as it was at that moment. Everything recorded\n'
  printf 'since — registrations, benefits, grievances, audit entries — is discarded.\n\n'
  exit 0
fi

# -------------------------------------------------------------------- arguments
TARGET_TAG="${1:-${PREVIOUS_TAG}}"
RESTORE_DUMP=""

if [[ "${2:-}" == "--database" ]]; then
  RESTORE_DUMP="${3:-}"
  [[ -n "${RESTORE_DUMP}" ]] || die "--database needs a dump file. Try: ./rollback.sh --list"
fi

[[ -n "${TARGET_TAG}" ]] || die "No target tag given and .last-deployed-tag is empty. Try: ./rollback.sh --list"
[[ -n "${GHCR_OWNER}" ]] || die "GHCR_OWNER is not set in ${ENV_FILE}."

if [[ "${TARGET_TAG}" == "${CURRENT_TAG}" && -z "${RESTORE_DUMP}" ]]; then
  die "Already running ${TARGET_TAG} — nothing to roll back. Use --database to restore data at this tag."
fi

# --- Is the target actually pullable? Checked BEFORE anything changes. ---------
log "Checking ${TARGET_TAG} is pullable from GHCR"
for svc in api web; do
  img="ghcr.io/${GHCR_OWNER}/spmis-${svc}:${TARGET_TAG}"
  if ! docker manifest inspect "${img}" >/dev/null 2>&1; then
    die "Cannot read ${img} — wrong tag, or this host is not logged in to GHCR."
  fi
  echo "    ✓ ${img}"
done

# ---------------------------------------------------------------- confirmation
printf '\n\033[1mAbout to roll back\033[0m\n'
printf '  image tag   %s  →  %s\n' "${CURRENT_TAG:-<unset>}" "${TARGET_TAG}"

if [[ -n "${RESTORE_DUMP}" ]]; then
  [[ -r "${RESTORE_DUMP}" ]] || die "Dump not readable: ${RESTORE_DUMP}"

  printf '  database    RESTORE FROM %s\n' "${RESTORE_DUMP}"
  printf '\n\033[1;31mThis DESTROYS every row written since that dump.\033[0m\n'
  printf 'Beneficiaries registered, benefits recorded, grievances raised and audit\n'
  printf 'entries written after that moment are gone. A safety dump of the CURRENT\n'
  printf 'database is taken first, so this step is itself reversible.\n\n'
  read -rp 'Type RESTORE to continue: ' answer
  [[ "${answer}" == "RESTORE" ]] || die "Aborted — nothing was changed."
else
  printf '  database    untouched\n'
  printf '\nCode only. Migrations here are additive, so %s runs against the current\n' "${TARGET_TAG}"
  printf 'schema — it ignores columns it does not know about. Add --database only if\n'
  printf 'the DATA is wrong, not merely the code.\n\n'
  read -rp 'Continue? [y/N] ' answer
  [[ "${answer}" =~ ^[Yy]$ ]] || die "Aborted — nothing was changed."
fi

# ------------------------------------------------------------ database restore
if [[ -n "${RESTORE_DUMP}" ]]; then
  DB_NAME="$(grep -E '^DB_DATABASE=' "${ENV_FILE}" | head -1 | cut -d= -f2- | tr -d '"'"'")"
  DB_USER="$(grep -E '^DB_USERNAME=' "${ENV_FILE}" | head -1 | cut -d= -f2- | tr -d '"'"'")"
  DB_NAME="${DB_NAME:-spmis}"
  DB_USER="${DB_USER:-spmis}"

  # Stop everything that writes. Leaving the app up during a restore means it
  # reconnects mid-way and writes into a half-restored database.
  log "Stopping application containers (postgres stays up)"
  $DC stop api worker scheduler web nginx

  log "Safety dump of the CURRENT database before overwriting it"
  mkdir -p "${BACKUP_DIR}"
  SAFETY="${BACKUP_DIR}/pre-rollback-$(date +%Y%m%d-%H%M%S).sql.gz"
  if ! $DC exec -T postgres pg_dump -U "${DB_USER}" -d "${DB_NAME}" | gzip > "${SAFETY}"; then
    $DC start api worker scheduler web nginx
    die "Safety dump FAILED — refusing to restore. The stack has been restarted unchanged."
  fi
  echo "    saved ${SAFETY}"

  # A plain pg_dump has no DROP statements, so it cannot be replayed over a
  # populated database. Dropping only `public` is not enough either: PostGIS lives
  # there, and the dump also creates the topology/tiger schemas, which would then
  # collide. Recreating the whole database is the clean way.
  log "Recreating ${DB_NAME} and replaying the dump"
  $DC exec -T postgres dropdb -U "${DB_USER}" --if-exists "${DB_NAME}" \
    || { $DC start api worker scheduler web nginx; die "dropdb failed — stack restarted, database untouched."; }
  $DC exec -T postgres createdb -U "${DB_USER}" "${DB_NAME}" \
    || die "createdb FAILED. The database does not exist. Restore ${SAFETY} by hand before anything else."

  if ! gunzip -c "${RESTORE_DUMP}" | $DC exec -T postgres psql -U "${DB_USER}" -d "${DB_NAME}" -v ON_ERROR_STOP=1 -q; then
    die "Restore FAILED partway. The database is INCOMPLETE. Replay the safety dump: gunzip -c ${SAFETY} | ${DC} exec -T postgres psql -U ${DB_USER} -d ${DB_NAME}"
  fi

  log "Database restored from ${RESTORE_DUMP}"
  warn "The schema is now as it was in that dump. Booting the app re-runs any"
  warn "migrations it is missing, so you end with RESTORED DATA at the target"
  warn "release's schema — which is what you want. Migrations are forward-only."
fi

# --------------------------------------------------------------- switch the tag
log "Setting IMAGE_TAG=${TARGET_TAG}"
cp "${ENV_FILE}" "${ENV_FILE}.bak.$(date +%Y%m%d%H%M%S)"
sed -i -E "s|^IMAGE_TAG=.*|IMAGE_TAG=${TARGET_TAG}|" "${ENV_FILE}"

# Record where we rolled back FROM, so rolling forward again is one command.
echo "${CURRENT_TAG}" > .last-deployed-tag 2>/dev/null || true

log "Pulling ${TARGET_TAG}"
$DC pull

log "Starting the stack"
$DC up -d

# ------------------------------------------------------------- wait and verify
log "Waiting up to ${HEALTH_TIMEOUT}s for services to report healthy"
deadline=$(( $(date +%s) + HEALTH_TIMEOUT ))
while :; do
  unhealthy="$($DC ps --format '{{.Service}} {{.State}} {{.Health}}' 2>/dev/null \
    | awk '$2!="running" || ($3!="healthy" && $3!="") {print $1}' | tr '\n' ' ')"
  if [[ -z "${unhealthy// /}" ]]; then
    log "All services healthy."
    break
  fi
  if (( $(date +%s) > deadline )); then
    warn "Still not healthy after ${HEALTH_TIMEOUT}s: ${unhealthy}"
    $DC ps
    warn "Logs: ${DC} logs --tail=80 ${unhealthy%% *}"
    exit 1
  fi
  sleep 5
done

VERIFY="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/verify.sh"
if [[ -x "${VERIFY}" ]]; then
  log "Running verification"
  if ! "${VERIFY}"; then
    warn "Verification reported problems. The stack is UP but not confirmed healthy."
    exit 1
  fi
else
  warn "verify.sh not found at ${VERIFY} — verify manually (DEPLOY.md §2.7)."
fi

log "Rolled back to ${TARGET_TAG}."
if [[ -n "${RESTORE_DUMP}" ]]; then
  echo "    Database restored from ${RESTORE_DUMP}"
  echo "    Pre-rollback state saved at ${SAFETY}"
fi
echo "    Roll forward again with: ./scripts/deploy.sh ${CURRENT_TAG}"
