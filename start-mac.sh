#!/usr/bin/env bash
# ---------------------------------------------------------------------------
# Starts the local dev stack on macOS (Docker Desktop).
#   MySQL 5.7        -> localhost:3331
#   App (PHP 5.6)    -> http://localhost:8081/public/
#   phpMyAdmin       -> http://localhost:7771
#
# Usage:
#   ./start-mac.sh           # start containers and open the app
#   ./start-mac.sh --watch   # same, then run the live-reload watcher
#   ./start-mac.sh --stop    # stop the containers
#
# Run ./setup-mac.sh once before the first start.
# ---------------------------------------------------------------------------
set -euo pipefail

cd "$(dirname "$0")"

die() { printf '\n\033[1;31mERROR: %s\033[0m\n' "$*" >&2; exit 1; }

# mysql:5.7 and the app image are amd64-only.
[ "$(uname -m)" = "arm64" ] && export DOCKER_DEFAULT_PLATFORM=linux/amd64

command -v docker >/dev/null 2>&1 || die "docker not found. Run ./setup-mac.sh first."

if ! docker info >/dev/null 2>&1; then
    echo "Starting Docker Desktop..."
    open -a Docker
    for _ in $(seq 1 90); do
        docker info >/dev/null 2>&1 && break
        sleep 2
    done
    docker info >/dev/null 2>&1 || die "Docker did not start within 3 minutes."
fi

if [ "${1:-}" = "--stop" ]; then
    docker compose stop
    exit 0
fi

docker image inspect landhome-uat-docker-app >/dev/null 2>&1 \
    || die "app image not built yet. Run ./setup-mac.sh first."

echo "Starting containers (mysql, apache, phpmyadmin)..."
docker compose up -d

cat <<EOF

Up and running:
  App:         http://localhost:8081/public/
  phpMyAdmin:  http://localhost:7771
  MySQL:       localhost:3331  (user: land_home / land_home)

EOF
open "http://localhost:8081/public/"

if [ "${1:-}" = "--watch" ]; then
    cd dev-tools/livereload
    command -v node >/dev/null 2>&1 || die "node not found. Run ./setup-mac.sh first."
    [ -d node_modules ] || npm install --no-fund --no-audit
    exec node server.js
fi
