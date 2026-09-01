#!/usr/bin/env bash
#
# verify.sh — Prove a deployed SP-MIS stack is actually working.
#
# Run after a first deploy, after every redeploy, and after a rollback. It checks the
# things whose failure is SILENT — where the stack looks healthy and is not:
#
#   * No active matching config → the import pipeline skips duplicate screening
#     entirely, raises nothing, and quietly fills the registry with duplicates.
#   * A data service published to the host → UFW does not protect Docker's ports.
#   * A backup that has not run → discovered when you need a restore.
#
# Read-only. It changes nothing and is safe to run against production at any time.
#
# USAGE (from the compose directory, as the deploy user):
#   ./verify.sh                        # uses NGINX_SERVER_NAME from .env
#   DOMAIN=spmis.example.gov.ng ./verify.sh
#
set -uo pipefail   # deliberately NOT -e: every check must run and report

COMPOSE_FILE="${COMPOSE_FILE:-docker-compose.prod.yml}"
DC="docker compose -f ${COMPOSE_FILE}"
ENV_FILE="${ENV_FILE:-.env}"

DOMAIN="${DOMAIN:-}"
if [[ -z "${DOMAIN}" && -f "${ENV_FILE}" ]]; then
  DOMAIN="$(grep -E '^NGINX_SERVER_NAME=' "${ENV_FILE}" | head -1 | cut -d= -f2- | tr -d '"'"'" )"
fi

PASS=0; FAIL=0; WARN=0
ok()   { printf '  \033[1;32m✓\033[0m %-46s %s\n' "$1" "${2:-}"; PASS=$((PASS+1)); }
bad()  { printf '  \033[1;31m✗\033[0m %-46s %s\n' "$1" "${2:-}"; FAIL=$((FAIL+1)); }
note() { printf '  \033[1;33m!\033[0m %-46s %s\n' "$1" "${2:-}"; WARN=$((WARN+1)); }
head_() { printf '\n\033[1m%s\033[0m\n' "$1"; }

command -v docker >/dev/null || { echo "docker not found"; exit 1; }
[[ -f "${COMPOSE_FILE}" ]] || { echo "${COMPOSE_FILE} not found — run from the compose directory"; exit 1; }

# ---------------------------------------------------------------- containers
head_ "Containers"
EXPECTED=(postgres redis rabbitmq api worker scheduler web nginx)
for svc in "${EXPECTED[@]}"; do
  state="$($DC ps --format '{{.Service}} {{.State}} {{.Health}}' 2>/dev/null | awk -v s="$svc" '$1==s {print $2" "$3}')"
  if [[ -z "${state}" ]]; then
    bad "${svc}" "not running"
  elif [[ "${state}" == *healthy* ]]; then
    ok "${svc}" "healthy"
  elif [[ "${state}" == running* ]]; then
    note "${svc}" "running (no healthcheck reported yet)"
  else
    bad "${svc}" "${state}"
  fi
done

# ------------------------------------------------------------- port exposure
head_ "Port exposure (only nginx may be published)"
PUBLISHED="$($DC ps --format '{{.Service}} {{.Ports}}' 2>/dev/null | grep -E '0\.0\.0\.0|:::' || true)"
LEAKED="$(echo "${PUBLISHED}" | awk '$1!="nginx" && NF>1 {print $1}' | sort -u | tr '\n' ' ')"
if [[ -z "${LEAKED// /}" ]]; then
  ok "data services internal-only" "postgres/redis/rabbitmq not published"
else
  bad "PUBLISHED TO THE HOST" "${LEAKED}— UFW does NOT filter Docker ports"
fi

# -------------------------------------------------------------------- HTTP/TLS
if [[ -n "${DOMAIN}" ]]; then
  head_ "HTTP / TLS (${DOMAIN})"

  code="$(curl -s -o /dev/null -w '%{http_code}' -m 15 "http://${DOMAIN}/" 2>/dev/null)"
  if [[ "${code}" == 30* ]]; then
    ok "HTTP redirects to HTTPS" "${code}"
  else
    bad "HTTP redirect" "got ${code:-no response}"
  fi

  if curl -fsS -m 20 "https://${DOMAIN}/api/v1/health" -o /tmp/spmis-health.json 2>/dev/null; then
    status="$(grep -o '"status"[[:space:]]*:[[:space:]]*"[^"]*"' /tmp/spmis-health.json | head -1 | cut -d'"' -f4)"
    if [[ "${status}" == "ok" ]]; then
      ok "API health" "status=ok"
    else
      bad "API health" "status=${status:-unknown}"
    fi
  else
    bad "API health" "endpoint unreachable over HTTPS"
  fi

  hsts="$(curl -sI -m 15 "https://${DOMAIN}/" 2>/dev/null | grep -ci 'strict-transport-security')"
  if (( hsts > 0 )); then
    ok "HSTS header present"
  else
    bad "HSTS header" "missing"
  fi

  # Certificate expiry — a cert that lapses takes the whole site down.
  if command -v openssl >/dev/null; then
    end="$(echo | openssl s_client -servername "${DOMAIN}" -connect "${DOMAIN}:443" 2>/dev/null \
           | openssl x509 -noout -enddate 2>/dev/null | cut -d= -f2)"
    if [[ -n "${end}" ]]; then
      days=$(( ( $(date -d "${end}" +%s) - $(date +%s) ) / 86400 ))
      if (( days > 21 )); then
        ok "TLS certificate" "${days} days left"
      else
        note "TLS certificate" "expires in ${days} days — check renewal"
      fi
    fi
  fi

  # RENEWAL METHOD. certbot's first issuance is `--standalone`, which needs port 80
  # FREE. nginx holds port 80 for the life of the deployment, so a renewal left on
  # standalone fails every time, silently, until the certificate expires and the whole
  # site goes down. It must be `webroot` against the dir nginx serves at
  # /.well-known/acme-challenge/.
  rconf="$(find /etc/letsencrypt/renewal -maxdepth 1 -name '*.conf' 2>/dev/null | head -1)"
  if [[ -n "${rconf}" && -r "${rconf}" ]]; then
    auth="$(grep -m1 '^authenticator' "${rconf}" | cut -d= -f2- | tr -d ' ')"
    case "${auth}" in
      webroot) ok "TLS renewal method" "webroot — works while nginx holds :80" ;;
      standalone) bad "TLS RENEWAL WILL FAIL" "authenticator=standalone needs port 80 free; nginx has it" ;;
      *) note "TLS renewal method" "authenticator=${auth:-unknown}" ;;
    esac
  else
    note "TLS renewal method" "renewal config unreadable (run as root to check)"
  fi
else
  note "HTTP / TLS" "no domain known (set DOMAIN= or NGINX_SERVER_NAME in .env)"
fi

# ------------------------------------------------------------ app invariants
head_ "Application state"
ART="$DC exec -T api php artisan"

pending="$($ART migrate:status 2>/dev/null | grep -c 'Pending' || true)"
if [[ -z "${pending}" || "${pending}" == "0" ]]; then
  ok "migrations" "none pending"
else
  bad "migrations" "${pending} pending"
fi

# THE SILENT ONE. Without an active matching config the import pipeline treats
# "no config" as "do not screen" — no error, no warning, duplicates accumulate.
mc="$($ART tinker --execute="echo app(App\\Domain\\Matching\\Services\\MatchingConfigService::class)->activeOrNull()?->version ?? 'NONE';" 2>/dev/null | tr -d '\r' | tail -1)"
if [[ -n "${mc}" && "${mc}" != "NONE" ]]; then
  ok "duplicate screening ACTIVE" "matching config v${mc}"
else
  bad "NO ACTIVE MATCHING CONFIG" "imports will NOT be screened for duplicates"
fi

roles="$($ART tinker --execute="echo App\\Domain\\Access\\Models\\Role::count();" 2>/dev/null | tr -d '\r' | tail -1)"
if [[ "${roles}" =~ ^[0-9]+$ ]] && (( roles >= 6 )); then
  ok "roles + permissions seeded" "${roles} roles"
else
  bad "roles" "found '${roles:-none}' (expected 6)"
fi

admins="$($ART tinker --execute="echo App\\Domain\\Access\\Models\\User::whereHas('role', fn(\$q)=>\$q->where('key','system_administrator'))->count();" 2>/dev/null | tr -d '\r' | tail -1)"
if [[ "${admins}" =~ ^[0-9]+$ ]] && (( admins >= 1 )); then
  ok "system administrator exists" "${admins}"
else
  bad "no system administrator" "run spmis:create-admin"
fi

wards="$($ART tinker --execute="echo App\\Domain\\Reference\\Models\\Ward::count();" 2>/dev/null | tr -d '\r' | tail -1)"
if [[ "${wards}" =~ ^[0-9]+$ ]] && (( wards > 0 )); then
  ok "ward reference data" "${wards} wards"
else
  note "no ward data" "ward validation stands down; ward accepted as free text"
fi

# ------------------------------------------------------------------- audit
head_ "Audit + backups"
if $ART audit:verify-chain 2>/dev/null | grep -qi 'intact'; then
  ok "audit chain intact"
else
  bad "audit chain" "verification did not report intact"
fi

# A backup that stopped running is silent until you need a restore. Backup age lives
# behind the AUTHENTICATED metrics endpoint, so this points at it rather than guessing
# from container state — and the dead-man's switch (DEPLOY.md §8.3) is what actually
# alerts when a run is missed.
note "backup freshness" "check GET /api/v1/health/metrics (backup age vs RPO)"

# ------------------------------------------------------------------- summary
printf '\n\033[1mResult:\033[0m %d passed, %d failed, %d to review\n\n' "${PASS}" "${FAIL}" "${WARN}"
(( FAIL == 0 )) || { echo "Deployment is NOT healthy — resolve the ✗ items above."; exit 1; }
echo "Stack verified."
