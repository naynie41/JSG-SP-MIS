#!/usr/bin/env bash
#
# deploy.sh — Deploy or roll back an SP-MIS release on the VPS. PULL-ONLY.
#
# The VPS never builds. This pulls a tag that GitHub Actions already built and
# published to GHCR, brings the stack up, waits for health, and verifies.
#
# USAGE (from the compose directory, as the deploy user):
#   ./deploy.sh v1.1.0             # deploy a release
#   ./deploy.sh v1.0.0 --rollback  # go back to a previous release
#   ./deploy.sh v1.1.0 --no-backup # skip the pre-deploy backup (not advised)
#
# What it does, in order:
#   1. Refuses if the tag is not pullable (wrong tag, or not logged in to GHCR)
#   2. Takes a pre-deploy backup, unless rolling back or told not to
#   3. Records the CURRENT tag so a failed deploy tells you what to roll back to
#   4. Rewrites IMAGE_TAG in .env, pulls, and brings the stack up
#   5. Waits for every service to be healthy, then runs verify.sh
#
set -euo pipefail

COMPOSE_FILE="${COMPOSE_FILE:-docker-compose.prod.yml}"
ENV_FILE="${ENV_FILE:-.env}"
DC="docker compose -f ${COMPOSE_FILE}"
HEALTH_TIMEOUT="${HEALTH_TIMEOUT:-300}"   # seconds to wait for all services healthy

log()  { printf '\n\033[1;32m==>\033[0m %s\n' "$*"; }
warn() { printf '\n\033[1;33m[!]\033[0m %s\n'  "$*"; }
die()  { printf '\n\033[1;31m[x]\033[0m %s\n'  "$*" >&2; exit 1; }

TAG="${1:-}"
MODE="${2:-}"
[[ -n "${TAG}" ]] || die "Usage: ./deploy.sh <tag> [--rollback|--no-backup]   e.g. ./deploy.sh v1.1.0"
[[ -f "${COMPOSE_FILE}" ]] || die "${COMPOSE_FILE} not found — run from the compose directory."
[[ -f "${ENV_FILE}" ]]     || die "${ENV_FILE} not found. See docs/DEPLOY.md §2.3."

GHCR_OWNER="$(grep -E '^GHCR_OWNER=' "${ENV_FILE}" | head -1 | cut -d= -f2- | tr -d '"'"'")"
[[ -n "${GHCR_OWNER}" ]] || die "GHCR_OWNER is not set in ${ENV_FILE}."

CURRENT_TAG="$(grep -E '^IMAGE_TAG=' "${ENV_FILE}" | head -1 | cut -d= -f2- | tr -d '"'"'")"
log "Current: ${CURRENT_TAG:-<unset>}   →   Requested: ${TAG}"

# --- 1. Is the tag actually there? -----------------------------------------
# Checked BEFORE anything is changed. The usual first-deploy failure is not being
# logged in to GHCR, and finding that out after .env has been rewritten is worse.
log "Checking ${TAG} is pullable from GHCR"
for svc in api web; do
  img="ghcr.io/${GHCR_OWNER}/spmis-${svc}:${TAG}"
  docker manifest inspect "${img}" >/dev/null 2>&1 \
    || die "$(cat <<EOF
Cannot read ${img}

Either the tag was never pushed, or this host is not authenticated to GHCR.
  • Confirm the release workflow succeeded for ${TAG}
  • Confirm login:  echo \$TOKEN | docker login ghcr.io -u <user> --password-stdin
  • If you use sudo for docker, note root has its OWN ~/.docker/config.json
See docs/GHCR-Setup-Runbook.pdf.
EOF
)"
  echo "    ✓ ${img}"
done

# --- 2. Pre-deploy backup ---------------------------------------------------
# Migrations are forward-only. Rolling back code does not roll back the schema, so
# the backup taken here is what makes a bad release survivable.
if [[ "${MODE}" == "--rollback" ]]; then
  warn "Rollback mode — skipping the pre-deploy backup."
  warn "If the release you are leaving ran a destructive migration, restore the"
  warn "database from the backup taken before IT was deployed (DEPLOY.md §5)."
elif [[ "${MODE}" == "--no-backup" ]]; then
  warn "--no-backup given. Proceeding without a restore point."
else
  log "Taking a pre-deploy backup"
  $DC exec -T scheduler php artisan backup:run \
    || die "Backup FAILED. Deployment stopped — fix the backup before changing anything."
fi

# --- 3. Record where we came from -------------------------------------------
echo "${CURRENT_TAG}" > .last-deployed-tag 2>/dev/null || true
[[ -n "${CURRENT_TAG}" ]] && log "Previous tag recorded: ${CURRENT_TAG} (./deploy.sh ${CURRENT_TAG} --rollback)"

# --- 4. Switch the tag and bring the stack up -------------------------------
log "Setting IMAGE_TAG=${TAG}"
cp "${ENV_FILE}" "${ENV_FILE}.bak.$(date +%Y%m%d%H%M%S)"
if grep -qE '^IMAGE_TAG=' "${ENV_FILE}"; then
  sed -i -E "s|^IMAGE_TAG=.*|IMAGE_TAG=${TAG}|" "${ENV_FILE}"
else
  echo "IMAGE_TAG=${TAG}" >> "${ENV_FILE}"
fi

log "Pulling images"
$DC pull

log "Starting the stack"
# `api` runs `migrate --force` on boot; worker and scheduler do not, so there is no
# migration race between the three.
$DC up -d

# --- 5. Wait for health, then verify ----------------------------------------
log "Waiting up to ${HEALTH_TIMEOUT}s for services to report healthy"
deadline=$(( $(date +%s) + HEALTH_TIMEOUT ))
while :; do
  unhealthy="$($DC ps --format '{{.Service}} {{.State}} {{.Health}}' 2>/dev/null \
    | awk '$2!="running" || ($3!="healthy" && $3!="") {print $1}' | tr '\n' ' ')"
  [[ -z "${unhealthy// /}" ]] && { log "All services healthy."; break; }
  if (( $(date +%s) > deadline )); then
    warn "Still not healthy after ${HEALTH_TIMEOUT}s: ${unhealthy}"
    $DC ps
    echo
    warn "Logs:    $DC logs --tail=80 ${unhealthy%% *}"
    warn "Rollback: ./deploy.sh ${CURRENT_TAG:-<previous-tag>} --rollback"
    exit 1
  fi
  sleep 5
done

# Resolved against THIS script's directory, not the working directory: deploy.sh is
# run from the compose dir (/opt/spmis) but lives in /opt/spmis/scripts, so `./verify.sh`
# would silently miss and skip verification altogether.
VERIFY="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/verify.sh"
if [[ -x "${VERIFY}" ]]; then
  log "Running verification"
  "${VERIFY}" || {
    warn "Verification reported problems. The stack is UP but not confirmed healthy."
    warn "Rollback: ./deploy.sh ${CURRENT_TAG:-<previous-tag>} --rollback"
    exit 1
  }
else
  warn "verify.sh not found at ${VERIFY} — verify manually (DEPLOY.md §2.7)."
fi

log "Deployed ${TAG}."
