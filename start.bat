@echo off
REM ---------------------------------------------------------------------------
REM Starts the local dev stack for this project (Windows, Docker Desktop).
REM   MySQL 5.7        -> localhost:3331
REM   App (PHP 5.6)    -> http://localhost:8081/public/
REM   phpMyAdmin       -> http://localhost:7771
REM
REM Just double-click this file, or run it from a terminal in this folder.
REM ---------------------------------------------------------------------------

cd /d "%~dp0"

where /q docker
if errorlevel 1 (
    echo ERROR: docker was not found on PATH. Install/start Docker Desktop first.
    pause
    exit /b 1
)

echo Starting containers (mysql, apache, phpmyadmin)...
docker compose up -d
if errorlevel 1 (
    echo.
    echo ERROR: docker compose failed to start. See the message above.
    pause
    exit /b 1
)

echo.
echo Up and running:
echo   App:         http://localhost:8081/public/
echo   phpMyAdmin:  http://localhost:7771
echo   MySQL:       localhost:3331  (user: land_home / land_home)
echo.
start http://localhost:8081/public/
pause
