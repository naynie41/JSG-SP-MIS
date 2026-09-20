#!/usr/bin/env bash
#
# rollback.sh — Get back to the last release that was working.
#
# For a critical failure after a deploy: put the previous image tag back, bring the
# stack up, confirm it is healthy. One job.
#
# It does NOT touch the database, deliberately. Migrations here are additive, so the
# previous image runs against the current schema and simply ignores the columns it
# does not know about — code is almost always the thing that needs undoing. Restoring
# data is a different act with different risk (it discards every registration,
# benefit, grievance and audit entry written since the dump), so it stays a manual,
# deliberate operation rather than a flag on the emergency script. See the deployment runbook §5.
#
# USAGE (from the compose directory, as the deploy user):
#   ./scripts/rollback.sh              # → the previously deployed tag
#   ./scripts/rollback.sh v1.1.1       # → an explicit tag
#   ./scripts/rollback.sh --list       # what is available to roll back to
#   ./scripts/rollback.sh v1.1.1 --yes # skip the prompt (for a scripted response)
#
set -uo pipefail

COMPOSE_FILE="${COMPOSE_FILE:-docker-compose.prod.yml}"
ENV_FILE="${ENV_FILE:-.env}"
DC="docker compose -f ${COMPOSE_FILE}"
HEALTH_TIMEOUT="${HEALTH_TIMEOUT:-300}"

log()  { printf '\n\033[1;32m==>\033[0m %s\n' "$*"; }
warn() { printf '\n\033[1;33m[!]\033[0m %s\n'  "$*"; }
die()  { printf '\n\033[1;31m[x]\033[0m %s\n'  "$*" >&2; exit 1; }

[[ -f "${COMPOSE_FILE}" ]] || die "${COMPOSE_FILE} not found — run from the compose directory."
[[ -f "${ENV_FILE}" ]]     || die "${ENV_FILE} not found."

CURRENT_TAG="$(grep -E '^IMAGE_TAG=' "${ENV_FILE}" | head -1 | cut -d= -f2- | tr -d '"'"'")"
PREVIOUS_TAG="$(tr -d '[:space:]' < .last-deployed-tag 2>/dev/null)"
GHCR_OWNER="$(grep -E '^GHCR_OWNER=' "${ENV_FILE}" | head -1 | cut -d= -f2- | tr -d '"'"'")"

# ---------------------------------------------------------------------- --list
if [[ "${1:-}" == "--list" ]]; then
  printf '\n\033[1mRunning now\033[0m\n  %s\n' "${CURRENT_TAG:-<unset>}"
  printf '\n\033[1mRolls back to (from .last-deployed-tag)\033[0m\n  %s\n' "${PREVIOUS_TAG:-<none recorded>}"
  printf '\n\033[1mOther tags available locally\033[0m\n'
  docker image ls "ghcr.io/${GHCR_OWNER}/spmis-api" --format '  {{.Tag}}  ({{.CreatedSince}})' 2>/dev/null | grep -v '<none>' || printf '  (none pulled yet)\n'
  printf '\nThe database is NOT touched by a rollback.\n\n'
  exit 0
fi

# -------------------------------------------------------------------- arguments
TARGET_TAG="${1:-${PREVIOUS_TAG}}"
ASSUME_YES="no"
for arg in "$@"; do
  [[ "${arg}" == "--yes" ]] && ASSUME_YES="yes"
done

[[ -n "${TARGET_TAG}" ]] || die "No tag given and .last-deployed-tag is empty. Try: ./scripts/rollback.sh --list"
[[ -n "${GHCR_OWNER}" ]] || die "GHCR_OWNER is not set in ${ENV_FILE}."
[[ "${TARGET_TAG}" != "${CURRENT_TAG}" ]] || die "Already running ${TARGET_TAG} — nothing to roll back."

# --- Is the target actually pullable? Checked BEFORE anything changes. ---------
# The worst moment to discover a tag was never pushed, or that this host is not
# logged in to GHCR, is after .env has already been rewritten.
log "Checking ${TARGET_TAG} is pullable from GHCR"
for svc in api web; do
  img="ghcr.io/${GHCR_OWNER}/spmis-${svc}:${TARGET_TAG}"
  if ! docker manifest inspect "${img}" >/dev/null 2>&1; then
    die "Cannot read ${img} — wrong tag, or this host is not logged in to GHCR."
  fi
  echo "    ✓ ${img}"
done

# ---------------------------------------------------------------- confirmation
printf '\n\033[1mRoll back\033[0m  %s  →  %s\n' "${CURRENT_TAG:-<unset>}" "${TARGET_TAG}"
printf '  The database is left exactly as it is.\n\n'

if [[ "${ASSUME_YES}" != "yes" ]]; then
  read -rp 'Continue? [y/N] ' answer
  [[ "${answer}" =~ ^[Yy]$ ]] || die "Aborted — nothing was changed."
fi

# --------------------------------------------------------------- switch the tag
log "Setting IMAGE_TAG=${TARGET_TAG}"
cp "${ENV_FILE}" "${ENV_FILE}.bak.$(date +%Y%m%d%H%M%S)"
sed -i -E "s|^IMAGE_TAG=.*|IMAGE_TAG=${TARGET_TAG}|" "${ENV_FILE}"

# Record where we came FROM, so rolling forward again is one command.
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
  warn "verify.sh not found at ${VERIFY} — verify manually (deployment runbook §2.7)."
fi

log "Rolled back to ${TARGET_TAG}."
echo "    Roll forward again with: ./scripts/deploy.sh ${CURRENT_TAG}"
