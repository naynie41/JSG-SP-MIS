#!/usr/bin/env bash
#
# provision.sh — Bare Ubuntu VPS → hardened, firewalled, Docker-ready host for SP-MIS.
#
# Covers deployment Stages 1–3:
#   1. System update + non-root deploy user
#   2. SSH hardening + UFW firewall + fail2ban + unattended-upgrades
#   3. Docker Engine + Compose plugin (official Docker repo) + host tuning for this stack
#
# Idempotent: safe to re-run, and safe on a host that was set up PARTLY BY HAND.
# Every mutating block inspects current state first and skips work already done; the
# four blocks that write config files compare content and only restart a service when
# something actually changed.
#
# USAGE (as root, on a fresh OR partly-configured Ubuntu 22.04/24.04 box):
#   scp scripts/provision.sh root@<server-ip>:/root/
#   ssh root@<server-ip>
#   chmod +x provision.sh
#
#   ./provision.sh --check          # AUDIT ONLY — reports state, changes NOTHING
#   DEPLOY_USER=deploy ./provision.sh
#
# Run --check first on a host you configured by hand: it prints what is already done
# and what a real run would still change, without touching the box.
#
# Environment:
#   SKIP_UPGRADE=yes    skip `apt-get update && upgrade` (slow; already done by hand)
#
# THEN, before logging out of root, verify in a SECOND terminal:
#   ssh deploy@<server-ip>     # must succeed
#   ssh root@<server-ip>       # must be refused
#
# Only close your root session once that succeeds.
#
# ---------------------------------------------------------------------------
set -euo pipefail

DEPLOY_USER="${DEPLOY_USER:-deploy}"       # non-root user to create
SSH_PORT="${SSH_PORT:-}"                   # blank = detect the port sshd actually uses
ALLOW_HTTP="${ALLOW_HTTP:-yes}"            # open 80/443 for nginx + TLS
APP_DIR="${APP_DIR:-/opt/spmis}"           # where docker-compose.prod.yml will live
SWAP_GB="${SWAP_GB:-2}"                    # 0 disables; see "Why swap" in Stage 3
TIMEZONE="${TIMEZONE:-Africa/Lagos}"
SKIP_UPGRADE="${SKIP_UPGRADE:-no}"         # yes = skip apt update/upgrade

CHECK_ONLY=no
for arg in "$@"; do
  case "${arg}" in
    --check|--dry-run) CHECK_ONLY=yes ;;
    -h|--help) sed -n '2,30p' "$0"; exit 0 ;;
    *) echo "Unknown argument: ${arg}" >&2; exit 2 ;;
  esac
done

# The prod compose declares exactly 4096M of container memory limits. Below this the
# host has nothing left for itself, so the kernel starts killing containers.
MIN_RAM_MB=4096
RECOMMENDED_RAM_MB=8192

# ---------------------------------------------------------------------------
# Helpers
# ---------------------------------------------------------------------------
log()  { printf '\n\033[1;32m==>\033[0m %s\n' "$*"; }
warn() { printf '\n\033[1;33m[!]\033[0m %s\n'  "$*"; }
die()  { printf '\n\033[1;31m[x]\033[0m %s\n'  "$*" >&2; exit 1; }

require_root() {
  [[ "$(id -u)" -eq 0 ]] || die "Run this as root (or via sudo). Try: sudo ./provision.sh"
}

pkg_installed() { dpkg-query -W -f='${Status}' "$1" 2>/dev/null | grep -q 'ok installed'; }

# sshd lives in /usr/sbin, which is not always on a non-login PATH.
SSHD_BIN="$(command -v sshd || echo /usr/sbin/sshd)"

# sshd's EFFECTIVE config. Wrapped because `sshd -T` is used inside command
# substitutions and `if` pipelines: with `set -e` + `pipefail`, a missing or failing
# sshd would abort the whole script with a bare exit 127 and no explanation.
sshd_conf() { "${SSHD_BIN}" -T 2>/dev/null || true; }

# Install only what is genuinely missing. `apt-get install` on an installed package is
# harmless but slow, and on a hand-configured box the log then reads as though this
# script did work it did not do.
apt_ensure() {
  local p missing=()
  for p in "$@"; do pkg_installed "${p}" || missing+=("${p}"); done
  if (( ${#missing[@]} == 0 )); then
    log "Already installed: $*"
  else
    log "Installing: ${missing[*]}"
    apt-get install -y "${missing[@]}"
  fi
}

# Write a config file ONLY if the content differs. Returns 0 when it changed, 1 when it
# was already correct — so the caller can restart a service only when there is a reason
# to. Restarting sshd on a box you are connected to is a small but real risk, and
# restarting it for no change is that risk taken for nothing.
write_if_changed() {   # write_if_changed <dest>   (content on stdin)
  local dest="$1" tmp
  tmp="$(mktemp)"
  cat > "${tmp}"
  if [[ -f "${dest}" ]] && cmp -s "${tmp}" "${dest}"; then
    rm -f "${tmp}"
    return 1
  fi
  install -m 644 "${tmp}" "${dest}"
  rm -f "${tmp}"
  return 0
}

# ---------------------------------------------------------------------------
# State audit — what is already done on this host?
# ---------------------------------------------------------------------------
AUDIT_DONE=0
AUDIT_TODO=0

arow() {   # arow <label> <DONE|TODO|WARN> [detail]
  local mark
  case "$2" in
    DONE) mark=$'\033[1;32m✓\033[0m'; AUDIT_DONE=$((AUDIT_DONE + 1)) ;;
    TODO) mark=$'\033[1;33m·\033[0m'; AUDIT_TODO=$((AUDIT_TODO + 1)) ;;
    *)    mark=$'\033[1;31m!\033[0m'; AUDIT_TODO=$((AUDIT_TODO + 1)) ;;
  esac
  printf '  %b %-36s %s\n' "${mark}" "$1" "${3:-}"
}

audit_host() {
  local keys="/home/${DEPLOY_USER}/.ssh/authorized_keys"
  local n

  printf '\n\033[1mCurrent state of %s\033[0m\n' "$(hostname)"

  if id "${DEPLOY_USER}" &>/dev/null; then arow "user '${DEPLOY_USER}'" DONE "exists"
  else arow "user '${DEPLOY_USER}'" TODO "would be created"; fi

  if id -nG "${DEPLOY_USER}" 2>/dev/null | grep -qw sudo; then arow "  group: sudo" DONE
  else arow "  group: sudo" TODO; fi

  if id -nG "${DEPLOY_USER}" 2>/dev/null | grep -qw docker; then arow "  group: docker" DONE
  else arow "  group: docker" TODO; fi

  if [[ -s "${keys}" ]]; then
    n="$(grep -cvE '^[[:space:]]*(#|$)' "${keys}" 2>/dev/null || echo 0)"
    arow "  authorized SSH key(s)" DONE "${n}"
  else
    arow "  authorized SSH key(s)" WARN "NONE — hardening would lock you out"
  fi

  if sshd_conf | grep -qi '^permitrootlogin no'; then
    arow "sshd: root login disabled" DONE
  else arow "sshd: root login disabled" TODO "currently permitted"; fi

  if sshd_conf | grep -qi '^passwordauthentication no'; then
    arow "sshd: password auth off" DONE
  else arow "sshd: password auth off" TODO "currently permitted"; fi

  arow "sshd: listening port" DONE "${SSH_PORT}"

  if ufw status 2>/dev/null | head -1 | grep -q 'Status: active'; then
    arow "UFW firewall" DONE "active"
    ufw status 2>/dev/null | grep -E 'ALLOW|LIMIT' | sed 's/^/        /' | head -10
    if [[ "${ALLOW_HTTP}" == "yes" ]]; then
      if ufw status 2>/dev/null | grep -qE '(^|[^0-9])443/tcp'; then
        arow "  443/tcp (HTTPS)" DONE
      else
        arow "  443/tcp (HTTPS)" WARN "NOT open — the site will be unreachable"
      fi
      if ufw status 2>/dev/null | grep -qE '(^|[^0-9])433/tcp'; then
        arow "  433/tcp" WARN "open — typo for 443? remove it"
      fi
    fi
  elif pkg_installed ufw; then arow "UFW firewall" TODO "installed but INACTIVE"
  else arow "UFW firewall" TODO "not installed"; fi

  # Other drop-ins may set the same keywords. sshd takes the FIRST value, so this only
  # MATTERS when the effective config is not hardened — otherwise ours already won and a
  # permanent red flag would just train people to ignore this table.
  local conflicts
  conflicts="$(grep -rliE '^[[:space:]]*(PasswordAuthentication|PermitRootLogin)' \
                 /etc/ssh/sshd_config.d/ 2>/dev/null \
               | grep -v '00-spmis-hardening.conf' \
               | xargs -r -n1 basename | tr '\n' ' ')"
  if [[ -n "${conflicts// /}" ]]; then
    if sshd_conf | grep -qi '^passwordauthentication no' \
       && sshd_conf | grep -qi '^permitrootlogin no'; then
      arow "  other sshd drop-ins" DONE "${conflicts}— overridden by 00-spmis"
    else
      arow "  other sshd drop-ins" WARN "${conflicts}— these WIN over ours; hardening inert"
    fi
  fi

  if systemctl is-active fail2ban &>/dev/null; then arow "fail2ban" DONE "running"
  elif pkg_installed fail2ban; then arow "fail2ban" TODO "installed, not running"
  else arow "fail2ban" TODO "not installed"; fi

  if pkg_installed unattended-upgrades; then arow "unattended-upgrades" DONE
  else arow "unattended-upgrades" TODO; fi

  if command -v docker &>/dev/null; then
    arow "Docker Engine" DONE "$(docker --version 2>/dev/null | cut -d, -f1)"
    if docker compose version &>/dev/null; then
      arow "  compose plugin" DONE "$(docker compose version --short 2>/dev/null)"
    else
      arow "  compose plugin" TODO "MISSING — deploy.sh needs it"
    fi
  else
    arow "Docker Engine" TODO "not installed"
  fi

  if [[ -f /etc/docker/daemon.json ]] && grep -q '"log-driver"' /etc/docker/daemon.json; then
    arow "Docker log rotation" DONE
  elif [[ -f /etc/docker/daemon.json ]]; then
    arow "Docker log rotation" WARN "daemon.json exists without log-driver — merge by hand"
  else
    arow "Docker log rotation" TODO
  fi

  if swapon --show 2>/dev/null | grep -q .; then
    arow "swap" DONE "$(swapon --show --noheadings --raw 2>/dev/null | head -1 | awk '{print $1" "$3}')"
  else
    arow "swap" TODO "${SWAP_GB}G would be created"
  fi

  if [[ "$(timedatectl show -p NTPSynchronized --value 2>/dev/null)" == "yes" ]]; then
    arow "time synchronised" DONE "$(timedatectl show -p Timezone --value 2>/dev/null)"
  else
    arow "time synchronised" TODO
  fi

  if [[ -d "${APP_DIR}" ]]; then
    arow "app dir ${APP_DIR}" DONE "owner $(stat -c '%U' "${APP_DIR}" 2>/dev/null)"
  else
    arow "app dir ${APP_DIR}" TODO
  fi

  [[ -f /var/run/reboot-required ]] && arow "reboot required" WARN "kernel/libs updated"

  printf '\n  %d already done, %d outstanding\n' "${AUDIT_DONE}" "${AUDIT_TODO}"
}

# ---------------------------------------------------------------------------
# Pre-flight
# ---------------------------------------------------------------------------
require_root

grep -qiE 'ubuntu' /etc/os-release \
  || warn "This script targets Ubuntu. Detected something else — proceed with caution."

log "Provisioning $(hostname) — deploy user: ${DEPLOY_USER}, app dir: ${APP_DIR}"

# ---- RAM check ------------------------------------------------------------
# Done BEFORE any changes: a box too small for this stack should be replaced, not
# provisioned and then discovered at 02:00 when the OOM killer takes Postgres.
TOTAL_RAM_MB=$(( $(awk '/MemTotal/ {print $2}' /proc/meminfo) / 1024 ))
log "Detected RAM: ${TOTAL_RAM_MB} MB"
if (( TOTAL_RAM_MB < MIN_RAM_MB )); then
  warn "SP-MIS's docker-compose.prod.yml declares ${MIN_RAM_MB}M of container memory limits."
  warn "This host has ${TOTAL_RAM_MB} MB — the stack CANNOT fit, even before the OS."
  warn "Resize to at least ${RECOMMENDED_RAM_MB} MB before deploying."
  if [[ "${CHECK_ONLY}" != "yes" ]]; then
    read -rp "Continue provisioning anyway? [y/N] " _c
    [[ "${_c:-N}" =~ ^[Yy]$ ]] || die "Stopped. Resize the VPS and re-run."
  fi
elif (( TOTAL_RAM_MB < RECOMMENDED_RAM_MB )); then
  warn "RAM (${TOTAL_RAM_MB} MB) meets the ${MIN_RAM_MB}M of limits with little headroom."
  warn "${RECOMMENDED_RAM_MB} MB is recommended. Swap is configured below as a cushion,"
  warn "but swap is a safety net, not capacity — a swapping Postgres is a slow Postgres."
fi

# ---- Which port does sshd actually listen on? -----------------------------
# Opening a firewall port sshd is NOT listening on is a lockout. Read the real value
# instead of assuming 22.
if [[ -z "${SSH_PORT}" ]]; then
  SSH_PORT="$(sshd_conf | awk '/^port /{print $2; exit}')"
  if [[ -z "${SSH_PORT}" ]]; then
    SSH_PORT=22
    warn "Could not read sshd's port (${SSHD_BIN} unavailable) — assuming 22."
    warn "If sshd listens elsewhere, re-run with SSH_PORT=<port> or the firewall will lock you out."
  else
    log "Detected sshd port: ${SSH_PORT}"
  fi
else
  ACTUAL_PORT="$(sshd_conf | awk '/^port /{print $2; exit}')"
  if [[ -n "${ACTUAL_PORT}" && "${ACTUAL_PORT}" != "${SSH_PORT}" ]]; then
    die "SSH_PORT=${SSH_PORT} but sshd listens on ${ACTUAL_PORT}. Opening the wrong port locks you out."
  fi
fi

# ---- What is already done here? -------------------------------------------
# Always audited, never assumed — this host may have been configured by hand.
audit_host

if [[ "${CHECK_ONLY}" == "yes" ]]; then
  cat <<'EOF'

  --check: nothing was changed.

  Legend:  ✓ already done   · outstanding   ! needs your attention
  Re-run without --check to apply the outstanding items. Every step below is
  guarded, so the items already marked ✓ will be skipped, not repeated.

EOF
  exit 0
fi

# ===========================================================================
# STAGE 1 — System update + non-root deploy user
# ===========================================================================
export DEBIAN_FRONTEND=noninteractive
# Ubuntu 22.04+ ships `needrestart`, which opens an interactive dialog mid-upgrade and
# hangs an unattended run. `a` = restart services automatically, no prompt.
export NEEDRESTART_MODE=a

if [[ "${SKIP_UPGRADE}" == "yes" ]]; then
  log "STAGE 1: Skipping apt update/upgrade (SKIP_UPGRADE=yes)"
  apt-get update -y   # still refresh the index; apt_ensure below needs it accurate
else
  log "STAGE 1: Updating system packages"
  apt-get update -y
  apt-get upgrade -y
fi

log "STAGE 1: Ensuring deploy user '${DEPLOY_USER}' exists"
if id "${DEPLOY_USER}" &>/dev/null; then
  log "User '${DEPLOY_USER}' already exists — leaving it alone."
else
  # --disabled-password: no password login; key-based only.
  adduser --disabled-password --gecos "" "${DEPLOY_USER}"
  log "Created user '${DEPLOY_USER}'."
fi

usermod -aG sudo "${DEPLOY_USER}"

# ---- SSH keys: the lockout gate -------------------------------------------
# The original script WARNED when no key was available and hardened SSH anyway. That
# combination — no key, no password auth, no root login — is an unrecoverable lockout
# needing console/rescue access. It is now a hard stop.
log "STAGE 1: Ensuring '${DEPLOY_USER}' has an authorized SSH key"
DEPLOY_SSH_DIR="/home/${DEPLOY_USER}/.ssh"
DEPLOY_KEYS="${DEPLOY_SSH_DIR}/authorized_keys"

install -d -m 700 -o "${DEPLOY_USER}" -g "${DEPLOY_USER}" "${DEPLOY_SSH_DIR}"

if [[ -s /root/.ssh/authorized_keys ]]; then
  # MERGE, never overwrite: re-running must not drop keys added since the first run.
  touch "${DEPLOY_KEYS}"
  while IFS= read -r key; do
    [[ -z "${key}" || "${key}" =~ ^# ]] && continue
    grep -qxF "${key}" "${DEPLOY_KEYS}" || printf '%s\n' "${key}" >> "${DEPLOY_KEYS}"
  done < /root/.ssh/authorized_keys
  chown "${DEPLOY_USER}:${DEPLOY_USER}" "${DEPLOY_KEYS}"
  chmod 600 "${DEPLOY_KEYS}"
  log "Root's authorized key(s) merged into ${DEPLOY_KEYS}."
fi

if [[ ! -s "${DEPLOY_KEYS}" ]]; then
  die "$(cat <<EOF
'${DEPLOY_USER}' has NO authorized SSH key, and this script is about to disable
password authentication and root login. Continuing would lock you out of this server.

Fix it from your LOCAL machine, then re-run:
    ssh-copy-id ${DEPLOY_USER}@$(hostname -I 2>/dev/null | awk '{print $1}')

Or paste a public key into ${DEPLOY_KEYS} from this session.
EOF
)"
fi

KEY_COUNT=$(grep -cvE '^\s*(#|$)' "${DEPLOY_KEYS}" || true)
log "'${DEPLOY_USER}' has ${KEY_COUNT} authorized key(s). Safe to harden SSH."

# ===========================================================================
# STAGE 2 — SSH hardening + firewall + fail2ban + auto security updates
# ===========================================================================
log "STAGE 2: Hardening SSH (disable root login + password auth)"

# NAMED `00-` DELIBERATELY, AND IT MUST STAY THAT WAY.
#
# sshd takes the FIRST value it obtains for each keyword (sshd_config(5)), and
# Ubuntu's `Include /etc/ssh/sshd_config.d/*.conf` sits near the TOP of sshd_config,
# so drop-ins are read in lexical order before the main file. Ubuntu cloud images
# ship 50-cloud-init.conf containing `PasswordAuthentication yes`. A drop-in named
# 99-* therefore loses to it SILENTLY: sshd restarts cleanly, this script reports
# success, and password authentication is still enabled. Sorting first is what makes
# the hardening actually take effect. `sshd -T` is checked below to prove it did.
SSHD_DROPIN="/etc/ssh/sshd_config.d/00-spmis-hardening.conf"
SSHD_DROPIN_LEGACY="/etc/ssh/sshd_config.d/99-hardening.conf"
install -d -m 755 /etc/ssh/sshd_config.d

# Drop-in rather than editing sshd_config: survives package updates that rewrite it,
# and leaves any hand-made edits to sshd_config itself untouched.
SSHD_BACKUP=""
if [[ -f "${SSHD_DROPIN}" ]]; then
  SSHD_BACKUP="${SSHD_DROPIN}.bak.$(date +%Y%m%d%H%M%S)"
  cp "${SSHD_DROPIN}" "${SSHD_BACKUP}"
fi

if write_if_changed "${SSHD_DROPIN}" <<'EOF'
# Managed by provision.sh — SSH hardening. Do not edit by hand.
PermitRootLogin no
PasswordAuthentication no
PubkeyAuthentication yes
# ChallengeResponseAuthentication was renamed in OpenSSH 8.7; the old name is
# deprecated and warns on Ubuntu 24.04.
KbdInteractiveAuthentication no
UsePAM yes
X11Forwarding no
MaxAuthTries 3
EOF
then
  # A legacy 99-* drop-in from an older version of this script is dead weight now that
  # the real one sorts first. Remove it, but only if it is ours.
  if [[ -f "${SSHD_DROPIN_LEGACY}" ]] && grep -q 'Managed by provision.sh' "${SSHD_DROPIN_LEGACY}"; then
    rm -f "${SSHD_DROPIN_LEGACY}"
    log "Removed superseded ${SSHD_DROPIN_LEGACY}."
  fi

  # Validate with the drop-in IN PLACE, using an absolute path (sshd is in /usr/sbin,
  # which is not always on a non-login PATH).
  if ! "${SSHD_BIN}" -t; then
    # Restore whatever was there before, rather than just deleting ours.
    if [[ -n "${SSHD_BACKUP}" ]]; then mv "${SSHD_BACKUP}" "${SSHD_DROPIN}"; else rm -f "${SSHD_DROPIN}"; fi
    die "sshd config test failed — hardening reverted. SSH is unchanged."
  fi

  # A syntactically valid file is NOT proof the settings took effect: an
  # earlier-sorting drop-in can override every line in it and sshd will not complain.
  # Read back the EFFECTIVE config and refuse to restart unless it actually hardened.
  EFF_ROOT="$(sshd_conf | awk '/^permitrootlogin /{print $2; exit}')"
  EFF_PW="$(sshd_conf | awk '/^passwordauthentication /{print $2; exit}')"
  if [[ "${EFF_ROOT}" != "no" || "${EFF_PW}" != "no" ]]; then
    warn "Hardening did NOT take effect — something overrides it:"
    warn "  permitrootlogin       = ${EFF_ROOT:-?}  (want: no)"
    warn "  passwordauthentication= ${EFF_PW:-?}  (want: no)"
    warn "Conflicting directives, in the order sshd reads them:"
    grep -rniE '^[[:space:]]*(PasswordAuthentication|PermitRootLogin)' \
      /etc/ssh/sshd_config.d/ /etc/ssh/sshd_config 2>/dev/null | sed 's/^/    /' || true
    if [[ -n "${SSHD_BACKUP}" ]]; then mv "${SSHD_BACKUP}" "${SSHD_DROPIN}"; else rm -f "${SSHD_DROPIN}"; fi
    die "sshd NOT restarted and nothing was changed. Remove the conflicting directive above, then re-run."
  fi

  # Ubuntu 24.04 socket-activates ssh; restarting both units covers either layout.
  systemctl restart ssh 2>/dev/null || systemctl restart sshd 2>/dev/null || true
  systemctl restart ssh.socket 2>/dev/null || true
  [[ -n "${SSHD_BACKUP}" ]] && log "Previous drop-in saved to ${SSHD_BACKUP}"
  log "SSH hardened (verified: root login no, password auth no) and reloaded."
  warn "Do NOT close this session yet."
  warn "In a NEW terminal confirm:  ssh ${DEPLOY_USER}@<server-ip>   (must succeed)"
  warn "                            ssh root@<server-ip>            (must be refused)"
else
  # Already byte-identical. Do NOT restart sshd: there is nothing to apply, and a
  # needless restart of the daemon carrying your session is risk for no benefit.
  log "SSH hardening already in place — sshd not restarted."
  "${SSHD_BIN}" -t || warn "sshd config does not validate — investigate before logging out."
fi

log "STAGE 2: Configuring UFW firewall"
apt_ensure ufw
ufw default deny incoming
ufw default allow outgoing
# `limit` rather than `allow`: throttles repeated connection attempts from one source.
ufw limit "${SSH_PORT}/tcp" comment 'SSH (rate-limited)'
if [[ "${ALLOW_HTTP}" == "yes" ]]; then
  ufw allow 80/tcp  comment 'HTTP (ACME + redirect to HTTPS)'
  ufw allow 443/tcp comment 'HTTPS'
fi
ufw --force enable

# Prove the rules that matter are actually present. A hand-made rule with a transposed
# digit (433 for 443) leaves HTTPS firewalled off, and the symptom appears only after
# nginx is up and the site is unreachable — far from the cause.
if [[ "${ALLOW_HTTP}" == "yes" ]]; then
  ufw status | grep -qE '(^|[^0-9])443/tcp' \
    || warn "443/tcp is NOT allowed after configuring UFW — HTTPS will be unreachable."
  if ufw status | grep -qE '(^|[^0-9])433/tcp'; then
    warn "UFW allows 433/tcp — almost certainly a typo for 443, and a port open for nothing."
    warn "Review and remove it:  ufw status numbered   then   ufw delete <number>"
  fi
fi

ufw status verbose

warn "Docker publishes ports by writing iptables rules directly and BYPASSES UFW."
warn "SP-MIS's docker-compose.prod.yml is built for this: only nginx has a 'ports:'"
warn "entry. Postgres, Redis, RabbitMQ, php-fpm, the worker, the scheduler and the SPA"
warn "are internal-only. If you ever add a 'ports:' to a data service, UFW will NOT"
warn "protect it — bind it to 127.0.0.1 explicitly instead."

log "STAGE 2: Installing fail2ban"
apt_ensure fail2ban
# Ubuntu's default jail reads /var/log/auth.log; 24.04 logs auth to the journal only,
# so the jail silently matches nothing unless told to read systemd.
install -d -m 755 /etc/fail2ban/jail.d
if write_if_changed /etc/fail2ban/jail.d/99-spmis.conf <<EOF
[sshd]
enabled  = true
port     = ${SSH_PORT}
backend  = systemd
maxretry = 5
bantime  = 1h
findtime = 10m
EOF
then
  systemctl enable --now fail2ban
  systemctl restart fail2ban
  log "fail2ban jail written and reloaded."
elif ! systemctl is-active fail2ban &>/dev/null; then
  log "fail2ban jail already correct but the service is down — starting it."
  systemctl enable --now fail2ban
else
  log "fail2ban already configured and running — left alone."
fi

log "STAGE 2: Enabling unattended security upgrades"
apt_ensure unattended-upgrades
if write_if_changed /etc/apt/apt.conf.d/20auto-upgrades <<'EOF'
APT::Periodic::Update-Package-Lists "1";
APT::Periodic::Unattended-Upgrade "1";
EOF
then
  log "Unattended security upgrades enabled."
else
  log "Unattended upgrades already configured — left alone."
fi

log "STAGE 2: Setting timezone + time sync (${TIMEZONE})"
# The audit log is hash-chained and SLA windows are measured in hours. Both assume the
# clock is right; a drifting VPS clock corrupts the record quietly.
timedatectl set-timezone "${TIMEZONE}" 2>/dev/null || warn "Could not set timezone."
timedatectl set-ntp true 2>/dev/null || warn "Could not enable NTP."

# ===========================================================================
# STAGE 3 — Docker Engine + Compose plugin + host tuning
# ===========================================================================
log "STAGE 3: Installing Docker Engine + Compose plugin"

if command -v docker &>/dev/null; then
  log "Docker already installed ($(docker --version)) — skipping install."
  # A hand-installed docker.io often has NO compose plugin, and deploy.sh is entirely
  # `docker compose`. Catch that here rather than at the first deploy.
  if ! docker compose version &>/dev/null; then
    warn "Docker is present but the COMPOSE PLUGIN is missing — deploy.sh cannot run."
    if apt-cache policy docker-compose-plugin 2>/dev/null | grep -q 'Candidate: [^(]'; then
      apt_ensure docker-compose-plugin
    else
      warn "docker-compose-plugin is not available from the configured repos."
      warn "Add Docker's official repo (see the else-branch below) or install it by hand."
    fi
  fi
else
  for pkg in docker.io docker-doc docker-compose docker-compose-v2 podman-docker containerd runc; do
    apt-get remove -y "$pkg" 2>/dev/null || true
  done

  apt_ensure ca-certificates curl
  install -m 0755 -d /etc/apt/keyrings
  curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
  chmod a+r /etc/apt/keyrings/docker.asc

  # shellcheck disable=SC1091
  UBUNTU_CODENAME="$(. /etc/os-release && echo "${UBUNTU_CODENAME:-${VERSION_CODENAME}}")"
  echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu ${UBUNTU_CODENAME} stable" \
    > /etc/apt/sources.list.d/docker.list

  apt-get update -y
  apt-get install -y \
    docker-ce docker-ce-cli containerd.io \
    docker-buildx-plugin docker-compose-plugin

  log "Docker installed: $(docker --version)"
fi

log "STAGE 3: Adding '${DEPLOY_USER}' to the docker group"
usermod -aG docker "${DEPLOY_USER}"
systemctl enable --now docker

# ---- Daemon-level log rotation --------------------------------------------
# docker-compose.prod.yml caps its own services' logs, but anything run OUTSIDE that
# file (the uptime monitor, a one-off `docker run`) inherits Docker's unbounded
# default. An unbounded log fills the disk, and the first symptom is Postgres refusing
# writes — a data-loss-shaped failure caused by logging.
log "STAGE 3: Setting daemon-wide container log rotation"
install -d -m 755 /etc/docker
if [[ -f /etc/docker/daemon.json ]] && ! grep -q '"log-driver"' /etc/docker/daemon.json; then
  warn "/etc/docker/daemon.json exists without a log-driver — merge these by hand:"
  warn '  "log-driver": "json-file", "log-opts": {"max-size":"10m","max-file":"5"}'
elif [[ ! -f /etc/docker/daemon.json ]]; then
  cat > /etc/docker/daemon.json <<'EOF'
{
  "log-driver": "json-file",
  "log-opts": { "max-size": "10m", "max-file": "5" },
  "live-restore": true
}
EOF
  systemctl restart docker
  log "Daemon log rotation configured."
fi

# ---- Swap ------------------------------------------------------------------
# Why swap on a server: the compose file declares 4096M of limits. Limits are ceilings,
# not reservations, but Postgres + RabbitMQ + three PHP processes will transiently peak
# together (a large import while a report runs). Without swap the kernel's only option
# is to kill something; with it the peak is slow instead of fatal.
if (( SWAP_GB > 0 )); then
  if swapon --show | grep -q .; then
    log "STAGE 3: Swap already active — leaving it alone."
  else
    log "STAGE 3: Creating ${SWAP_GB}G swap file"
    fallocate -l "${SWAP_GB}G" /swapfile || dd if=/dev/zero of=/swapfile bs=1M count=$((SWAP_GB * 1024))
    chmod 600 /swapfile
    mkswap /swapfile
    swapon /swapfile
    grep -q '^/swapfile' /etc/fstab || echo '/swapfile none swap sw 0 0' >> /etc/fstab
    # Prefer RAM; use swap as a cushion rather than routinely.
    sysctl -w vm.swappiness=10 >/dev/null
    grep -q '^vm.swappiness' /etc/sysctl.conf || echo 'vm.swappiness=10' >> /etc/sysctl.conf
    log "Swap active: $(swapon --show --noheadings --raw | head -1)"
  fi
fi

# ---- App directory ---------------------------------------------------------
# DEPLOY.md §2.1 clones the repo here; §2.4 serves ACME challenges from ./acme.
log "STAGE 3: Preparing ${APP_DIR}"
install -d -m 755 -o "${DEPLOY_USER}" -g "${DEPLOY_USER}" "${APP_DIR}"
install -d -m 755 -o "${DEPLOY_USER}" -g "${DEPLOY_USER}" "${APP_DIR}/acme"

# ===========================================================================
# Verification
# ===========================================================================
log "Verifying the host"
FAILED=0

# Every probe returns its own OK/not-OK string. `systemctl is-active` exits non-zero
# for an inactive unit, which under `set -e` would abort the run mid-report — so each
# is wrapped to always succeed and report instead.
check() {
  printf '  %-42s %s\n' "$1" "$2"
  [[ "$2" == OK* ]] || FAILED=1
}
unit_ok() { [[ "$(systemctl is-active "$1" 2>/dev/null || true)" == "active" ]] && echo OK || echo "NOT-RUNNING"; }

check "Docker daemon running"      "$(unit_ok docker)"
check "fail2ban running"           "$(unit_ok fail2ban)"
check "UFW enabled"                "$(ufw status 2>/dev/null | head -1 | grep -q 'Status: active' && echo OK || echo INACTIVE)"
check "sshd: root login disabled"  "$(sshd_conf | grep -qi '^permitrootlogin no' && echo OK || echo STILL-ENABLED)"
check "sshd: password auth off"    "$(sshd_conf | grep -qi '^passwordauthentication no' && echo OK || echo STILL-ENABLED)"
check "${DEPLOY_USER} SSH keys"    "$([[ -s "${DEPLOY_KEYS}" ]] && echo "OK (${KEY_COUNT} key(s))" || echo MISSING)"
check "${DEPLOY_USER} in docker"   "$(id -nG "${DEPLOY_USER}" 2>/dev/null | grep -qw docker && echo OK || echo NO)"
check "${DEPLOY_USER} in sudo"     "$(id -nG "${DEPLOY_USER}" 2>/dev/null | grep -qw sudo && echo OK || echo NO)"
check "Swap active"                "$(swapon --show 2>/dev/null | grep -q . && echo OK || echo NONE)"
check "Time synchronised"          "$(timedatectl show -p NTPSynchronized --value 2>/dev/null | grep -q yes && echo OK || echo PENDING)"

(( FAILED == 0 )) || warn "One or more checks did not pass — review the table above before proceeding."

REBOOT_NOTE=""
[[ -f /var/run/reboot-required ]] && REBOOT_NOTE="
  0. A REBOOT IS REQUIRED (kernel or core library updated).
     Do this BEFORE step 1 so you verify SSH on the kernel you will actually run:
         sudo reboot
"

# ===========================================================================
log "Provisioning complete."
cat <<EOF

  ------------------------------------------------------------------
  NEXT STEPS (manual — they need a fresh login or your input):
${REBOOT_NOTE}
  1. VERIFY LOGIN before closing this root session. In a NEW terminal:
         ssh ${DEPLOY_USER}@<server-ip>      # must succeed (key-based)
         ssh root@<server-ip>                # must be REFUSED

  2. The docker group needs a fresh login. As ${DEPLOY_USER}:
         docker run --rm hello-world         # must work WITHOUT sudo

  3. TAKE SNAPSHOT #1 via your provider — label it
     'baseline-hardened-docker'. Clean rollback point before app config.

  4. Stage 4: authenticate to GHCR and pull the images.
     Follow docs/GHCR-Setup-Runbook.pdf, then docs/DEPLOY.md §2.3 onward.
     The app directory is ready at ${APP_DIR} (with ./acme for Let's Encrypt).
  ------------------------------------------------------------------

EOF
