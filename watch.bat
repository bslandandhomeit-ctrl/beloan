@echo off
REM ---------------------------------------------------------------------------
REM Live-reload watcher for local dev (Windows).
REM
REM Run start.bat first so the app is up on http://localhost:8081.
REM This watches html/app, html/resources, html/public, and pushes a
REM reload to your open browser tab whenever a file changes there -
REM no manual refresh needed. Keep browsing the app at :8081 as usual.
REM
REM Only active while APP_ENV=local (html/.env) - the reload snippet
REM never renders otherwise, so it's a no-op in any other environment.
REM
REM First run: cd dev-tools\livereload && npm install (one-time).
REM Requires Node.js on PATH.
REM ---------------------------------------------------------------------------

cd /d "%~dp0dev-tools\livereload"

where /q node
if errorlevel 1 (
    echo ERROR: node not found on PATH. Install Node.js first.
    pause
    exit /b 1
)

if not exist "node_modules" (
    echo Installing dependencies (one-time)...
    npm install
)

node server.js
pause
