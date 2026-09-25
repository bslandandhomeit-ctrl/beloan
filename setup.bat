@echo off
REM ---------------------------------------------------------------------------
REM One-time setup of the local dev environment on Windows 10/11.
REM
REM Installs:  Git, Node.js, Docker Desktop (via winget, if missing)
REM Then:      builds the app image (PHP 5.6 + Apache), starts the stack,
REM            creates html\.env, runs composer install inside the container,
REM            and installs the live-reload watcher deps.
REM
REM PHP / Composer / MySQL run inside Docker - nothing PHP-related is installed
REM on Windows itself.
REM
REM Usage:
REM   setup.bat                     fresh setup, empty database
REM   setup.bat path\to\dump.sql    also import a DB dump into land_home
REM
REM Safe to re-run: every step skips itself when already done.
REM After this, use start.bat every day.
REM ---------------------------------------------------------------------------
setlocal

cd /d "%~dp0"

set "APP_IMAGE=landhome-uat-docker-app"
set "APP_CONTAINER=landhome-uat-apache"
set "DB_CONTAINER=landhome-uat-mysql"
REM must match docker-compose.yml
set "DB_ROOT_PASSWORD=BSLH@2020"
set "DUMP_FILE=%~1"
set "NEED_RESTART="

REM --- 1. Tools via winget ---------------------------------------------------
echo.
echo ==^> Git, Node.js, Docker Desktop
where /q winget
if errorlevel 1 (
    echo ERROR: winget not found. Install "App Installer" from the Microsoft Store,
    echo        or install Git, Node.js and Docker Desktop by hand, then re-run.
    pause
    exit /b 1
)

where /q git
if errorlevel 1 (
    winget install -e --id Git.Git --accept-package-agreements --accept-source-agreements
    set "NEED_RESTART=1"
) else (
    echo     git already installed
)

where /q node
if errorlevel 1 (
    winget install -e --id OpenJS.NodeJS.LTS --accept-package-agreements --accept-source-agreements
    set "NEED_RESTART=1"
) else (
    echo     node already installed
)

where /q docker
if errorlevel 1 (
    winget install -e --id Docker.DockerDesktop --accept-package-agreements --accept-source-agreements
    set "NEED_RESTART=1"
) else (
    echo     docker already installed
)

if defined NEED_RESTART (
    echo.
    echo New tools were installed. Windows must pick up the new PATH first:
    echo   - If Docker Desktop was just installed: restart the PC, open Docker
    echo     Desktop once and finish its first-run setup ^(it may enable WSL 2^).
    echo   - Then open a NEW terminal and run setup.bat again.
    pause
    exit /b 0
)

REM --- 2. Docker running -----------------------------------------------------
echo.
echo ==^> Docker Desktop
docker info >nul 2>&1
if errorlevel 1 (
    echo     Starting Docker Desktop...
    start "" "%ProgramFiles%\Docker\Docker\Docker Desktop.exe"
)
set "DOCKER_READY="
for /l %%i in (1,1,90) do (
    if not defined DOCKER_READY (
        docker info >nul 2>&1 && set "DOCKER_READY=1" || timeout /t 2 /nobreak >nul
    )
)
if not defined DOCKER_READY (
    echo ERROR: Docker did not start within 3 minutes. Open Docker Desktop manually, then re-run.
    pause
    exit /b 1
)
echo     Docker is running

REM --- 3. App image ----------------------------------------------------------
echo.
echo ==^> Build app image (%APP_IMAGE%: PHP 5.6 + Apache)
docker image inspect %APP_IMAGE% >nul 2>&1
if errorlevel 1 (
    docker build -t %APP_IMAGE% -f docker/Dockerfile .
    if errorlevel 1 (
        echo ERROR: image build failed. See the message above.
        pause
        exit /b 1
    )
) else (
    echo     image already built ^(delete it with "docker rmi %APP_IMAGE%" to rebuild^)
)

REM --- 4. Laravel .env -------------------------------------------------------
echo.
echo ==^> html\.env
if exist "html\.env" (
    echo     html\.env already exists - leaving it untouched
) else (
    copy /y "html\.env.example" "html\.env" >nul
    echo     created from .env.example ^(DB_HOST=%DB_CONTAINER%^)
)

REM --- 5. Start stack --------------------------------------------------------
echo.
echo ==^> Start containers
if not exist "mysql" mkdir mysql
docker compose up -d
if errorlevel 1 (
    echo ERROR: docker compose failed to start. See the message above.
    pause
    exit /b 1
)

echo     Waiting for MySQL to accept connections...
set "DB_READY="
for /l %%i in (1,1,60) do (
    if not defined DB_READY (
        docker exec %DB_CONTAINER% mysqladmin ping -uroot -p%DB_ROOT_PASSWORD% --silent >nul 2>&1 && set "DB_READY=1" || timeout /t 2 /nobreak >nul
    )
)
if not defined DB_READY (
    echo ERROR: MySQL did not become ready. Check: docker logs %DB_CONTAINER%
    pause
    exit /b 1
)
echo     MySQL is up

REM --- 6. Composer / app key / permissions -----------------------------------
echo.
echo ==^> composer install (inside %APP_CONTAINER%)
docker exec -w /var/www/html -e COMPOSER_MEMORY_LIMIT=-1 %APP_CONTAINER% composer install --no-interaction
if errorlevel 1 (
    echo ERROR: composer install failed. See the message above.
    pause
    exit /b 1
)

REM Generate APP_KEY only when it is empty (checked inside the container).
docker exec -w /var/www/html %APP_CONTAINER% sh -c "grep -qE '^APP_KEY=[[:space:]]*$' .env && php artisan key:generate || true"

docker exec -w /var/www/html %APP_CONTAINER% sh -c "chown -R www-data:www-data storage bootstrap/cache && chmod -R ug+rwX storage bootstrap/cache"
echo     storage\ and bootstrap\cache writable

REM --- 7. Optional DB import -------------------------------------------------
if not "%DUMP_FILE%"=="" (
    echo.
    echo ==^> Import %DUMP_FILE% into land_home
    if not exist "%DUMP_FILE%" (
        echo ERROR: dump file not found: %DUMP_FILE%
        pause
        exit /b 1
    )
    docker exec -i %DB_CONTAINER% mysql -uroot -p%DB_ROOT_PASSWORD% land_home < "%DUMP_FILE%"
    if errorlevel 1 (
        echo ERROR: import failed. See the message above.
        pause
        exit /b 1
    )
    echo     imported. Apply any newer patches from docker\sql\ by hand if the dump predates them.
)

REM --- 8. Live-reload watcher deps -------------------------------------------
echo.
echo ==^> Live-reload watcher (dev-tools\livereload)
if exist "dev-tools\livereload\package.json" (
    pushd dev-tools\livereload
    call npm install --no-fund --no-audit
    popd
) else (
    echo     dev-tools\livereload not present - skipping
)

echo.
echo ==^> Done
echo     App:         http://localhost:8081/public/
echo     phpMyAdmin:  http://localhost:7771
echo     MySQL:       localhost:3331  (user: land_home / land_home)
echo.
echo     Next time just run:  start.bat
if "%DUMP_FILE%"=="" (
    echo.
    echo     Note: the database is empty. The repo has no full schema, only patches in
    echo     docker\sql\. Re-run with a dump:  setup.bat path\to\dump.sql
)
echo.
pause
endlocal
