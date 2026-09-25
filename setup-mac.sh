#!/usr/bin/env bash
# ---------------------------------------------------------------------------
# One-time setup of the local dev environment on macOS (Intel or Apple Silicon).
#
# Installs:  Homebrew, git, Node.js, Docker Desktop
# Then:      builds the app image (PHP 5.6 + Apache), starts the stack,
#            creates html/.env, runs composer install inside the container,
#            and installs the live-reload watcher deps.
#
# PHP / Composer / MySQL run inside Docker - nothing PHP-related is installed
# on the Mac itself (PHP 5.6 is not available from Homebrew anyway).
#
# Usage:
#   chmod +x setup-mac.sh start-mac.sh
#   ./setup-mac.sh                     # fresh setup, empty database
#   ./setup-mac.sh path/to/dump.sql    # also import a DB dump into land_home
#
# Safe to re-run: every step skips itself when already done.
# ---------------------------------------------------------------------------
set -euo pipefail

cd "$(dirname "$0")"
ROOT="$(pwd)"
DUMP_FILE="${1:-}"

APP_IMAGE="landhome-uat-docker-app"
APP_CONTAINER="landhome-uat-apache"
DB_CONTAINER="landhome-uat-mysql"
DB_ROOT_PASSWORD="BSLH@2020"   # must match docker-compose.yml

step() { printf '\n\033[1;34m==> %s\033[0m\n' "$*"; }
ok()   { printf '    \033[32m%s\033[0m\n' "$*"; }
die()  { printf '\n\033[1;31mERROR: %s\033[0m\n' "$*" >&2; exit 1; }

# Remove containers that use our fixed container_name values but were not
# created by this compose project (e.g. a leftover `docker run` or another
# checkout), otherwise `docker compose up` fails with "already in use".
remove_stray_containers() {
    local project name owner
    project="$(docker compose config 2>/dev/null | awk '/^name:/{print $2; exit}')"
    for name in "$DB_CONTAINER" "$APP_CONTAINER" landhome-uat-pma; do
        docker container inspect "$name" >/dev/null 2>&1 || continue
        owner="$(docker container inspect -f '{{ index .Config.Labels "com.docker.compose.project" }}' "$name" 2>/dev/null || true)"
        if [ "$owner" != "$project" ]; then
            ok "removing stray container $name (not part of compose project '$project')"
            docker rm -f "$name" >/dev/null
        fi
    done
}

[ "$(uname -s)" = "Darwin" ] || die "This script is for macOS only."

# mysql:5.7 has no arm64 image and the app image links an x86_64 LibXL build,
# so on Apple Silicon everything runs as amd64 under emulation.
if [ "$(uname -m)" = "arm64" ]; then
    export DOCKER_DEFAULT_PLATFORM=linux/amd64
    ok "Apple Silicon detected - using linux/amd64 images (Rosetta emulation)"
fi

# --- 1. Homebrew ------------------------------------------------------------
step "Homebrew"
if ! command -v brew >/dev/null 2>&1; then
    /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
    # Put brew on PATH for this session (and future shells).
    if [ -x /opt/homebrew/bin/brew ]; then BREW=/opt/homebrew/bin/brew; else BREW=/usr/local/bin/brew; fi
    eval "$("$BREW" shellenv)"
    grep -q 'brew shellenv' "$HOME/.zprofile" 2>/dev/null \
        || echo "eval \"\$($BREW shellenv)\"" >> "$HOME/.zprofile"
fi
ok "$(brew --version | head -1)"

# --- 2. CLI tools -----------------------------------------------------------
step "git, Node.js"
for pkg in git node; do
    if command -v "$pkg" >/dev/null 2>&1; then
        ok "$pkg already installed ($("$pkg" --version | head -1))"
    else
        brew install "$pkg"
    fi
done

# --- 3. Docker Desktop ------------------------------------------------------
step "Docker Desktop"
if [ ! -d "/Applications/Docker.app" ] && ! command -v docker >/dev/null 2>&1; then
    brew install --cask docker
fi
if [ "$(uname -m)" = "arm64" ] && ! /usr/bin/pgrep -q oahd; then
    # Rosetta is needed for fast amd64 emulation in Docker Desktop.
    softwareupdate --install-rosetta --agree-to-license || true
fi

if ! docker info >/dev/null 2>&1; then
    echo "    Starting Docker Desktop (accept the license / permissions prompt on first launch)..."
    open -a Docker
    for _ in $(seq 1 90); do
        docker info >/dev/null 2>&1 && break
        sleep 2
    done
    docker info >/dev/null 2>&1 || die "Docker did not start within 3 minutes. Open Docker Desktop manually, then re-run."
fi
ok "$(docker --version)"

# --- 4. App image -----------------------------------------------------------
step "Build app image ($APP_IMAGE: PHP 5.6 + Apache)"
if docker image inspect "$APP_IMAGE" >/dev/null 2>&1; then
    ok "image already built (delete it with 'docker rmi $APP_IMAGE' to rebuild)"
else
    docker build -t "$APP_IMAGE" -f docker/Dockerfile .
fi

# --- 5. Laravel .env --------------------------------------------------------
step "html/.env"
if [ -f html/.env ]; then
    ok "html/.env already exists - leaving it untouched"
else
    cp html/.env.example html/.env
    ok "created from .env.example (DB_HOST=$DB_CONTAINER)"
fi

# --- 6. Start stack ---------------------------------------------------------
step "Start containers"
mkdir -p mysql
remove_stray_containers
docker compose up -d

echo "    Waiting for MySQL to accept connections..."
for _ in $(seq 1 60); do
    docker exec "$DB_CONTAINER" mysqladmin ping -uroot -p"$DB_ROOT_PASSWORD" --silent >/dev/null 2>&1 && break
    sleep 2
done
docker exec "$DB_CONTAINER" mysqladmin ping -uroot -p"$DB_ROOT_PASSWORD" --silent >/dev/null 2>&1 \
    || die "MySQL did not become ready. Check: docker logs $DB_CONTAINER"
ok "MySQL is up"

# --- 7. Composer / app key / permissions ------------------------------------
step "composer install (inside $APP_CONTAINER)"
docker exec -w /var/www/html -e COMPOSER_MEMORY_LIMIT=-1 "$APP_CONTAINER" \
    composer install --no-interaction

if grep -qE '^APP_KEY=\s*$' html/.env; then
    docker exec -w /var/www/html "$APP_CONTAINER" php artisan key:generate
fi

docker exec "$APP_CONTAINER" sh -c \
    'chown -R www-data:www-data storage bootstrap/cache && chmod -R ug+rwX storage bootstrap/cache'
ok "storage/ and bootstrap/cache writable"

# --- 8. Optional DB import --------------------------------------------------
if [ -n "$DUMP_FILE" ]; then
    step "Import $DUMP_FILE into land_home"
    [ -f "$DUMP_FILE" ] || die "dump file not found: $DUMP_FILE"
    docker exec -i "$DB_CONTAINER" mysql -uroot -p"$DB_ROOT_PASSWORD" land_home < "$DUMP_FILE"
    ok "imported. Apply any newer patches from docker/sql/ by hand if the dump predates them."
fi

# --- 9. Live-reload watcher deps --------------------------------------------
step "Live-reload watcher (dev-tools/livereload)"
if [ -f dev-tools/livereload/package.json ]; then
    (cd dev-tools/livereload && npm install --no-fund --no-audit)
else
    ok "dev-tools/livereload not present - skipping"
fi

step "Done"
cat <<EOF
    App:         http://localhost:8081/public/
    phpMyAdmin:  http://localhost:7771
    MySQL:       localhost:3331  (user: land_home / land_home)

    Next time just run:  ./start-mac.sh
EOF
[ -n "$DUMP_FILE" ] || echo "
    Note: the database is empty. The repo has no full schema, only patches in
    docker/sql/. Re-run with a dump:  ./setup-mac.sh path/to/dump.sql"
